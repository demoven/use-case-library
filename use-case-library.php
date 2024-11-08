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


/**
 * Add custom template to the theme
 */
function template_array()
{
    // Array of custom templates
    $temps = [];

    // Add the custom template
    $temps['use-case-template.php'] = 'Use Case Template';

    // Return the custom template
    return $temps;
}

/**
 * Register the custom template
 * @param $page_templates
 * @param $theme
 * @param $post
 */
function template_register($page_templates, $theme, $post)
{
    // Get the custom templates
    $templates = template_array();

    // Add the custom templates to the theme
    foreach ($templates as $tk=>$tv) 
    {
        // Add the template to the theme
        $page_templates[$tk] = $tv;
    }

    // Return the custom templates
    return $page_templates;
}

// Add filter to register the custom template
add_filter('theme_page_templates', 'template_register', 10, 3);

/**
 * Select the custom template
 * @param $template
 */
function template_select($template)
{
    // Get the post, wp_query and wpdb
    global $post, $wp_query, $wpdb;

    // Get the page template slug 
    $page_temp_slug = get_page_template_slug($post->ID);

    // Get the custom templates with the function
    $templates = template_array();

    // Check if the custom template is set
    if (isset($templates[$page_temp_slug]))
    {
        // Get the custom template
        $template = plugin_dir_path(__FILE__) . 'includes/templates/' . $page_temp_slug;
    }

    // Return the custom template
    return $template;
}

// Add filter to select the custom template 
add_filter('template_include', 'template_select', 99 );



