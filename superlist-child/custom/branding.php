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
	//add_filter( 'wpseo_opengraph_image', 'prefix_filter_og_image', 10, 1 );
	function prefix_filter_og_image( $img ) {
    if( is_post_type_archive( 'helpwanted' ) ) {
	    $img = get_stylesheet_directory_uri().'/images/post-cover-image-jobs.jpg';
		}
		if( is_post_type_archive( 'rentals' ) ) {
			$img = get_stylesheet_directory_uri().'/images/post-cover-image-rrentals.jpg';
		}
		if( is_post_type_archive( 'classifieds' )  || is_front_page() ) {
			$img = get_stylesheet_directory_uri().'/images/post-cover-image-classifieds.jpg';
		}
		if( is_post_type_archive( 'realestate' ) ) {
			$img = get_stylesheet_directory_uri().'/images/post-cover-image-real-estate.jpg';
		}
    return $img;
}
// add og:desc for CPT archive
	//add_filter( 'wpseo_opengraph_desc', 'reach_filter_og_desc', 10, 1 );
	function reach_filter_og_desc( $ogdesc ) {
		if( is_post_type_archive( 'helpwanted' ) ) {
			//$ogdesc = "An employer in Downeast Maine is looking for you! Find job openings at www.Ellsworthamerican.com/Jobs. ";
		}
		if( is_post_type_archive( 'rentals' ) ) {
			$ogdesc = "Find apartments, houses, offices spaces and cottages for rent in Downeast Maine at Ellsworthamerican.com/Rentals.";
		}
		if( is_post_type_archive( 'classifieds' )  || is_front_page() ) {
			$ogdesc = "Whatever you're looking for, you'll find it at EllsworthAmerican.com/Classifieds.";
		}
		if( is_post_type_archive( 'realestate' ) ) {
			//$ogdesc = "Search homes, property, camps and businesses locations for sale at EllsworthAmerican.com/RealEstate.";
		}
		return $ogdesc;
}
// add the og:title for CPT Archives
//add_filter('wpseo_title', 'reach_product_wpseo_title');
function reach_product_wpseo_title($title) {
    if(  is_post_type_archive( 'helpwanted' ) ) {
        //$title = "Help Wanted - Job Listings In Downeast Maine";
    }
		if(  is_post_type_archive( 'rentals' ) ) {
        $title = "Homes & Apartments for rent in Hancock County";
    }
		if(  is_post_type_archive( 'classifieds' ) || is_front_page() ) {
        $title = "Find what you're looking for - Hancock County Classifieds";
    }
		if(  is_post_type_archive( 'realestate' ) ) {
        //$title = "Find properties for sale in Hancock County ";
    }
    return $title;
}

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */

function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;
		} else {
			return home_url().'/my-listings/';
		}
	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );
