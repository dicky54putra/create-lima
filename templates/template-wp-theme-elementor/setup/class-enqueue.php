<?php

/**
 * Enqueue custom scripts and styles.
 */

namespace MI_Theme\setup;

defined('ABSPATH') || die("Can't access directly");

class Enqueue
{
    public function __construct()
    {
        add_action("wp_enqueue_scripts", [$this, "frontEnd"]);
        add_filter('script_loader_tag', [$this, 'mind_defer_scripts'], 10, 3);
    }

    function frontEnd()
    {
        wp_enqueue_style(
            "frontend_style",
            get_stylesheet_directory_uri() . "/assets/dist/css/style.min.css",
            [],
            filemtime(get_template_directory() . "/assets/dist/css/style.min.css")
        );

        wp_enqueue_script(
            "vendors_script",
            get_template_directory_uri() . "/assets/dist/js/vendors.min.js",
            [],
            filemtime(get_template_directory() . "/assets/dist/js/vendors.min.js"),
            true
        );

        wp_enqueue_script(
            "frontend_script",
            get_template_directory_uri() . "/assets/dist/js/script.min.js",
            ["jquery"],
            filemtime(get_template_directory() . "/assets/dist/js/script.min.js"),
            true
        );

        /**
         * enqueue Example Ajax
         */
        wp_localize_script(
            'frontend_script', // Ajax Name
            'parameters', // Object name parameter
            [
                'url_admin_ajax'       => admin_url('admin-ajax.php'),
            ]
        );
    }

    public function mind_defer_scripts($tag, $handle, $src)
    {
        $defer = array(
            'vendors_script'
        );

        if (in_array($handle, $defer)) {
            return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
        }

        return $tag;
    }
}

/*
 * initialize
 * */
new Enqueue();
