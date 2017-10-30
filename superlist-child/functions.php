<?php
/**
 * Superlist child functions and definitions
 *
 * @package Superlist Child
 * @since Superlist Child 1.0.0
 */

 require_once(get_stylesheet_directory().'/custom/inventor.php'); // custom shortcodes, etc
 require_once(get_stylesheet_directory().'/custom/branding.php'); // WP back end login screen
 require_once(get_stylesheet_directory().'/custom/language.php');
 require_once(get_stylesheet_directory().'/custom/custom.php');
 require_once(get_stylesheet_directory().'/custom/rss.php'); // custom rss feed for mailchimp

 add_action( 'after_setup_theme', 'reach_theme_mods' );
 function reach_theme_mods() {

 }

// strip "category:" or "tag:" from the archive title
 add_filter( 'get_the_archive_title', function ($title) {
        if ( is_category() ) {
            $title = single_term_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;
        } elseif ( is_tax() ) {
            $title = single_term_title( '', false );
        }

      return $title;
 });



   if ( function_exists('register_sidebar') ){

       /* add top banner ad widget */
      register_sidebar(array(
       'name' => 'Top Banner Ad',
       'id' => 'topbanner',
       'description' => 'Widget for a targetted banner ad.',
       'before_widget' => '<div id="%1$s" class=" %2$s ad-container">',
       'after_widget'  => '</div>'

       ));
    }


    /* Add CPTs to author archives */
    function custom_post_author_archive($query) {
        if ($query->is_author)
            $query->set( 'post_type', array('realestate', 'post') );
        remove_action( 'pre_get_posts', 'custom_post_author_archive' );
    }
    add_action('pre_get_posts', 'custom_post_author_archive');
