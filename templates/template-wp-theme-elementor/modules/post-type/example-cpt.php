<?php

/**
 * The template for Create Custom Post Type - Laten Beleggen used in WP-admin
 *
 * Author: Andi Gustanto and Muhammad Rizki
 *
 * @package HelloElementor
 */

defined('ABSPATH') || die("Can't access directly");

class ExampleCPT extends RegisterCPT
{

	public function __construct()
	{
		// add_action('init',  [$this, 'exampleCreatCPT']);
	}

	public function exampleCreatCPT()
	{

		// argument cpt
		$title = 'example CPT';
		$slug_cpt = 'example-cpt';
		$args = [
			'menu_position' => 5,
		];
		$this->customPostType($title, $slug_cpt, $args);

		// args taxonomy
		$slug_tax = 'example-taxonomy';
		$args = [
			'label' => __('example Taxonomy'),
		];
		$this->taxonomy($slug_cpt, $slug_tax, $args);
	}
}

// initialize
new ExampleCPT();
