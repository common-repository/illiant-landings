<?php
/**
 * @package  illiantLandings
 */
namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Illiantland\Base\BaseController;

class SettingsLinks extends BaseController
{
	/**
	 * Registers the filter to add a custom settings link to the plugin action links.
	 */
	public function register() 
	{
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	/**
	 * Adds a custom settings link to the plugin action links array.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links including the new settings link.
	 */
	public function settings_link( $links ) 
	{
		$settingsText = esc_html__( 'Settings', 'illiantlandings' );
		$settingsUrl = esc_url( admin_url( 'admin.php?page=illiant_landings' ) );
    	$settingsLink = '<a href="' . $settingsUrl . '">' . $settingsText . '</a>';
		$links[] = $settingsLink;
		
    	return $links;
	}
}