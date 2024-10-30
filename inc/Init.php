<?php
/**
 * @package  illiantLandings
 */
namespace Illiantland;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Init
{
	/**
	 * Store all the classes inside an array.
	 * 
	 * @return array Full list of classes that provide services.
	 */
	public static function get_services() 
	{
		return [
			Pages\Settings::class,
			Base\Enqueue::class,
			Base\SettingsLinks::class,		
			Base\ConvertController::class,	
			Base\KnowledgeController::class,		
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists.
	 * @return void
	 */
	public static function register_services() 
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class and return a new instance of it.
	 * 
	 * @param string $class The class name from the services array.
	 * @return object New instance of the class.
	 */
	private static function instantiate( string $class )
	{
		if (class_exists($class)) {
			return new $class();
		}
		return null;
	}
}