<?php 
/**
 * @package  illiantLandings
 */
namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Illiantland\Base\BaseController;

class Enqueue extends BaseController
{
    /**
     * Registers enqueue actions for admin and front-end scripts and styles.
     */
	public function register() 
	{
        // Admin enqueue
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
    }

    /**
     * Enqueues scripts and styles for admin pages, conditionally based on the current page hook.
     *
     * @param string $hook The current admin page hook.
     */
    public function enqueue($hook) {

        switch ($hook) {
            case "wplandings_page_illiant_wplandings_create":
            case "toplevel_page_illiant_wplandings":
                $this->enqueue_for_create_page();
                break;
            case "wplandings_page_illiant_wplandings_doc":
            case "wplandings_page_illiant_wplandings_faq":
                $this->enqueue_for_other_pages();
                break;
            default:
                break;
        }
    }

    /**
     * Enqueues scripts and styles specifically for the 'Create' admin page.
     * 
     * This method handles the enqueuing of various scripts and styles needed for the plugin's
     * 'Create' page functionality. It includes media upload scripts, Google Fonts, Select2, 
     * CodeMirror for code editing, HTML2Canvas for screenshot capture, and custom scripts and styles.
     * Additionally, it localizes scripts with PHP variables for use in JavaScript, providing URLs and
     * other configurations necessary for the page's interactivity and visual appearance.
     */
    public function enqueue_for_create_page() {
        wp_enqueue_script( 'media-upload' ); 
        wp_enqueue_media();
        wp_enqueue_style( 'select2-css', $this->plugin_url . 'assets/select2.min.css' );
        wp_enqueue_style( 'illiantstyle', $this->plugin_url . 'assets/illiantstyle.css' );
        wp_enqueue_script( 'select2', $this->plugin_url . 'assets/select2.min.js' );    
        wp_enqueue_script( 'illiantscripts', $this->plugin_url . 'assets/illiantscripts.min.js', array('jquery', 'wp-i18n'), false, true );
        wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap', array(), null );

        wp_set_script_translations('illiantscripts', 'illiant-landings', $this->plugin_url . '/languages');

        global $wp_version;
        $plugin_data = get_plugin_data(__FILE__);

        // Create nonces
        $illiant_nonce = wp_create_nonce('illiant_nonce');

        // Localize additional script data including both nonces
        $translation_array = array(
            'baseUrl' => $this->plugin_url . 'assets/',
            'ajax_url' => admin_url('admin-ajax.php'),
            'illiant_nonce' => $illiant_nonce,
            'nonce' => wp_create_nonce('wp_rest'),
            'root'      => esc_url_raw(rest_url()),
            'wpVersion' => $wp_version,
        );

        wp_localize_script('illiantscripts', 'illiantscriptsData', $translation_array);
    }

    /**
     * Enqueues a general stylesheet for other admin pages of the plugin.
     * 
     * This method is responsible for adding a general CSS file that affects the appearance of
     * the plugin's admin pages outside of the 'Create' page. It ensures a consistent look and feel
     * across the plugin's admin interface by applying the 'illiantstyle.css' stylesheet.
     */
    public function enqueue_for_other_pages() {
        wp_enqueue_style('illiantstyle', $this->plugin_url . 'assets/illiantstyle.css');
        wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap', array(), null );

        $accordion_script = "
            jQuery(document).ready(function($) {
                $('.lp-acc-title').on('click', function() {
                    $('.lp-acc-content').not($(this).next()).slideUp();
                    $('.lp-acc-title').not($(this)).removeClass('active');
                    $(this).toggleClass('active').next('.lp-acc-content').slideToggle();
                });
            });
        ";

        wp_add_inline_script('jquery', $settings_form_script);
        wp_add_inline_script('jquery', $accordion_script);
    }
}