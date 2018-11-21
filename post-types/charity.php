<?php
/**
 * Fuctions to add the custom post type charity
 *
 * @since           0.1.0
 * @package         charity_export
 */

add_action( 'init', 'charity_init' );
add_filter( 'post_updated_messages', 'charity_updated_messages' );

/**
 * Registers the `charity` post type.
 */
function charity_init() {
	register_post_type( 'charity', array(
		'labels'                => array(
			'name'                  => __( 'Charities', 'charity' ),
			'singular_name'         => __( 'Charity', 'charity' ),
			'all_items'             => __( 'All Charities', 'charity' ),
			'archives'              => __( 'Charity Archives', 'charity' ),
			'attributes'            => __( 'Charity Attributes', 'charity' ),
			'insert_into_item'      => __( 'Insert into charity', 'charity' ),
			'uploaded_to_this_item' => __( 'Uploaded to this charity',
				'charity' ),
			'featured_image'        => _x( 'Featured Image', 'charity',
				'charity' ),
			'set_featured_image'    => _x( 'Set featured image', 'charity',
				'charity' ),
			'remove_featured_image' => _x( 'Remove featured image', 'charity',
				'charity' ),
			'use_featured_image'    => _x( 'Use as featured image', 'charity',
				'charity' ),
			'filter_items_list'     => __( 'Filter charities list', 'charity' ),
			'items_list_navigation' => __( 'Charities list navigation',
				'charity' ),
			'items_list'            => __( 'Charities list', 'charity' ),
			'new_item'              => __( 'New Charity', 'charity' ),
			'add_new'               => __( 'Add New', 'charity' ),
			'add_new_item'          => __( 'Add New Charity', 'charity' ),
			'edit_item'             => __( 'Edit Charity', 'charity' ),
			'view_item'             => __( 'View Charity', 'charity' ),
			'view_items'            => __( 'View Charities', 'charity' ),
			'search_items'          => __( 'Search charities', 'charity' ),
			'not_found'             => __( 'No charities found', 'charity' ),
			'not_found_in_trash'    => __( 'No charities found in trash',
				'charity' ),
			'parent_item_colon'     => __( 'Parent Charity:', 'charity' ),
			'menu_name'             => __( 'Charities', 'charity' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'charity',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}

/**
 * Sets the post updated messages for the `charity` post type.
 *
 * @param  array $messages Post updated messages.
 *
 * @return array Messages for the `charity` post type.
 */
function charity_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['charity'] = array(
		0  => '',
		// Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Charity updated. <a target="_blank" href="%s">View charity</a>',
			'charity' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'charity' ),
		3  => __( 'Custom field deleted.', 'charity' ),
		4  => __( 'Charity updated.', 'charity' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Charity restored to revision from %s',
			'charity' ),
			wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Charity published. <a href="%s">View charity</a>',
			'charity' ), esc_url( $permalink ) ),
		7  => __( 'Charity saved.', 'charity' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Charity submitted. <a target="_blank" href="%s">Preview charity</a>',
			'charity' ),
			esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Charity scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview charity</a>',
			'charity' ),
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ),
			esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Charity draft updated. <a target="_blank" href="%s">Preview charity</a>',
			'charity' ),
			esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
