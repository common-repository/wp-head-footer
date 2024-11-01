<?php
/**
 * WP_Head_Footer_Settings
 *
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
*/
class WP_Head_Footer_Settings implements iWP_Head_Footer
{
	public static function admin_menu()
	{
		add_options_page(
			self::PLUGIN_TITLE,
			self::PLUGIN_TITLE,
			self::REQUIRED_CAPABILITY,
			self::PLUGIN_SLUG,
			[ __CLASS__, 'page' ]
		);
	}

	public static function admin_init()
	{
		register_setting(
			self::OPTION_NAME . '_option_group',
			self::OPTION_NAME,
			[ 'WP_Head_Footer_Sanitize', 'sanitize_options' ]
		);

		foreach (WP_Head_Footer_Options::get_sections() as $section => $section_args) {
			add_settings_section(
				self::OPTION_NAME . '_section_' . $section,
				$section_args['title'],
				[ __CLASS__, 'render_section' ],
				self::PLUGIN_SLUG . '-admin-page'
			);

			foreach ($section_args['options'] as $key => $value) {
				// Salta i campi "replace" perché non sono necessari qui, sono utilizzati solo nel metabox
				if (strpos($value['input_name'], 'replace') !== false) {
					continue;
				}

				add_settings_field(
					$key,
					$value['label'],
					[ __CLASS__, 'render_field' ],
					self::PLUGIN_SLUG . '-admin-page',
					self::OPTION_NAME . '_section_' . $section,
					$value
				);
			}
		}
	}

	public static function page()
	{
		include_once plugin_dir_path(self::FILE) . 'templates/wp-head-footer-templates-settings-page.php';
	}

	//// Genera il markup per la sezione di impostazioni
	public static function render_section($args) {}

	public static function render_field($args)
	{
		$WP_Head_Footer_Form = new WP_Head_Footer_Form('site');
		$WP_Head_Footer_Form->field($args);
	}
}
