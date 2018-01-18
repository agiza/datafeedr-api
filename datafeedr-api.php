<?php
/*
Plugin Name: Datafeedr API
Plugin URI: http://www.datafeedr.com
Description: Connect to the Datafeedr API and configure your API settings.
Author: datafeedr.com
Author URI: http://www.datafeedr.com
License: GPL v3
Requires at least: 3.8
Tested up to: 4.9.2
Version: 1.0.78

Datafeedr API Plugin
Copyright (C) 2018, Datafeedr - help@datafeedr.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants.
 */
define( 'DFRAPI_VERSION', '1.0.78' );
define( 'DFRAPI_URL', plugin_dir_url( __FILE__ ) );
define( 'DFRAPI_PATH', plugin_dir_path( __FILE__ ) );
define( 'DFRAPI_BASENAME', plugin_basename( __FILE__ ) );
define( 'DFRAPI_DOMAIN', 'datafeedr-api' );
define( 'DFRAPI_HOME_URL', 'http://www.datafeedr.com' );
define( 'DFRAPI_KEYS_URL', 'https://members.datafeedr.com/api' );
define( 'DFRAPI_USER_URL', 'https://members.datafeedr.com/' );
define( 'DFRAPI_HELP_URL', 'https://datafeedrapi.helpscoutdocs.com/contact' );
define( 'DFRAPI_BUG_REPORTS_URL', 'https://datafeedrapi.helpscoutdocs.com/' );
define( 'DFRAPI_QNA_URL', 'https://datafeedrapi.helpscoutdocs.com/' );
define( 'DFRAPI_DOCS_URL', 'https://datafeedrapi.helpscoutdocs.com/' );
define( 'DFRAPI_REPORT_BUG_URL', 'https://datafeedrapi.helpscoutdocs.com/contact' );
define( 'DFRAPI_ASK_QUESTION_URL', 'https://datafeedrapi.helpscoutdocs.com/contact' );
define( 'DFRAPI_EMAIL_US_URL', 'https://datafeedrapi.helpscoutdocs.com/contact' );

/**
 * Require WP 3.8+
 */
add_action( 'admin_init', 'dfrapi_wp_version_check' );
function dfrapi_wp_version_check() {
	$version = get_bloginfo( 'version' );
	if ( version_compare( $version, '3.8', '<' ) ) {
		deactivate_plugins( DFRAPI_BASENAME );
	}
}

/**
 * Notify user that this plugin is deactivated.
 */
add_action( 'admin_notices', 'dfrapi_wp_version_notice' );
function dfrapi_wp_version_notice() {
	$version = get_bloginfo( 'version' );
	if ( version_compare( $version, '3.8', '<' ) ) {
		echo '<div class="error"><p>' . __( 'The ', DFRAPI_DOMAIN ) . '<strong><em>';
		_e( 'Datafeedr API', DFRAPI_DOMAIN );
		echo '</em></strong>';
		_e( ' plugin could not be activated because it requires WordPress version 3.8 or greater. Please upgrade your installation of WordPress.',
			DFRAPI_DOMAIN );
		echo '</p></div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

/**
 * Load files for all pages.
 */
require_once( DFRAPI_PATH . 'classes/class-datafeedr-plugin-dependency.php' );
require_once( DFRAPI_PATH . 'classes/class-datafeedr-timer.php' );
require_once( DFRAPI_PATH . 'classes/class-datafeedr-image-importer.php' );
require_once( DFRAPI_PATH . 'functions/functions.php' );
require_once( DFRAPI_PATH . 'functions/upgrade.php' );
require_once( DFRAPI_PATH . 'libraries/datafeedr.php' );
require_once( DFRAPI_PATH . 'libraries/zanox_client.php' );
require_once( DFRAPI_PATH . 'classes/class-dfrapi-searchform.php' );
require_once( DFRAPI_PATH . 'functions/api.php' );

/**
 * Display admin notices for each required plugin that needs to be
 * installed, activated and/or updated.
 *
 * @since 1.0.75
 */
function dfrapi_admin_notice_plugin_dependencies() {

	/**
	 * @var Dfrapi_Plugin_Dependency[] $dependencies
	 */
	$dependencies = array();

	foreach ( $dependencies as $dependency ) {

		$action = $dependency->action_required();

		if ( ! $action ) {
			continue;
		}

		echo '<div class="notice notice-error"><p>';
		echo $dependency->msg( 'Datafeedr API' );
		echo $dependency->link();
		echo '</p></div>';
	}
}

add_action( 'admin_notices', 'dfrapi_admin_notice_plugin_dependencies' );

/**
 * Load files only if we're in the admin section of the site.
 */
if ( is_admin() ) {
	require_once( DFRAPI_PATH . 'classes/class-dfrapi-initialize.php' );
}