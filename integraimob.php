<?php

if ( !defined('ABSPATH') ) {
    //If wordpress isn't loaded load it up.
    $path = $_SERVER['DOCUMENT_ROOT'];
    include_once $path . '/grazi/wp-load.php';
}

/*
Plugin Name: Integraimob
Description: Integraimob é um plugin de Integração de Imóveis com os principais portais imobiliários do Brasil.
Author: Bóson & Higgs - Digital Solutions
*/

require_once plugin_dir_path(__FILE__) . 'core/inc/xml_writer.php';

require_once plugin_dir_path(__FILE__) . 'core/sample_olx.php';

require_once plugin_dir_path(__FILE__) . 'core/export-xml.php';

require_once plugin_dir_path(__FILE__) . 'imb-functions.php';

if( $_GET['export'] ){
	imb_export_xml_ativos();
}