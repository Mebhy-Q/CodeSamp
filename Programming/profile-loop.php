<?php
/**
 * Members Profile Loop
 * All fields custom added to profile loops. 
 * @since 3.0.0
 * @version 3.1.0
 */

?>

	<h2 class="screen-heading view-profile-screen"><?php esc_html_e( 'View Profile', 'buddypress' ); ?></h2>

<?php bp_nouveau_xprofile_hook( 'before', 'loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>

	<?php
	while ( bp_profile_groups() ) :
		bp_the_profile_group();
		?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

		<?php bp_nouveau_xprofile_hook( 'before', 'field_content' ); ?>

		<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

			<?php if (bp_get_the_profile_group_name() !== 'CoverOptions') : ?>
				<h3 class="screen-heading profile-group-title">
				<!-- User title -->
					<?php bp_the_profile_group_name(); ?>
				</h3>
			<?php endif; ?>
				<!-- construct table -->
			<table class="profile-fields bp-tables-user">

				<?php
				while ( bp_profile_fields() ) :
					bp_the_profile_field();
					?>
					<!-- pull all fields for profiles but admin notes -->
					<?php if ( bp_field_has_data() && bp_get_the_profile_field_name() !== 'Woffice_Notes' ) : ?>

					<tr<?php bp_field_css_class(); ?>>
						<!-- table for entry fields -->
						<td class="label"><?php bp_the_profile_field_name(); ?></td>

						<td class="data"><?php bp_the_profile_field_value(); ?></td>

					</tr>

				<?php endif; ?>

					<?php bp_nouveau_xprofile_hook( '', 'field_item' ); ?>

				<?php endwhile; ?>

			</table>
			<div class="row">		
		<div class="col-xs-12 col-sm-5">								 
			<div class="shadowBox">
						<!-- Shadowbox container -->
				<?php bp_member_profile_data('field=Shadowbox' );?>										
			</div>
		</div>
		<div class="col-xs-12 col-sm-7">								
			<div class="bioField">
						<!-- Bio Text -->
				<?php bp_member_profile_data( 'field=Bio' ); ?>
			</div>				
		</div>
		</div>
				</div>

		<?php bp_nouveau_xprofile_hook( 'after', 'field_content' ); ?>

	<?php endif; ?>

	<?php endwhile; ?>

	<?php bp_nouveau_xprofile_hook( '', 'field_buttons' ); ?>

<?php endif; ?>

<?php
bp_nouveau_xprofile_hook( 'after', 'loop_content' );