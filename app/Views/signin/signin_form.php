<div class="card bg-white mb15">
    <div class="card-header text-center">
        <?php if (get_setting("show_logo_in_signin_page") === "yes") { ?>
            <img class="p20 mw100p" src="<?php echo get_logo_url(); ?>" />
        <?php } else { ?>
            <h2><?php echo app_lang('signin'); ?></h2>
        <?php } ?>
    </div>
    <div class="card-body p30 rounded-bottom">
        <?php
        $session = \Config\Services::session();
        $signin_validation_errors = $session->getFlashdata("signin_validation_errors");
        if ($signin_validation_errors && is_array($signin_validation_errors)) {
            ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($signin_validation_errors as $validation_error) { ?>
                    <i data-feather="alert-circle" class="icon-16"></i>
                    <?php echo $validation_error; ?>
                    <br />
                <?php } ?>
            </div>
        <?php } ?>

        <?php if (ENVIRONMENT !== 'production') { ?>
        <?php echo form_open("signin/authenticate", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>
        <div class="form-group">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control p10",
                "placeholder" => app_lang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
                "data-rule-email" => true,
                "data-msg-email" => app_lang("enter_valid_email")
            ));
            ?>
        </div>
        <div class="form-group">
            <?php
            echo form_password(array(
                "id" => "password",
                "name" => "password",
                "class" => "form-control p10",
                "placeholder" => app_lang('password'),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required")
            ));
            ?>
        </div>
        <input type="hidden" name="redirect" value="<?php
        if (isset($redirect)) {
            echo $redirect;
        }
        ?>" />


        <?php echo view("signin/re_captcha"); ?>

        <button class="w-100 btn btn-lg btn-primary" type="submit"><?php echo app_lang('signin'); ?></button>

        <?php echo form_close(); ?>
        <?php } ?>
        <div class="mt20 text-center">
            <div class="ncs-ugpass-label text-muted mb10">Sign in with</div>
            <?php
            $ugpass_login_url = get_uri('ugpass/login');
            if (isset($redirect) && $redirect) {
                $ugpass_login_url .= '?redirect=' . urlencode($redirect);
            }
            ?>
            <a href="<?php echo $ugpass_login_url; ?>" class="ncs-ugpass-button" role="button" aria-label="Sign in with UG Pass" title="Authenticate securely with National Information Technology Authority (NITA-U) UG Pass">
                <img src="<?php echo get_file_uri('assets/images/ugpass.png'); ?>" alt="Sign in with UG Pass" />
            </a>
        </div>

        <?php
        app_hooks()->do_action('app_hook_signin_extension');
        ?>
    </div>
</div>


<?php if (ENVIRONMENT !== 'production') { ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#signin-form").appForm({ajaxSubmit: false, isModal: false});
    });
</script>
<?php } ?>

<style>
    .ncs-ugpass-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 200px;
        min-height: 52px;
        padding: 8px 24px;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(24, 39, 75, .08), 0 2px 6px rgba(24, 39, 75, .04);
        opacity: 1;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }
    .ncs-ugpass-button:hover {
        background: #f8fafc;
        border-color: #0b69a3;
        box-shadow: 0 4px 12px rgba(11, 105, 163, .15);
        transform: translateY(-1px);
        text-decoration: none;
    }
    .ncs-ugpass-button:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(24, 39, 75, .1);
    }
    .ncs-ugpass-button:focus {
        outline: 2px solid #0b69a3;
        outline-offset: 2px;
    }
    .ncs-ugpass-button img {
        display: block;
        max-width: 155px;
        max-height: 38px;
        width: auto;
        height: auto;
    }
    .ncs-ugpass-label {
        font-size: 1.1rem;
        font-weight: 500;
    }
    @media (max-width: 576px) {
        .ncs-ugpass-label {
            font-size: 1rem;
        }
    }
</style>
