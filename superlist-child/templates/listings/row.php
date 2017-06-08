<?php /*
2Jun17 zig - change location to town.
       - move image to third in the row
6Jun17 zig - dont show title on classifleds or help wanted.
*/ ?>
<?php $featured = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'featured', true ); ?>
<?php $reduced = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'reduced', true ); ?>

<?php $posttype = get_post_type( get_the_ID()); ?>

<div class="listing-row <?php if ( $featured ) : ?>featured<?php endif; ?> <?php if ( $posttype ) {echo $posttype;} ?>">


    <div class="listing-row-body">
      <?php // decide if we should show the title.
        $scraped_content = (get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'scraped', true) == 'on' ) ? true : false;
        $show_title = true;
        if (!$scraped_content )  {// check for no content??? i.e. display ads?  depends on how we're going to do them (featured image or in content)
            switch($posttype) { // dont show title for help wanted or classifieds.
            case 'classifieds':
            case 'rentals':
            //case 'helpwanted':
              $show_title = false;
              break;
            default:
          } // end switch
        }

        if ($show_title) { ?>
        <h2 class="listing-row-title"><a href="<?php the_permalink(); ?>"><?php echo Inventor_Utilities::excerpt( get_the_title(), 50 ); ?></a></h2>
      <?php } ?>
        <div class="listing-row-content">
            <?php
              if ($scraped_content) {
                  the_excerpt();
                  ?>
                  <p> <a class="see-more-link" href="#">See website </a></p>
                  <?php
                } else {
                  the_content();
                  /* no sense having a read more if we've seen the whole content*/

                } ?>
                <p> <?php /* JFN */ ?>
                    <a class="read-more-link" href="<?php echo esc_attr( get_permalink( get_the_ID() ) ); ?>"><?php echo esc_attr__( 'Read More', 'inventor' ); ?><i class="fa fa-chevron-right"></i></a>
                </p>

        </div><!-- /.listing-row-content -->
    </div><!-- /.listing-row-body -->

    <?php $price = Inventor_Price::get_price( get_the_ID() );
     $location = Inventor_Query::get_listing_location_name( get_the_ID(), '/', false );
     $beds = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'beds', true );
     $baths =  get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'baths', true );
     $acreage = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'acreage', true );
     $sqft = get_post_meta( get_the_ID(), INVENTOR_LISTING_PREFIX . 'sqft', true );
     if ( $price || $location || $beds || $baths || $sqft || $acreage ) { ?>
        <div class="listing-row-properties">
            <dl>
                <?php if ( ! empty( $price ) ) : ?>
                    <dt><?php echo esc_attr__( 'Price', 'inventor' ); ?></dt>
                    <dd><?php echo wp_kses( $price, wp_kses_allowed_html( 'post' ) ); ?></dd>
                <?php endif; ?>
                <?php if ( ! empty( $location ) ) : ?>
                    <dt><?php echo esc_attr__( 'Town'/* 'Location' */, 'inventor' ); ?></dt>
                    <dd><?php echo wp_kses( $location, wp_kses_allowed_html( 'post' ) ); ?></dd>
                <?php endif; ?>
                <?php if ( ! empty( $beds ) ) : ?>
                    <dt><?php echo esc_attr__( 'Bedrooms', 'inventor' ); ?></dt>
                    <dd><?php echo wp_kses( $beds, wp_kses_allowed_html( 'post' ) ); ?></dd>
                <?php endif; ?>
                <?php if ( ! empty( $baths ) ) : ?>
                    <dt><?php echo esc_attr__( 'Bathrooms', 'inventor' ); ?></dt>
                    <dd><?php echo wp_kses( $baths, wp_kses_allowed_html( 'post' ) ); ?></dd>
                <?php endif; ?>
                <?php if ( ! empty( $sqft ) ) : ?>
                    <dt><?php echo esc_attr__( 'Square Footage', 'inventor' ); ?></dt>
                    <dd><?php echo wp_kses( $sqft, wp_kses_allowed_html( 'post' ) ); ?></dd>
                <?php endif; ?>
                <?php if ( ! empty( $acreage ) ) : ?>
                    <dt><?php echo esc_attr__( 'Square Footage', 'inventor' ); ?></dt>
                    <dd><?php echo wp_kses( $acreage, wp_kses_allowed_html( 'post' ) ); ?></dd>
                <?php endif; ?>
                <?php do_action( 'inventor_listing_content', get_the_ID(), 'row' ); ?>
            </dl>
        </div><!-- /.listing-row-properties -->
      <?php } ?>
      <?php  if ( has_post_thumbnail() ) {
          $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
          $image = $thumbnail[0];
          $img_class ="img-thumb";

          $image = apply_filters( 'inventor_listing_featured_image', $image, get_the_ID() ); ?>
          <div class="listing-row-image <?php echo $img_class; ?>" style="background-image: url('<?php echo esc_attr( $image ); ?>');">
              <a href="<?php the_permalink() ?>" class="listing-row-image-link"></a>

              <?php if ( $featured ) : ?>
                  <div class="listing-row-label-top listing-row-label-top-left"><?php echo esc_attr__( 'Featured', 'inventor' ); ?></div><!-- /.listing-row-label-top-left -->
              <?php endif; ?>

              <?php if ( $reduced ) : ?>
                  <div class="listing-row-label-top listing-row-label-top-right"><?php echo esc_attr__( 'Reduced', 'inventor' ); ?></div><!-- /.listing-row-label-top-right -->
              <?php endif; ?>

          </div><!-- /.listing-row-image -->
      <?php } /* end if thumb */?>
</div><!-- /.listing-row -->
