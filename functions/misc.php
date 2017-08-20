<?php
if( !function_exists('es_preit') ) {
	function es_preit( $obj, $echo = true ) {
		if( $echo ) {
			echo '<pre>' . print_r( $obj, true ) . '</pre>';
		} else {
			return '<pre>' . print_r( $obj, true ) . '</pre>';
		}
	}
}
if( !function_exists('es_silent') ) {
	function es_silent( $obj ) {
		echo '<pre style="display: none;">' . print_r( $obj, true ) . '</pre>';
	}
}

function init_constants() {
	if(!defined('TEMPLATEURI')) {
		define('TEMPLATEURI', trailingslashit( get_stylesheet_directory_uri() ) );
	}
}
add_action( 'init', 'init_constants' );




$sidebar = array(
	'name'         => __( 'Airflow Checkout Sidebar' ),
    'id'           => 'airflow',
    'description'  => 'The only widget here should be the one called Airfilter Flow Widget.',
    'before_title' => '<h1>',
    'after_title'  => '</h1>',
);


register_sidebar( $sidebar );

function es_custom( $key, $custom, $all = false) {
    return (array_key_exists($key, $custom)) ? ($all) ? $custom[$key] : $custom[$key][0] : '';
}

function new_excerpt_more( $more ) {
	return '... <a class="post-read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Continue reading <span class="post-read-more-arrow">&rarr;</span>', 'twentyten') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );