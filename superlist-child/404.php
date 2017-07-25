<?php
/**
 * The template for displaying not found page
 *
 * @package Superlist
 * @since Superlist 1.0.0
 */

get_header(); ?>

<div class="row">
    <div class="col-sm-12">
        <div id="primary">
            <?php dynamic_sidebar( 'content-top' ); ?>

            <section class="no-results not-found">
              <div class="document-title">
                <h1>Page not found.</h1>
              </div>
                <div class="page-content">

                    <div class="number">
                        <div class="number-description"><?php echo esc_attr__( "Sorry, we didn't find what you are looking for..", 'superlist' ); ?></div><!-- /.number-description -->
                    </div><!-- /.number -->
                    <?php $thisurl = $_SERVER['REQUEST_URI'];
                      if ( (strpos($thisurl, "/helpwanted")  !== FALSE ) || (strpos($thisurl, "/classifieds") !== FALSE ) || (strpos($thisurl, "/realestate") !== FALSE ) || (strpos($thisurl, "/rentals") !== FALSE ) ) {
                        echo '<p class="eac-ad-expired">';
                          echo 'This classified ad has expired.  If this is your listing, please call the Ellsworth American at <a href="2076672576">(207) 667-2576</a>';
                        echo '</p>';
                      }
                    ?>
                </div><!-- .page-content -->
            </section><!-- .no-results -->

            <?php dynamic_sidebar( 'content-bottom' ); ?>
        </div><!-- /#primary -->
    </div><!-- /.col-* -->
</div><!-- /.row -->

<?php get_footer(); ?>
