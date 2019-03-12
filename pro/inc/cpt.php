<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! function_exists('openagenda_event') ) {

// Register Custom Post Type
	function openagenda_event() {

		$labels = array(
			'name'                  => _x( 'OpenAgenda Events', 'Post Type General Name', 'wp-openagenda' ),
			'singular_name'         => _x( 'OpenAgenda Event', 'Post Type Singular Name', 'wp-openagenda' ),
			'menu_name'             => __( 'Openagenda', 'wp-openagenda' ),
			'name_admin_bar'        => __( 'Openagenda', 'wp-openagenda' ),
			'archives'              => __( 'Event Archives', 'wp-openagenda' ),
			'attributes'            => __( 'Event Attributes', 'wp-openagenda' ),
			'parent_item_colon'     => __( 'Parent Event:', 'wp-openagenda' ),
			'all_items'             => __( 'All Events', 'wp-openagenda' ),
			'add_new_item'          => __( 'Add New Event', 'wp-openagenda' ),
			'add_new'               => __( 'Add New', 'wp-openagenda' ),
			'new_item'              => __( 'New Event', 'wp-openagenda' ),
			'edit_item'             => __( 'Edit Event', 'wp-openagenda' ),
			'update_item'           => __( 'Update Event', 'wp-openagenda' ),
			'view_item'             => __( 'View Event', 'wp-openagenda' ),
			'view_items'            => __( 'View Events', 'wp-openagenda' ),
			'search_items'          => __( 'Search Event', 'wp-openagenda' ),
			'not_found'             => __( 'Not found', 'wp-openagenda' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp-openagenda' ),
			'featured_image'        => __( 'Featured Image', 'wp-openagenda' ),
			'set_featured_image'    => __( 'Set featured image', 'wp-openagenda' ),
			'remove_featured_image' => __( 'Remove featured image', 'wp-openagenda' ),
			'use_featured_image'    => __( 'Use as featured image', 'wp-openagenda' ),
			'insert_into_item'      => __( 'Insert into Event', 'wp-openagenda' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Event', 'wp-openagenda' ),
			'items_list'            => __( 'Events list', 'wp-openagenda' ),
			'items_list_navigation' => __( 'Events list navigation', 'wp-openagenda' ),
			'filter_items_list'     => __( 'Filter Events list', 'wp-openagenda' ),
		);
		$args = array(
			'label'                 => __( 'OpenAgenda Event', 'wp-openagenda' ),
			'description'           => __( 'OpenAgenda Event', 'wp-openagenda' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-calendar-alt',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
			'rewrite'               => false,
			'show_in_rest'               => false,
		);
		register_post_type( 'openagenda-events', $args );

	}
	add_action( 'init', 'openagenda_event', 0 );

}
