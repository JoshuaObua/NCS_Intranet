<?php

namespace App\Controllers;

class Fixed_assets extends Security_Controller {

    protected $db;
    public $Fixed_assets_model;
    public $Asset_transaction_logs_model;

    function __construct() {
        parent::__construct();
        helper(['currency', 'general', 'custom', 'plugin']);
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Fixed_assets_model = model("App\Models\Fixed_assets_model");
        $this->Asset_transaction_logs_model = model("App\Models\Asset_transaction_logs_model");
    }

    function index() {
        return $this->assets();
    }

    /* ─── 1. MASTER ASSET REGISTER ────────────────────────────────── */
    function assets() {
        $view_data['totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        return $this->template->rander("fixed_assets/index", $view_data);
    }

    function assets_list_data() {
        $list_data = $this->Fixed_assets_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_asset_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_asset_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->status === "UNDER_MAINTENANCE") {
            $status_badge = "<span class='badge bg-warning'>MAINTENANCE</span>";
        } else if ($data->status === "DISPOSED" || $data->status === "WRITE_OFF") {
            $status_badge = "<span class='badge bg-danger'>" . $data->status . "</span>";
        }

        $ver_badge = "<span class='badge bg-info'>" . $data->verification_status . "</span>";
        if ($data->verification_status === "VERIFIED") {
            $ver_badge = "<span class='badge bg-success'><i data-feather='check' class='icon-14'></i> VERIFIED</span>";
        } else if ($data->verification_status === "DISCREPANCY") {
            $ver_badge = "<span class='badge bg-danger'>DISCREPANCY</span>";
        }

        $actions = modal_anchor(get_uri("fixed_assets/modal_asset_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Asset Record", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->asset_number . "</strong><br><small class='text-muted'>" . $data->interface_line_number . "</small>",
            "<code>" . $data->tag_number . "</code>",
            "<div><strong>" . $data->asset_description . "</strong><br><small class='text-muted'>" . $data->category_segment4 . " (" . $data->worksheet_source . ")</small></div>",
            "<span class='badge bg-primary'>" . $data->category_segment3 . "</span>",
            "<strong>" . to_currency($data->fb_cost) . "</strong>",
            "<strong>" . to_currency($data->adjusted_cost) . "</strong>",
            "<span class='text-success'>" . to_currency($data->net_book_value) . "</span>",
            $data->date_placed_in_service,
            $status_badge,
            $ver_badge,
            $actions
        );
    }

    function asset_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Fixed_assets_model->get_one($id);
        return $this->template->view("fixed_assets/asset_modal_form", $view_data);
    }

    function modal_asset_form() {
        return $this->asset_modal_form();
    }

