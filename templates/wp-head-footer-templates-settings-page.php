<?php
/**
 * Pagina delle impostazioni
 *
 * @package     WP_Head_Footer\Templates
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
?>
<div class="wrap">

	<h2 class="title">
		<?php echo esc_html(get_admin_page_title()); ?>
		( <span style="color:green;"><?php echo esc_html('Site-wide'); ?></span> )
	</h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- Content -->
			<div id="post-body-content">

				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h3 class="hndle"><?php echo esc_html__('Site-wide settings', 'wp-head-footer'); ?></h3>

						<div class="inside">

							<form method="post" action="options.php">

								<?php

								settings_fields(self::OPTION_NAME . '_option_group');
								do_settings_sections(self::PLUGIN_SLUG . '-admin-page');
								submit_button(
									__('Update', 'wp-head-footer'),
									'primary',
									null,
									false
								);
								?>

							</form> <!-- /form -->

						</div>

					</div> <!-- /postbox -->

				</div> <!-- /normal-sortables -->

			</div> <!-- /post-body-content -->

			<!-- /Content -->

			<!-- Sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				<?php include_once(plugin_dir_path(self::FILE) . 'templates/wp-head-footer-templates-settings-page-sidebar.php'); ?>
			</div> <!-- /#postbox-container-1 -->

			<div id="postbox-container-2" class="postbox-container"></div> <!-- /#postbox-container-2 -->
			<!-- /Sidebar -->

		</div> <!-- /#post-body -->

		<br class="clear" />

	</div> <!-- /#poststuff -->

</div> <!-- /.wrap -->