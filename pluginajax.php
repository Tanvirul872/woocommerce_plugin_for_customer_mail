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


