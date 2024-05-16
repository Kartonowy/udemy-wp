<?php

add_action('rest_api_init', 'univRegSearch');

function univRegSearch()
{
    register_rest_route('univ/v1', 'search', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'univSearchResults'
    ]);
}

function univSearchResults($params)
{
    $mainQuery = new WP_Query([
        'post_type' => ['post', 'page', 'professor', 'campus', 'event', 'program'],
        's' => sanitize_text_field($params['term'])
    ]);


    $returnal = [
        'generalInfo' => [],
        'professors' => [],
        'programs' => [],
        'campuses' => [],
        'events' => [],
    ];

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if (get_post_type() == 'post' or get_post_type() == 'page') {
            array_push($returnal['generalInfo'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }

        if (get_post_type() == 'professor') {
            array_push($returnal['professors'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }
        if (get_post_type() == 'program') {
            array_push($returnal['programs'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }
        if (get_post_type() == 'event') {
            array_push($returnal['events'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }
        if (get_post_type() == 'campus') {
            array_push($returnal['campuses'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }
    }

    return $returnal;
}
