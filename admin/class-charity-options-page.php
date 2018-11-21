<?php
/**
 * Charity Options Page Class
 * Add a options page under the charity custop post type
 *
 * @since           0.1.0
 * @package         charity_export
 */


class CharityOptionsPage {

	/**
	 * A static reference to track the single instance of this class
	 */
	private static $instance = null;


	/**
	 * Method used to provide a single instance of this
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new CharityOptionsPage();
		}

		return self::$instance;

	}

	/**
	 * CharityOptionsPage constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu',
			array( $this, 'add_submenu_page_to_post_type' ) );
		add_action( 'admin_init', array( $this, 'sub_menu_page_init' ) );
	}

	/**
	 * Add sub menu page to the custom post type
	 */
	public function add_submenu_page_to_post_type() {
		add_submenu_page(
			'edit.php?post_type=charity',
			__( 'Charities Export', 'charity' ),
			__( 'Charities Export', 'charity' ),
			'edit_others_posts',
			'charity_export',
			array( $this, 'charity_options_display' ) );
	}

	/**
	 * Options page callback
	 */
	public function charity_options_display() {
		$this->options = get_option( 'charity_exports' );

		echo '<div class="wrap">';

		printf( '<h1>%s</h1>', __( 'Charities Export', 'charity' ) );

		if ( ! empty( $_REQUEST['message'] ) && isset( $_REQUEST['message'] ) ) {
			$message = $_REQUEST['message'];
		}

		if ( ! empty( $message ) ) { ?>
            <div class="error">
                <p><?php echo $message; ?></p>
            </div>
			<?php
		}

		echo '<form method="post">';

		settings_fields( 'charities_exports' );
		do_settings_sections( 'charities-exports-page' );
		submit_button( 'Download Export (CSV)', 'primary', 'csv_export' );

		echo '</form></div>';

	}

	/**
	 * Register and add settings
	 */
	public function sub_menu_page_init() {
		register_setting(
			'charities_exports',
			'charity_exports'
		);

		add_settings_section(
			'header_settings_section',
			'', // Title
			array( $this, 'print_section_info' ), // Callback
			'charities-exports-page'
		);

		add_settings_field(
			'start_date',
			__( 'Start date', 'charity' ), // Title
			array( $this, 'start_date_callback' ), // Callback
			'charities-exports-page',
			'header_settings_section'
		);

		add_settings_field(
			'end_date',
			__( 'End date', 'charity' ), // Title
			array( $this, 'end_date_callback' ), // Callback
			'charities-exports-page',
			'header_settings_section'
		);
	}

	/**
	 * Print the section text
	 */
	public function print_section_info() {
		print '<p>Export all Charities into CSV, or select a date range.</p>';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function start_date_callback() {
		printf(
			'<input type="date" id="start_date" name="charity_exports[start_date]" value="%s" />',
			''
		);
	}

	public function end_date_callback() {
		printf(
			'<input type="date" id="end_date" name="charity_exports[end_date]" value="%s" />',
			''
		);
	}


}