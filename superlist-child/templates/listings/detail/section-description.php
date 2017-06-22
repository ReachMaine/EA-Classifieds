<?php /* mods
	26Dec17 zig - dont display the description.
*/
global $post; ?>

<?php if ( ! empty( $post->post_content ) ) : ?>
	<div class="listing-detail-section col-sm-9" id="listing-detail-section-description">
	    <?php /* <h2 class="page-header"><?php echo $section_title; ?></h2> */ ?>
		<div class="listing-detail-description-wrapper">
			<?php if ( has_post_thumbnail(get_the_ID()) ) {
				reach_listing_thumb( get_the_ID());
			} ?>
	    	<?php the_content(); ?>
			<?php $targetpage = get_post_meta(get_the_ID(), INVENTOR_LISTING_PREFIX .'realtorpage', true);
			if ($targetpage) {
				echo '<a class="see-more-link" href="'.$targetpage.'">See listing at source</a>';
			}
			?>
		</div>
	</div><!-- /.listing-detail-section -->

<?php endif; ?>
</div><!-- end row 1 -->
