<?php
get_header();
while (have_posts()) {


    the_post();
    pageBanner() ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i>All Programs</a> <span class="metabox__main"><?php the_title() ?></span></p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>

        <?php
        $relatedProfessors = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ));
        if ($relatedProfessors->have_posts()) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php echo get_the_title() ?> Professor(s)</h2>
            <ul class="professor-cards">
                <?php



                while ($relatedProfessors->have_posts()) {
                    $relatedProfessors->the_post(); ?>
                    <li class="professor-card__list-item">
                        <a class="professor-card" href=" <?php the_permalink() ?>">
                            <img src="<?php the_post_thumbnail_url('prepared') ?>" alt="photo" class="professor-card__image">
                            <span class="professor-card__name"><?php the_title() ?></span>
                        </a>
                    </li>

                <?php }
                echo '</ul>';
            }


            wp_reset_postdata();

            $homepageEvents = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'event',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_key' => 'event_date',
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>=',
                        'value' => date('Ymd'),
                        'type' => 'numeric'
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            ));
            if ($homepageEvents->have_posts()) { ?>
                <hr class="section-break">
                <h2 class="headline headline--medium">Upcoming <?php echo get_the_title() ?> Event(s)</h2>
                <?php

                while ($homepageEvents->have_posts()) {
                    $homepageEvents->the_post();
                    get_template_part('/templates/content-event');
                }
            }

            wp_reset_postdata();
            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses:';

                echo '<ul class="min-list link-list">';
                foreach ($relatedCampuses as $campus) {
                ?> <li>
                        <a href="<?php echo get_the_permalink($campus) ?>"><?php echo get_the_title($campus) ?></a>
                    </li> <?php
                        } ?>
            </ul> <?php
                }
                    ?>

    </div>

<?php }
get_footer();
