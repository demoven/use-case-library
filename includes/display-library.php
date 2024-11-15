<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryDisplay')) {
    class UseCaseLibraryDisplay {
        public function __construct() {

            // Add shortcode
            add_shortcode('display_use_cases', array($this, 'display_use_cases_shortcode'));

            // Load assets
            add_action('wp_enqueue_scripts', array($this, 'load_assets'));
        }

        /**
         * Load assets for the library
         */
        public function load_assets() {
            // Load CSS and JS files
            wp_enqueue_style(
                'use-case-library-style',
                plugin_dir_url(__FILE__) . '../assets/css/library.css',
                array(),
                '1.0',
                'all'
            );
            wp_enqueue_script(
                'use-case-library-script',
                plugin_dir_url(__FILE__) . '../assets/js/library.js',
                array('jquery'),
                '1.0',
                true
            );
        }

        /**
         * Shortcode to display published use cases.
         *
         * @return string HTML output of the published use cases.
         */
        public function display_use_cases_shortcode() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';

            // Get the selected filter values
            $selected_minors = isset($_GET['w_minor']) ? array_map('sanitize_text_field', $_GET['w_minor']) : array();
            $selected_value_chains = isset($_GET['value_chain']) ? array_map('sanitize_text_field', $_GET['value_chain']) : array();
            $selected_themes = isset($_GET['themes']) ? array_map('sanitize_text_field', $_GET['themes']) : array();
            $selected_sdgs = isset($_GET['sdgs']) ? array_map('sanitize_text_field', $_GET['sdgs']) : array();
            $selected_innovation_sectors = isset($_GET['innovation_sectors']) ? array_map('sanitize_text_field', $_GET['innovation_sectors']) : array();

            // Build the query to get all published use cases
            $query = "SELECT * FROM $table_name WHERE published = 1";
            $conditions = [];

            // Add filter for Windesheim Minor if selected
            if (!empty($selected_minors)) {
                $placeholders = implode(',', array_fill(0, count($selected_minors), '%s'));
                $conditions[] = "w_minor IN ($placeholders)";
            }

            // Add filter for Value Chain if selected
            if (!empty($selected_value_chains)) {
                $value_chain_conditions = [];
                foreach ($selected_value_chains as $value_chain) {
                    $value_chain_conditions[] = "value_chain LIKE %s";
                }
                $conditions[] = '(' . implode(' OR ', $value_chain_conditions) . ')';
            }

            // Add filter for Themes if selected
            if (!empty($selected_themes)) {
                $theme_conditions = [];
                foreach ($selected_themes as $theme) {
                    $theme_conditions[] = "themes LIKE %s";
                }
                $conditions[] = '(' . implode(' OR ', $theme_conditions) . ')';
            }

            // Add filter for SDGs if selected
            if (!empty($selected_sdgs)) {
                $sdg_conditions = [];
                foreach ($selected_sdgs as $sdg) {
                    $sdg_conditions[] = "sdgs LIKE %s";
                }
                $conditions[] = '(' . implode(' OR ', $sdg_conditions) . ')';
            }

            // Add filter for Innovation Sectors if selected
            if (!empty($selected_innovation_sectors)) {
                $placeholders = implode(',', array_fill(0, count($selected_innovation_sectors), '%s'));
                $conditions[] = "innovation_sectors IN ($placeholders)";
            }

            // Combine conditions into the query
            if (!empty($conditions)) {
                $query .= ' AND ' . implode(' AND ', $conditions);
            }

            $query .= ' ORDER BY id DESC';

            // Prepare the query with the selected filter values
            $prepared_query = $wpdb->prepare($query, array_merge(
                $selected_minors,
                array_map(function($value) { return '%' . $value . '%'; }, $selected_value_chains),
                array_map(function($value) { return '%' . $value . '%'; }, $selected_themes),
                array_map(function($value) { return '%' . $value . '%'; }, $selected_sdgs),
                $selected_innovation_sectors
            ));

            // Execute the query
            $use_cases = $wpdb->get_results($prepared_query);

            // Start the output buffer
            ob_start();

            // Display the filter form and use cases
            ?>
            <div class="use-case-container">
                <div class="filter-container">
                <h2>Filter Use Cases <i class="fa-solid fa-filter"></i></h2>
                <form id="filter-form" method="GET" action="" onchange="this.submit();">
                    <!-- Filter by Windesheim Minor -->
                    <div class="collapsible">
                        <button type="button" class="collapsible-button">Windesheim Minor<i class="fa-solid fa-chevron-down"></i></button>
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
                                    $checked = in_array($minor, $selected_minors) ? 'checked' : '';
                                    echo '<div class="minor-checkbox">';
                                    echo '<input type="checkbox" name="w_minor[]" value="' . esc_attr($minor) . '" ' . $checked . '> ' . esc_html($minor);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by Value Chain -->
                    <div class="collapsible">
                        <button type="button" class="collapsible-button">Value Chain<i class="fa-solid fa-chevron-down"></i></button>
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
                                    $checked = in_array($value_chain, $selected_value_chains) ? 'checked' : '';
                                    echo '<div class="value-chain-checkbox">';
                                    echo '<input type="checkbox" name="value_chain[]" value="' . esc_attr($value_chain) . '" ' . $checked . '> ' . esc_html($value_chain);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by Themes -->
                    <div class="collapsible">
                        <button type="button" class="collapsible-button">Themes<i class="fa-solid fa-chevron-down"></i></button>
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
                                    $checked = in_array($theme, $selected_themes) ? 'checked' : '';
                                    echo '<div class="theme-checkbox">';
                                    echo '<input type="checkbox" name="themes[]" value="' . esc_attr($theme) . '" ' . $checked . '> ' . esc_html($theme);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by SDGs -->
                    <div class="collapsible">
                        <button type="button" class="collapsible-button">SDGs<i class="fa-solid fa-chevron-down"></i></button>
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
                                    $checked = in_array($sdg, $selected_sdgs) ? 'checked' : '';
                                    echo '<div class="sdg-checkbox">';
                                    echo '<input type="checkbox" name="sdgs[]" value="' . esc_attr($sdg) . '" ' . $checked . '> ' . esc_html($sdg);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by Innovation Sectors -->
                    <div class="collapsible">
                        <button type="button" class="collapsible-button">Innovation Sectors<i class="fa-solid fa-chevron-down"></i></button>
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
                                    $checked = in_array($sector, $selected_innovation_sectors) ? 'checked' : '';
                                    echo '<div class="innovation-sector-checkbox">';
                                    echo '<input type="checkbox" name="innovation_sectors[]" value="' . esc_attr($sector) . '" ' . $checked . '> ' . esc_html($sector);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
                </div>

                <!-- Display the use cases -->
                <?php
                if ($use_cases) {
                    echo '<div class="use-cases">';
                    foreach ($use_cases as $use_case) {
                        $project_name = esc_html($use_case->project_name);
                        $smart_goal = esc_html($use_case->smart_goal);
                        $project_image = esc_url($use_case->project_image);
                        $post_id = esc_html($use_case->id);

                        echo '<div class="use-case">';
                        if ($project_image) {
                            echo '<div class="image-wrapper">';
                            echo '<img src="' . $project_image . '" alt="Project Image">';
                            echo '</div>';
                        }
                        echo '<h2><a href="' . esc_url(home_url('/use-case-details/?post_id=' . $post_id)) . '" target="_blank">' . $project_name . '</a></h2>';
                        echo '<p>' . $smart_goal . '</p>';
                        echo '<div class="use-case-footer">';
                        echo '<a id="learn-more" href="' . esc_url(home_url('/use-case-details/?post_id=' . $post_id)) . '" target="_blank">Learn more</a>';
                        echo '<i class="fa-solid fa-arrow-right"></i>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>No use case found</p>';
                }
                ?>
            </div>
            <?php

            // Get the contents of the output buffer
            return ob_get_clean();
        }
    }
}
?>