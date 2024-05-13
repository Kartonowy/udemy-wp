<?php
function pico()
{
    register_post_type('campus', array(
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ),
        'menu_icon' => 'dashicons-location-alt',
        'has_archive' => true,
        'rewrite' => array('slug' => 'campus'),
        'supports' => array('title', 'editor', 'excerpt')
    ));
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
    register_post_type('professor', array(
        'public' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Professor',
            'add_new_item' => 'Add New Professor',
            'edit item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => array('title', 'editor', 'thumbnail')
    ));
}

add_action('init', 'pico');
