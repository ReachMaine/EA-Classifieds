<?php /* mods zig - put title here since removed the big header. */
/* zig here */
global $post;
$posttype = get_post_type( get_the_ID());
?>
      	<h1 class="detail-title">
      			<?php /* echo apply_filters( 'inventor_listing_title', get_the_title(), get_the_ID() ); */ ?>
      			<?php
            $title =  get_the_title();
            $scraped_content = (get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'scraped', true) == 'on' ) ? true : false;
            if (!$scraped_content) {
              switch($posttype) {
                case 'helpwanted':
                      $title = "Job Openings in the Hancock County Area";
                    break;
                case 'classifieds':
                  $title = "Classified Ads in Hancock County Area";
                  break;
              }
            }
            echo apply_filters( 'inventor_listing_title', $title, get_the_ID() );
            ?>
      	</h1>

      	<?php $slogan = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'slogan', true ); ?>
      	<?php if ( ! empty( $slogan ) ) : ?>
      			<h4 class="detail-banner-slogan">
      					<?php echo esc_attr( $slogan ); ?>
      			</h4>
      	<?php endif; ?>

<?php  /* zig here */ echo wp_kses( do_shortcode( '[inventor_breadcrumb]' ), wp_kses_allowed_html( 'post' ) ); ?>
<?php /* end zig here */ ?>


<?php do_action( 'inventor_before_listing_detail', get_the_ID() ); ?>
<?php /* $posttype = get_post_type( get_the_ID()); */
if ( has_post_thumbnail(get_the_ID()) ) { $has_thumbclass = "has_thumb"; } else { $has_thumbclass = ""; } ?>
<div class="listing-detail <?php echo $posttype; ?>">
    <div class="listing-detail-desc-wrap">
     <?php get_template_part('section-description'); ?>
   </div>
    <div class="listing-detail-sections-wrap <?php echo $has_thumbclass; ?>">
      <?php Inventor_Post_Types::render_listing_detail_sections(); ?>
    </div> <!-- end listing-detail-sections -->
    <?php if ( has_post_thumbnail(get_the_ID()) ) {
      echo '<div class="listing-detail-thumb-wrap">';
      reach_listing_thumb( get_the_ID());
      echo '</div>';
    } ?>
    <?php if ( ($post_type == 'realestate') && (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'show_author_info', true) == 'on') ) {
        echo '<div class="list-detail-author-wrap">';
          echo '<div id="listing-detail-section-author" class="listing-detail-section"> ';
            reach_listing_author( get_the_ID());
          echo '</div>'; // section
        echo '</div>'; // wrap
    } ?>
</div><!-- /.listing-detail -->

<?php do_action( 'inventor_after_listing_detail', get_the_ID() ); ?>
