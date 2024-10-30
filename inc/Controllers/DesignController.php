<?php
/**
 * @package  illiantLandings
 */

namespace Illiantland\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class DesignController
 *
 * Handles design-related functionalities such as saving Figma design data,
 * retrieving styles data, and generating updated Figma structure HTML.
 */
class DesignController
{
    /**
     * Registers AJAX actions related to design functionality.
     */
    public function register()
    {   
        add_action('wp_ajax_save_illiant_design_data', array($this, 'handle_save_illiant_design_data'));
        add_action('wp_ajax_get_figma_styles_data', array($this, 'handle_get_figma_styles_data'));
        add_action('wp_ajax_get_updated_figma_structure_html', array($this, 'get_updated_figma_structure_html'));
    }

    /**
     * Handles AJAX request to save Figma design data.
     */
    public function handle_save_illiant_design_data() 
    {
        check_ajax_referer('illiant_nonce', '_ajax_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have sufficient permissions.', 'illiant-landings'));
            return;
        } 

        if (isset($_POST['pages'])) {
            $pages_raw = wp_unslash($_POST['pages']);
            $pages = $this->sanitize_recursive($pages_raw);
        } else {
            $pages = [];
        }

        if (isset($_POST['styles'])) {
            $styles_raw = wp_unslash($_POST['styles']);
            $styles = $this->sanitize_recursive($styles_raw);
        } else {
            $styles = [];
        }

        if (isset($_POST['figmaURL'])) {
            $figmaURL_raw = wp_unslash($_POST['figmaURL']);
            $figmaURL = esc_url_raw($figmaURL_raw);
        } else {
            $figmaURL = '';
        }

        if (isset($_POST['selectedNode'])) {
            $selectedNode_raw = wp_unslash($_POST['selectedNode']);
            $selectedNode = sanitize_text_field($selectedNode_raw);
        } else {
            $selectedNode = '';
        }

        if (empty($pages)) {
            wp_send_json_error(__('Invalid data: Pages are required.', 'illiant-landings'));
            return;
        } 

        $figma_data = array(
            'pages'        => $pages,
            'styles'       => $styles,
            'figmaURL'     => $figmaURL,
            'selectedNode' => $selectedNode,
        );

        $current_data = get_option('illiant_design_data');

        if ($figma_data === $current_data) {
            wp_send_json_success(['message' => __('Figma design data is already up to date.', 'illiant-landings'), 'status' => 'up-to-date']);
            return;
        }

        $update_result = update_option('illiant_design_data', $figma_data);

        if ($update_result) {
            wp_send_json_success(['message' => __('Figma design data saved successfully.', 'illiant-landings'), 'status' => 'updated']);
        } else {
            wp_send_json_error(__('Failed to save Figma design data.', 'illiant-landings'));
        }

        wp_die();
    }

    /**
     * Handles AJAX request to retrieve Figma styles data.
     */
    public function handle_get_figma_styles_data() 
    {
        check_ajax_referer('illiant_nonce', '_ajax_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have sufficient permissions.', 'illiant-landings'));
            return;
        }

        $figma_data = get_option('illiant_design_data', []);

        if (empty($figma_data)) {
            wp_send_json_error(__('No Figma design data found.', 'illiant-landings'));
            return;
        }

        $styles = isset($figma_data['styles']) ? $this->sanitize_recursive($figma_data['styles']) : [];
        $figmaURL = isset($figma_data['figmaURL']) ? esc_url($figma_data['figmaURL']) : '';
        $selectedNode = isset($figma_data['selectedNode']) ? sanitize_text_field($figma_data['selectedNode']) : '';

        $response_data = [
            'styles'       => $styles,
            'figmaURL'     => $figmaURL,
            'selectedNode' => $selectedNode,
        ];

        wp_send_json_success($response_data);

        wp_die();
    }

    /**
     * Generates updated Figma structure HTML and returns it via AJAX.
     */
    public function get_updated_figma_structure_html() 
    {
        check_ajax_referer('illiant_nonce', '_ajax_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have sufficient permissions.', 'illiant-landings'));
            return;
        }

        $figma_data = get_option('illiant_design_data');

        if (!$figma_data || !isset($figma_data['pages'])) {
            wp_send_json_error(__('No design structure found. Please import a Figma design first.', 'illiant-landings'));
            return;
        }

        $selectedNode = isset($figma_data['selectedNode']) ? sanitize_text_field(str_replace('-', ':', $figma_data['selectedNode'])) : '';
        $adjustedSelectedNode = $selectedNode;

        ob_start();

        echo '<ul class="figma-pages-list">';

        foreach ($figma_data['pages'] as $page) {
            $page = $this->sanitize_recursive($page);
            $isSelectedPage = $page['id'] === $selectedNode;
            $firstFrameId   = isset($page['frames'][0]['id']) ? $page['frames'][0]['id'] : null;

            if ($isSelectedPage && $firstFrameId) {
                $adjustedSelectedNode = $firstFrameId;
            }

            echo '<li class="figma-page-item">';
            echo '<span class="figma-page-name">' . esc_html($page['name']) . '</span>';

            if (isset($page['frames']) && !empty($page['frames'])) {
                echo '<ul class="figma-frames-list">';

                foreach ($page['frames'] as $index => $frame) {
                    $frame = $this->sanitize_recursive($frame);
                    $isActiveFrame = ($frame['id'] === $selectedNode) || ($isSelectedPage && $index === 0);
                    $activeClass   = $isActiveFrame ? 'active' : '';

                    echo '<li class="figma-frame-item ' . esc_attr($activeClass) . '" data-node="' . esc_attr($frame['id']) . '" data-page="' . esc_attr($page['id']) . '" data-preview="null">';
                    echo '<a class="figma-frame-name">' . esc_html($frame['name']) . '</a>';
                    echo '</li>';
                }

                echo '</ul>';
            }

            echo '</li>';
        }

        echo '</ul>';

        $updated_html = ob_get_clean();

        wp_send_json_success([
            'html'         => $updated_html,
            'selectedNode' => sanitize_text_field($adjustedSelectedNode),
        ]);

        wp_die();
    }

    /**
     * Recursively sanitizes data.
     *
     * @param mixed $data Data to sanitize.
     * @return mixed Sanitized data.
     */
    private function sanitize_recursive($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_recursive($value);
            }
        } else {
            $data = sanitize_text_field($data);
        }
        return $data;
    }
}
