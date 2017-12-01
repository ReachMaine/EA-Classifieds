<script>
var dataLayer = window.dataLayer = window.dataLayer || [];
dataLayer.push({
  'adNumber': '<?php $adnumber = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'adnumber', true ); if ( ! empty( $adnumber ) ) : ?><?php echo esc_attr( $adnumber ); ?><?php endif; ?>',
  'adCustomer': '<?php $adCustomer = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'customer', true ); if ( ! empty( $adCustomer ) ) : ?><?php echo esc_attr( $adCustomer ); ?><?php endif; ?>',
  'AdSalesperson': '<?php $adSalesperson = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'salesperson', true ); if ( ! empty( $adSalesperson ) ) : ?><?php echo esc_attr( $adSalesperson ); ?><?php endif; ?>'
});
</script>


<?php /* mods zig - put title here since removed the big header. */
/* zig here */


global $post;
$posttype = get_post_type( get_the_ID());
?>
<?php  /* zig here */ echo wp_kses( do_shortcode( '[inventor_breadcrumb]' ), wp_kses_allowed_html( 'post' ) ); ?>
      	<h1 class="detail-title container">
      			<?php /* echo apply_filters( 'inventor_listing_title', get_the_title(), get_the_ID() ); */ ?>
      			<?php
            $title =  get_the_title();

            switch ($posttype) {
              case 'helpwanted':
                    //$title = "Job Openings in the Hancock County Area";
                  break;
              case 'classifieds':
                $title = "Classified Ads in Hancock County Area";
                break;
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


<?php /* end zig here */ ?>


<?php do_action( 'inventor_before_listing_detail', get_the_ID() ); ?>

<div class="listing-detail <?php echo $posttype; ?>">
      <?php Inventor_Post_Types::render_listing_detail_sections(); ?>
</div><!-- /.listing-detail -->
<?php do_action( 'inventor_after_listing_detail', get_the_ID() ); ?>
