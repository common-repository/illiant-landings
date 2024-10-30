<?php
/**
 * @package  illiantLandings
 */
/*
Plugin Name: WPLandings
Plugin URI: https://illiant.ai
Description: Convert Figma to WordPress Gutenberg Blocks with WPLandings. Try it for free without creating an account or installing additional figma plugins.
Version: 2.4.1
Author: illiant.ai
License: GPLv2 or later
Text Domain: illiant-landings
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// If this file is called directly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );

/**
 * Freemius SDK initialization
 */

 if ( function_exists( 'illiantlandings_fs' ) ) {
    illiantlandings_fs()->set_basename( true, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( ! function_exists( 'illiantlandings_fs' ) ) {
        // Create a helper function for easy SDK access.
        function illiantlandings_fs() {
            global $illiantlandings_fs;

            if ( ! isset( $illiantlandings_fs ) ) {
                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/freemius/start.php';
                
                $illiantlandings_fs = fs_dynamic_init( array(
                    'id'                  => '14971',
                    'slug'                => 'wplandings',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_7460d071c2558bf6b811742f88f85',
                    'is_premium'          => false,
                    // If your plugin is a serviceware, set this option to false.
                    'has_premium_version' => false,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'menu'                => array(
                        'slug'           => 'illiant_wplandings',
                        'support'        => false,
                    ),
                ) );
            }

            return $illiantlandings_fs;
        }

        // Init Freemius.
        illiantlandings_fs();

        // Signal that SDK was initiated.
        do_action( 'illiantlandings_fs_loaded' );

    }

    // Require once the Composer Autoload
    if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
        require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    }
    
    

    /**
     * The code that runs during plugin activation
     */
    function illiant_landings_activate() {
        Illiantland\Base\Activate::activate();
    }
    register_activation_hook( __FILE__, 'illiant_landings_activate' );

    /**
     * The code that runs during plugin deactivation
     */
    function illiant_landings_deactivate() {
        Illiantland\Base\Deactivate::deactivate();
    }
    register_deactivation_hook( __FILE__, 'illiant_landings_deactivate' );

    /**
     * Initialize all the core classes of the plugin
     */
    if ( class_exists( 'Illiantland\\Init' ) ) {
        Illiantland\Init::register_services();
    }

}