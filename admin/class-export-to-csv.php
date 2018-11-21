<?php
/**
 * Export To CSV Class
 * Export all the custom post charity into CSV
 *
 * @since           0.1.0
 * @package         charity_export
 */

class ExportToCsv {

	/**
	 * A static reference to track the single instance of this class
	 */
	private static $instance = null;


	/**
	 * Method used to provide a single instance of this
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new ExportToCsv();
		}

		return self::$instance;

	}

	/**
	 * ExportToCsv constructor
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'do_csv_export' ], 20 );
	}


	/**
	 * Setup the date query
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function get_date_range( $data ) {

		$date_array = array();
		$date_query = array();

		if ( ! empty( $data['charity_exports']['start_date'] ) ) {
			$date_after          = $data['charity_exports']['start_date'] . ' 00:00:00';
			$date_array['after'] = $date_after;
		}


		if ( ! empty( $data['charity_exports']['end_date'] ) ) {
			$date_before          = $data['charity_exports']['end_date'] . ' 23:59:59';
			$date_array['before'] = $date_before;
		}

		if ( ! empty( $date_array ) ) {

			$date_array['inclusive'] = true;

			$date_query = array(
				$date_array
			);

		}

		return $date_query;
	}


	/**
	 * Exports the CSV
	 *
	 * @param $array_data
	 */
	public function do_csv_download( $array_data ) {

		$n_rows = 100;
		$memory = '300M';

		$sitename = sanitize_key( get_bloginfo( 'name' ) );
		if ( ! empty( $sitename ) ) {
			$sitename .= '-';
		}
		$filename = $sitename . 'charities-' . date( 'Y-m-d-H-i-s' ) . '.csv';


		ini_set( 'memory_limit', $memory );

		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Cache-Control: private', false );
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
		header( 'Content-Transfer-Encoding: binary' );


		ini_set( 'zlib.output_compression_level', 9 );
		ob_start( 'ob_gzhandler' );

		$count = 0;
		$out   = fopen( 'php://output', 'w' );
		foreach ( $array_data as $row ) {
			fputcsv( $out, $row );

			if ( ++ $count % $n_rows == 0 ) {
				ob_flush();
				flush();
			}
		}
		fclose( $out );
		exit();
	}


	/**
	 * Setup the data for the CSV export
	 */
	public function do_csv_export() {
		global $pagenow;

		if ( 'edit.php' != $pagenow || empty( $_REQUEST['page'] ) || 'charity_export' != $_REQUEST['page'] || empty( $_POST['csv_export'] ) ) {
			return;
		}

		$args = array(
			'posts_per_page' => '-1',
			'post_type'      => 'charity',
			'orderby'        => 'date',
			'order'          => 'ASC'
		);

		$date_query = $this->get_date_range( $_POST );

		if ( $date_query ) {
			$args['date_query'] = $date_query;
		}

		$out = array();


		$titles = array(
			'Title',
			'Description',
			'Status',
			'Date',
			'Category',
			'Tag',
			'Address 1',
			'Address 2',
			'Address 3',
			'City',
			'Postcode',
			'Latitude',
			'Longtitude',
			'URL',
			'Logo'
		);
		array_push( $out, $titles );

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ): $query->the_post();

				$post_id = get_the_ID();

				$categories = '';
				$allcats    = get_the_category( $post_id );
				if ( $allcats ) {
					$catsarray = array();
					foreach ( $allcats as $cat ) {
						$catsarray[] = $cat->name;
						$categories  = implode( ", ", $catsarray );
					}
				}


				$tags    = '';
				$alltags = get_the_tags( $post_id );
				if ( $alltags ) {
					$tagsarray = array();
					foreach ( $alltags as $tag ) {
						$tagsarray[] = $tag->name;
						$tags        = implode( ", ", $tagsarray );
					}
				}

				$title = html_entity_decode( esc_html( wp_strip_all_tags( get_the_title() ) ),
					ENT_QUOTES | ENT_XML1, get_option( 'blog_charset' ) );

				$description = html_entity_decode( esc_html( wp_strip_all_tags( strip_shortcodes( get_the_content() ) ) ),
					ENT_QUOTES | ENT_XML1, get_option( 'blog_charset' ) );

				$row = array(
					$title,
					$description,
					get_post_status( $post_id ),
					get_the_date( 'd/m/Y', $post_id ),
					$categories,
					$tags,
					get_post_meta( $post_id, 'address_1', true ),
					get_post_meta( $post_id, 'address_2', true ),
					get_post_meta( $post_id, 'address_3', true ),
					get_post_meta( $post_id, 'address_city', true ),
					get_post_meta( $post_id, 'latitude', true ),
					get_post_meta( $post_id, 'longtitude', true ),
					get_post_meta( $post_id, 'URL', true ),
					get_the_post_thumbnail_url( $post_id )

				);
				array_push( $out, $row );

			endwhile;
			wp_reset_postdata();
			$this->do_csv_download( $out );

		} else {
			wp_redirect( add_query_arg( [
				'post_type' => 'charity',
				'page'      => 'charity_export',
				'message'   => urlencode( 'No charity found.' )
			], admin_url( 'edit.php' ) ) );
		}

	}

}