    function save_asset() {
        $id = $this->request->getPost('id');
        $data = array(
            "interface_line_number" => $this->request->getPost('interface_line_number'),
            "asset_book" => $this->request->getPost('asset_book') ?: 'NCS FA BOOK',
            "asset_number" => $this->request->getPost('asset_number'),
            "tag_number" => $this->request->getPost('tag_number'),
            "asset_description" => $this->request->getPost('asset_description'),
            "category_segment1" => $this->request->getPost('category_segment1'),
            "category_segment3" => $this->request->getPost('category_segment3'),
            "category_segment4" => $this->request->getPost('category_segment4'),
            "asset_units" => $this->request->getPost('asset_units') ?: 1,
            "fb_cost" => unformat_currency($this->request->getPost('fb_cost')),
            "adjusted_cost" => unformat_currency($this->request->getPost('adjusted_cost')),
            "net_book_value" => unformat_currency($this->request->getPost('adjusted_cost')),
            "date_placed_in_service" => $this->request->getPost('date_placed_in_service'),
            "custodian_department" => $this->request->getPost('custodian_department') ?: 'General Administration',
            "location_building" => $this->request->getPost('location_building') ?: 'NCS Lugogo Head Office',
            "status" => $this->request->getPost('status') ?: 'ACTIVE',
            "worksheet_source" => $this->request->getPost('worksheet_source') ?: 'MANUAL_ENTRY'
        );

        $save_id = $this->Fixed_assets_model->ci_save($data, $id);
        if ($save_id) {
            $log_data = array(
                "asset_id" => $save_id,
                "transaction_type" => $id ? 'UPDATE' : 'CREATE',
                "previous_val" => 0,
                "new_val" => $data["adjusted_cost"],
                "notes" => "Asset saved via portal form",
                "performed_by" => $this->login_user->id
            );
            $this->Asset_transaction_logs_model->ci_save($log_data);

            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 2. BULK EXCEL IMPORT ENGINE & TEMPLATE DOWNLOAD ──────────── */
    function import_modal() {
        return $this->template->view("fixed_assets/import_modal_form");
    }

    function modal_import_form() {
        return $this->import_modal();
    }

    function download_template() {
        $filename = "NCS_Fixed_Asset_Register_Import_Template.csv";
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        $output = fopen("php://output", "w");
        fputcsv($output, array(
            "INTERFACE_LINE_NUMBER",
            "ASSET_BOOK",
            "ASSET_NUMBER",
            "TAG_NUMBER",
            "ASSET_DESCRIPTION",
            "ASSET_CATEGORY_SEGMENT1",
            "ASSET_CATEGORY_SEGMENT3",
            "ASSET_CATEGORY_SEGMENT4",
            "ASSET_UNITS",
            "FB_COST",
            "ADJUSTED_COST",
            "DATE_PLACED_IN_SERVICE",
            "CUSTODIAN_DEPARTMENT",
            "LOCATION_BUILDING"
        ));

        // Sample demo row
        fputcsv($output, array(
            "1413999",
            "NCS FA BOOK",
            "M1009999",
            "NCS-FA-2026-001",
            "Dell PowerEdge R750 Server 64GB RAM",
            "MACHINERY AND EQUIPMENT",
            "LIGHT ICT HARDWARE",
            "Server Hardware",
            "1",
            "28500000.00",
            "28500000.00",
            "2026-01-15",
            "ICT & Media",
            "NCS Lugogo Head Office Server Room"
        ));

        fclose($output);
        exit();
    }

    function upload_excel() {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            echo json_encode(array("success" => false, "message" => "Please select a valid CSV or Excel file to upload."));
            return;
        }

        $filepath = $file->getTempName();
        $imported = 0;

        if (($handle = fopen($filepath, "r")) !== FALSE) {
            $header = fgetcsv($handle, 2000, ",");
            while (($row = fgetcsv($handle, 2000, ",")) !== FALSE) {
                if (count($row) < 5) continue;

                $data = array(
                    "interface_line_number" => $row[0] ?: "LN-" . rand(1000, 9999),
                    "asset_book" => $row[1] ?: "NCS FA BOOK",
                    "asset_number" => $row[2] ?: "M" . rand(1000000, 9999999),
                    "tag_number" => $row[3] ?: "TAG-" . rand(1000, 9999),
                    "asset_description" => $row[4],
                    "category_segment1" => $row[5] ?: "MACHINERY AND EQUIPMENT",
                    "category_segment3" => $row[6] ?: "BULK IMPORT",
                    "category_segment4" => $row[7] ?: "GENERAL ASSETS",
                    "asset_units" => isset($row[8]) ? (int)$row[8] : 1,
                    "fb_cost" => isset($row[9]) ? (float)$row[9] : 0,
                    "adjusted_cost" => isset($row[10]) ? (float)$row[10] : 0,
                    "net_book_value" => isset($row[10]) ? (float)$row[10] : 0,
                    "date_placed_in_service" => isset($row[11]) ? $row[11] : date("Y-m-d"),
                    "custodian_department" => isset($row[12]) ? $row[12] : "General Administration",
                    "location_building" => isset($row[13]) ? $row[13] : "NCS Lugogo Head Office",
                    "worksheet_source" => "CSV_BULK_IMPORT",
                    "verification_status" => "UNVERIFIED"
                );

                $save_id = $this->Fixed_assets_model->ci_save($data);
                if ($save_id) {
                    $imported++;
                }
            }
            fclose($handle);
        }

        echo json_encode(array("success" => true, "message" => "Successfully imported $imported fixed assets into register."));
    }

    /* ─── 3. ASSET REVALUATION & ADJUSTMENTS ───────────────────────── */
    function adjustments() {
        $view_data['categories'] = $this->Fixed_assets_model->get_category_summary()->getResult();
        return $this->template->rander("fixed_assets/adjustments", $view_data);
    }

    function revaluation_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Fixed_assets_model->get_one($id);
        return $this->template->view("fixed_assets/revaluation_modal_form", $view_data);
    }

    function modal_revaluation_form() {
        return $this->revaluation_modal_form();
    }

