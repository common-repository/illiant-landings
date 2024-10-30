<?php 
/**
 * @package  illiantLandings
 */

namespace Illiantland\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class ImageController
 *
 * Handles image-related functionalities such as uploading images from Figma to WordPress,
 * downloading images to the server, and saving Figma preview images.
 */
class ImageController
{
    /**
     * Registers AJAX actions related to image functionality.
     */
    public function register()
    {   
        add_action('wp_ajax_your_action', array($this, 'handle_figma_request'));
        add_action('wp_ajax_nopriv_your_action', array($this, 'handle_figma_request'));
        add_action('wp_ajax_upload_figma_images', array($this, 'handle_figma_image_upload'));
        add_action('wp_ajax_save_figma_preview_image', array($this, 'save_figma_preview_image'));
    }

    /**
     * Handles the AJAX request for Figma data.
     * This method responds to the AJAX action 'your_action'.
     */
    public function handle_figma_request() 
    {
        wp_send_json();
        wp_die(); 
    }

    /**
     * Downloads an image from a given URL to the server.
     *
     * @param string $imageUrl The URL of the image to download.
     * @return string|false The file path to the downloaded image on success, false on failure.
     */
    private function download_image_to_server($imageUrl) 
    {
        if ( ! wp_http_validate_url( $imageUrl ) ) {

            return false;
        }

        $args = [
            'timeout'     => 20,
            'redirection' => 5,
            'httpversion' => '1.1',
            'blocking'    => true,
            'headers'     => [],
            'cookies'     => []
        ];

        $response = wp_remote_get($imageUrl, $args);

        if (is_wp_error($response)) {
            return false; 
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code != 200) {
            return false;
        }

        $imageData = wp_remote_retrieve_body($response);

        if (!$imageData) {
            return false;
        } 

        global $wp_filesystem;
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();

        // Use a hash of the URL to shorten the filename
        $hash = md5($imageUrl);
        $tmpFilePath = wp_tempnam($hash);

        if (strlen($tmpFilePath) > 255) {
            return false;
        }

        if ($wp_filesystem->put_contents($tmpFilePath, $imageData)) {
            return $tmpFilePath;
        } else {
            return false;
        }
    }


