<?php

/**
 * The template for Example used in Widget Elementor
 * Author: Dicky Saputra, Andigusta, M.Rizki9591
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
  exit;
}

class ExampleWidget extends \Elementor\Widget_Base
{
  public function get_name()
  {
    // change this
    return "example-widget";
  }

  public function get_title()
  {
    // change this
    return __("Example Widget", THEME_DOMAIN);
  }

  public function get_keywords()
  {
    return ['cew'];
  }

  public function get_icon()
  {
    // change this
    return "eicon-posts-grid";
  }

  public function get_script_depends()
  {
    return ["mi_scripts"];
  }

  public function get_style_depends()
  {
    return ["mi_styles"];
  }

  public function get_categories()
  {
    return ["my-widgets"];
  }

  protected function register_controls()
  {
    // change this
    $this->start_controls_section(
      "content",
      [
        "label" => __("Content", THEME_DOMAIN),
      ]
    );

    //This is just example control
    $this->add_control(
      "title",
      [
        "label" => __("Title", THEME_DOMAIN),
        "type" => \Elementor\Controls_Manager::TEXT,
        "default" => __("", THEME_DOMAIN),
      ]
    );

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    require __DIR__ . '/example-render.php';
  }
}
