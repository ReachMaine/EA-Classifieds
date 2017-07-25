<?php
/* languages customizations

__( 'Keyword', 'inventor' ); ?>"
*/
	if ( !function_exists('reach_change_theme_text') ){
		function reach_change_theme_text( $translated_text, $text, $domain ) {
			 /* if ( is_singular() ) { */
			    switch (inventor) {
						case 'inventor':
							switch ( $translated_text ) {
											case 'Keyword' :
													$translated_text = __( 'Search',  'inventor'  );
													break;
									}
							break;
					case 'woocommerce':
						switch ( $translated_text ) {
				            case 'Place order' :
				                $translated_text = __( 'Make Payment',  'woocommerce'  );
				                break;
				           case 'Add to cart':
				            	$translated_text = __( 'Continue to Checkout',  'woocommerce'  );
				            	break;
				        }
						break;
					case 'superlist' :
						switch ( $translated_text ) {
								case 'Sorry, but nothing matched your search terms. Please try again with some different keywords.' :
									$translated_text = __ ('Sorry, no current listings match your filters. Please try again with different filters. ', 'superlist');
									break;
						}
					default:
						/* switch ( $translated_text ) {
				            case 'Category' :
				                $translated_text = __( '',  $domain  );
				                break;
				         	case 'Type here...':
				            	$translated_text = __( 'Search...',  $domain  );
				            	break;
				            case 'BLOG CATEGORIES':
				            	$translated_text = __( 'Found in',  $domain  );
				            	break;
				            case 'Share this post:':
				            	$translated_text = __('Share', ' $domain );
				            	break;
				        } */

				}


	    	return $translated_text;
		} // end function reach_change_theme_text
		add_filter( 'gettext', 'reach_change_theme_text', 20, 3 );
	} // end if not exists reach_change_theme_text
?>