    function save_revaluation() {
        $id = $this->request->getPost('id');
        $asset = $this->Fixed_assets_model->get_one($id);
        if (!$asset || !$asset->id) {
            echo json_encode(array("success" => false, "message" => "Asset record not found."));
            return;
        }

        $new_val = unformat_currency($this->request->getPost('adjusted_cost'));
        $prev_val = $asset->adjusted_cost;

        $data = array(
            "adjusted_cost" => $new_val,
            "net_book_value" => $new_val - $asset->accumulated_depreciation
        );

        $this->Fixed_assets_model->ci_save($data, $id);

        $log_data = array(
            "asset_id" => $id,
            "transaction_type" => 'REVALUATION',
            "previous_val" => $prev_val,
            "new_val" => $new_val,
            "notes" => $this->request->getPost('revaluation_notes') ?: "Asset valuation adjusted under IPSAS 17",
            "performed_by" => $this->login_user->id
        );
        $this->Asset_transaction_logs_model->ci_save($log_data);

        echo json_encode(array("success" => true, "message" => "Revaluation saved successfully."));
    }

    /* ─── 4. IPSAS 17 DEPRECIATION ENGINE ──────────────────────────── */
    function depreciation() {
        $view_data['totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        return $this->template->rander("fixed_assets/depreciation", $view_data);
    }

    function run_depreciation() {
        // Run IPSAS 17 Straight-line depreciation calculation across active assets
        $sql = "UPDATE ncs_fixed_assets 
                SET accumulated_depreciation = accumulated_depreciation + (adjusted_cost * 0.05 / 12),
                    net_book_value = GREATEST(0, adjusted_cost - (accumulated_depreciation + (adjusted_cost * 0.05 / 12)))
                WHERE deleted=0 AND status='ACTIVE' AND category_segment1 != 'NATURALLY OCCURRING ASSETS'";
        $this->db->query($sql);

        echo json_encode(array("success" => true, "message" => "Monthly IPSAS 17 straight-line depreciation run completed successfully."));
    }

    /* ─── 5. AUDIT SPOT-CHECKS & TAG SCANNING ──────────────────────── */
    function audit_verification() {
        $view_data['totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        return $this->template->rander("fixed_assets/audit_verification", $view_data);
    }

    function spot_check_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Fixed_assets_model->get_one($id);
        return $this->template->view("fixed_assets/spot_check_modal_form", $view_data);
    }

    function modal_spot_check_form() {
        return $this->spot_check_modal_form();
    }

    function mark_verified() {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('verification_status') ?: 'VERIFIED';
        $data = array(
            "verification_status" => $status,
            "last_verified_at" => date("Y-m-d H:i:s")
        );

        $this->Fixed_assets_model->ci_save($data, $id);

        $log_data = array(
            "asset_id" => $id,
            "transaction_type" => 'AUDIT_SPOT_CHECK',
            "notes" => "Auditor physical tag verification: " . $status,
            "performed_by" => $this->login_user->id
        );
        $this->Asset_transaction_logs_model->ci_save($log_data);

        echo json_encode(array("success" => true, "message" => "Audit verification logged successfully."));
    }

    /* ─── 6. DISPOSALS & STATUTORY WRITE-OFFS ──────────────────────── */
    function disposals() {
        $options = array("status" => "DISPOSED");
        $view_data['disposals'] = $this->Fixed_assets_model->get_details($options)->getResult();
        return $this->template->rander("fixed_assets/disposals", $view_data);
    }

    function disposal_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Fixed_assets_model->get_one($id);
        return $this->template->view("fixed_assets/disposal_modal_form", $view_data);
    }

    function modal_disposal_form() {
        return $this->disposal_modal_form();
    }

    function request_disposal() {
        $id = $this->request->getPost('id');
        $data = array(
            "status" => "DISPOSED"
        );
        $this->Fixed_assets_model->ci_save($data, $id);

        $log_data = array(
            "asset_id" => $id,
            "transaction_type" => 'DISPOSAL',
            "notes" => $this->request->getPost('notes') ?: "Statutory write-off approved",
            "performed_by" => $this->login_user->id
        );
        $this->Asset_transaction_logs_model->ci_save($log_data);

        echo json_encode(array("success" => true, "message" => "Statutory disposal & write-off processed successfully."));
    }

    /* ─── 7. FIXED ASSETS FINANCIAL REPORTS ────────────────────────── */
    function reports() {
        $view_data['totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        $view_data['categories'] = $this->Fixed_assets_model->get_category_summary()->getResult();
        return $this->template->rander("fixed_assets/reports", $view_data);
    }
}
