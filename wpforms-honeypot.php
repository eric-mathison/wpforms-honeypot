<?php
/**
 * Plugin Name: WPForms Honeypot
 * Description: Allows you to add a custom honeypot field to WPForms to help combat spam.
 * Version: 0.0.0-development
 * Author: Eric Mathison
 * Text Domain: wpforms-honeypot
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: eric-mathison/wpforms-honeypot
 *
 * @package WPForms_Honeypot
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Definitons.
define( 'WPFORMS_HONEYPOT_VERSION' , '0.0.0-development' );
define( 'WPFORMS_HONEYPOT_CLASS', 'wpfhp-field' );

/**
 * Load plugin files.
 */
function wpforms_honeypot_load_text_domain() {
    load_plugin_textdomain( 'wpforms-honeypot', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
}

add_action( 'plugins_loaded', 'wpforms_honeypot_load_text_domain' );

// Load CSS file
function wpforms_honeypot_enqueue_css() {
    wp_enqueue_style( 'wpforms-honeypot-css', plugins_url( '/includes/css/wpforms-honeypot.css', __FILE__ ), false, WPFORMS_HONEYPOT_VERSION, 'all' );
}

add_action( 'wp_enqueue_scripts', 'wpforms_honeypot_enqueue_css' );

// Build custom honeypot
function wpforms_honeypot_field( $honeypot, $fields, $entry, $form_data ) {
    $honeypot_class = WPFORMS_HONEYPOT_CLASS;
    $honeypot_field = false;
    
    foreach( $form_data['fields'] as $form_field ) {
        if ( false !== strpos( $form_field['css'], $honeypot_class ) ) {
            $honeypot_field = absint( $form_field['id'] );
        }
    }

    if ( !empty( $entry['fields'][$honeypot_field] ) ) {
        $honeypot = 'WPForms Honeypot';
    }

    return $honeypot;

}

add_filter( 'wpforms_process_honeypot', 'wpforms_honeypot_field', 10, 4 );

// Setup Optional Logging
add_action ( 'init', function() {
    $debug = get_option( 'wpforms_logging' );
    if ( empty( $debug ) || !in_array( 'spam', $debug ) ) {
        update_option( 'wpforms_logging', [ 'spam' ] );
    }
});

add_filter( 'wpforms_log_cpt', function( $args ) {
    $args['show_ui'] = true;
    unset( $args['capability_type'] );
    return $args;
});