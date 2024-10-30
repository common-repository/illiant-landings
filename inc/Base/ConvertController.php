<?php 
/**
 * @package  illiantLandings
 */

namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Illiantland\Api\SettingsApi;
use Illiantland\Base\BaseController;
use Illiantland\Api\Callbacks\AdminCallbacks;

use Illiantland\Controllers\ImageController;
use Illiantland\Controllers\DesignController;
use Illiantland\Controllers\StyleController;
use Illiantland\Controllers\PublishController;

/**
 * Class ConvertController
 *
 * 
 * This class is responsible for setting up the landing page creation form,
 * saving the settings, and handling the rendering of the landing page.
 */

class ConvertController extends BaseController
{
    /**
     * Settings API
     * 
     * @var SettingsApi
     */
    public $settings;

    /**
     * Holds subpages information
     * 
     * @var array
     */
    public $subpages = array();

    /**
     * Holds the callbacks for admin area
     * 
     * @var AdminCallbacks
     */
    public $callbacks;

    /**
     * Registers settings, sections, fields, and AJAX actions for landing page functionality.
     * Sets up necessary hooks and configurations for creating and managing landing pages.
     */
    public function register()
    {
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->setSubpages();

        $this->settings->addSubPages( $this->subpages )->register();

        $imageController = new ImageController();
        $designController = new DesignController();
        $styleController = new StyleController();
        $publishController = new PublishController();

        $imageController->register();
        $designController->register();
        $styleController->register();
        $imageController->register();
        $publishController->register();

    }      
    
    
    /**
     * Defines the subpages to be added under the main plugin menu.
     */
    private function setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'illiant_wplandings', 
                'page_title' => 'Convert Figma design', 
                'menu_title' => 'Convert Figma design', 
                'capability' => 'manage_options', 
                'menu_slug' => 'illiant_wplandings_create', 
                'callback' => array( $this->callbacks, 'adminCreateLanding' )
            )
        );
    }

}