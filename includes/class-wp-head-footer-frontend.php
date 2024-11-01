<?php 
/**
 * WP_Head_Footer_Frontend
 *
 * @package     WP_Head_Footer\Includes
 * @author      Francesco Taurino
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
class WP_Head_Footer_Frontend implements iWP_Head_Footer
{

	/**
	 * Template redirect
	 * This action hook executes just before WordPress determines which template page to load. 
	 * 
	 * @access private
	 * @return void
	 */
	public static function template_redirect()
	{

		if (is_admin() || is_feed() || is_robots() || is_trackback()) {
			return;
		}

		$post_settings = WP_Head_Footer_Options::get_options(get_the_ID(), null);

		/**
		 * Mostra il codice del header a livello di sito (site-wide) solo se l'opzione wp_header_replace non è stata selezionata nel metabox.
		 */
		if (empty($post_settings['wp_head_replace'])) {

			add_action(
				'wp_head',
				array(__CLASS__, 'site_wp_head'),
				WP_Head_Footer_Options::get_priority(0, 'wp_head', true)
			);
		}

		/**
		 * Mostra il codice del footer a livello di sito (site-wide) solo se l'opzione wp_footer_replace non è stata selezionata nel metabox.
		 */
		if (empty($post_settings['wp_footer_replace'])) {

			add_action(
				'wp_footer',
				array(__CLASS__, 'site_wp_footer'),
				WP_Head_Footer_Options::get_priority(0, 'wp_footer', true)
			);
		}

		/**
		 * Mostra il codice dell'header e/o del footer aggiunto nel metabox solo se stiamo visualizzando un post type supportato da questo plugin.
		 */
		if (self::is_post()) {

			add_action(
				'wp_head',
				array(__CLASS__, 'post_wp_head'),
				WP_Head_Footer_Options::get_priority(get_the_ID(), 'wp_head')
			);

			add_action(
				'wp_footer',
				array(__CLASS__, 'post_wp_footer'),
				WP_Head_Footer_Options::get_priority(get_the_ID(), 'wp_footer')
			);
		}
	}


	/**
	 * Stampa l'output di wp_head su tutte le pagine del sito
	 * 
	 * @access private
	 * @return void
	 */
	public static function site_wp_head()
	{
		self::html('wp_head', true);
	}


	/**
	 * Stampa l'output di wp_footer su tutte le pagine del sito
	 * 
	 * @access private
	 * @return void
	 */
	public static function site_wp_footer()
	{
		self::html('wp_footer', true);
	}


	/**
	 * Stampa l'output di wp_head su pagine singole o post
	 * 
	 * @access private
	 * @return void
	 */
	public static function post_wp_head()
	{
		self::html('wp_head');
	}


	/**
	 * Stampa l'output di wp_footer su pagine singole o post
	 * 
	 * @access private
	 * @return void
	 */
	public static function post_wp_footer()
	{
		self::html('wp_footer');
	}


	/**
	 * Verifica se la pagina corrente è una pagina singola, 
	 * un post o un Custom Post Type, 
	 * e se il post type corrente supporta il plugin.
	 * 
	 * @return bool
	 */
	public static function is_post()
	{
		global $post;
		$x = ((is_front_page() || is_home()) && get_option('show_on_front') == 'page');
		$y = is_single() || is_page() || $x;
		$z = ($y && (isset($post->post_type) && isset($post->ID) && $post->ID > 0));
		return ($z && post_type_supports($post->post_type, self::PLUGIN_SLUG));
	}


	/**
	 * Stampa il codice html sulle pagine del sito, 
	 * e se l'utente ha l'autorizzazione stampa il debug.
	 * 
	 * @param  string $key specifica se si vuole stampare l'output di wp_head() o wp_footer()
	 * @param  boolean $sitewide specifica se si vuole stampare l'output del sito o del post
	 * @return void
	 */
	private static function html($key = '', $sitewide = false)
	{
		$debug 	= '';
		$html = WP_Head_Footer_Options::get_options(get_the_ID(), $key, $sitewide);
		if (empty($html)) return;

		if (WP_Head_Footer_Utils::is_user_authorized()) {

			$p_wp_head = WP_Head_Footer_Options::get_priority(get_the_ID(), 'wp_head', $sitewide);
			$p_wp_footer = WP_Head_Footer_Options::get_priority(get_the_ID(), 'wp_footer', $sitewide);

			$type 		= $sitewide ? '[Site_Wide]' : '[ post_type = ' . esc_attr(get_post_type()) . ' ]';
			$where 		= $key == 'wp_head' ? '[Header]' : '[Footer]';
			$priority 	= $key == 'wp_head' ? '[' . $p_wp_head . ']' : '[' . $p_wp_footer . ']';

			$debug 	= ' ' . $type . $where . $priority;
		}


		echo PHP_EOL;
		echo PHP_EOL;
		echo "<!-- " . self::PLUGIN_TITLE . $debug . " -->";
		echo PHP_EOL . $html . PHP_EOL;
		echo "<!-- / " . self::PLUGIN_TITLE . $debug . " -->";
		echo PHP_EOL;
		echo PHP_EOL;
	}
}
