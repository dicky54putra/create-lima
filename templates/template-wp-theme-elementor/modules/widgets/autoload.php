<?php

/**
 * Used for ....
 *
 * @package HelloElementor
 */

class My_Elementor_Widgets
{

  protected static $instance = null;

  public static function get_instance()
  {
    if (!isset(static::$instance)) {
      static::$instance = new static;
    }

    return static::$instance;
  }

  protected function __construct()
  {
    add_action('elementor/widgets/register', [$this, 'register_widgets']);
    add_action("elementor/elements/categories_registered", [$this, "register_new_category"]);
  }

  public function includes()
  {
    require_once('example/example.php');
  }

  public function register_widgets($widgetManager)
  {
    $this->includes();
    $widgetManager->register(new \ExampleWidget());
  }

  public function register_new_category($elements_manager)
  {
    $elements_manager->add_category("my-widgets", [
      "title" => __("MY WIDGETS", THEME_DOMAIN),
    ]);
  }
}

add_action('init', 'my_elementor_init');

function my_elementor_init()
{
  My_Elementor_Widgets::get_instance();
}
