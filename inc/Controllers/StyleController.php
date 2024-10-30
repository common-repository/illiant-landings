<?php 
/**
 * @package  illiantLandings
 */

namespace Illiantland\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

/**
 * Class StyleController
 *
 * Handles style-related functionalities such as saving styles to the theme.
 */
class StyleController
{
    /**
     * Registers AJAX actions related to style functionality.
     */
    public function register()
    {   
        add_action('wp_ajax_save_styles_to_theme', array($this, 'save_styles_to_theme_function'));
    }

    /**
     * Handles AJAX request to save styles to the theme.
     */
    public function save_styles_to_theme_function()
    {
        check_ajax_referer('illiant_nonce', '_ajax_nonce');
        
        if ( ! current_user_can('edit_theme_options') ) {
            wp_send_json_error( __( 'You do not have permission to edit theme options.', 'illiant-landings' ) );
            return;
        }

        $processed_styles_raw = isset( $_POST['processedStyles'] ) ? wp_unslash( $_POST['processedStyles'] ) : null;
        
        if ( ! $processed_styles_raw ) {
            wp_send_json_error( __( 'Invalid styles data.', 'illiant-landings' ) );
            return;
        }

        $processed_styles = json_decode( $processed_styles_raw, true );

        if ( is_null( $processed_styles ) ) {
            wp_send_json_error( __( 'Invalid JSON data.', 'illiant-landings' ) );
            return;
        }

        $processed_styles = $this->sanitize_recursive( $processed_styles );

        global $wp_filesystem;
        require_once( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();

        $theme_dir  = get_stylesheet_directory();
        $styles_dir = trailingslashit( $theme_dir ) . 'styles';
        $file_path  = trailingslashit( $styles_dir ) . 'test.json';

        if ( ! $wp_filesystem->is_dir( $styles_dir ) ) {
            if ( ! $wp_filesystem->mkdir( $styles_dir, FS_CHMOD_DIR ) ) {
                wp_send_json_error( __( 'Failed to create styles directory.', 'illiant-landings' ) );
                return;
            }
        }

        $json_data = wp_json_encode( $processed_styles, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

        if ( false === $json_data ) {
            wp_send_json_error( __( 'Failed to encode JSON data.', 'illiant-landings' ) );
            return;
        }

        $file_saved = $wp_filesystem->put_contents( $file_path, $json_data, FS_CHMOD_FILE );

        if ( ! $file_saved ) {
            wp_send_json_error( __( 'Failed to save the file.', 'illiant-landings' ) );
        } else {
            wp_send_json_success( __( 'Styles saved successfully.', 'illiant-landings' ) );
        }

        wp_die();
    }

    private function sanitize_recursive( $data ) {
        if ( is_array( $data ) ) {
            foreach ( $data as $key => $value ) {
                $data[ $key ] = $this->sanitize_recursive( $value );
            }
        } else {
            $data = sanitize_text_field( $data );
        }
        return $data;
    }
}
