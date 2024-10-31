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

// Inclure les classes principales
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-details-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-library.php';

// Instancier les classes
new UseCaseLibraryAdmin();
new UseCaseLibraryForm();
new UseCaseLibraryDisplay();