<?php 
/**
 * @package illiantLandings
 */
namespace Illiantland\Api;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SettingsApi class to create admin pages and register settings.
 * Handles the settings API for the WPLandings plugin.
 * This class is responsible for creating admin pages, subpages, and registering custom settings.
 *
 */
class SettingsApi
{
	public $admin_pages = [];
    public $admin_subpages = [];
    public $settings = [];
    public $sections = [];
    public $fields = [];

	/**
     * Registers the admin pages and settings.
     */
	public function register() {
        if (!empty($this->admin_pages) || !empty($this->admin_subpages)) {
            add_action('admin_menu', [$this, 'addAdminMenu']);
        }

        if (!empty($this->settings)) {
            add_action('admin_init', [$this, 'registerCustomFields']);
        }
    }

	/**
     * Adds admin pages.
     *
     * @param array $pages Array of pages to add.
     * @return $this
     */
	public function addPages( array $pages )
	{
		$this->admin_pages = $pages;
		return $this;
	}

	/**
     * Adds a default subpage.
     *
     * @param string|null $title The title of the subpage.
     * @return $this
     */
	public function withSubPage( string $title = null ) 
	{
		if ( empty($this->admin_pages) ) {
			return $this;
		}

		$admin_page = $this->admin_pages[0];

		$subpage = array(
			array(
				'parent_slug' => $admin_page['menu_slug'], 
				'page_title' => $admin_page['page_title'], 
				'menu_title' => ($title) ? $title : $admin_page['menu_title'], 
				'capability' => $admin_page['capability'], 
				'menu_slug' => $admin_page['menu_slug'], 
				'callback' => $admin_page['callback']
			)
		);

		$this->admin_subpages = $subpage;

		return $this;
	}

	/**
     * Adds subpages.
     *
     * @param array $pages Array of subpages to add.
     * @return $this
     */
	public function addSubPages( array $pages )
	{
		$this->admin_subpages = array_merge( $this->admin_subpages, $pages );

		return $this;
	}

	/**
     * Registers admin menu and submenus.
     */
	public function addAdminMenu()
	{
		foreach ( $this->admin_pages as $page ) {
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}

		foreach ( $this->admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
		}
	}

	/**
     * Sets settings array.
     *
     * @param array $settings Array of settings to register.
     * @return $this
     */
	public function setSettings( array $settings )
	{
		$this->settings = $settings;

		return $this;
	}

	/**
     * Sets sections array.
     *
     * @param array $sections Array of sections to add.
     * @return $this
     */
	public function setSections( array $sections )
	{
		$this->sections = $sections;

		return $this;
	}

	/**
     * Sets fields array.
     *
     * @param array $fields Array of fields to add.
     * @return $this
     */
	public function setFields( array $fields )
	{
		$this->fields = $fields;

		return $this;
	}

	/**
     * Registers custom fields using the settings, sections, and fields arrays.
     */
	public function registerCustomFields()
	{
		// register setting
		foreach ( $this->settings as $setting ) {
			register_setting( $setting["option_group"], $setting["option_name"], ( isset( $setting["callback"] ) ? $setting["callback"] : '' ) );
		}

		// add settings section
		foreach ( $this->sections as $section ) {
			add_settings_section( $section["id"], $section["title"], ( isset( $section["callback"] ) ? $section["callback"] : '' ), $section["page"] );
		}

		// add settings field
		foreach ( $this->fields as $field ) {
			add_settings_field( $field["id"], $field["title"], ( isset( $field["callback"] ) ? $field["callback"] : '' ), $field["page"], $field["section"], ( isset( $field["args"] ) ? $field["args"] : '' ) );
		}
	}
}