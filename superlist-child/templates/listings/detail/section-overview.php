<?php $attributes = Inventor_Post_Types::get_attributes(); ?>
<div class="row row1"> <?php /* start here, end in description */ ?>
<?php if ( ! empty( $attributes ) && is_array( $attributes ) && count( $attributes ) > 0 ) : ?>
    <div class="listing-detail-section col-sm-3" id="listing-detail-section-attributes">
        <?php /* <h2 class="page-header"><?php echo $section_title; ?></h2> */ ?>

        <div class="listing-detail-attributes">
            <ul style="column-count: 1;">
                <?php foreach( $attributes as $key => $attribute ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>">
                        <?php switch ($attribute['name']) {
                                case 'Listing categories':
                                    $att_name = '';
                                    break;
                                case 'Location / Region':
                                case 'Price':
                                    $att_name = "";
                                    break;
                                default:
                                    $att_name = $attribute['name'];

                        } ?>
                        <?php if ($att_name) { ?><strong class="key"><?php echo wp_kses( $att_name, wp_kses_allowed_html( 'post' ) ); ?></strong> <?php } ?>
                        <span class="value"><?php echo wp_kses( $attribute['value'], wp_kses_allowed_html( 'post' ) ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div><!-- /.listing-detail-attributes -->
    </div><!-- /.listing-detail-section -->
<?php endif; ?>
