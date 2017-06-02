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

// use a different default image (based on listing type)
add_filter ('inventor_listing_featured_image', 'ea_class_defimg', 10, 2);
function ea_class_defimg($str_imgurl, $int_listingID) {
  $pos = strpos($str_imgurl,'/wp-content/plugins/inventor/assets/img/default-item.png' );
  if ($pos !== false) {
    $posttype = get_post_type($int_listingID);
    switch ($posttype) {
      case "helpwanted":
           $str_imgurl = esc_attr(get_stylesheet_directory_uri() ) . '/images/'.'default-image-jobs.jpg';
        break;
      case "classifieds":
        $str_imgurl = esc_attr(get_stylesheet_directory_uri() ) . '/images/'.'default-listing.png';
        break;
      case "rentals":
        $str_imgurl = esc_attr(get_stylesheet_directory_uri() ) . '/images/'.'ea_square_rentals.jpg';
        break;
      case "realestate":
        $str_imgurl = esc_attr(get_stylesheet_directory_uri() ) . '/images/'.'ea_square_realestate.png';
        break;
      case "local":
        /* $str_imgurl = esc_attr(get_stylesheet_directory_uri() ) . '/images/'.'default-listing.png';
        break; */
      default:
        $str_imgurl = esc_attr(get_stylesheet_directory_uri() ) . '/images/'.'default-listing.png';
        break;
    } // end switch
  }
 return $str_imgurl;
}

// put featured image at bottom of listing
//add_action('inventor_after_listing_detail', 'reach_listing_thumb', 10, 1);
function reach_listing_thumb( $int_listing_id) {

  if ( has_post_thumbnail($int_listing_id) ) {
    echo '<div class="listing-detail-section" id="listing-detail-section-thumb">';
    echo '<div class="listing-detail-thumb">';
    echo get_the_post_thumbnail($int_listing_id, 'medium', ['class' => 'img-responsive responsive--full aligncenter', 'title' => 'Featured image']);
    echo '</div></div>';
  }

}
