<?php
/**
 * WP_Head_Footer_Metabox
 *
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
class WP_Head_Footer_Metabox implements iWP_Head_Footer
{


	/**
	 * Inizializza la classe registrando i metadati necessari.
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public static function init()
	{

		self::register_meta();
	}


	/**
	 * Registra il meta box
	 * 
	 * @access private
	 * 
	 * @return void
	 */
	private static function register_meta()
	{

		register_meta(
			'post',
			self::META_KEY,
			array(
				'sanitize_callback' => array('WP_Head_Footer_Sanitize', 'sanitize_options'),
				'auth_callback'     => array('WP_Head_Footer_Utils', 'is_user_authorized'),
			)
		);
	}


	/**
	 * Aggiunge il meta box, ma solo per i tipi di post che lo supportano.
	 * 
	 * @access public
	 * 
	 * @param string  $post_type Il tipo di post.
	 * @param WP_Post $post      L'oggetto post corrente.
	 * 
	 * @return void
	 */
	public static function add_meta_boxes($post_type, $post)
	{

		if (!post_type_supports($post_type, self::PLUGIN_SLUG)) {
			return;
		}

		add_meta_box(
			self::PLUGIN_SLUG . '-' . $post_type,
			self::PLUGIN_TITLE,
			array(__CLASS__, 'template_metabox'),
			$post_type,
			'advanced',
			'default'
		);
	}


	/**
	 * Visualizza il template del meta box.
	 * 
	 * @access public
	 * 
	 * @param WP_Post $post   L'oggetto post corrente.
	 * @param array   $array  Array di opzioni per il template (opzionale).
	 * 
	 * @return void
	 */
	public static function template_metabox($post = null, $array = array())
	{
		$WP_Head_Footer_Form = new WP_Head_Footer_Form('post');
		include_once(plugin_dir_path(self::FILE) . 'templates/wp-head-footer-templates-metabox.php');
	}


	/**
	 * Salva i dati del meta box.
	 * 
	 * @access public
	 * 
	 * @param int     $post_id      L'ID del post corrente.
	 * @param WP_Post $post_object L'oggetto post corrente.
	 * @param bool    $update      Indica se l'operazione è di aggiornamento (opzionale, default: false).
	 * 
	 * @return void
	 */
	public static function save_post($post_id = 0, $post_object = null, $update = false): bool
	{

		$key = self::META_KEY;

		if (!isset($_POST[$key]) || empty($_POST[$key])) {

			return false;
		}

		if (!is_array($_POST[$key])) {

			return false;
		}

		if (!isset($_POST[$key]['nonce']) || empty($_POST[$key]['nonce'])) {

			return false;
		}

		if (!wp_verify_nonce($_POST[$key]['nonce'], plugin_basename(self::FILE))) {

			return false;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {

			return false;
		}

		if (wp_is_post_revision($post_id)) {

			return false;
		}

		if (!(isset($post_object->post_type) && !empty($post_object->post_type))) {

			return false;
		}
		
		if (!post_type_supports($post_object->post_type, self::PLUGIN_SLUG)) {

			return false;
		}

		if ('page' == $post_object->post_type) {

			if (!current_user_can('edit_page', $post_id))
				return false;
		} else {

			if (!current_user_can('edit_post', $post_id))
				return false;
		}


		/**
		 * Salva
		 *
		 * NOTA: $_POST è già stato trasformato con le "slash" da wp_magic_quotes
		 * in wp-settings quindi non c'è bisogno di fare nulla prima di salvare
		 * @see wp_magic_quotes() in WP 4.8.2 -> wp-settings.php;
		 *
		 * WP_Head_Footer_Options::sanitize_options verrà utilizzato come sanitize_callback in register_meta
		 */
		update_post_meta($post_id, self::META_KEY, $_POST[$key]);
		return true;
	}
}