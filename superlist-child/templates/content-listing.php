<?php /* mods zig - put title here since removed the big header. */
 /* zig here */
global $post;
 if ( has_post_thumbnail() ) { ?>
    <div class="row">
        <div class="col-md-3">
          <?php the_post_thumbnail('medium', ['class' => 'img-responsive responsive--full aligncenter', 'title' => 'Featured image']); ?>
        </div>
        <div class="col-md-9">
   <?php } ?>
      	<h1 class="detail-title">
      			<?php /* echo apply_filters( 'inventor_listing_title', get_the_title(), get_the_ID() ); */ ?>
      			<?php echo get_the_title(); ?>
      	</h1>

      	<?php $slogan = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'slogan', true ); ?>
      	<?php if ( ! empty( $slogan ) ) : ?>
      			<h4 class="detail-banner-slogan">
      					<?php echo esc_attr( $slogan ); ?>
      			</h4>
      	<?php endif; ?>
    <? if ( has_post_thumbnail() ) { ?>
        </div> <!-- end col -->
      </div> <!--end row -->
    <? }  ?>
<?php  /* zig here */ echo wp_kses( do_shortcode( '[inventor_breadcrumb]' ), wp_kses_allowed_html( 'post' ) ); ?>
<?php /* end zig here */ ?>


<?php do_action( 'inventor_before_listing_detail', get_the_ID() ); ?>

<div class="listing-detail">
    <?php Inventor_Post_Types::render_listing_detail_sections(); ?>
</div><!-- /.listing-detail -->

<?php do_action( 'inventor_after_listing_detail', get_the_ID() ); ?>
