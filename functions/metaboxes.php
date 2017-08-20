<?php
/*
 * File name - metaboxes.php
 * Meta box functionality has been created by Rilwis @ http://www.deluxeblogtips.com and can be downloaded as a plugin from the wordpress repository.
 * Use underscore (_) at the beginning to make keys hidden, for example $prefix = '_es_';
 */
$prefix = '_es_';

global $meta_boxes;

// Set up the array to store all the meta boxes data.
$meta_boxes[] = array(
	'id' => 'into_text',
	'title' => 'Heading & Tagline',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Heading',
			'id'	=> $prefix . 'heading',
			'type'	=> 'text'
		),
		array(
			'name'	=> 'Tagline',
			'id'	=> $prefix . 'tagline',
			'type'	=> 'text',
			),
		array(
			'name'	=> 'Tagline2',
			'id'	=> $prefix . 'second_tagline',
			'type'	=> 'text',
			),
		array(
			'name'	=> 'Tagline3',
			'id'	=> $prefix . 'third_tagline',
			'type'	=> 'text',
			),
		array(
			'name'	=> 'Tagline4',
			'id'	=> $prefix . 'fourth_tagline',
			'type'	=> 'text',
			),
		array(
			'name'	=> 'Tagline5',
			'id'	=> $prefix . 'fifth_tagline',
			'type'	=> 'text',
			)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'filter_number',
	'title' => 'Air Filter Selection',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'id'	=> $prefix . 'filter_number',
			'type'	=> 'textarea',
			'desc' => 'Originally "How many air filters are in your home?"',
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'sub_now',
	'title' => 'Subscribe List',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Subscribe list items',
			'id'	=> $prefix . 'list_item',
			'type'	=> 'text',
			'clone' => true,
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'custom_filter_list',
	'title' => 'Custom Filter List',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Custom Filter list items',
			'id'	=> $prefix . 'custom_list_item',
			'type'	=> 'text',
			'clone' => true,
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'custom_filter_text',
	'title' => 'Custom Filter Text',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Text',
			'id'	=> $prefix . 'custom_filter_text',
			'type'	=> 'textarea',
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);

$meta_boxes[] = array(
	'id' => 'step_one',
	'title' => 'Step 1',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Text',
			'id'	=> $prefix . 'desc_text',
			'type'	=> 'text',
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'step_two',
	'title' => 'Step 2',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Text',
			'id'	=> $prefix . 'second_desc_text',
			'type'	=> 'text',
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'step_three',
	'title' => 'Step 3',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Text',
			'id'	=> $prefix . 'third_desc_text',
			'type'	=> 'text',
		)
	),
	'only_on' => array(
			'slug' => 'home'
		)
);
$meta_boxes[] = array(
	'id' => 'testimonials',
	'title' => 'Testimonials',
	'pages' => array( 'post' ),
	'context' => 'normal',
	'priority' => 'high',
	'clone' => true,
	'fields' => array(
		array(
			'name'	=> 'Testimonial Author',
			'id'	=> $prefix . 'testimonial_author',
			'type'	=> 'text',
			'desc'	=> 'Testimonial author',
		),

	),

);
$meta_boxes[] = array(
	'id' => 'subtitle',
	'title' => 'Subtitle',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name'	=> 'Subtitle',
			'id'	=> $prefix . 'subtitle',
			'type'	=> 'text',
		),

	),

);



// Meta box definitions live in the class.banners.php file as well!

/**
 * Register meta boxes
 *
 * @return void
 */
if(!function_exists('rw_register_meta_boxes')) {

	function rw_register_meta_boxes() {
		global $meta_boxes;
		// Make sure there's no errors when the plugin is deactivated or during upgrade
		if ( class_exists( 'RW_Meta_Box' ) ) {
			foreach ( $meta_boxes as $meta_box ) {
				if( isset( $meta_box[ 'not_on' ] ) && !rw_maybe_include( $meta_box[ 'not_on' ], 0 ) ) {
					continue;
				}
				if( isset( $meta_box['only_on'] ) && !rw_maybe_include( $meta_box['only_on'], 1 ) ) {
					continue;
				}

				new RW_Meta_Box( $meta_box );
			}

		}
	}

	add_action( 'admin_init', 'rw_register_meta_boxes' );
}

/**
 * Check if meta boxes is included
 *
 * @return bool
 */
if(!function_exists('rw_maybe_include')) {
	function rw_maybe_include( $conditions, $bool = -1 ) {
		// Include in back-end only
		if ( ! defined( 'WP_ADMIN' ) || ! WP_ADMIN ) {
			return false;
		}

		// Always include for ajax
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}

		if ( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'];
		}
		elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = $_POST['post_ID'];
		}
		else {
			$post_id = false;
		}

		$post_id = (int) $post_id;
		$post    = get_post( $post_id );
		if(!is_object($post)) {
			return;
		}
		switch( $bool ) {
			// if we're including (only_on)
			case 1:
				foreach ( $conditions as $cond => $v ) {
					// Catch non-arrays too
					if ( ! is_array( $v ) ) {
						$v = array( $v );
					}

					switch ( $cond ) {
						case 'id':
							if ( in_array( $post_id, $v ) ) {
								return true;
							}
						break;
						case 'parent':
							$post_parent = $post->post_parent;
							if ( in_array( $post_parent, $v ) ) {
								return true;
							}
						break;
						case 'slug':
							$post_slug = $post->post_name;
							if ( in_array( $post_slug, $v ) ) {
								return true;
							}
						break;
						case 'template':
							$template = get_post_meta( $post_id, '_wp_page_template', true );
							if ( in_array( $template, $v ) ) {
								return true;
							}
						break;
						case 'is_meta':
							$true = 1;
							foreach( $v as $_key => $_value ) {
								$_meta = get_post_meta( $post_id, $_key, true );
								if( $_meta != $_value ) {
									$true = 0;
								} else {
									wp_die( es_preit( array( $post_id, $_meta, $_value, $_key ), true ) );
								}
							}
							if( $true ) {
								return true;
							}
						break;
						case 'post_format':
							$pf = get_post_format( $post_id );
							if( in_array( $pf, $v ) ) {
								return true;
							}
							return false;
						break;
					}
				}
				break;
			// when we're excluding (not_on)
			case 0:
				foreach ( $conditions as $cond => $v ) {
					// Catch non-arrays too
					if ( ! is_array( $v ) ) {
						$v = array( $v );
					}

					switch ( $cond ) {
						case 'id':
							if ( !in_array( $post_id, $v ) ) {
								return true;
							}
						break;
						case 'parent':
							$post_parent = $post->post_parent;
							if ( !in_array( $post_parent, $v ) ) {
								return true;
							}
						break;
						case 'slug':
							$post_slug = $post->post_name;
							if ( !in_array( $post_slug, $v ) ) {
								return true;
							}
						break;
						case 'template':
							$template = get_post_meta( $post_id, '_wp_page_template', true );
							if ( !in_array( $template, $v ) ) {
								return true;
							}
						break;
						case 'post_format':
							$pf = get_post_format( $post_id );
							if( !in_array( $pf, $v ) ) {
								return true;
							}
							return false;
						break;
					}
				}
				break;
			default:
				return true;
		}


		// If no condition matched
		return false;
	}
}
