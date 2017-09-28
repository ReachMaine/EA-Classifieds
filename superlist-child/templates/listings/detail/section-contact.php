<?php /*
  1Jun17 zig - dont display address & move website to second column
*/ ?>

<?php if ( apply_filters( 'inventor_metabox_allowed', true, 'contact', get_the_author_meta('ID') ) && isset( $fields ) ): ?>
    <?php $predefined_fields = array(
        INVENTOR_LISTING_PREFIX . 'email',
        INVENTOR_LISTING_PREFIX . 'website',
        INVENTOR_LISTING_PREFIX . 'phone',
        INVENTOR_LISTING_PREFIX . 'person',
        INVENTOR_LISTING_PREFIX . 'address'
    ); ?>
    <?php $custom_fields = array_diff( array_keys( $fields ), $predefined_fields ); ?>

    <?php
    $posttype = get_post_type( get_the_ID());
    $address = ""; // zig x-out get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'address', true );
    $contactlogo = "";
    if ( (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'hide_author_info', true) != 'on') ) {

      $authorID = get_the_author_meta( 'ID' );
      $user_stuff = get_user_meta($authorID);
      //echo "<pre>"; var_dump($user_stuff); echo "</pre>";
      $website = $user_stuff["user_companywebsite"][0];
      $email = $user_stuff["user_general_email"][0];
      $address = "";
      if ( $user_stuff["user_general_phone"] ) {
        $phone = $user_stuff["user_general_phone"][0];
      } else if ($user_stuff["user_agentphone"]) {
        $phone = $user_stuff["user_agentphone"][0];
      }
      if ( $user_stuff["user_companyphoto"] ) {
        //$contactlogo = $user_stuff["user_companyphoto"][0];
      }
    } else {
      $email = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'email', true );
      $website = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'website', true );
      $phone = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'phone', true );
      $person = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'person', true );
      $contactlogo = "";

   } /* end if for autho_info */ ?>

   <?php if ( ! empty( $website ) ) {
       if (strpos($website, '://') === false) {
           $website_display = $website;
           $website  = "http://".$website;
         } else {
           $website_display = parse_url($website, PHP_URL_HOST);
         }
       } ?>
    <?php// if ( ! empty( $email ) || ! empty( $website ) || ! empty( $phone ) || ! empty( $person ) || ! empty( $address ) ) {  ?>
        <div class="listing-detail-section  col-md-4" id="listing-detail-section-contact">
          <?php if ( (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'hide_author_info', true) != 'on') ) {
              //echo '<div class="listing-detail-section" id="listing-detail-section-author"  > ';
                  echo '<div class="listing-detail-author">';
                      //echo "user ID:  ".$authorID."<br>";
                      //echo "<pre>"; var_dump($user_stuff); echo "</pre>";
                      echo '<div class="author">';
                        //echo '<div class="row">';
                        //  echo '<div class="col-md-3">';
                        if ($user_stuff["nickname"] ) {
                          echo '<div class="listing-author-name">';
                            echo $user_stuff["nickname"][0] ;
                          echo '</div><!-- end name -->';
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

                          if ($user_stuff["user_company"]) {
                            echo '<div class="listing-company-name">';
                            if ($website) {
                              echo '<a href="'.esc_attr( $website ).'" target="_blank">'.$user_stuff["user_company"][0].'</a>';
                              $website = ""; // clear $website so dont print later if link here.
                            } else {
                              echo $user_stuff["user_company"][0] ;
                            }
                            echo '</div><!-- end company -->';
                          }
                         if ($user_stuff["user_companyaddress"]) {
                           if ($user_stuff["user_companyaddress"][0] || $user_stuff["user_companytown"][0] ){
                             echo '<div class="listing-company-address">';
                             if ($user_stuff["user_companyaddress"][0]) {
                                 $COaddress = $user_stuff["user_companyaddress"][0]."<br>";
                             }

                            if ( $user_stuff["user_companytown"] ) {
                              $COaddress .= $user_stuff["user_companytown"][0];
                              if ($user_stuff["user_companystate"]) {
                                $COaddress .= ", ".$user_stuff["user_companystate"][0];
                              }
                            }
                              echo $COaddress ;
                              echo '</div><!-- end company -->';
                            }
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

                            <?php foreach( $custom_fields as $custom_field ): ?>
                                <?php if ( ! empty( $fields[ $custom_field ]['skip'] ) ) continue; ?>

                                <?php $value = get_post_meta( get_the_ID(), $custom_field, true ); ?>
                                <?php if ( ! empty( $value ) ): ?>
                                    <li class="<?php echo str_replace( INVENTOR_LISTING_PREFIX, '', esc_attr( $custom_field ) ); ?>">
                                        <strong class="key"><?php echo $fields[ $custom_field ]['name']; ?></strong>
                                        <span class="value"><?php echo esc_attr( $value ); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                          <?php if ( ! empty( $website ) ) { ?>
                              <li class="website">
                                  <strong class="key"><i class="fa fa-external-link" aria-hidden="true"></i></strong>
                                  <span class="value">
                                      <a href="<?php echo esc_attr( $website ); ?>" target="_blank"><?php echo esc_attr( $website_display ); ?></a>
                                  </span>
                              </li>
                          <?php } ?>
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
<?php endif; ?>
</div><!-- /.row -->
