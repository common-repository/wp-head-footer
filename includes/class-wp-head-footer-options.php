<?php
/**
 * WP_Head_Footer_Options
 *
 * @package WP_Head_Footer\Includes
 * @author Francesco Taurino
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 */

class WP_Head_Footer_Options implements iWP_Head_Footer
{
	/**
	 * Get sections
	 *
	 * @param string|null $key
	 * @return array|null
	 */
	public static function get_sections($key = null)
	{
		$defaults = [
			'head' => [
				'name' => 'head',
				'title' => 'Head',
				'description' => 'Head section',
				'options' => self::filter_options_by_section('head'),
			],
			'footer' => [
				'name' => 'footer',
				'title' => 'Footer',
				'description' => 'Footer section',
				'options' => self::filter_options_by_section('footer'),
			],
		];

		if ($key !== null) {
			return isset($defaults[$key]) ? $defaults[$key] : null;
		}

		return $defaults;
	}

	/**
	 * Get default options
	 *
	 * @param string|null $key
	 * @return array|null
	 */
	public static function get_default_options($key = null)
	{
		$defaults = [
			'wp_head_priority' => [
				'type' => 'integer',
				'input_name' => 'wp_head_priority',
				'input_type' => 'number',
				'input_value' => 10,
				'sanitize_callback' => 'sanitize_priority',
				'label' => esc_html__('Head code priority', 'wp-head-footer'),
				'description' => '',
				'section' => 'head',
			],
			'wp_head_replace' => [
				'type' => 'integer',
				'input_name' => 'wp_head_replace',
				'input_type' => 'select',
				'input_value' => 0,
				'select' => [
					0 => esc_html__('No', 'wp-head-footer'),
					1 => esc_html__('Yes', 'wp-head-footer'),
				],
				'sanitize_callback' => 'sanitize_replace',
				'label' => esc_html__('Replace site-wide head code', 'wp-head-footer'),
				'description' => '',
				'section' => 'head',
			],
			'wp_head' => [
				'type' => 'string',
				'input_name' => 'wp_head',
				'input_type' => 'textarea',
				'input_value' => '',
				'sanitize_callback' => 'sanitize_content',
				'label' => 'Head code',
				'description' => '',
				'section' => 'head',
			],
			'wp_footer_priority' => [
				'type' => 'integer',
				'input_name' => 'wp_footer_priority',
				'input_type' => 'number',
				'input_value' => 10,
				'sanitize_callback' => 'sanitize_priority',
				'label' => esc_html__('Footer code priority', 'wp-head-footer'),
				'description' => '',
				'section' => 'footer',
			],
			'wp_footer_replace' => [
				'type' => 'integer',
				'input_name' => 'wp_footer_replace',
				'input_type' => 'select',
				'input_value' => 0,
				'select' => [
					0 => esc_html__('No', 'wp-head-footer'),
					1 => esc_html__('Yes', 'wp-head-footer'),
				],
				'sanitize_callback' => 'sanitize_replace',
				'label' => esc_html__('Replace site-wide footer code', 'wp-head-footer'),
				'description' => '',
				'section' => 'footer',
			],
			'wp_footer' => [
				'type' => 'string',
				'input_name' => 'wp_footer',
				'input_type' => 'textarea',
				'input_value' => '',
				'sanitize_callback' => 'sanitize_content',
				'label' => 'Footer code',
				'description' => '',
				'section' => 'footer',
			],
		];

		if ($key !== null) {
			return isset($defaults[$key]) ? $defaults[$key] : null;
		}

		return $defaults;
	}

	/**
	 * Filter options by section
	 *
	 * @param string $section
	 * @return array
	 */
	private static function filter_options_by_section($section)
	{
		return wp_filter_object_list(
			self::get_default_options(),
			['section' => $section],
			'and'
		);
	}

	/**
	 * Get options
	 *
	 * @param int $post_id
	 * @param string|null $key
	 * @param bool $sitewide
	 * @return array|null
	 */
	public static function get_options($post_id = 0, $key = null, $sitewide = false)
	{
		$options = [];
		$defaults = self::get_default_options();

		if ($sitewide) {
			$db_options = get_option(self::OPTION_NAME, $defaults);
		} else {
			$db_options = get_post_meta($post_id, self::META_KEY, true);
		}

		foreach ($defaults as $x => $default) {
			//$options[$x] = self::sanitize_option($x, isset($db_options[$x]) ? json_decode($db_options[$x]) : $default);
			$options[$x] = WP_Head_Footer_Sanitize::sanitize_option($x, isset($db_options[$x]) ? $db_options[$x] : $default);
		}

		if ($key !== null) {
			return isset($options[$key]) ? $options[$key] : null;
		}

		return $options;
	}

	/**
	 * Get priority
	 *
	 * Used to specify the order in which the functions associated
	 * with a particular action are executed.
	 * Lower numbers correspond with earlier execution, and functions
	 * with the same priority are executed in the order in which they were added to the action.
	 *
	 * @param int $post_id
	 * @param string $key [wp_head|wp_footer]
	 * @param bool $sitewide
	 * @return int
	 */
	public static function get_priority($post_id = 0, $key = '', $sitewide = false)
	{
		return (int) self::get_options($post_id, $key . '_priority', $sitewide);
	}

	
}
