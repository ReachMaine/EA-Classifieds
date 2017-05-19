<?php
/* inventor & listing custom programing. */

add_action('inventor_listing_content', 'reach_listing_archive_extra', 10, 2);
function reach_listing_archive_extra( $listing_id, $display = null) {
   if ( $display == 'row' ) {
    $jobtype = get_post_meta( $listing_id, 'listing_parttime', true );
    if ($jobtype) {
        echo "<dt></dt><dd>".$jobtype."</dd>";
    }
    $company= get_post_meta( $listing_id, 'listing_company', true );
    if ($company) {
        echo "<dt></dt><dd>".$company."</dd>";
    }
  }
}

add_filter( 'inventor_filter_sort_by_choices', function( $choices ) {
    if( array_key_exists( 'price', $choices ) ) {
        unset( $choices['price'] );
    }
    return $choices;
}, 11 );
