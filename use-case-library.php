<?php
/**
 * Plugin Name: Use Case Library
 * Description: Description de mon plugin.
 * Version: 1.0
 * Author: Théo
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-page/form-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-details-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-library.php';



// Instantiate the main plugin class

new UseCaseLibraryAdmin();
new UseCaseLibraryForm();
new UseCaseLibraryDetailsPage();
new UseCaseLibraryDisplay();

function template_array()
{
    $temps = [];

    $temps['use-case-template.php'] = 'Use Case Template';

    return $temps;
}

function template_register($page_templates, $theme, $post)
{
    $templates = template_array();

    foreach ($templates as $tk=>$tv) {
        $page_templates[$tk] = $tv;
    }

    return $page_templates;
}

add_filter('theme_page_templates', 'template_register', 10, 3);

function template_select($template)
{
    global $post, $wp_query, $wpdb;

    $page_temp_slug = get_page_template_slug($post->ID);

    $templates = template_array();

    if (isset($templates[$page_temp_slug]))
    {
        $template = plugin_dir_path(__FILE__) . 'includes/templates/' . $page_temp_slug;
    }

    return $template;
}

add_filter('template_include', 'template_select', 99 );



