<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bat_send_report_to_user_ajax_endpoint(){

    if( $_SERVER['REQUEST_METHOD'] !== 'POST' ||
        !isset($_POST['bat_ajax_nonce']) || 
        !wp_verify_nonce($_POST['bat_ajax_nonce'], 'bat_ajax') ) {
        
        wp_send_json_error(['success' => false, 'message' => 'Not of POST method or Nonce invalid'], 403);
        
    }

    if( !current_user_can('edit_report')){
        wp_send_json_error(['success'=>false, 'message' => 'I am afraid you can not do that'], 403);
    }

    if( !isset($_POST['report_id'] )){
        wp_send_json_error(['success'=>false, 'message' => 'Report ID was not provided. Cannot send message.'], 403);
    }

    $report_id = intval( sanitize_text_field( $_POST['report_id'] ));
    $connected_application = get_post_meta( $report_id, 'connected_application', true);

    $to = get_userdata(get_post($connected_application)->post_author)->user_email;
    $subject = "New report available | BAT Tool";
    $message = "Hello,\n\n";
    $message .= "there is new report prepared for you.\n\n";
    $message .= "You can view it by following this link:\n";
    $message .= get_permalink($report_id) . "\n";
    $message .= "Password: " . get_post($report_id)->post_password . "\n\n";
    $message .= "This is automated message. If you will have any questions feel free to reach out to your coach.";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Bcc: ' . get_userdata(get_current_user_id())->user_email,
    ];

    $mail_response = wp_mail($to, $subject, $message, $headers);

    if( $mail_response === true ){
        wp_send_json([
            'success' => true,
            'message' => 'Mail sucessfully sent.'
        ]);
    } else {
        wp_send_json_error([
            'success' => false,
            'message' => 'Something went wrong while sending the e-mail.'
        ], 500);
    }
    
}

add_action('wp_ajax_bat_reports_notification', 'bat_send_report_to_user_ajax_endpoint');
