<?php
/**
 * Padma Theme main function file
 *
 * @since 1.0.0
 * @package Padma
 *
 * - Original by Clay Griffiths - Headway Themes
 * - New files by Maarten Schraven - UNITED 7
 * - Padma by PS Padma Team - PS Padma S.A.
 */

/**
 * Automatic Updates
 * Must go before Padma::init();
 */
if ( get_option( 'padma-disable-automatic-core-updates' ) !== '1' ) {

	add_filter( 'auto_update_theme', '__return_true' );

}

/**
 *
 * Load Padma
 */

/* Prevent direct access to this file */
if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	die( 'Please do not access this file directly.' );
}

/* Make sure PHP 7.0 or newer is installed and WordPress 3.4 or newer is installed. */
require_once get_template_directory() . '/library/common/compatibility-checks.php';

/* Load required packages */
require_once get_template_directory() . '/vendor/autoload.php';

/* Load Padma! */
require_once get_template_directory() . '/library/common/functions.php';
require_once get_template_directory() . '/library/common/parse-php.php';
require_once get_template_directory() . '/library/common/settings.php';
require_once get_template_directory() . '/library/loader.php';

Padma::init();


/**
 *
 * Plugin templates support
 */

add_filter(
	'template_include',
	function( $template ) {
		return PadmaDisplay::load_plugin_template( $template );
	}
);

/**
 * @@@@@@@@@@@@@@@@@ PS UPDATER 1.3 @@@@@@@@@@@
 **/
require 'psource/psource-plugin-update/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/cp-psource/ps-padma',
	__FILE__,
	'ps-padma'
);


//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');
/**
 * @@@@@@@@@@@@@@@@@ ENDE PS UPDATER 1.3 @@@@@@@@@@@
 **/
