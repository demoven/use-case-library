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