<?php
/**
 * BaseController Class File
 *
 * This file contains the BaseController class which is used throughout the plugin.
 *
 * @package  illiantLandings
 */

namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BaseController Class
 *
 * This class is used to define some common properties and methods that can be used throughout the plugin.
 */
class BaseController
{
    /**
     * @var string $plugin_path The path of the plugin.
     */
    public $plugin_path;

    /**
     * @var string $plugin_url The URL of the plugin.
     */
    public $plugin_url;

    /**
     * @var string $plugin The basename of the plugin.
     */
    public $plugin;

    /**
     * Constructor of the BaseController class.
     *
     * This constructor initializes the properties of the BaseController class.
     */
    public function __construct() {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/illiant-landings.php';
    }

    /**
     * Check if a specific plugin option is activated.
     *
     * This method checks if a specific option of the plugin is activated, if not set it to false.
     *
     * @param string $key The key of the option to check.
     * @return bool True if the option is activated, false otherwise.
     */
    public function activated( string $key ) {
        $option = get_option( 'illiant_landings' );

        return isset( $option[ $key ] ) ? $option[ $key ] : false;
    }

    /**
     * Retrieves header options for dropdown in settings.
     *
     * @return array Associative array of header options.
     */
    public function getHeaderOptions() { 
        $options = [
            'none' => 'None',
            'classic' => 'Classic Header'
        ];
    
        if (function_exists('get_block_templates')) {
            $template_parts = get_block_templates(array(), 'wp_template_part');
            if (is_array($template_parts)) {
                foreach ($template_parts as $template_part) {
                    if (strpos($template_part->slug, 'header') !== false) {
                        $options[$template_part->slug] = $template_part->title;
                    }
                }
            }
        }
    
        return $options;
    }
    
    /**
     * Retrieves footer options for dropdown in settings.
     *
     * @return array Associative array of footer options.
     */
    public function getFooterOptions() {
        $options = [
            'none' => 'None',
            'classic' => 'Classic Footer'
        ];
    
        if (function_exists('get_block_templates')) {
            $template_parts = get_block_templates(array(), 'wp_template_part');
            if (is_array($template_parts)) {
                foreach ($template_parts as $template_part) {
                    if (strpos($template_part->slug, 'footer') !== false) {
                        $options[$template_part->slug] = $template_part->title;
                    }
                }
            }
        }
    
        return $options;
    }
}