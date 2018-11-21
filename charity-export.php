<?php
/**
 * Plugin Name:     Charity Export
 * Plugin URI:      https://github.com/xlthlx/charity-export
 * Description:     Plugin to generate a CSV file to list all the Charity, with an option to choose start and end date
 * Author:          Helpful Digital
 * Author URI:      https://www.helpfuldigital.com
 * Text Domain:     charity-export
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         charity_export
 */


if ( ! function_exists( 'charity_export_initialise' ) ) :

	/**
	 * A global function to rule them all
	 * @return void
	 */
	function charity_export_initialise() {

		/*
		 * Setup the charity custom post type.
		 * Ideally everything could be managed from the plugin,
		 * also the custom meta and the frontend logic, so you can change theme more easily
		 */

		//require_once __DIR__ . '/post-types/charity.php';

		if ( is_admin() ) {
			require_once __DIR__ . '/admin/functions-admin.php';
		}

	}
endif;

/**
 * Init the plugin
 * @return object
 */
function charity_export() {
	static $charity_export;

	if ( ! $charity_export ) {
		$charity_export = charity_export_initialise();
	}

	return $charity_export;
}

charity_export();