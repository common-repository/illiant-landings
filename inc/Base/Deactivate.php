<?php
/**
 * @package  illiantLandings
 */
namespace Illiantland\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Deactivate
{
	/**
	 * Handles operations to run during plugin deactivation.
	 * This includes flushing rewrite rules to clean up any custom post type or taxonomy
	 * URL structures that were registered by the plugin.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}