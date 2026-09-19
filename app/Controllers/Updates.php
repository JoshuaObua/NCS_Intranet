<?php

namespace App\Controllers;

class Updates extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    function index() {

        $updates_info = $this->_get_updates_info();

        $view_data['installable_updates'] = $updates_info->installable_updates;
        $view_data['downloadable_updates'] = $updates_info->downloadable_updates;
        $view_data['current_version'] = $updates_info->current_version;

        return $this->template->rander("updates/index", $view_data);
    }

    private function _curl_get_contents($url, $download = false) {
        $ch = curl_init();
        $file_name = "";
        $file_data = "";

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain'));

        if ($download) {

            curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) use (&$file_name) {
                if (preg_match('/^Content-Disposition:.*filename="([^"]+)"/i', $header, $matches)) {
                    $file_name = $matches[1];
                }
                return strlen($header);
            });

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$file_data) {
                $file_data .= $chunk;
                return strlen($chunk);
            });
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }

        $data = curl_exec($ch);
        curl_close($ch);

        if ($download) {
            $data = array("data" => $file_data, "file_name" => $file_name);
        }

        return $data;
    }

    private function _get_release_contents($url, $download = false) {
        $curl_data = $this->_curl_get_contents($url, $download);

        if (!$curl_data) {
            try {
                $curl_data = file_get_contents($url);
            } catch (\Exception $e) {
                log_message("error", $e->getMessage());
                $curl_data = null;
            }
        }

        return $curl_data;
    }

    private function _get_updates_info() {

        ini_set('max_execution_time', 180);

        $current_version = get_setting("app_version");
        $local_updates_dir = get_setting("updates_path");

        $none_installed_versions = array();
        $installable_updates = array();
        $downloadable_updates = array();
        $next_installable_version = "";

        $app_update_url = get_setting("app_update_url");
        if ($app_update_url) {
            $url = $app_update_url . "?api_version=2&type=update&version=" . $current_version;
            $releases = $this->_get_release_contents($url);

            if ($releases) {
                $releases = array_filter(explode("<br />", $releases));

                foreach ($releases as $version) {
                    if (version_compare($version, $current_version) > 0) {
                        if (!$next_installable_version) {
                            $next_installable_version = $version;
                        }
                        $none_installed_versions[] = $version;
                    }
                }

                foreach ($none_installed_versions as $version) {
                    $matching_files = glob($local_updates_dir . $version . '*.zip');
                    if (!empty($matching_files)) {
                        $installable_updates[] = $version;
                    } else {
                        $downloadable_updates[] = $version;
                    }
                }
            }
        }

        $info = new \stdClass();
        $info->current_version = $current_version;
        $info->none_installed_versions = $none_installed_versions;
        $info->installable_updates = $installable_updates;
        $info->downloadable_updates = $downloadable_updates;
        $info->next_installable_version = $next_installable_version;
        return $info;
    }

    function download_updates($version = "") {

        $local_updates_dir = get_setting("updates_path");

        $matching_files = glob($local_updates_dir . $version . '*.zip');
        if (!empty($matching_files)) {
            echo json_encode(array("success" => true, 'message' => "File already exists"));
        } else {
            ini_set('max_execution_time', 300);

            $app_update_url = get_setting("app_update_url");
            $download_url = $app_update_url . "?api_version=2&type=download&version=" . $version;

            $new_update = $this->_get_release_contents($download_url, true);

            $data = get_array_value($new_update, 'data');
            $file_name = get_array_value($new_update, 'file_name');
            if (!($new_update && $data && $file_name)) {
                echo json_encode(array("success" => false, 'message' => "Sorry, Version - $version download has been failed!"));
                exit();
            }

            $update_zip = $local_updates_dir . $file_name;

            if (!is_dir($local_updates_dir)) {
                if (!@mkdir($local_updates_dir)) {
                    echo json_encode(array("success" => false, 'message' => "Permission denied: $local_updates_dir directory is not writeable! Please set the writeable permission to the directory"));
                    exit();
                }
            }

            if (file_put_contents($update_zip, $data)) {
                echo json_encode(array("success" => true, 'message' => "Downloaded version-" . $version));
            } else {
                echo json_encode(array("success" => false, 'message' => $version . " - Download failed!"));
            }
        }
    }

    function do_update($version = "", $file_hash = "", $acknowledged = 0) {
        ini_set('max_execution_time', 300);
        if (!$version || !$file_hash) {
            echo json_encode(array("success" => false, 'message' => app_lang("something_went_wrong")));
            exit();
        }

        $updates_info = $this->_get_updates_info();
        if ($updates_info->next_installable_version != $version) {
            echo json_encode(array("success" => false, 'message' => "Please install the version - $updates_info->next_installable_version first!"));
            exit();
        }

        $local_updates_dir = get_setting("updates_path");

        if (!class_exists('ZipArchive')) {
            echo json_encode(array("success" => false, 'message' => "Please install the ZipArchive php extension in your server."));
            exit();
        }

        $zip = new \ZipArchive;
        $update_file = $local_updates_dir . $version . "-" . $file_hash . '.zip';
        $zip->open($update_file);

        $executeable_file = "";

        $env_checker_file = "env_checker.php";
        $removeable_env_checker_file_path = "";
        if ($zip->locateName($env_checker_file) !== false) {
            file_put_contents($env_checker_file, $zip->getFromName($env_checker_file));
            $removeable_env_checker_file_path = $env_checker_file;
            $check_result = include($env_checker_file);
            if (get_array_value($check_result, "response_type") == "success") {
                //can update...
            } else if ($acknowledged != "1" && get_array_value($check_result, "response_type") == "acknowledgement_required") {
                unlink($removeable_env_checker_file_path);
                echo json_encode(array("response_type" => "acknowledgement_required", 'message' => get_array_value($check_result, "message")));
                exit();
            } else if (get_array_value($check_result, "response_type") == "error") {
                unlink($removeable_env_checker_file_path);
                echo json_encode(array("response_type" => "error", 'message' => get_array_value($check_result, "message")));
                exit();
            }
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $file_info_array = $zip->statIndex($i);
            $file_name = get_array_value($file_info_array, "name");
            $dir = dirname($file_name);

            if (substr($file_name, -1, 1) == '/') {
                continue;
            }

            if (!is_dir('./' . $dir)) {
                mkdir('./' . $dir, 0755, true);
            }

            if (!is_dir('./' . $file_name)) {
                $contents = $zip->getFromIndex($i);
                if ($file_name == 'execute.php') {
                    $executeable_file = $file_name;
                }
                file_put_contents($file_name, $contents);
            }
        }

        $zip->close();

        if ($executeable_file) {
            include($executeable_file);
            unlink($executeable_file);
        }

        if ($removeable_env_checker_file_path) {
            unlink($removeable_env_checker_file_path);
        }

        if (is_file($update_file)) {
            unlink($update_file);
        }

        echo json_encode(array("response_type" => "success", 'message' => "Version - $version installed successfully!"));
    }

    function systeminfo() {
        phpinfo();
    }
}

/* End of file Updates.php */
/* Location: ./app/controllers/Updates.php */
