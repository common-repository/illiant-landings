<?php
/**
 * @package  illiantLandings
 */
namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Activate
{
	/**
	 * Handles tasks to run during plugin activation.
	 * This includes setting default plugin options
	 * if they do not already exist.
	 */
	public static function activate() {

		flush_rewrite_rules();

		$default = array();

		if ( ! get_option( 'illiant_landings' ) ) {
			update_option( 'illiant_landings', $default );
		}

		if ( ! get_option( 'illiant_design_data' ) ) {
			update_option( 'illiant_design_data', $default );
		}
	
	}
}