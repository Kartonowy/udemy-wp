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
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'author' => get_the_author()
            ]);
        }

        if (get_post_type() == 'professor') {
            array_push($returnal['professors'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(0, 'prepared')
            ]);
        }
        if (get_post_type() == 'program') {

            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
                foreach ($relatedCampuses as $campus) {
                    array_push($returnal['campuses'], [
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ]);
                }
            }

            array_push($returnal['programs'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            ]);
        }
        if (get_post_type() == 'event') {
            $desc = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);
            array_push($returnal['events'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => date_format(new DateTime(get_field('event_date')), 'M'),
                'day' => date_format(new DateTime(get_field('event_date')), 'd'),
                'description' => $desc
            ]);
        }
        if (get_post_type() == 'campus') {
            array_push($returnal['campuses'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
        }
    }

    if ($returnal['programs']) {
        $programs = ['relation' => 'OR'];

        foreach ($returnal['programs'] as $item) {
            array_push($programs, [
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ]);
        }

        $relate = new WP_Query([
            'post_type' => ['professor', 'event'],
            'meta_query' => $programs
        ]);

        while ($relate->have_posts()) {
            $relate->the_post();

            if (get_post_type() == 'event') {
                $desc = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);
                array_push($returnal['events'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => date_format(new DateTime(get_field('event_date')), 'M'),
                    'day' => date_format(new DateTime(get_field('event_date')), 'd'),
                    'description' => $desc
                ]);
            }

            if (get_post_type() == 'professor') {
                array_push($returnal['professors'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'thumbnail' => get_the_post_thumbnail_url(0, 'prepared')
                ]);
            }
        }

        $returnal['professors'] = array_values(array_unique($returnal['professors'], SORT_REGULAR));
        $returnal['events'] = array_values(array_unique($returnal['events'], SORT_REGULAR));
    }


    return $returnal;
}
