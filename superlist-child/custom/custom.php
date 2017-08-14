<?php /* custom functions */
// for some reason can find file js.cookie.js from plugins_url( '/inventor/libraries/js.cookie.js' ), but can find js-cookie.js so moved to custom & re-queued.
wp_dequeue_script('js-cookie');
wp_enqueue_script( 'js-cookie', get_stylesheet_directory_uri(). '/custom/js-cookie.js' , array(), false, false );

/*  trying to add slug to the custom post listing */
// Register the column
function adnumber_column_register( $columns ) {
    $columns['adnumber'] = __( 'adnumber', 'reach' );
    return $columns;
}
add_filter( 'manage_edit-helpwanted_columns', 'adnumber_column_register' );
add_filter( 'manage_edit-classifieds_columns', 'adnumber_column_register' );
add_filter( 'manage_edit-rentals_columns', 'adnumber_column_register' );
add_filter( 'manage_edit-realestate_columns', 'adnumber_column_register' );
// Display the column content
function adnumber_column_display( $column_name, $post_id ) {
    if ( 'adnumber' != $column_name )  return;
    $adnumber =  get_post_meta($post_id, 'listing_adnumber', true);
    if ( !$adnumber )
        $adnumber = '';
    echo $adnumber;
}
add_action( 'manage_helpwanted_posts_custom_column', 'adnumber_column_display', 10, 2 );
add_action( 'manage_classifieds_posts_custom_column', 'adnumber_column_display', 10, 2 );
add_action( 'manage_rentals_posts_custom_column', 'adnumber_column_display', 10, 2 );//rentals
add_action( 'manage_realestate_posts_custom_column', 'adnumber_column_display', 10, 2 );//realestate
// make adnumber sortable
// Register the column as sortable
function adnum_column_register_sortable( $columns ) {
    $columns['adnumber'] = 'adnumber';
    return $columns;
}
add_filter( 'manage_edit-helpwanted_sortable_columns', 'adnum_column_register_sortable' );
add_filter( 'manage_edit-classifieds_sortable_columns', 'adnum_column_register_sortable' );
add_filter( 'manage_edit-rentals_sortable_columns', 'adnum_column_register_sortable' );
add_filter( 'manage_edit-realestate_sortable_columns', 'adnum_column_register_sortable' );
// tell wp how to sort the column
function adnum_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'price' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'listing_adnumber',
            'orderby' => 'meta_value_num'
        ) );
    }
    return $vars;
}
add_filter( 'request', 'adnum_column_orderby' );
