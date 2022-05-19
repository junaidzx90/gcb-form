<?php
global $gcbloginAlerts;
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Gcb_Form
 * @subpackage Gcb_Form/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="gcb_form_wrapper">
    <div class="gcb_form_container">
        <div class="title_container">
            <h2>Login Form</h2>
            <?php
            if(!empty($gcbloginAlerts)){
                ?>
                <div class="gcb_errors"><i class="fas fa-exclamation-circle"></i>
                <p><?php echo $gcbloginAlerts ?></p>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="row clearfix">
            <div class="form-wrapper">
                <form id="gcb__login_form" method="post" enctype="multipart/form-data">
                    <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i></span>
                        <input type="text" name="gcb_login_email" placeholder="Email OR Username" />
                    </div>
                    <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i></span>
                        <input type="password" name="gcb_login_password" placeholder="Password" />
                    </div>
                    <div class="input_field checkbox_option">
                        <input type="checkbox" name="gcb_login_remember" id="cb1">
                        <label for="cb1">Remember me</label>
                    </div>
                    <?php wp_nonce_field( 'gcb_nonce', 'gcb_login_nonce' ) ?>
                    <input class="button" type="submit" name="gcb_login" value="Login" />
                </form>
            </div>
        </div>
    </div>
</div>