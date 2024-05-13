<?php
get_header();
pageBanner([
    'title' => 'Our Campuses',
    'subtitle' => 'We have several conveniently located campuses.'
]) ?>


<div class="container container--narrow page-section">
    <div class="acf-map">
        <?php
        while (have_posts()) {
            the_post();
            $mapLoc = get_field('map_location');
        ?>
            <div data-lat="<?php echo $mapLoc['lat'] ?>" data-lng="<?php echo $mapLoc['lng'] ?>" class="marker">
                <h3>
                    <a href="<?php the_permalink() ?>">
                        <?php the_title() ?>
                    </a>
                </h3>
                <?php echo $mapLoc['address'] ?>
            </div>


        <?php } ?>
    </div>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events') ?>">Check out our past events archive</a></p>
</div>
<?php
get_footer();
?>