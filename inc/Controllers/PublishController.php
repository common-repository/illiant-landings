<?php 
/**
 * @package  illiantLandings
 */

namespace Illiantland\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

/**
 * Class PublishController
 *
 * Handles publishing landing pages and retrieving existing Illiant pages.
 */
class PublishController
{
    /**
     * Registers AJAX actions related to publishing and retrieving landing pages.
     */
    public function register()
    {   
        add_action( 'wp_ajax_publish_landing_page', array( $this, 'handle_publish_landing_page_ajax' ) );
        add_action( 'wp_ajax_get_illiant_pages', array( $this, 'get_illiant_pages_ajax' ) );
    }

    /**
     * Handles AJAX request to publish or update a landing page.
     */
    public function handle_publish_landing_page_ajax() 
    {
        if ( ! isset( $_POST['_ajax_nonce'] ) || ! check_ajax_referer( 'illiant_nonce', '_ajax_nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Nonce verification failed!', 'illiant-landings' ) ) );
            wp_die();
        }

        if ( ! current_user_can( 'edit_pages' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions to access this page.', 'illiant-landings' ) ) );
            wp_die();
        }

        if ( isset( $_POST['landingData'] ) && is_array( $_POST['landingData'] ) ) {
            $landingData = wp_unslash( $_POST['landingData'] );
        } else {
            wp_send_json_error( array( 'message' => __( 'No landing data received.', 'illiant-landings' ) ) );
            wp_die();
        }

        // Ensure the title is set or use 'No title'
        $title  = isset( $landingData['title'] ) && ! empty( $landingData['title'] ) 
                ? sanitize_text_field( $landingData['title'] ) 
                : __( 'No title', 'illiant-landings' );
        
        $blocks = isset( $landingData['landing_blocks'] ) ?  $landingData['landing_blocks'] : __( 'Something went wrong', 'illiant-landings' );

        $frameID = isset( $landingData['frameID'] ) ? sanitize_text_field( $landingData['frameID'] ) : '';
        $pageID  = isset( $landingData['pageID'] ) ? sanitize_text_field( $landingData['pageID'] ) : '';

        $conversionType = isset( $landingData['conversionType'] ) ? sanitize_text_field( $landingData['conversionType'] ) : 'convert';
        $updateId       = isset( $landingData['updateId'] ) ? intval( $landingData['updateId'] ) : 0;

        if ( $conversionType === 'update' && $updateId > 0 ) {
            $post = get_post( $updateId );
            if ( ! $post || $post->post_type !== 'page' ) {
                wp_send_json_error( array( 'message' => __( 'The specified page does not exist.', 'illiant-landings' ) ) );
                wp_die();
            }

            $post_data = array(
                'ID'           => $updateId,
                'post_content' => $blocks,
            );

            $post_id = wp_update_post( $post_data, true );

            if ( is_wp_error( $post_id ) ) {
                wp_send_json_error( array( 'message' => __( 'Failed to update the landing page.', 'illiant-landings' ) ) );
                wp_die();
            }

        } else {
            $post_data = array(
                'post_title'   => $title,
                'post_content' => $blocks,
                'post_status'  => 'draft',
                'post_type'    => 'page',
            );

            $post_id = wp_insert_post( $post_data, true );

            if ( is_wp_error( $post_id ) ) {
                wp_send_json_error( array( 'message' => __( 'Failed to create the landing page.', 'illiant-landings' ) ) );
                wp_die();
            }
        }

        $illiant_meta = array(
            'frameID' => $frameID,
            'pageID'  => $pageID,
        );

        update_post_meta( $post_id, '_illiant_post_meta', $illiant_meta );

        $view_link = get_permalink( $post_id );

        if ( ! $view_link ) {
            $view_link = '';
        }

        wp_send_json_success( array( 'view_link' => esc_url( $view_link ) ) );
        wp_die();
    }


    /**
     * Handles AJAX request to retrieve Illiant pages matching the search criteria.
     */
    public function get_illiant_pages_ajax()
    {
        if ( ! isset( $_POST['_ajax_nonce'] ) || ! check_ajax_referer( 'illiant_nonce', '_ajax_nonce', false ) ) {
            wp_send_json_error( __( 'Nonce verification failed!', 'illiant-landings' ) );
            wp_die();
        }

        if ( ! current_user_can( 'edit_pages' ) ) {
            wp_send_json_error( __( 'You do not have sufficient permissions.', 'illiant-landings' ) );
            wp_die();
        }

        $search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

        $args = array(
            'post_type'      => 'page',
            'post_status'    => 'any',
            'meta_key'       => '_illiant_post_meta',
            'posts_per_page' => -1,
            's'              => $search,
        );

        $query = new \WP_Query( $args );

        $pages = array();

        if ( $query->have_posts() ) {
            foreach ( $query->posts as $post ) {
                $illiant_meta = get_post_meta( $post->ID, '_illiant_post_meta', true );

                if ( ! is_array( $illiant_meta ) ) {
                    $illiant_meta = array();
                }

                $frameID = isset( $illiant_meta['frameID'] ) ? sanitize_text_field( $illiant_meta['frameID'] ) : '';
                $pageID  = isset( $illiant_meta['pageID'] ) ? sanitize_text_field( $illiant_meta['pageID'] ) : '';

                $pages[] = array(
                    'id'      => $post->ID,
                    'title'   => html_entity_decode( get_the_title( $post->ID ), ENT_QUOTES, 'UTF-8' ),
                    'frameID' => $frameID,
                    'pageID'  => $pageID,
                );
            }
        }

        wp_send_json_success( array( 'pages' => $pages ) );
        wp_die();
    }
}
