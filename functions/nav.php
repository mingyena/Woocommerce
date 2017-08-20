<?php


register_nav_menus(array(
	'main' => 'Main navigation',
	'top' => 'Top Header navigation',
));

function display_nav_menu_items( $items, $args ) {
	$counter = 0;
    foreach ($items as $item) {
        if( $item->current ) {
            $item->classes[] = 'active';
        }
        if($counter == 1){
            $item->classes[] = 'no-left-padding';

        }
        $counter++;
    }
    return $items;
}

add_filter( 'wp_nav_menu_objects', 'display_nav_menu_items', 10, 2 );


function register_my_menu() {
    register_nav_menu('top-menu',__( 'Top Menu' ));
}
add_action( 'init', 'register_my_menu' );

// Filter wp_nav_menu() to add additional links and other output
function new_nav_menu_items($items) {
    $homelink = '<li class="home"><a href="' . home_url( '/' ) . '"><span class="icon-home"></span></a></li>';
    $items = $homelink . $items;
    return $items;
}
add_filter( 'wp_nav_menu_items', 'new_nav_menu_items' );

class Child_Wrap extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<a class=\"mobile-drop\"></a>\n<ul>\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

function register_my_menu_footer() {
    register_nav_menu('first-footer-menu',__( 'First Footer Menu' ));
}
add_action( 'init', 'register_my_menu_footer' );

function register_my_menu_second_footer() {
    register_nav_menu('second-footer-menu',__( 'Second Footer Menu' ));
}
add_action( 'init', 'register_my_menu_second_footer' );
