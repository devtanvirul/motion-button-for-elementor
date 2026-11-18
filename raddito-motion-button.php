<?php
/**
 * Plugin Name: Motion Button for Elementor
 * Description: A customizable animated button for Elementor Free with directional reveals, icon animation, responsive styles and global colors.
 * Version: 1.0.5
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Author: devtanvirul
 * Author URI: https://devtanvirul.com
 * Text Domain: raddito-motion-button
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'RMB_VERSION', '1.0.5' );
define( 'RMB_PATH', plugin_dir_path( __FILE__ ) );
define( 'RMB_URL', plugin_dir_url( __FILE__ ) );

/** Display a non-blocking notice when Elementor is missing. */
function rmb_missing_elementor_notice() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
    echo '<div class="notice notice-warning"><p>' . esc_html__( 'Motion Button for Elementor requires the free Elementor plugin to be installed and active.', 'raddito-motion-button' ) . '</p></div>';
}

/** Register frontend dependencies; Elementor loads them only for pages using the widget. */
function rmb_register_assets() {
    wp_register_style( 'rmb-button', RMB_URL . 'assets/css/button.css', array(), RMB_VERSION );
    // GSAP is loaded only when Elementor outputs this widget.
    // Uses an official public distribution; local Web Animations fallback remains available if blocked.
    wp_register_script( 'rmb-gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.15.0/dist/gsap.min.js', array(), '3.15.0', true );
    wp_register_script( 'rmb-button', RMB_URL . 'assets/js/button.js', array( 'rmb-gsap' ), RMB_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'rmb_register_assets' );
add_action( 'elementor/frontend/after_register_styles', 'rmb_register_assets' );
add_action( 'elementor/frontend/after_register_scripts', 'rmb_register_assets' );

function rmb_boot() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'rmb_missing_elementor_notice' );
        return;
    }
    add_action( 'elementor/widgets/register', 'rmb_register_widgets' );
}
add_action( 'plugins_loaded', 'rmb_boot' );

function rmb_register_widgets( $widgets_manager ) {
    require_once RMB_PATH . 'includes/class-rmb-button-widget.php';
    $widgets_manager->register( new \Raddito_Motion_Button_Widget() );
}
