<?php
/*
Plugin Name: Plugin AJAX
Plugin URI: https://makitweb.com
description: A custom plugin to demonstrate call and handle AJAX request
Version: 1.0.0
Author: Yogesh Singh
Author URI: https://makitweb.com/about
*/

// Add menu
function pluginajax_menu() {
    add_menu_page("Plugin AJAX", "Plugin AJAX","manage_options", "myplugin", "employeeList",plugins_url('/pluginajax/img/icon.png'));
}

add_action("admin_menu", "pluginajax_menu");

function employeeList(){
    include "employeeList.php";

}





/**
 * @param \PHPMailer\PHPMailer\PHPMailer $mail
 * @return void
 */

function serverSettings(\PHPMailer\PHPMailer\PHPMailer $mail): void
{
    $mail->SMTPDebug = 1;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = '6e380d75012caa';
    $mail->Password = '3a6c7363c5dc8f';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 2525;
}

add_action( 'phpmailer_init', 'mailer_config', 10, 1);
function mailer_config(PHPMailer $mailer){
    $mailer->IsSMTP();
    $mailer->Host = "smtp.gmail.com"; // your SMTP server
    $mailer->Port = 25;
    $mailer->SMTPDebug = 2; // write 0 if you don't want to see client/server communication in page
    $mailer->SMTPAuth = true;                                   //Enable SMTP authentication
    $mailer->Username = '6e380d75012caa';
    $mailer->Password = '3a6c7363c5dc8f';
    $mailer->CharSet  = "utf-8";
}

add_action( 'wp_ajax_contactMailList', 'contactMailList' );
add_action( 'wp_ajax_nopriv_contactMailList', 'contactMailList' );
function contactMailList() {

    $formdata = [];
    wp_parse_str($_POST['formData'], $formdata);


    //user posted variables
    $name = 'tanvir';
    $email = 'anmtanvir872@gmail.com';
    $message = 'hello message';

//php mailer variables
    $to = 'anmtanvir872@gmail.com';
    $subject = "Some text in subject...";
    $headers = 'From: '. $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n";


//    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

     //Server settings
//      mailer_config($mail) ;

//Here put your Validation and send mail
    $sent = wp_mail($to, $subject, strip_tags($message), $headers);

    if($sent) {
        //message sent!
    }
    else  {
        //message wasn't sent
    }



//    print_r($formdata['states']);

//    //Admin email address
//    $admin_email = 'feedback@mediasoft-bd.net'; //sender email
//
//    //Email headers
//    $headers[] = 'Content-Type:text/html; charset=UTF-8';
//    $headers[] = 'From:' . $admin_email;
//    $headers[] = 'Reply-to:' . $formdata['email'];
//
//    //who are we sending email to ?
//
//    $send_to = $admin_email;
//
//    //subject
//    $subject = "Feedback from  " . $formdata['Name:'];
//
//    try {
//
//        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
//
//            //Server settings
//            serverSettings($mail);
//
//
//            $customer_mail = $formdata['states'];
//
//            //Recipients
//            $mail->setFrom('enquiry@mediasoftbd.com', 'Enquiry Mail');
//            $mail->addAddress('khanmarzuk@gmail.com', 'Enquiry Mail');     //will be enquiry email
//            $mail->addReplyTo('enquiry@mediasoftbd.com', 'Enquiry Mail');
//
//            //Content
//            $mail->isHTML(true);   //Set email format to HTML
//            $mail->Subject = 'Inquiry Mail';
//            $mail->Body = 'hello';
//            $mail->send();
//
//
//
//    } catch (Exception $e) {
//        wp_send_json_error($e->getMessage());
//    }

}

/* Include CSS and Script */
add_action('wp_enqueue_scripts','plugin_css_jsscripts');
function plugin_css_jsscripts() {
    // CSS
    wp_enqueue_style( 'style-css', plugins_url( '/style.css', __FILE__ ));

    // JavaScript
    wp_enqueue_script( 'script-js', plugins_url( '/script.js', __FILE__ ),array('jquery'));

    // Pass ajax_url to script.js
    wp_localize_script( 'script-js', 'plugin_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

/* AJAX request */

## Fetch all records
add_action( 'wp_ajax_employeeList', 'employeeList_callback' );
add_action( 'wp_ajax_nopriv_employeeList', 'employeeList_callback' );
function employeeList_callback() {
    global $wpdb;

    $response = array();

    // Fetch all records
    $response = $wpdb->get_results("SELECT * FROM employee");

    echo json_encode($response);
    wp_die();



}

## Search record
add_action( 'wp_ajax_searchEmployeeList', 'searchEmployeeList_callback' );
add_action( 'wp_ajax_nopriv_searchEmployeeList', 'searchEmployeeList_callback' );
function searchEmployeeList_callback() {
    global $wpdb;

    $request = $_POST['request'];
    $response = array();

    // Fetch record by id
    $searchText = $_POST['searchText'];

    $searchQuery = "";
    if($searchText != ''){
        $searchQuery = " and ( emp_name like '%".$searchText."%' or email like '%".$searchText."%' or city like '%".$searchText."%' )";
    }

    $response = $wpdb->get_results("SELECT * FROM employee WHERE 1 ".$searchQuery);

    echo json_encode($response);
    wp_die();
}


