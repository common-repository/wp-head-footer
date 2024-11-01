<?php
/**
 * La classe WP_Head_Footer_Form gestisce la creazione dei campi
 *
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
class WP_Head_Footer_Form implements iWP_Head_Footer
{


	/**
	 * Nome dell'opzione
	 *
	 * @var string
	 */
	protected static $option_name = '';


	/**
	 * Indica se l'opzione è sitewide o no
	 * @var bool
	 */
	protected static $sitewide = false;

	/**
	 * Costruttore della classe. 
	 * Genera il nome prefisso della chiave per  campo di input
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function __construct($key = '')
	{

		if ($key == 'site') {
			self::$option_name = self::OPTION_NAME;
			self::$sitewide = true;
		} elseif ($key == 'post') {
			self::$option_name = self::META_KEY;
		} else {
			return;
		}
	}


	/**
	 * Genera il nome del campo di input.
	 *
	 * @param  string  $key
	 * @param  boolean $sitewide
	 * @return string
	 */
	public static function input_name($key = null)
	{
		return self::$option_name . '[' . $key . ']';
	}


	/**
	 * Genera l'ID del campo di input.
	 *
	 * @param  string  $key
	 * @return string
	 */
	public static function input_id($key = null)
	{
		return self::$option_name . '-' . $key;
	}


	/**
	 * Genera un campo di input in base al valore di $type.
	 *
	 * @param  array  $args
	 * @return void
	 */
	public static function field($args)
	{

		$post_id = isset($args['post_id']) ? $args['post_id'] : 0;
		$type = isset($args['input_type']) ? $args['input_type'] : false;

		if (isset($args['post_id'])) {
			unset($args['post_id']);
		}

		if ($type == 'select') {

			self::select(
				array(
					'id' => self::input_id($args['input_name']),
					'name' => self::input_name($args['input_name']),
					'list' => WP_Head_Footer_options::get_default_options($args['input_name'])['select'],
					'value' => WP_Head_Footer_options::get_options($post_id, $args['input_name'], self::$sitewide),
					'label' => WP_Head_Footer_options::get_default_options($args['input_name'])['label'],
				)
			);
		} elseif ($type == 'text' || $type == 'number') {

			self::text(
				array(
					'id' => self::input_id($args['input_name']),
					'name' => self::input_name($args['input_name']),
					'value' => WP_Head_Footer_options::get_options($post_id, $args['input_name'], self::$sitewide),
					'label' => WP_Head_Footer_options::get_default_options($args['input_name'])['label'],
					'label_top' => true,
				),
				$type
			);
		} elseif ($type == 'textarea') {

			self::textarea(
				array(
					'id' => self::input_id($args['input_name']),
					'name' => self::input_name($args['input_name']),
					'value' => WP_Head_Footer_options::get_options($post_id, $args['input_name'], self::$sitewide),
					'label' => WP_Head_Footer_options::get_default_options($args['input_name'])['label'],
					'label_top' => true,
				)
			);
		}
	}


	/**
	 * Genera un campo nascosto con il valore del nonce
	 * 
	 * @return string
	 */

	public static function nonce(): void
	{
		echo '<input type="hidden" id="' . esc_attr(self::input_id('nonce')) . '" name="' . esc_attr(self::input_name('nonce')) . '" value="' . wp_create_nonce(plugin_basename(self::FILE)) . '" />';
	}

	/**
	 * Genera i campi di input definiti nelle opzioni
	 * 
	 * @param  object  $post_object
	 * @param  string $section
	 * @return void
	 */
	final public static function fields($post_object, $section = '')
	{

		$options = wp_filter_object_list(
			WP_Head_Footer_options::get_default_options(),
			!empty($section) ? array('section' => $section) : array(),
			'and',
			null
		);

		foreach ((array) $options as $key => $args) {
			if (self::$sitewide && strpos($key, 'replace') !== false) continue;

			if (!self::$sitewide) {
				$args['post_id'] = isset($post_object->ID) ? $post_object->ID : 0;
			}

?>
			<table class="form-table">
				<tr>
					<?php
					echo '
			<th scope="row">
			<label for="' . esc_attr( self::input_id($key) ) . '">
			' . esc_html($args['label']) . '
			</label>
			</th>';
					echo '<td>';
					echo '<fieldset>';
					echo self::field($args);
					if (isset($args['description']) && !empty($args['description'])) {
						echo '<p class="description">' . esc_html($args['description']) . '</p>';
					}
					echo '</fieldset>';
					echo '</td>';
					?>
				</tr>
			</table> <?php

					}
				}


				/**
				 * Genera un campo di input di tipo text o number.
				 * 
				 * @param  array  $args
				 * @return void
				 */
				public static function text($args = array(), $input = 'text')
				{
					$input = !in_array($input, array('text', 'number')) ? $input : 'text';
					if (!isset($args['id']) || empty($args['id'])) return;

					$val = isset($args['value']) ? $args['value'] : '';
					echo '<input ';
					echo 'id="' . esc_attr(isset($args['id']) ? $args['id'] : '') . '" ';
					echo 'name="' . esc_attr(isset($args['name']) ? $args['name'] : $args['id']) . '" ';
					echo 'type="' . $input . '" ';
					echo 'class="' . esc_attr(isset($args['class']) ? $args['class'] : 'input') . '" ';
					//echo 'style="width:100%; min-height:200px" ';
					echo 'value="' . esc_attr($val) . '" ';
					echo '/>';
				}

				/**
				 * Genera la textarea
				 * 
				 * @param  array  $args
				 * @return void
				 */
				public static function textarea($args = array())
				{
					if (!isset($args['id']) || empty($args['id'])) return;
					$val = isset($args['value']) ? $args['value'] : '';
					echo '<textarea ';
					echo 'id="' . esc_attr(isset($args['id']) ? $args['id'] : '') . '" ';
					echo 'name="' . esc_attr(isset($args['name']) ? $args['name'] : $args['id']) . '" ';
					echo 'class="' . esc_attr(isset($args['class']) ? $args['class'] : 'textarea') . '" ';
					echo 'style="width:100%; min-height:200px" ';
					echo '>';
					echo esc_textarea($val);
					echo '</textarea>';
				}


				/**
				 * Genera un campo di selezione
				 * 
				 * @param  array  $args
				 * @return void
				 */
				public static function select($args = array())
				{
					if (!isset($args['id']) || empty($args['id'])) return;
					if (!isset($args['list'])) return;
					$val = isset($args['value']) ? $args['value'] : '';
					echo '<select ';
					echo 'id="' . esc_attr(isset($args['id']) ? $args['id'] : '') . '" ';
					echo 'name="' . esc_attr(isset($args['name']) ? $args['name'] : $args['id']) . '" ';
					echo 'class="' . esc_attr(isset($args['class']) ? $args['class'] : 'select-option') . '" ';
					echo '>';
					foreach (!empty($args['list']) ? $args['list'] : array() as $v => $l) :
						$s = ($v == $val) ? ' selected="selected" ' : ' ';
						echo '<option' . $s . 'value="' . esc_attr($v) . '">' . esc_html($l) . '</option>';
					endforeach;
					echo '</select>';
				}
			}

