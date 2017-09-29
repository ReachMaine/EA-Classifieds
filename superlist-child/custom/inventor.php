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
    $str_imgurl = reach_get_post_type_image($post_type);
  }
 return $str_imgurl;
}

// put featured image at bottom of listing
//add_action('inventor_after_listing_detail', 'reach_listing_thumb', 10, 1); - calling directly now.
function reach_listing_thumb( $int_listing_id) {
  if ( has_post_thumbnail($int_listing_id) ) {
    //echo '<div class="listing-detail-section" id="listing-detail-section-thumb">';
    echo '<div class="listing-detail-thumb">';
    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($int_listing_id), 'large' );
    echo '<a href="'.esc_url($large_image_url[0]).'">' ;
    echo get_the_post_thumbnail($int_listing_id, 'medium', ['class' => 'alignleft', 'title' => 'Featured image']);
    $displayad = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'displayad', true );
    if ( $displayad == 'on' ){
      echo '<div class="expand-display-ad"><i class="fa fa-plus" aria-hidden="true"></i></div>';
    };
    echo '</a>';
    echo '</div>';
    //echo '</div>';
  }

}

// disable street view
add_filter( 'inventor_metabox_field_enabled', 'disable_gmap_views', 10, 4 );
function disable_gmap_views( $enabled, $metabox_id, $field_id, $post_type ) {
    $nope = array('listing_map_location',  'listing_street_view' , 'listing_inside_view', 'listing_map_location_polygon');
    if ( in_array($field_id, $nope )  ) {
        return false;
    }

    return $enabled;
}

function reach_get_post_type_image($str_post_type) {
  $str_imgurl = "";
  switch ($str_post_type) {
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
    return $str_imgurl;
}

// swtich order of details & description.  by removing them & then puthsing them back onto the beginning of the array
add_filter( 'inventor_listing_detail_sections', 'reach_list_details', 10, 2);
function reach_list_details($sections, $post_type) {
  //echo "<pre>"; var_dump($sections); echo "</pre>";
  unset($sections['overview']);
  unset($sections['gallery']);
  unset($sections['contact']);

  $sections = array('overview'=> "Details" ) + $sections  + array('gallery' => "Gallery") + array('contact' => "Contact");
  //echo "<pre>"; var_dump($sections); echo "</pre>";
  return $sections;
}

// unassigne metaboxes for homeseller.
add_filter( 'inventor_metabox_assigned', 'unassign_metabox', 10, 3 );

function unassign_metabox( $assigned, $metabox, $post_type ) {
    $not_for_homeseller = array('branding', 'banner', 'flags', 'contact');
    if (current_user_can('contributor')) {
      if ( in_array($metabox, $not_for_homeseller)  && $post_type == 'realestate' ) {
          return false;
      }
    }


    return $assigned;
}

add_filter( 'inventor_submission_steps', function( $steps, $post_type ) {
    return array(
        'general' => $steps['general'],
        'price'=> $steps['price'],
        'beds-baths'=> $steps['beds-baths'],

        'location'=> $steps['location'],
        'listing_category'=> $steps['listing_category'],
        'gallery' => $steps['gallery'],
        'video' => $steps['video'],
    );
}, 10, 2 );
