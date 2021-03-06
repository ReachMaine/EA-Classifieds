<?php if ( empty( $instance['hide_location'] ) ) : ?>
	<div class="form-group form-group-locations">
		<?php if ( 'labels' == $input_titles ) : ?>
			<label for="<?php echo ! empty( $field_id_prefix ) ? $field_id_prefix : ''; ?><?php echo esc_attr( $args['widget_id'] ); ?>_locations"><?php echo __( 'Location', 'inventor' ); ?></label>
		<?php endif; ?>

		<?php /* zig xout $locations = get_terms( 'locations', array(
			'hide_empty'    => false ,
		) ); */
		$tax_args = [
				'taxonomy' => 'locations',
				'parent' => '0', // no parent
		];
		$locations = get_terms($tax_args);
		?>

		<select class="form-control"
				name="locations"
				data-size="10"
				<?php if ( count( $locations ) > 10 ) : ?>data-live-search="true"<?php endif;?>
				id="<?php echo ! empty( $field_id_prefix ) ? $field_id_prefix : ''; ?><?php echo esc_attr( $args['widget_id'] ); ?>_locations">
			<option value="">
				<?php if ( 'placeholders' == $input_titles ) : ?>
					<?php echo __( 'Any Region', 'inventor' ); /* zig - change location to 'Any Region' */ ?>
				<?php else : ?>
					<?php echo __( 'All locations', 'inventor' ); ?>
				<?php endif; ?>
			</option>

			<?php global $wp_query; ?>
			<?php $selected = empty( $_GET['locations'] ) ? null : $_GET['locations']; ?>
			<?php $parent = is_tax('locations') ? $wp_query->queried_object : null; ?>
			<?php //zig  only display toplevel locations (but not towns in select list)
				$options = "";
				foreach( $locations as $locale ) {
					$options .= Inventor_Utilities::get_term_select_option( $locale, $depth, $selected );
				}
			?>
			<?php /* $options = Inventor_Utilities::build_hierarchical_taxonomy_select_options( 'locations', $selected, $parent ); */ ?>
			<?php echo $options; ?>

		</select>
	</div><!-- /.form-group -->
<?php endif; ?>
