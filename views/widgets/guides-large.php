<?php 
$guides = [
  'post_type' => 'sports_guides',
  'post_status' => 'publish',
  'has_password' => false,
  'posts_per_page' => 6,
  'orderby' => 'menu_order',
  'order' => 'ASC'
];
query_posts( $guides ); ?>
<div class="uk-card uk-card-default uk-card-body" data-card="guides-full">
  <h1 class="uk-card-title"><?php the_field('widget_title_guides', 'option'); ?></h1>
  <!-- <div class="guides-full-lists"> -->
  <div style="position: relative">
    <div uk-grid>
      <?php while ( have_posts() ) : the_post();
        $img = get_field('feature_image'); ?>
        <div class="uk-width-1-2@s uk-width-1-3@m">
          <figure>
              <a href="<?php the_permalink(); ?>">
                <?php echo wp_get_attachment_image( $img['id'], [ 640, 360, true ] ); ?>
              </a>
          </figure>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </div>
      <?php endwhile; wp_reset_query(); ?>
    </div>
    <div style='margin-top: 15px;'>
      <a href="<?php echo esc_url( site_url('gambling-guides') ); ?>" class="uk-button uk-button-primary uk-button-small">View All Guides</a> 
    </div>
  </div>
</div>