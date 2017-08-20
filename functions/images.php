<?php

add_theme_support('post-thumbnails');

/**
 * Add new image sizes
 * Structure is 'name' => array( $width, $height, $crop )
 *
 * Reason we have to do this is due to the picturefill bit
 */

global $picture_sizes;
$picture_sizes = array(
	'small-img' => array( 480, 300),
	'medium-img' => array( 700, 372),
	'large-img' => array( 1000, 702)
);

foreach ($picture_sizes as $name => $data) {
	add_image_size( $name, $data[0], $data[1] );
	// add_image_size( $name, $data[0], $data[1], $data[2] );
}
//set_post_thumbnail_size( 480, 300);

/**
 * Adds the medium and the full to the image size list in the editor, so people can only insert them
 * into pages and articles.
 */
function add_additional_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty($_wp_additional_image_sizes) ) {
		return $sizes;
	}

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset($sizes[$id]) ) {
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
		}
	}

	return $sizes;
}

add_filter( 'image_size_names_choose', 'add_additional_image_sizes' );


/**
 * For the picturefill
 */
function get_picturefill() {
	$r = get_stylesheet_directory_uri() . '/js/';
	wp_register_script( 'picturefill', $r . 'vendor/picturefill.min.js', null, null, false);
	wp_enqueue_script( 'picturefill' );
}


function get_picture_srcs( $image, $mappings ) {
	$arr = array();

	foreach ( $mappings as $size => $data ) {
		$image_src = wp_get_attachment_image_src( $image, $size );
		$arr[] ='<source srcset="'. $image_src[0] . '" media="(min-width:'. $data[0] .'px)">';
	}
	$arr = array_reverse($arr);
	return implode($arr);
}


function responsive_img_shortcode( $atts ) {
	global $picture_sizes;
	// Removes false and/or empty values in array. This would be caused if a small photo was uploaded and there was no version for the "large" size in array.
	//$pix = array_filter( $picture_sizes, 'picture_sizes_for_picturefill' );
	$pix = array_filter( $picture_sizes );

	extract(shortcode_atts(array(
		'imageid'    => 1,
	), $atts));

	// $output = "<script>console.log( 'Debug Objects: " . print_r($pix) . "' );</script>";
	// echo $output;

	return
		'<picture>'
			. '<!--[if IE 9]><video style="display: none;"><![endif]-->'
			. get_picture_srcs($imageid, $pix)
			. wp_get_attachment_image( $imageid, 'large' )
			. '<!--[if IE 9]></video><![endif]-->'
		. '</picture>';
}

function picture_sizes_for_picturefill( $element ) {
	return $element[3];
}


function responsive_insert_image( $html, $id, $caption, $title, $align, $url ) {
  return "[responsive imageid='" . $id . "']";
}


add_filter( 'image_send_to_editor', 'responsive_insert_image', 10, 9);
add_action( 'wp_enqueue_scripts', 'get_picturefill' );
add_shortcode( 'responsive', 'responsive_img_shortcode' );
