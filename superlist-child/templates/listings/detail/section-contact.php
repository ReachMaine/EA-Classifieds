<?php /*
  1Jun17 zig - dont display address & move website to second column
*/ ?>

<?php if ( apply_filters( 'inventor_metabox_allowed', true, 'contact', get_the_author_meta('ID') ) && isset( $fields ) ): ?>
  <div class="row justify-content-center"> <?php /* start of next row2 */ ?>
  <?php if ( (get_post_meta(get_the_id(), INVENTOR_LISTING_PREFIX.'show_author_info', true) == 'on') ) {
        echo '<div class="list-detail-author-wrap col-md-4">';
          echo '<div id="listing-detail-section-author" class="listing-detail-section"> ';
            reach_listing_author( get_the_ID());
          echo '</div>'; // section
        echo '</div>'; // wrap
    } ?>

    <?php $predefined_fields = array(
        INVENTOR_LISTING_PREFIX . 'email',
        INVENTOR_LISTING_PREFIX . 'website',
        INVENTOR_LISTING_PREFIX . 'phone',
        INVENTOR_LISTING_PREFIX . 'person',
        INVENTOR_LISTING_PREFIX . 'address'
    ); ?>
    <?php $custom_fields = array_diff( array_keys( $fields ), $predefined_fields ); ?>

    <?php $email = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'email', true ); ?>
    <?php $website = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'website', true ); ?>
    <?php $phone = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'phone', true ); ?>
    <?php $person = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'person', true ); ?>
    <?php $address = ""; // zig x-out get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'address', true ); ?>

    <?php if ( ! empty( $email ) || ! empty( $website ) || ! empty( $phone ) || ! empty( $person ) || ! empty( $address ) ) : ?>
        <div class="listing-detail-section  col-md-4" id="listing-detail-section-contact">
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
                          <?php if ( ! empty( $website ) ): ?>
                              <?php $website_display = parse_url($website, PHP_URL_HOST); ?>
                              <li class="website">
                                  <strong class="key"><i class="fa fa-external-link" aria-hidden="true"></i></strong>
                                  <span class="value">
                                      <a href="<?php echo esc_attr( $website ); ?>" target="_blank"><?php echo esc_attr( $website_display ); ?></a>
                                  </span>
                              </li>
                          <?php endif; ?>
                            <?php if ( ! empty( $person ) ): ?>
                                <li class="person">
                                    <strong class="key"><?php echo __( 'Person', 'inventor' ); ?></strong>
                                    <span class="value"><?php echo wp_kses( $person, wp_kses_allowed_html( 'post' ) ); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ( ! empty( $address ) ): ?>
                                <li class="address">
                                    <strong class="key"><?php echo __( 'Address', 'inventor' ); ?></strong>
                                    <span class="value"><?php echo wp_kses( nl2br( $address ), wp_kses_allowed_html( 'post' ) ); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
            </div><!-- /.listing-detail-contact -->
        </div><!-- /.listing-detail-section -->
    <?php endif; ?>
  </div><!-- /.row -->
<?php endif; ?>
