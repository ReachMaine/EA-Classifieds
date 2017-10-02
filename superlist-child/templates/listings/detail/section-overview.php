<?php $attributes = Inventor_Post_Types::get_attributes(); ?>
<div class="row"> <?php /* start row1 here, end in description */ ?>
<?php if ( ! empty( $attributes ) && is_array( $attributes ) && count( $attributes ) > 0 ) : ?>
    <div class="listing-detail-section col-md-3 float-lg-right" id="listing-detail-section-attributes">
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
                                  $att_name = '<i class="inventor-poi inventor-poi-pin"></i>';
                                  break;
                                case 'Address':
                                case 'Price':
                                    $att_name = "";
                                    break;
                                case 'Beds':
                                  $att_name='<i class="inventor-poi inventor-poi-hotel"></i>';
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
