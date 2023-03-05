<?php
defined('ABSPATH') || die("Can't access directly");

class ShortCodeMI
{
    public function __construct()
    {
        $this->shortcode();
    }

    public function shortcode()
    {
        $scan_files = scandir(__DIR__ . '/templates');
        $shortcodes = array_slice($scan_files, 2);

        // $shortcodes = $this->lists();
        foreach ($shortcodes as $key => $sc) {
            $name = str_replace('.php', '', $sc);
            add_shortcode($name, function ($atts) use ($sc) {
                ob_start();
                require __DIR__ . '/templates/' . $sc;
                return ob_get_clean();
            });
        }
    }
}

new ShortCodeMI;
