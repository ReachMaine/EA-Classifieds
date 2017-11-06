<?php /* mods
	26Dec17 zig - dont display the description.
*/
global $post; ?>

<?php // if ( ! empty( $post->post_content ) ) : ?>

		<?php if ( has_post_thumbnail(get_the_ID()) ) {
			echo '<div class="listing-detail-section col-md-3" id="listing-detail-section-image">';
				reach_listing_thumb( get_the_ID());
			echo '</div>';
			echo '<div class="listing-detail-section col-md-6" id="listing-detail-section-description">';
		}  else {
			echo '<div class="listing-detail-section col-md-9" id="listing-detail-section-description">';
		} ?>

	    <?php /* <h2 class="page-header"><?php echo $section_title; ?></h2> */ ?>
		<div class="listing-detail-description">

	    	<?php the_content(); ?>
			<?php $targetpage = get_post_meta(get_the_ID(), INVENTOR_LISTING_PREFIX .'realtorpage', true);
			if ($targetpage) {
				$domain = parse_url($targetpage, PHP_URL_HOST);
				echo '<a class="see-more-link btn btn-primary" href="'.$targetpage.'" target="_blank">See full listing on '.$domain.'</a>';
			}
			?>
		</div>
	</div><!-- /.listing-detail-section -->

<?php// endif; ?>
</div><!-- end row 1 -->
