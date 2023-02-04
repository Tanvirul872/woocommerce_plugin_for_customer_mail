<?php
/*
Plugin Name: Send Mail from Woocommerce
Plugin URI: https://anmtanvir.com/send-mail-woocommerce
description: A custom plugin which we can use to send email template to customers of woocommrece
Version: 1.0.0
Author: Tanvirul Karim
Author URI: https://anmtanvir.com/
*/

// Add menu
function pluginajax_menu() {
    add_menu_page("Plugin AJAX", "Plugin AJAX","manage_options", "myplugin", "employeeList",plugins_url('/pluginajax/img/icon.png'));
}

add_action("admin_menu", "pluginajax_menu");

function employeeList(){ 


    include "template.php";

}


add_action('woocommerce_after_shop_loop_item_title','cm_woo_stock_quantity');
function cm_woo_stock_quantity(){

echo "Hi vaijan"  ; 

global $product;

// When product sizes are available



}


add_filter(‘woocommerce_product_add_to_cart_text’, function ($text) {
    global $product;
    if ($product->is_type(‘variable’)) {
    $text = $product->is_purchasable() ? __(‘Add to cart’, ‘woocommerce’)
    : __(‘Read more’, ‘woocommerce’);
    }
    return $text;
    }, 10); 



// function mailtrap($phpmailer) {
//     $phpmailer->isSMTP();
//     $phpmailer->Host = 'smtp.gmail.com';
//     $phpmailer->SMTPAuth = true;
//     $phpmailer->Port = 465;
//     $phpmailer->Username = 'giftmail@tandkhospitality.com';
//     $phpmailer->Password = 'Gift2023$$';
//   }
  
  function mailtrap($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = '6e380d75012caa';
    $phpmailer->Password = '3a6c7363c5dc8f';
  }
  
  add_action('phpmailer_init', 'mailtrap');

add_action( 'wp_ajax_contactMailList', 'contactMailList' );
add_action( 'wp_ajax_nopriv_contactMailList', 'contactMailList' );
function contactMailList() {

    $formdata = [];
    wp_parse_str($_POST['formData'], $formdata); 


    print_r($formdata) ; 

$to = $formdata['emails'] ; 

$subject = $formdata['temp_title'];
$body = $formdata['temp_desc'];
$headers[] = 'Content-type: text/html; charset=utf-8';
$headers[] = 'From:' . "testing@gmail.com";

$test = wp_mail( $to , $subject, $body, $headers );

 if($test){
    echo 'send' ; 
 }else{
    echo 'not send' ; 
 }
     
}



function enqueue_select2_jquery() {
    wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
    wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );



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


