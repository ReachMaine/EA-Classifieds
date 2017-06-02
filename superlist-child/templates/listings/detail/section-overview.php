<?php $attributes = Inventor_Post_Types::get_attributes(); ?>

<?php if ( ! empty( $attributes ) && is_array( $attributes ) && count( $attributes ) > 0 ) : ?>
    <div class="listing-detail-section" id="listing-detail-section-attributes">
        <h2 class="page-header"><?php echo $section_title; ?></h2>

        <div class="listing-detail-attributes">
            <ul>
                <?php foreach( $attributes as $key => $attribute ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>">
                        <?php switch ($attribute['name']) {
                                case 'Listing categories':
                                    $att_name = 'Listing Type';
                                    break;
                                case 'Location / Region':
                                    $att_name = "Region / Town";
                                    break;
                                default:
                                    $att_name = $attribute['name'];

                        } ?>
                        <strong class="key"><?php echo wp_kses( $att_name, wp_kses_allowed_html( 'post' ) ); ?></strong>
                        <span class="value"><?php echo wp_kses( $attribute['value'], wp_kses_allowed_html( 'post' ) ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div><!-- /.listing-detail-attributes -->
    </div><!-- /.listing-detail-section -->
<?php endif; ?>