    /**
     * Handles the uploading of images from Figma to WordPress via AJAX.
     */
    public function handle_figma_image_upload() 
    {
        if (!current_user_can('upload_files')) {
            wp_send_json_error(__('Unauthorized: You do not have permission to upload files.', 'illiant-landings'));
            wp_die();
        }

        $nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
        if ( ! $nonce || ! wp_verify_nonce( $nonce, 'illiant_nonce' ) ) {
            wp_send_json_error(__('Nonce verification failed', 'illiant-landings'));
            wp_die();
        }

        $uploadedImageUrls = [];
        $debugMessages = [];
        $errorOccurred = false;

        if ( ! isset( $_POST['images'] ) || ! is_array( $_POST['images'] ) ) {
            $debugMessages[] = esc_html__( 'No images provided or invalid structure.', 'illiant-landings' );
            wp_send_json_error( [ 'debugMessages' => $debugMessages ] );
            return;
        }

        $sectionId = isset( $_POST['sectionId'] ) ? sanitize_text_field( wp_unslash( $_POST['sectionId'] ) ) : '';

        $images_raw = wp_unslash( $_POST['images'] );
        $imagesData = [];

        foreach ( $images_raw as $image ) {
            if ( ! is_array( $image ) ) {
                continue;
            }
            $sanitizedImageUrl = isset( $image['imageUrl'] ) ? esc_url_raw( $image['imageUrl'] ) : '';
            $sanitizedDescription = isset( $image['imageDescription'] ) ? sanitize_text_field( $image['imageDescription'] ) : '';
            $sanitizedNodeId = isset( $image['nodeId'] ) ? sanitize_text_field( $image['nodeId'] ) : '';
            $imagesData[] = [
                'imageUrl'         => $sanitizedImageUrl,
                'imageDescription' => $sanitizedDescription,
                'nodeId'           => $sanitizedNodeId
            ];
        }

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        foreach ( $imagesData as $image ) {
            $imageUrl = $image['imageUrl'];
            $nodeId = $image['nodeId'];
            $imageDescription = $image['imageDescription'];

            if ( empty( $imageUrl ) || empty( $nodeId ) ) {
                $debugMessages[] = esc_html__( 'Invalid image data provided.', 'illiant-landings' );
                continue;
            }

            $illiant_image_id = $sectionId . '-' . $nodeId;

            $existing_attachment = get_posts( array(
                'post_type'      => 'attachment',
                'posts_per_page' => 1,
                'post_status'    => 'inherit',
                'meta_query'     => array(
                    array(
                        'key'     => 'illiant_image_id',
                        'value'   => $illiant_image_id,
                        'compare' => '=',
                    ),
                ),
            ) );

            if ( ! empty( $existing_attachment ) ) {
                $attachment_id = $existing_attachment[0]->ID;
                $uploadedImageUrl = wp_get_attachment_url( $attachment_id );

                $uploadedImageUrls[] = array(
                    'url'         => esc_url( $uploadedImageUrl ),
                    'description' => sanitize_text_field( $imageDescription ),
                );
                $debugMessages[] = esc_html__( 'Image already exists: ', 'illiant-landings' ) . esc_url( $uploadedImageUrl );
                continue;
            }

            if ( ! filter_var( $imageUrl, FILTER_VALIDATE_URL ) ) {
                $debugMessages[] = esc_html__( 'Invalid image URL: ', 'illiant-landings' ) . esc_url( $imageUrl );
                continue;
            }

            $tmpFilePath = $this->download_image_to_server( $imageUrl );
            if ( ! $tmpFilePath ) {
                $debugMessages[] = esc_html__( 'Failed to download image: ', 'illiant-landings' ) . esc_url( $imageUrl );
                continue;
            }

            $mimeType = mime_content_type( $tmpFilePath );
            $extension = '';
            switch ( $mimeType ) {
                case 'image/jpeg':
                    $extension = '.jpg';
                    break;
                case 'image/png':
                    $extension = '.png';
                    break;
                default:
                    $debugMessages[] = esc_html__( 'Unrecognized image MIME type: ', 'illiant-landings' ) . esc_html( $mimeType );
                    wp_delete_file( $tmpFilePath );
                    $errorOccurred = true;
                    break;
            }

            if ( empty( $extension ) ) {
                continue;
            }

            global $wp_filesystem;
            require_once( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();

            $newFilePath = $tmpFilePath . $extension;
            if ( $wp_filesystem->move( $tmpFilePath, $newFilePath, true ) ) {
                $tmpFilePath = $newFilePath;
            } else {
                $debugMessages[] = esc_html__( 'Failed to move file to new path: ', 'illiant-landings' ) . esc_html( $newFilePath );
                wp_delete_file( $tmpFilePath );
                continue;
            }

            $file = [
                'name'     => sanitize_file_name( $sectionId . '-' . $nodeId . $extension ),
                'type'     => $mimeType,
                'tmp_name' => $tmpFilePath,
                'error'    => 0,
                'size'     => filesize( $tmpFilePath ),
            ];

            $sideloaded = media_handle_sideload( $file, 0 );
            if ( is_wp_error( $sideloaded ) ) {
                $errorOccurred = true;
                $debugMessages[] = esc_html__( 'Error sideloading image: ', 'illiant-landings' ) . esc_html( $sideloaded->get_error_message() );
                wp_delete_file( $tmpFilePath );
                continue;
            }

            add_post_meta( $sideloaded, 'illiant_image_id', $illiant_image_id, true );

            $uploadedImageUrl = wp_get_attachment_url( $sideloaded );
            $uploadedImageUrls[] = [
                'url'         => esc_url( $uploadedImageUrl ),
                'description' => sanitize_text_field( $imageDescription ),
            ];
            $debugMessages[] = esc_html__( 'Image uploaded: ', 'illiant-landings' ) . esc_url( $uploadedImageUrl );

            wp_delete_file( $tmpFilePath );
        }

        if ( $errorOccurred ) {
            wp_send_json_error( [ 'debugMessages' => $debugMessages ] );
        } else {
            wp_send_json_success( [ 'uploadedImageUrls' => $uploadedImageUrls, 'debugMessages' => $debugMessages ] );
        }
        wp_die();
    }

    /**
     * Saves the Figma preview image URL via AJAX.
     */
    public function save_figma_preview_image() 
    {
        if ( ! isset( $_POST['_ajax_nonce'] ) ) {
            wp_send_json_error( __( 'Nonce not set.', 'illiant-landings' ) );
            return;
        }

        if ( ! check_ajax_referer( 'illiant_nonce', '_ajax_nonce', false ) ) {
            wp_send_json_error( __( 'Invalid nonce.', 'illiant-landings' ) );
            return;
        } 

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'You do not have sufficient permissions.', 'illiant-landings' ) );
            return;
        } 

        $node_id = isset( $_POST['nodeId'] ) ? sanitize_text_field( wp_unslash( $_POST['nodeId'] ) ) : '';
        $image_url = isset( $_POST['imageUrl'] ) ? esc_url_raw( wp_unslash( $_POST['imageUrl'] ) ) : '';

        if ( empty( $node_id ) || empty( $image_url ) ) {
            wp_send_json_error( __( 'Invalid node ID or image URL.', 'illiant-landings' ) );
            return;
        } 

        $illiant_design_data = get_option( 'illiant_design_data', [] );

        if ( ! isset( $illiant_design_data['framePreviews'] ) ) {
            $illiant_design_data['framePreviews'] = [];
        }

        $illiant_design_data['framePreviews'][ $node_id ] = $image_url;

        if ( update_option( 'illiant_design_data', $illiant_design_data ) ) {
            wp_send_json_success( __( 'Preview image URL saved successfully.', 'illiant-landings' ) );
        } else {
            wp_send_json_error( __( 'Failed to save preview image URL.', 'illiant-landings' ) );
        }

        wp_die();
    }
}
