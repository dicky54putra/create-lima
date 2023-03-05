<?php

/**
 * Register ACF options page.
 */
defined('ABSPATH') || die("Can't access directly");

class customACF
{
	public function __construct()
	{
		$this->addOptionPage();
	}

	private function addOptionPage()
	{
		if (function_exists("acf_add_options_page")) {
			acf_add_options_page([
				"page_title" => "Theme General Settings",
				"menu_title" => "Theme Settings",
				"menu_slug" => "theme-general-settings",
				"capability" => "edit_posts",
				"redirect" => false,
			]);
		}
	}
}

// initiasi
new customACF();
