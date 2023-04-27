<?php
/*
Plugin Name: AI Connecter
Plugin URI: https://aiconnecter.com/
Description: This plugin integrates OpenAI API and AI Connecter service for WordPress and WooCommerce.
Version: 1.0
Author: AI Connecter
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: aiconnecter
Domain Path: /languages
*/

if (!defined('WPINC')) {
    die;
}

// Incluir archivos necesarios
require_once(plugin_dir_path(__FILE__) . 'inc/api-functions.php');
require_once(plugin_dir_path(__FILE__) . 'inc/settings.php');
require_once(plugin_dir_path(__FILE__) . 'inc/frontend.php');

// Activación del plugin
function aiconn_support_plugin_activate() {

}
register_activation_hook(__FILE__, 'aiconn_support_plugin_activate');

// Desactivación del plugin
function aiconn_support_plugin_deactivate() {
}
register_deactivation_hook(__FILE__, 'aiconn_support_plugin_deactivate');

// Cargar el dominio de texto para la internacionalización
function aiconn_support_plugin_load_textdomain() {
    load_plugin_textdomain('aiconnecter', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'aiconn_support_plugin_load_textdomain');
