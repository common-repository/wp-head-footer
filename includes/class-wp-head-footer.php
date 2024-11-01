<?php
/**
 * WP_Head_Footer
 * 
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * 
 */
class WP_Head_Footer implements iWP_Head_Footer
{

	/**
	 * Costruttore della classe, 
	 * dichiarato private per evitare che sia istanziata
	 */
	private function __construct()
	{
	}

	/**
	 * plugins_loaded
	 */
	public static function plugins_loaded()
	{

		self::includes();

		// Carica il file di traduzione del plugin
		load_plugin_textdomain(
			self::PLUGIN_SLUG,
			false,
			basename(dirname(self::FILE)) . '/languages'
		);

		// Aggiunge il supporto per i post_type pubblici
		add_action('init', array(__CLASS__, 'add_post_type_support'));

		if (is_admin() && WP_Head_Footer_Utils::is_user_authorized()) {

			// Aggiunge la metabox
			add_action('add_meta_boxes', array('WP_Head_Footer_Metabox', 'add_meta_boxes'), 10, 2);

			// Salva i dati della metabox
			add_action('save_post', array('WP_Head_Footer_Metabox', 'save_post'), 10, 3);

			// Aggiunge il menu delle impostazioni del plugin
			add_action('admin_menu', array('WP_Head_Footer_Settings', 'admin_menu'));

			// Registra le impostazioni del plugin
			add_action('admin_init', array('WP_Head_Footer_Settings', 'admin_init'));
		}

		add_action('template_redirect', array('WP_Head_Footer_Frontend', 'template_redirect'));
	}


	/**
	 * Metodo che aggiunge il supporto al plugin per tutti i tipi di post pubblici
	 */
	public static function add_post_type_support()
	{
		foreach (get_post_types(array('public' => true), 'names', 'and') as $post_type) {
			add_post_type_support($post_type, self::PLUGIN_SLUG);
		}
	}


	/**
	 * TODO: 
	 * Registra l'autoloader delle classi del plugin
	 */
	private static function register_autoloader()
	{
		//require_once plugin_dir_path( self::FILE ) . 'includes/class-wp-head-footer-utils.php';

		spl_autoload_register(function ($class) {
			$namespace = 'WP_Head_Footer\\';
			$base_dir  = plugin_dir_path(self::FILE) . 'includes/';

			if (strpos($class, $namespace) === 0) {
				$relative_class = substr($class, strlen($namespace));
				$file           = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

				if (file_exists($file)) {
					require $file;
				}
			}
		});
	}


	/**
	 * Include i file necessari
	 */
	private static function includes()
	{

		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-utils.php');
		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-options.php');
		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-form.php');
		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-frontend.php');
		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-metabox.php');
		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-settings.php');
		require_once( plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-sanitize.php');
		// Include tutti i file che rispettano la regola del nome di classe 'class-wp-head-footer-*'
		//foreach (glob(plugin_dir_path(self::FILE) . 'includes/class-wp-head-footer-*.php') as $filename) {
			//require_once($filename);
		//}
	}
}