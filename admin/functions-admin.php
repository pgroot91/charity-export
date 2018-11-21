<?php
/**
 * Admin functions
 *
 * @since           0.1.0
 * @package         charity_export
 */


/*
 * Init the option page class
 */
require_once( __DIR__ . '/class-charity-options-page.php' );
add_action( 'plugins_loaded', array( 'CharityOptionsPage', 'get_instance' ) );

/*
 * Init the export to CSV class
 */
require_once( __DIR__ . '/class-export-to-csv.php' );
add_action( 'plugins_loaded', array( 'ExportToCsv', 'get_instance' ) );