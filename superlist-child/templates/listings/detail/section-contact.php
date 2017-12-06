<?php /*
  1Jun17 zig - dont display address & move website to second column      echo "<!-- authorID is".$authorID." -->";
*/
global $post;
global $fields;
 ?>

<?php
  $authorID = $post->post_author;
  echo "<!-- authorID is: ".$authorID." -->";
if (  apply_filters( 'inventor_metabox_allowed', true, 'contact', $authorID ) /* &&  isset( $fields ) */ ) { ?>
    <?php echo "<!-- yep1.-->"; ?>
    <?php $predefined_fields = array(
        INVENTOR_LISTING_PREFIX . 'email',
        INVENTOR_LISTING_PREFIX . 'website',
        INVENTOR_LISTING_PREFIX . 'phone',
        INVENTOR_LISTING_PREFIX . 'person',
        INVENTOR_LISTING_PREFIX . 'address'
    ); ?>
    <?php /* $custom_fields = array_diff( array_keys( $fields ), $predefined_fields ); */ ?>

    <?php
    $posttype = get_post_type( get_the_ID());
    $address = ""; // zig x-out get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'address', true );
    $contactlogo = "";


    if (($posttype == 'realestate') && (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'hide_author_info', true) != 'on') ) {
      $user_stuff = get_user_meta($authorID);
      //echo "<pre>"; var_dump($user_stuff); echo "</pre>";
      $website = $user_stuff["user_general_website"][0];
      $email = $user_stuff["user_general_email"][0];
      $address = "";
      if ( $user_stuff["user_general_phone"] ) {
        $phone = $user_stuff["user_general_phone"][0];
      } else if ($user_stuff["user_agentphone"]) {
        $phone = $user_stuff["user_agentphone"][0];
      }
      if ( $user_stuff["user_photo"] ) {
        //$contactlogo = $user_stuff["user_companyphoto"][0];
      }
    } else {
      $email = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'email', true );
      $website = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'website', true );
      $phone = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'phone', true );
      $person = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'person', true );
      $contactlogo = "";

   } /* end if for autho_info */  ?>

   <?php if ( ! empty( $website ) ) {
       if (strpos($website, '://') === false) {
           $website_display = $website;
           $website  = "http://".$website;
         } else {
           $website_display = parse_url($website, PHP_URL_HOST);
         }
    }  ?>
    <?php// if ( ! empty( $email ) || ! empty( $website ) || ! empty( $phone ) || ! empty( $person ) || ! empty( $address ) ) {  ?>
        <div class="listing-detail-section  col-md-4" id="listing-detail-section-contact">
          <?php if ( ($posttype == 'realestate') && (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'hide_author_info', true) != 'on') ) {
              //echo '<div class="listing-detail-section" id="listing-detail-section-author"  > ';
                  echo '<div class="listing-detail-author '.$authorID.'x">';
                  $author_post_link = get_author_posts_url($authorID);
                      //echo "user ID:  ".$authorID."<br>";
                      //echo "<pre>"; var_dump($user_stuff); echo "</pre>";
                      echo '<div class="author">';
                        //echo '<div class="row">';
                        //  echo '<div class="col-md-3">';
                        if ($user_stuff["nickname"] ) {
                          echo '<div class="listing-author-name">';
                            echo '<a href="'.$author_post_link.'">';
                            echo $user_stuff["nickname"][0] ;
                            echo '</a>';
                          echo '</div><!-- end name -->';
                          echo '<div class="listing-author-link">';
                            echo '<a href="'.$author_post_link.'">';
                              echo 'See listings >>';
                            echo '</a>';
                          echo '</div>';
                        }
                          if ($user_stuff["user_general_image"]) {
                             echo '<div class="mug">';
                                echo '<img src="'.$user_stuff["user_general_image"][0].'" class="listing-author-image" >';
                              echo '</div><!-- end mug -->';
                          } else if ($user_stuff["user_photo"]) {
                            echo '<div class="mug">';
                               echo '<img src="'.$user_stuff["user_photo"][0].'" class="listing-author-image" >';
                             echo '</div><!-- end mug -->';
                          }

                          if ($user_stuff["user_companyname"]) {
                            echo '<div class="listing-company-name">';
                              echo $user_stuff["user_companyname"][0] ;
                            echo '</div><!-- end company -->';
                          }
                          if ($user_stuff["user_address_street_and_number"][0] || $user_stuff["user_address_city"][0] ){
                           echo '<div class="listing-company-address">';
                            if ($user_stuff["user_address_street_and_number"][0]) {
                             $COaddress = $user_stuff["user_address_street_and_number"][0]."<br>";
                            }

                            if ( $user_stuff["user_address_city"] ) {
                              $COaddress .= $user_stuff["user_address_city"][0];
                              if ($user_stuff["user_address_county"]) {
                                $COaddress .= ", ".$user_stuff["user_address_county"][0];
                              }
                            }
                              echo $COaddress ;
                              echo '</div><!-- end company -->';
                          }


                      //  echo '</div><!-- end author "row" -->';
                      echo '</div><!-- end author-->';
                      /* if ( class_exists( 'Inventor_Template_Loader' ) ) {
                        echo Inventor_Template_Loader::load( 'widgets/listing-author' );
                      } */
                      echo '</div>'; // listing-detail-author

                  } // show autho is on ?>
            <div class="listing-detail-contact">
                        <ul>
                            <?php if ( ! empty( $email ) ): ?>
                                <li class="email">
                                    <strong class="key"><i class="inventor-poi inventor-poi-mail"></i></strong>
                                    <span class="value">
                                        <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_attr( $email ); ?></a>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <?php if ( ! empty( $phone ) ): ?>
                                <li class="phone">
                                    <strong class="key"><i class="inventor-poi inventor-poi-phone"></i></strong>
                                    <span class="value"><a href="tel:<?php echo wp_kses( str_replace(' ', '', $phone), wp_kses_allowed_html( 'post' ) ); ?>"><?php echo wp_kses( $phone, wp_kses_allowed_html( 'post' ) ); ?></a></span>
                                </li>
                            <?php endif; ?>

                            <?php /* foreach( $custom_fields as $custom_field ): ?>
                                <?php if ( ! empty( $fields[ $custom_field ]['skip'] ) ) continue; ?>

                                <?php $value = get_post_meta( get_the_ID(), $custom_field, true ); ?>
                                <?php if ( ! empty( $value ) ): ?>
                                    <li class="<?php echo str_replace( INVENTOR_LISTING_PREFIX, '', esc_attr( $custom_field ) ); ?>">
                                        <strong class="key"><?php echo $fields[ $custom_field ]['name']; ?></strong>
                                        <span class="value"><?php echo esc_attr( $value ); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; */ ?>
                          <?php if ( ! empty( $website ) ) { ?>
                              <li class="website">
                                  <strong class="key"><i class="fa fa-external-link" aria-hidden="true"></i></strong>
                                  <span class="value">
                                      <a href="<?php echo esc_attr( $website ); ?>" target="_blank"><?php echo esc_attr( $website_display ); ?></a>
                                  </span>
                              </li>
                          <?php }  else { echo '<li class="empty_website"></li>'; }?>
                            <?php if ( ! empty( $person ) && false ): /*zig false out - dont show person here */ ?>
                                <li class="person">
                                    <strong class="key"><?php echo __( 'Person', 'inventor' ); ?></strong>
                                    <span class="value"><?php echo wp_kses( $person, wp_kses_allowed_html( 'post' ) ); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ( ! empty( $address ) ): ?>
                                <li class="address">
                                    <strong class="key"><i class="inventor-poi inventor-poi-pin"></i></strong>
                                    <span class="value"><?php echo wp_kses( nl2br( $address ), wp_kses_allowed_html( 'post' ) ); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ( ! empty( $contactlogo  ) ): ?>
                                <li class="contact-logo">
                                    <img src='<?php echo $contactlogo; ?>'>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <?php /* social icons */
                          $default_social_networks = Inventor_Metaboxes::social_networks();
                          $social_networks = apply_filters( 'inventor_metabox_social_networks', array(), 'user' );
                          $social = '';

                          foreach( $social_networks as $key => $title ) {
                              $field_id = INVENTOR_USER_PREFIX . 'social_' . $key;
                              if ( apply_filters( 'inventor_metabox_field_enabled', true, INVENTOR_USER_PREFIX . 'profile', $field_id, 'user' ) ) {
                                  //$social_value = get_user_meta( $user_stuff->ID, $field_id, true );
                                 $social_value = $user_stuff[$field_id][0];

                                  if ( ! empty( $social_value ) ) {
                                      $class = array_key_exists( $key, $default_social_networks ) ? 'default' : '';
                                      $social_network_url = apply_filters( 'inventor_social_network_url', esc_attr( $social_value ), $key );
                                      $fa_desc =  esc_attr( $key );
                                      if ($fa_desc == 'youtube') {
                                        $fa_desc = 'youtube-play';
                                      }
                                      $social .= '<a href="' . $social_network_url . '" target="_blank" class="' . $class . '"><i class="fa fa-'.$fa_desc.'"></i></a>';
                                  }
                              } else { echo "<!-- $field_id not enabled."; }
                          }
                          if ( ! empty( $social ) ) { ?>
                              <div class="user-contact-social">
                                  <?php echo $social; ?>
                              </div><!-- /.user-banner-social -->
                          <?php } /* end if */
                        /* end social icons */
                        ?>
            </div><!-- /.listing-detail-contact -->
        <?php /* </div><!-- /.listing-detail-section --> */ ?>

    <?php if ( ($posttype == 'realestate') && (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'hide_author_info', true) != 'on') && ($user_stuff["user_userbio"] ) ) {
      /* show the autho bio if there is one */
        echo '<div class="listing-detail-author-bio"  > ';
            echo '<div class="listing-detail-author-bio">';
                if ($user_stuff["user_userbio"]) {
                    echo '<div class="listing-author-desc">';
                      echo $user_stuff["user_userbio"][0] ;
                    echo '</div><!-- end desc -->';
                }
            echo '</div>'; //author-bio

          echo '</div>'; //section
        } // show autho is on

    ?>
    <?php echo '</div>'; //section ?>
  <?php } else { echo "<!-- nada -->"; } ?>
</div><!-- /.row -->
