<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function  thet_customize_report_enter_password_form() {

    ob_start();

    ?>
        <div class="container mt-6 box p-5 has-text-centered" style="max-width: 560px!important;">
            <form action="<?php echo esc_url(site_url() . '/wp-login.php?action=postpass' )?>" method="post">
                <h3 class="title is-3">Password protected</h3>
                <div class="block mx-auto" style="max-width: 80%;;"><p>
                    If you don't know the password reach out to the person sharing the link with you and ask for it.
                </p></div>
                
                <div class="field">
                    <label class="label" for="post_password">Password:</label>
                    <div class="control">
                        <input class="input is-info has-text-centered" name="post_password" id="post-password" type="password" spellcheck="false" style="max-width: 60%;">
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <input class="button is-link" type="submit" name="Submit" value="Enter">
                    </div>
                </div>
            </form>
        </div>
    <?php

    $output = ob_get_clean();
    return $output;

}

add_filter('the_password_form', 'thet_customize_report_enter_password_form');
