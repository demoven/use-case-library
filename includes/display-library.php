<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryDisplay')) {
    class UseCaseLibraryDisplay
    {
        public function __construct()
        {

            // Add shortcode
            add_shortcode('display_use_cases', array($this, 'display_use_cases_shortcode'));

            // Load assets
            add_action('wp_enqueue_scripts', array($this, 'load_assets'));
        }

        /**
         * Load assets for the library
         */
        public function load_assets()
        {
            // Load CSS and JS files
            if (has_shortcode(get_post()->post_content, 'display_use_cases')) {
                wp_enqueue_style(
                    'use-case-library-style',
                    plugin_dir_url(__FILE__) . '../assets/css/library.css',
                    array(),
                    '1.0',
                    'all'
                );
                wp_enqueue_script(
                    'library-script',
                    plugin_dir_url(__FILE__) . '../assets/js/library.js',
                    array('jquery'),
                    '1.0',
                    true
                );
            }
        }

        /**
         * Shortcode to display published use cases.
         *
         * @return string HTML output of the published use cases.
         */
        public function display_use_cases_shortcode()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';

            // Build the query to get all published use cases
            $query = "SELECT * FROM $table_name WHERE published = 1 ORDER BY id DESC";

            // Execute the query
            $use_cases = $wpdb->get_results($query);

            // Start the output buffer
            ob_start();

            // Display the filter form and use cases
            ?>
            <div class="search-bar">
                <input type="text" id="search" placeholder="Search by Project Name">
                <button type="button" class="button button-primary" onclick="applyFilters()">Search</button>
            </div>
            <div class="use-case-container">
                <div class="filter-container">
                    <h2>Filter Use Cases <i class="fa-solid fa-filter"></i></h2>
                    <form id="filter-form">
                        <!-- Search by Project Name -->


                        <!-- Filter by Innovation -->
                        <div class="collapsible">
                            <button type="button" class="collapsible-button">Innovation Sectors<i
                                        class="fa-solid fa-chevron-down"></i></button>
                            <div class="collapsible-content">
                                <div id="innovation-sectors">
                                    <?php
                                    $innovation_sectors = array(
                                        'Culture & Media',
                                        'Data Sharing',
                                        'Department of Defense',
                                        'ELSA Labs',
                                        'Energy & Sustainability',
                                        'Financial Services',
                                        'Health & Care',
                                        'Port & Maritime',
                                        'Agriculture & Nutrition',
                                        'Logistics & Mobility',
                                        'Human-centered AI',
                                        'Mobility, Transport & Logistics',
                                        'Education',
                                        'Public Services',
                                        'Research & Innovation',
                                        'Startups & Scaleups',
                                        'Technical Industry',
                                        'Security, Peace & Justice'
                                    );
                                    foreach ($innovation_sectors as $sector) {
                                        echo '<div class="innovation-sector-checkbox">';
                                        echo '<input type="checkbox" name="innovation_sectors[]" value="' . esc_attr($sector) . '"> ' . esc_html($sector);
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by Value Chain -->
                        <div class="collapsible">
                            <button type="button" class="collapsible-button">Value Chain<i
                                        class="fa-solid fa-chevron-down"></i></button>
                            <div class="collapsible-content">
                                <div id="value-chain">
                                    <?php
                                    $value_chains = array(
                                        'Inbound logistics',
                                        'Operations',
                                        'Outbound logistics',
                                        'Marketing and sales',
                                        'Service',
                                        'Firm infrastructure',
                                        'Human resource management',
                                        'Technology',
                                        'Procurement'
                                    );
                                    foreach ($value_chains as $value_chain) {
                                        echo '<div class="value-chain-checkbox">';
                                        echo '<input type="checkbox" name="value_chain[]" value="' . esc_attr($value_chain) . '"> ' . esc_html($value_chain);
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by Themes -->
                        <div class="collapsible">
                            <button type="button" class="collapsible-button">Themes<i
                                        class="fa-solid fa-chevron-down"></i></button>
                            <div class="collapsible-content">
                                <div id="lib-themes">
                                    <?php
                                    $themes = array(
                                        'Transaction to interaction',
                                        'Future of Work',
                                        'Cloud Everywhere',
                                        'Future of Programming',
                                        'Next UI',
                                        'Building Trust',
                                        'Green Tech',
                                        'Quantum computing',
                                        'Autonomy'
                                    );
                                    foreach ($themes as $theme) {
                                        echo '<div class="theme-checkbox">';
                                        echo '<input type="checkbox" name="themes[]" value="' . esc_attr($theme) . '"> ' . esc_html($theme);
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by SDGs -->
                        <div class="collapsible">
                            <button type="button" class="collapsible-button">SDGs<i
                                        class="fa-solid fa-chevron-down"></i></button>
                            <div class="collapsible-content">
                                <div id="lib-sdgs">
                                    <?php
                                    $sdgs = array(
                                        '1. No poverty',
                                        '2. No hunger',
                                        '3. Good health and well-being',
                                        '4. Quality education',
                                        '5. Gender equality',
                                        '6. Clean water and sanitation',
                                        '7. Affordable and sustainable energy',
                                        '8. Decent work and economic growth',
                                        '9. Industry, innovation and infrastructure',
                                        '10. Reduce inequality',
                                        '11. Sustainable cities and communities',
                                        '12. Responsible consumption and production',
                                        '13. Climate action',
                                        '14. Life in the water',
                                        '15. Life on land',
                                        '16. Peace, justice and strong public services',
                                        '17. Partnership to achieve goals'
                                    );
                                    foreach ($sdgs as $sdg) {
                                        echo '<div class="sdg-checkbox">';
                                        echo '<input type="checkbox" name="sdgs[]" value="' . esc_attr($sdg) . '"> ' . esc_html($sdg);
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by Innovation Sectors -->
                        <div class="collapsible">
                            <button type="button" class="collapsible-button">Windesheim Minors<i
                                        class="fa-solid fa-chevron-down"></i></button>
                            <div class="collapsible-content">
                                <div id="windesheim-minor">
                                    <?php
                                    $minors = array(
                                        'Concept & Creation',
                                        'Data driven Innovation',
                                        'Entrepreneurships',
                                        'Future Technology',
                                        'Game Studio',
                                        'Mobile Solutions',
                                        'Security Engineering',
                                        'Web & Analytics'
                                    );
                                    foreach ($minors as $minor) {
                                        echo '<div class="minor-checkbox">';
                                        echo '<input type="checkbox" name="w_minor[]" value="' . esc_attr($minor) . '"> ' . esc_html($minor);
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Display the use cases -->
                <div class="use-cases" id="use-cases">
                    <?php
                    foreach ($use_cases as $use_case) {

                        echo '<div class="use-case" data-url="' . esc_url(home_url('/use-case-details/?post_id=' . esc_html($use_case->id))) . '" data-minor="' . esc_attr($use_case->w_minor) . '" data-value-chain="' . esc_attr($use_case->value_chain) . '" data-themes="' . esc_attr($use_case->themes) . '" data-sdgs="' . esc_attr($use_case->sdgs) . '" data-innovation-sectors="' . esc_attr($use_case->innovation_sectors) . '">';
                        // a element to link to the use case details page in a new tab
                        echo '<a class="use-case-click" href="' . esc_url(home_url('/use-case-details/?post_id=' . esc_html($use_case->id))) . '" target="_blank">';
                        if (esc_url($use_case->project_image)) {
                            echo '<div class="image-wrapper">';
                            echo '<img src="' . esc_url($use_case->project_image) . '" alt="Project Image">';
                            echo '</div>';
                        }
                        echo '<h2>' . esc_html($use_case->project_name) . '</h2>';
                        echo '<p>' . esc_html($use_case->problem) . '</p>';
                        echo '<div class="use-case-footer">';
                        echo '<span id="learn-more">Learn more</span>';
                        echo '<i class="fa-solid fa-arrow-right"></i>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="pagination">
                    <span id="prev-page"><i class="fa-solid fa-arrow-left"></i></span>
                    <span id="page-info"></span>
                    <span id="next-page"><i class="fa-solid fa-arrow-right"></i></span>
                </div>
            <?php

            // Get the contents of the output buffer
            return ob_get_clean();
        }
    }
}
?>