<?php 
/**
 * @package  illiantLandings
 */
namespace Illiantland\Api\Callbacks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Illiantland\Base\BaseController;

/**
 * Handles callbacks for admin page views.
 *
 */
class AdminCallbacks extends BaseController
{
	/**
     * Renders the settings page view.
     */
    public function adminSettings() {
        $view_path = $this->plugin_path . 'views/admin.php';
        if (file_exists($view_path)) {
            require_once $view_path;
        }
    }

    /**
     * Renders the create landing page view.
     */
    public function adminCreateLanding() {
        $view_path = $this->plugin_path . 'views/createLanding.php';
        if (file_exists($view_path)) {
            require_once $view_path;
        }
    }

    /**
     * Renders the documentation page view.
     */
    public function adminDocumentation() {
        $view_path = $this->plugin_path . 'views/documentation.php';
        if (file_exists($view_path)) {
            require_once $view_path;
        }
    }

    /**
     * Renders the FAQ page view.
     */
    public function adminFAQ() {
        $view_path = $this->plugin_path . 'views/faq.php';
        if (file_exists($view_path)) {
            require_once $view_path;
        }
    }
}