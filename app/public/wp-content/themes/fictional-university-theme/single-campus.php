<?php
get_header();
while (have_posts()) {


    the_post();
    pageBanner() ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i>All Campuses</a> <span class="metabox__main"><?php the_title() ?></span></p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>

        <?php $mapLoc = get_field('map_location') ?>
        <div class="acf-map">
            <div data-lat="<?php echo $mapLoc['lat'] ?>" data-lng="<?php echo $mapLoc['lng'] ?>" class="marker">
                <h3>
                    <?php the_title() ?>
                </h3>
                <?php echo $mapLoc['address'] ?>
            </div>
        </div>

        <?php
        $relatedPrograms = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'program',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_campus',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ));
        if ($relatedPrograms->have_posts()) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Programs Available At This Campus</h2>
            <ul class="min-list link-list">
                <?php
                while ($relatedPrograms->have_posts()) {

                    $relatedPrograms->the_post(); ?>
                    <li class="">
                        <a class="" href=" <?php the_permalink() ?>">
                            <?php the_title() ?>
                        </a>
                    </li>

            <?php }
                echo '</ul>';
            } ?>



    </div>

<?php }
get_footer();
