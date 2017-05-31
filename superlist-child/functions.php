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
