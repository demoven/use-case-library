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
        }

        /**
         * Shortcode to display published use cases.
         *
         * @return string HTML output of the published use cases.
         */
        public function display_use_cases_shortcode() {

            // Get the selected filter values
            $selected_minors = isset($_GET['w_minor']) ? array_map('sanitize_text_field', $_GET['w_minor']) : array();
            $selected_value_chains = isset($_GET['value_chain']) ? array_map('sanitize_text_field', $_GET['value_chain']) : array();
            $selected_themes = isset($_GET['themes']) ? array_map('sanitize_text_field', $_GET['themes']) : array();
            $selected_sdgs = isset($_GET['sdgs']) ? array_map('sanitize_text_field', $_GET['sdgs']) : array();
            $selected_innovation_sectors = isset($_GET['innovation_sectors']) ? array_map('sanitize_text_field', $_GET['innovation_sectors']) : array();

            // Query to get all published use cases
            $args = array(
                'post_type' => 'use-case-library', // Custom post type
                'post_status' => 'publish',
                'meta_query' => array( // Query for the custom field 'status'
                    array(
                        'key' => 'status',
                        'value' => 'published',
                        'compare' => '='
                    )
                ),
                'posts_per_page' => -1 // Get all posts
            );

            // Add filter for Windesheim Minor if selected
            if (!empty($selected_minors)) {
                $args['meta_query'][] = array(
                    'key' => 'w_minor',
                    'value' => $selected_minors,
                    'compare' => 'IN'
                );
            }

            // Add filter for Value Chain if selected
            if (!empty($selected_value_chains)) {
                $meta_query = array('relation' => 'OR');
                foreach ($selected_value_chains as $value_chain) {
                    $meta_query[] = array(
                        'key' => 'value_chain',
                        'value' => '"' . $value_chain . '"',
                        'compare' => 'LIKE'
                    );
                }
                $args['meta_query'][] = $meta_query;
            }

            // Add filter for Themes if selected
            if (!empty($selected_themes)) {
                $meta_query = array('relation' => 'OR');
                foreach ($selected_themes as $theme) {
                    $meta_query[] = array(
                        'key' => 'themes',
                        'value' => '"' . $theme . '"',
                        'compare' => 'LIKE'
                    );
                }
                $args['meta_query'][] = $meta_query;
            }

            // Add filter for SDGs if selected
            if (!empty($selected_sdgs)) {
                $meta_query = array('relation' => 'OR');
                foreach ($selected_sdgs as $sdg) {
                    $meta_query[] = array(
                        'key' => 'sdgs',
                        'value' => '"' . $sdg . '"',
                        'compare' => 'LIKE'
                    );
                }
                $args['meta_query'][] = $meta_query;
            }

            // Add filter for Innovation Sectors if selected
            if (!empty($selected_innovation_sectors)) {
                $args['meta_query'][] = array(
                    'key' => 'innovation_sectors',
                    'value' => $selected_innovation_sectors,
                    'compare' => 'IN'
                );
            }

            // Get the published use cases
            $query = new WP_Query($args);

            //add a container for the content
            $output = '<div class="use-case-container">';

            // Display the filter form
            $output .= '<form id="filter-form" method="GET" action="" onchange="this.submit();">';

            // Filter by Windesheim Minor
            $output .= '<div id="windesheim-minor">';
            $output .= '<label>Filter by Windesheim Minor:</label>';
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

            // Display the checkboxes for the Windesheim Minors
            foreach ($minors as $minor) {

                // Check if the minor is selected
                $checked = in_array($minor, $selected_minors) ? 'checked' : '';

                // Display the checkbox
                $output .= '<div class="minor-checkbox">';
                $output .= '<input type="checkbox" name="w_minor[]" value="' . esc_attr($minor) . '" ' . $checked . '> ' . esc_html($minor) ;
                $output .= '</div>';
            }
            $output .= '</div>';


            // Filter by Value Chain
            $output .= '<div id="value-chain">';
            $output .= '<label>Filter by Value Chain:</label>';
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

            // Display the checkboxes for the Value Chains
            foreach ($value_chains as $value_chain) {

                // Check if the value chain is selected
                $checked = in_array($value_chain, $selected_value_chains) ? 'checked' : '';

                // Display the checkbox
                $output .= '<div class="value-chain-checkbox">';
                $output .= '<input type="checkbox" name="value_chain[]" value="' . esc_attr($value_chain) . '" ' . $checked . '> ' . esc_html($value_chain) ;
                $output .= '</div>';
            }
            $output .= '</div>';

            // Filter by Themes
            $output .= '<div id="themes">';
            $output .= '<label>Filter by Themes:</label>';
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

            // Display the checkboxes for the Themes
            foreach ($themes as $theme) {

                // Check if the theme is selected
                $checked = in_array($theme, $selected_themes) ? 'checked' : '';

                // Display the checkbox
                $output .= '<div class="theme-checkbox">';
                $output .= '<input type="checkbox" name="themes[]" value="' . esc_attr($theme) . '" ' . $checked . '> ' . esc_html($theme) ;
                $output .= '</div>';
            }
            $output .= '</div>';

            // Filter by SDGs
            $output .= '<div id="sdgs">';
            $output .= '<label>Filter by SDGs:</label>';
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

            // Display the checkboxes for the SDGs
            foreach ($sdgs as $sdg) {

                // Check if the SDG is selected
                $checked = in_array($sdg, $selected_sdgs) ? 'checked' : '';

                // Display the checkbox
                $output .= '<div class="sdg-checkbox">';
                $output .= '<input type="checkbox" name="sdgs[]" value="' . esc_attr($sdg) . '" ' . $checked . '> ' . esc_html($sdg) ;
                $output .= '</div>';
            }
            $output .= '</div>';

            // Filter by Innovation Sectors
            $output .= '<div id="innovation-sectors">';
            $output .= '<label>Filter by Innovation Sectors:</label>';
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

            // Display the checkboxes for the Innovation Sectors
            foreach ($innovation_sectors as $sector) {

                // Check if the sector is selected
                $checked = in_array($sector, $selected_innovation_sectors) ? 'checked' : '';

                // Display the checkbox
                $output .= '<div class="innovation-sector-checkbox">';
                $output .= '<input type="checkbox" name="innovation_sectors[]" value="' . esc_attr($sector) . '" ' . $checked . '> ' . esc_html($sector) ;
                $output .= '</div>';
            }
            $output .= '</div>';

            // End the form
            $output .= '</form>';

            if ($query->have_posts()) {

                // Start the use cases div
                $output .= '<div class="use-cases">';
                // Loop through the published use cases
                while ($query->have_posts()) {
                    // Get the post
                    $query->the_post();
                    
                    // Get the custom fields
                    $project_name = get_post_meta(get_the_ID(), 'project_name', true);
                    $smart_goal = get_post_meta(get_the_ID(), 'smart_goal', true);
                    $project_image = get_post_meta(get_the_ID(), 'project_image', true); 
                    $post_id = get_the_ID();

                    // Start the output buffer
                    ob_start(); 
                    ?>
                    <div class="use-case">
                        <?php if ($project_image): ?>
                            <!-- Style to change here -->
                            <div class="image-wrapper">
                                <img src="<?php echo esc_url($project_image); ?>" alt="Project Image">
                            </div>
                        <?php endif; ?>
                        <h2><a href="<?php echo esc_url(home_url('/use-case-details/?post_id=' . $post_id)); ?>" target="_blank"><?php echo esc_html($project_name); ?></a></h2>
                        <p><?php echo esc_html($smart_goal); ?></p>
                        <div class="use-case-footer">
                            <a id="learn-more" href="<?php echo esc_url(home_url('/use-case-details/?post_id=' . $post_id)); ?>" target="_blank">Learn more</a>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                    <?php
                    $output .= ob_get_clean(); // Get the contents of the output buffer
                }
                $output .= '</div>';
                $output .= '</div>';
                
                // Reset the post data
                wp_reset_postdata();
            } else {
                $output .= '<p>No use case found</p>';
                $output .= '</div>';
            }
            return $output;
        }
    }
}
