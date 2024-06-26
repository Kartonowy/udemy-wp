<?php
get_header();

while (have_posts()) {
    the_post();
    pageBanner(array()) ?>
    <div class="container container--narrow page-section">
        <?php
        $x = wp_get_post_parent_id(get_the_ID());
        if ($x) { ?>

            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($x) ?>"><i class="fa fa-home" aria-hidden="true"></i> back to <?php echo get_the_title($x) ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div> <?php
                }
                    ?>

        <?php if ($x or get_pages(
            array('child_of' => get_the_ID())
        )) { ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink($x) ?>"><?php echo get_the_title($x) ?></a></h2>
                <ul class="min-list">
                    <?php
                    if ($x) {
                        $childrensof = $x;
                    } else {
                        $childrensof = get_the_ID();
                    }

                    wp_list_pages(array(
                        'title_li' => NULL,
                        'child_of' => $childrensof,
                        'sort_column' => 'menu_order'
                    ));
                    ?>
                </ul>
            </div>

        <?php } ?>

        <div class="generic-content">
            <?php get_search_form() ?>
        </div>
    </div>
<?php
}


get_footer();
