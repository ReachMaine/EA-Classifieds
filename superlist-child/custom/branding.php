<?php
/* File for REACH branding stuff.


	/*****  change the login screen logo ****/
	function my_login_logo() { ?>
		<style type="text/css">
			body.login div#login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/admin-login.png);
				padding-bottom: 30px;
				background-size: contain;
				//margin-left: -30px;
				margin-bottom: 0px;
				margin-right: 0px;
				width: 100%;
			}
		</style>
	<?php }
	add_action( 'login_enqueue_scripts', 'my_login_logo' );

	/* put reach logo at bottom of login screen */
	add_action( 'login_footer', 'reach_login_branding' );
	function reach_login_branding() {
		$outstring = "";
		$outstring .= '<p style="text-align:center;">';
		$outstring .= 	'<img src="'.get_stylesheet_directory_uri().'/images/reach-favicon.png'.'">';
		$outstring .= 		'R<i style="color: #f58220">EA</i>CH Maine';
		$outstring .= '</p>';
		echo $outstring;
	}

	// custom post arhive seo images or descriptions & titles.

// add og:image for CPT archive
	add_filter( 'wpseo_opengraph_image', 'prefix_filter_og_image', 10, 1 );
	function prefix_filter_og_image( $img ) {
    if( is_post_type_archive( 'helpwanted' ) ) {
	    $img = get_stylesheet_directory_uri().'/images/post-cover-image-jobs.jpg';
		}
		if( is_post_type_archive( 'rentals' ) ) {
			$img = get_stylesheet_directory_uri().'/images/post-cover-image-rrentals.jpg';
		}
    return $img;
}
// add og:desc for CPT archive
	add_filter( 'wpseo_opengraph_desc', 'reach_filter_og_desc', 10, 1 );
	function reach_filter_og_desc( $ogdesc ) {
		if( is_post_type_archive( 'helpwanted' ) ) {
			$ogdesc = "An employer in Downeast Maine is looking for you! Find job openings at www.Ellsworthamerican.com/Jobs. ";
		}
		if( is_post_type_archive( 'rentals' ) ) {
			$ogdesc = "Find apartments, houses, offices spaces and cottages for rent in Downeast Maine at Ellsworthamerican.com/Rentals.";
		}
		return $ogdesc;
}

add_filter('wpseo_title', 'reach_product_wpseo_title');
function reach_product_wpseo_title($title) {
    if(  is_post_type_archive( 'helpwanted' ) ) {
        $title = "Help Wanted - Job Listings In Downeast Maine";
    }
		if(  is_post_type_archive( 'rentals' ) ) {
        $title = "Homes & Apartments for rent in Hancock County";
    }
    return $title;
}
