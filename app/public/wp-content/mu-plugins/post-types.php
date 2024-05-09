<?php 
function pico() {

    // event post type
    register_post_type('event', array(
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar',
        'has_archive' => true,
        'rewrite' => array('slug' => 'events'),
        'supports' => array('title', 'editor', 'excerpt')
    ));

    //Program post type
register_post_type('program', array(
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Program',
            'add_new_item' => 'Add New Program',
            'edit item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards',
        'has_archive' => true,
        'rewrite' => array('slug' => 'programs'),
        'supports' => array('title', 'editor')
    ));
}

add_action('init', 'pico');