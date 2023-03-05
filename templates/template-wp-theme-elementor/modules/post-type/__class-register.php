<?php

class RegisterCPT {

	public function customPostType($label_name,$slug, $args = []) {
		$defaultArgument = $this->defaultArgument($label_name,$slug);

		if( ! empty( $args )) {
			foreach ($args as $key => $value){
				$defaultArgument[$key] = $value;
			}
		}

		register_post_type($slug,$defaultArgument);
	}

	public function taxonomy($slug_cpt,$slug_tax,$args = []) {
		$defaultArgument = [
			'label' => __('Category'),
			'hierarchical' => true,
			'query_var' => true,
		];

		if(! empty($args) ){
			foreach ($args as $key => $value) {
				$defaultArgument[$key] = $value;
			}
		}
		register_taxonomy(
			$slug_tax,
			$slug_cpt,
			$defaultArgument
		);


	}

	private function label($label_name) {
		$labels = array(
			'name'                  => _x($label_name, $label_name.' General Name', THEME_DOMAIN),
			'singular_name'         => _x($label_name, 'Post Type Singular Name', THEME_DOMAIN),
			'menu_name'             => __($label_name, THEME_DOMAIN),
			'name_admin_bar'        => __($label_name, THEME_DOMAIN),
			'archives'              => __($label_name.' Archives', THEME_DOMAIN),
			'attributes'            => __($label_name.' Attributes', THEME_DOMAIN),
			'parent_item_colon'     => __('Parent '.$label_name.':', THEME_DOMAIN),
			'all_items'             => __('All '.$label_name, THEME_DOMAIN),
			'add_new_item'          => __('Add New '. $label_name, THEME_DOMAIN),
			'add_new'               => __('Add New', THEME_DOMAIN),
			'new_item'              => __('New '.$label_name, THEME_DOMAIN),
			'edit_item'             => __('Edit '.$label_name, THEME_DOMAIN),
			'update_item'           => __('Update '.$label_name, THEME_DOMAIN),
			'view_item'             => __('View '.$label_name, THEME_DOMAIN),
			'view_items'            => __('View '.$label_name, THEME_DOMAIN),
			'search_items'          => __('Search '.$label_name, THEME_DOMAIN),
			'not_found'             => __('Not found', THEME_DOMAIN),
			'not_found_in_trash'    => __('Not found in Trash', THEME_DOMAIN),
			'featured_image'        => __('Featured Image', THEME_DOMAIN),
			'set_featured_image'    => __('Set featured image', THEME_DOMAIN),
			'remove_featured_image' => __('Remove featured image', THEME_DOMAIN),
			'use_featured_image'    => __('Use as featured image', THEME_DOMAIN),
			'insert_into_item'      => __('Insert into item', THEME_DOMAIN),
			'uploaded_to_this_item' => __('Uploaded to this '.$label_name, THEME_DOMAIN),
			'items_list'            => __($label_name.' list', THEME_DOMAIN),
			'items_list_navigation' => __($label_name.' list navigation', THEME_DOMAIN),
			'filter_items_list'     => __('Filter '.$label_name.' list', THEME_DOMAIN),
		);

		return $labels;
	}

	private function defaultArgument($label_name, $slug) {

		$label_data = $this->label($label_name);

		$args = array(
			'label'                 => __($label_name, THEME_DOMAIN),
			'description'           => __($label_name.' Description', THEME_DOMAIN),
			'labels'                => $label_data,
			'supports'              => array('title', 'editor', 'thumbnail'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_icon'             => 'dashicons-admin-post',
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => array( 'slug' => $slug ),
			'capability_type'       => 'page',
		);
		return $args;
	}
}