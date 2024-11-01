<?php
/**
 * Meta box
 *
 * @package     WP_Head_Footer\Templates
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
?>
<?php $WP_Head_Footer_Form::nonce(); ?>

<div style="float: right;">
	<a title="<?php echo esc_attr__('Site-wide settings', 'wp-head-footer'); ?>" href="<?php menu_page_url(self::PLUGIN_SLUG); ?>">
		<span class="dashicons dashicons-editor-help"></span>
	</a>
</div>

<?php foreach (WP_Head_Footer_Options::get_sections() as $section => $section_args) : ?>
	<div class="<?php echo esc_html(self::PLUGIN_SLUG); ?>-mbox">
		<h2 style="color:green; font-size: 20px">
			<span>
				<?php echo esc_html($section_args['title']); ?>
			</span>
		</h2>
		<div class="inside">
			<?php $WP_Head_Footer_Form::fields(isset($post) ? $post : null, $section, false); ?>
		</div>
	</div>
<?php endforeach ?>