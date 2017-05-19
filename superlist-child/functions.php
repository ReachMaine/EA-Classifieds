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
