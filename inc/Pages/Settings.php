<?php 
/**
 * @package  illiantLandings
 */
namespace Illiantland\Pages;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Illiantland\Api\SettingsApi;
use Illiantland\Base\BaseController;
use Illiantland\Api\Callbacks\AdminCallbacks;
use Illiantland\Api\Callbacks\SettingsCallbacks;

/**
* 
*/
class Settings extends BaseController
{
	/**
	 * Instance of SettingsApi to manage plugin settings.
	 * @var SettingsApi
	 */
	public $settings;

	/**
	 * Instance of AdminCallbacks for handling admin callbacks.
	 * @var AdminCallbacks
	 */
	public $callbacks;

	/**
	 * Instance of SettingsCallbacks for handling settings callbacks.
	 * @var SettingsCallbacks
	 */
	public $callbacks_settings;

	/**
	 * Array of pages to be added to the admin menu.
	 * @var array
	 */
	public $pages = array();

	/**
	 * Register all necessary hooks and settings for the plugin's admin page.
	 */
	public function register() 
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_settings = new SettingsCallbacks();

		$this->setPages();
		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Settings' )->register();
	}

	/**
	 * Initializes the plugin's admin page settings.
	 * This method sets up the configuration for the main plugin page in the WordPress admin menu,
	 * including the page title, menu title, required capability for access, menu slug, and the callback
	 * for rendering the page view. An icon URL and position in the menu are also specified.
	 */
	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'illiant WPLandings', 
				'menu_title' => 'WPLandings', 
				'capability' => 'manage_options', 
				'menu_slug' => 'illiant_wplandings', 
				'callback' => array( $this->callbacks, 'adminSettings' ), 
				'icon_url' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="247" height="243" viewBox="0 0 247 243" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M0.917969 5.75195L38.0499 24.7239L34.9489 35.2777L13.0809 20.7906L37.6337 225.163L42.6326 234.329L27.8972 242.365L0.917969 5.75195Z" fill="#A7AAAD"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M32.0879 0V240.737L246.587 199.412V60.3483L32.0879 0ZM140.932 179.732L164.22 90.3984H140.805L127.825 155.426L112.554 90.3984H88.6298L72.7228 155.426L59.8699 90.3984H36.582L59.2336 179.732H85.5757L100.337 120.94L114.59 179.732H140.932ZM239.652 133.411C242.027 129.084 243.215 124.333 243.215 119.158C243.215 113.559 241.985 108.596 239.524 104.269C237.064 99.9426 233.374 96.5491 228.453 94.0889C223.532 91.6286 217.509 90.3984 210.383 90.3984H175.133V179.732H196.894V147.664H210.383C217.679 147.664 223.787 146.349 228.708 143.719C233.628 141.089 237.276 137.653 239.652 133.411ZM218.018 127.43C215.982 129.381 212.885 130.357 208.728 130.357H196.894V107.96H208.728C212.885 107.96 215.982 108.935 218.018 110.887C220.054 112.838 221.072 115.595 221.072 119.158C221.072 122.721 220.054 125.479 218.018 127.43Z" fill="#A7AAAD"/>
</svg>
'), 
				'position' => 6
			)
		);
	}

	/**
	 * Configures the plugin's settings.
	 * This method prepares the settings array for registration, specifying the option group,
	 * option name, and the callback function for sanitization of the settings' values. These
	 * settings are then registered using the SettingsApi.
	 */
	public function setSettings()
	{
		$args = array(
			array( 
				'option_group' => 'illiant_landings_settings',
				'option_name' => 'illiant_landings',
				'callback' => array( $this->callbacks_settings, 'keysSanitize' )
			)
		);

        $this->settings->setSettings( $args );
	}

	/**
 	* Sets up the sections for the plugin's settings page.
	* This method defines the sections that will be displayed on the plugin's settings page,
	* including their IDs, titles, callback functions for rendering the section descriptions,
	* and the page on which they should appear. These sections help organize settings logically.
	*/
	public function setSections()
	{
		$args = array(
			array(
				'id' => 'illiant_admin_index',
				'title' => 'Set-up your keys',
				'callback' => array( $this->callbacks_settings, 'settingsManager' ),
				'page' => 'illiant_wplandings'
			)
		);

		$this->settings->setSections( $args );
	}

	/**
	 * Initializes the fields for the plugin's settings sections.
	 * This method configures each setting field, including its ID, title, callback for rendering
	 * the field, the page and section it belongs to, and additional arguments such as the label
	 * for the field and any CSS classes. These fields allow users to input or adjust settings values.
	 */
	public function setFields()
	{
		$args = array(
			array(
                'id' => 'illiant_figma',
                'title' => 'Figma Token',
                'callback' => array($this->callbacks_settings, 'figmaKeyField'),
                'page' => 'illiant_wplandings',
                'section' => 'illiant_admin_index',
				'args' => array(
                    'label_for' => 'illiant_figma',
					'class' => 'lp-key',
				)
			),
			array(
                'id' => 'illiant_figma_id',
                'title' => 'Figma Id',
                'callback' => array($this->callbacks_settings, 'figmaIdField'),
                'page' => 'illiant_wplandings',
                'section' => 'illiant_admin_index',
				'args' => array(
                    'label_for' => 'illiant_figma_id',
					'class' => 'hidden',
				)
			)
        );
        $this->settings->setFields($args);
	}
}