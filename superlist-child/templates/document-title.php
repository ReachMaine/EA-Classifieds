<?php
/**
 * Template file
 *
 * @package Superlist
 * @subpackage Templates
 */

?>

<div class="document-title">
    <?php echo wp_kses( do_shortcode( '[inventor_breadcrumb]' ), wp_kses_allowed_html( 'post' ) ); ?>

    <h1>
        <?php if ( is_archive() ) : ?>
            <?php if (is_post_type_archive() ) {
              if (is_post_type_archive('helpwanted')) {$ptimg = reach_get_post_type_image('helpwanted');}
              elseif (is_post_type_archive('classifieds')) {$ptimg = reach_get_post_type_image('classifieds');}
              elseif (is_post_type_archive('rentals')) {$ptimg = reach_get_post_type_image('rentals');}
              elseif (is_post_type_archive('realestate')) {$ptimg = reach_get_post_type_image('realestate');}
              else { $ptimg = reach_get_post_type_image('');}
              echo '<img class="reach-cpt-img" src="'.$ptimg.'"></img>';
            }
             the_archive_title(); ?>
        <?php elseif ( is_404() ) : ?>
            <?php echo esc_attr__( 'Page Not Found', 'superlist' ); ?>
        <?php elseif ( is_search() ) : ?>
            <?php echo esc_attr__( 'Search for', 'superlist' ); ?>
            "<?php echo get_search_query(); ?>"
        <?php else : ?>
            <?php $title = get_the_title(); ?>
            <?php if ( empty( $title ) || is_home() ) : ?>
                <?php bloginfo( 'name' ); ?>
            <?php else : ?>
                <?php the_title(); ?>
            <?php endif; ?>
        <?php endif; ?>
    </h1>

    <?php $description = term_description(); ?>
    <?php if ( ! empty( $description ) ) : ?>
        <?php echo wp_kses( $description, wp_kses_allowed_html( 'post' ) ); ?>
    <?php endif; ?>
</div><!-- /.document-title -->
