<?php
get_header();
pageBanner([
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events.'
]) ?>

<div class="container container--narrow page-section">
  <?php
  $pastEvents = new WP_Query(array(
    'paged' => get_query_var('paged', 1),
    'posts_per_page' => 2,
    'post_type' => 'event',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => 'event_date',
    'meta_query' => array(
      array(
        'key' => 'event_date',
        'compare' => '<=',
        'value' => date('Ymd'),
        'type' => 'numeric'
      )
    )
  ));
  while ($pastEvents->have_posts()) {
    $pastEvents->the_post();
    get_template_part('/templates/event-template');
  }
  echo paginate_links(array(
    'total' => $pastEvents->max_num_pages
  ));
  ?>
</div>
<?php
get_footer();
?>