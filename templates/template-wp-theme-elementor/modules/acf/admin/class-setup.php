<?php

/**
 * @package BornDigital
 */

namespace BD\ACF;

/**
 * Class to setup the component
 */
class Setup
{
	/**
	 * Current module directory
	 *
	 * @var string
	 */
	private $dir;

	/**
	 * Current module url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Setup the flow
	 */
	public function __construct()
	{
		$this->dir = MODULES_DIR . '/acf';
		$this->url = MODULES_URL . '/acf';

		add_action('acf/init', array($this, 'load_acf_fields'));
		add_action('admin_enqueue_scripts', array($this, 'acf_custom_js'));
	}

	public function load_acf_fields()
	{
		$is_public_env = defined('IS_LOCAL') && IS_LOCAL ? false : true;
		if ($is_public_env) {
			if (file_exists(__DIR__ . '/../fields/bd-acf-fields.php')) {
				include  __DIR__ . '/../fields/bd-acf-fields.php';
			}

			add_action('admin_menu', function () {
				remove_menu_page('edit.php?post_type=acf-field-group');
			}, 100);
		}
	}

	public function acf_custom_js()
	{

		$screen = get_current_screen();

		if ($screen->id == 'custom-fields_page_acf-tools' && !isset($_GET['keys'])) {
			wp_enqueue_style(
				'bd-acf-css',
				$this->url . '/assets/acf-export-to-theme.css',
				[],
				'auto'
			);

			wp_enqueue_script(
				'bd-acf-js',
				$this->url . '/assets/acf-export-to-theme.js',
				['jquery'],
				'auto',
				true
			);

			wp_localize_script('bd-acf-js', 'objBdAcf', array(
				'ajaxURL' => admin_url('admin-ajax.php'),
				'action'  => 'generate_acf_file',
				'nonce'   => wp_create_nonce('GenerateAcfFile'),
			));
		}
	}
}

new Setup();
