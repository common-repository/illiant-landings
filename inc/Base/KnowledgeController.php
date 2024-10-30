<?php 
/**
 * @package  illiantLandings
 */
namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Illiantland\Api\SettingsApi;
use Illiantland\Base\BaseController;
use Illiantland\Api\Callbacks\AdminCallbacks;

class KnowledgeController extends BaseController
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
     * Registers the landing page functionalities including the settings, sections, fields, and subpages.
     * This method initializes required components and configures subpages to be added under the main plugin menu.
     */
    public function register()
    {
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->setSubpages();

        $this->settings->addSubPages( $this->subpages )->register();
    }   
   
    /**
     * Sets up the subpages under the main plugin menu.
     * This method configures additional admin pages such as Documentation and FAQ to enhance the plugin's admin interface.
     */
    private function setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'illiant_wplandings', 
                'page_title' => 'Documentation', 
                'menu_title' => 'Documentation', 
                'capability' => 'manage_options', 
                'menu_slug' => 'illiant_wplandings_doc', 
                'callback' => array( $this->callbacks, 'adminDocumentation' )
            ),
            array(
                'parent_slug' => 'illiant_wplandings', 
                'page_title' => 'FAQ', 
                'menu_title' => 'FAQ', 
                'capability' => 'manage_options', 
                'menu_slug' => 'illiant_wplandings_faq', 
                'callback' => array( $this->callbacks, 'adminFAQ' )
            )
        );
    }
}