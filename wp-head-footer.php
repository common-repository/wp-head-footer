<?php
/**
 * Plugin Name:   WP Head Footer
 * Plugin URI:    https://wordpress.org/plugins/wp-head-footer/
 * Description:   WP Head Footer allows you to add code to the <head> and/or footer of an individual post (or any post type) and/or site-wide
 * Author:        Francesco Taurino
 * Author URI:    https://profiles.wordpress.org/francescotaurino
 * Version:       1.2
 * Text Domain:   wp-head-footer
 * Domain Path: 	/languages
 * License: GPL v3
 *
 * @package     WP_Head_Footer
 * @author      Francesco Taurino 
 * @copyright   Copyright (c) 2017, Francesco Taurino
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */
 
interface iWP_Head_Footer
{

    /**
     * Il nome identificativo del plugin.
     * Supporto del post type
     *
     * @var string
     */
    const PLUGIN_SLUG = 'wp-head-footer';

    /**
     * Il titolo del plugin.
     *
     * @var string
     */
    const PLUGIN_TITLE = 'WP Head Footer';

    /**
     * La capacità richiesta per utilizzare il plugin.
     *
     * @var string
     */
    const REQUIRED_CAPABILITY = 'manage_options';

    /**
     * Il percorso assoluto del file wp-head-footer.php (dell'interfaccia).
     *
     * @var string
     */
    const FILE = __FILE__;

    /**
     * Il nome dell'opzione del sito utilizzata per archiviare le impostazioni del plugin per tutto il sito.
     *
     * @var string
     */
    const OPTION_NAME = '_wphf_site_settings';

    /**
     * Il nome del meta campo utilizzato per archiviare le impostazioni del plugin per i post.
     *
     * @var string
     * @protected
     */
    const META_KEY = '_wphf_post_settings';
}


 require_once(plugin_dir_path(__FILE__) . 'includes/class-wp-head-footer.php');
 add_action('plugins_loaded', array('WP_Head_Footer', 'plugins_loaded'), 0);


/* 
@todo register_uninstall_hook
global $wpdb;
//register_uninstall_hook(__FILE__, 'WP_Head_Footer_uninstall' );
// Ottieni tutti gli ID dei post
$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = %s", '_wphf_post_settings' ) );
// Rimuovi i metadati per ogni post
foreach ( $post_ids as $post_id ) {
    delete_post_meta_by_key( '_wphf_post_settings', $post_id );
}
delete_option('_wphf_site_settings');
 */
