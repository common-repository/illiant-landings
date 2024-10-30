<?php
/**
 * @package illiantLandings
 */

namespace Illiantland\Api\Callbacks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Handles callbacks for plugin settings.
 *
 */
class SettingsCallbacks {
    /**
     * Sanitizes the settings fields inputs.
     *
     * @param array $input Array of inputs to sanitize.
     * @return array Sanitized inputs.
     */
     public function keysSanitize($input) {
        $sanitized = [];
        if (isset($input['figmaV2'])) {
            $sanitized['figmaV2'] = sanitize_text_field(wp_unslash($input['figmaV2']));
        }
        if (isset($input['figmaId'])) {
            $sanitized['figmaId'] = sanitize_text_field(wp_unslash($input['figmaId']));
        }
        return $sanitized;
    }

    /**
     * Displays the settings manager instructions.
     */
    public function settingsManager() {
        echo '<p>' . esc_html__('To convert landing pages from Figma, a Figma Access Token is required.', 'illiant-landings') . '</p>';
    }
    
    /**
     * Renders the Figma Key field.
     */
    public function figmaKeyField() {
        $options = get_option('illiant_landings', []);
        $key = $options['figmaV2'] ?? '';
        echo '<input type="text" name="illiant_landings[figmaV2]" id="figmaKey" value="' . esc_attr($key) . '">';
    }
    public function figmaIdField() {
        $options = get_option('illiant_landings', []);
        $key = $options['figmaId'] ?? '';
        echo '<input type="text" name="illiant_landings[figmaId]" id="figmaId" value="' . esc_attr($key) . '">';
    }
}
