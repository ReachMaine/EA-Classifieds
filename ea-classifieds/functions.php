<?php
/**
 * Superlist child functions and definitions
 *
 * @package Superlist Child
 * @since Superlist Child 1.0.0
 */

// take off share buttons.
//add_action( 'after_setup_theme', 'reach_theme_mods' );
function reach_theme_mods() {
   remove_action( 'superlist_after_listing_banner', 'superlist_render_share_button' );
   remove_action( 'inventor_listing_actions', 'superlist_render_share_button');
}

add_action('inventor_listing_content', 'reach_listing_archive_extra', 10, 2);
function reach_listing_archive_extra( $listing_id, $display = null) {
  $jobtype = get_post_meta( $listing_id, 'listing_parttime', true );
  if ($jobtype) {
      echo "<dt></dt><dd>".$jobtype."</dd>";
  }
  $company= get_post_meta( $listing_id, 'listing_company', true );
  if ($company) {
      echo "<dt></dt><dd>".$company."</dd>";
  }

}
