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

            // Get the published use cases
            $query = new WP_Query($args);

            // Start the output buffer
            $output = '<form id="filter-form" method="GET" action="" onchange="this.submit();">';
            $output .= '<label>Filter by Windesheim Minor:</label><br>';
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
                $output .= '<input type="checkbox" name="w_minor[]" value="' . esc_attr($minor) . '" ' . $checked . '> ' . esc_html($minor) . '<br>';
            }
            $output .= '</form>';

            if ($query->have_posts()) {
                $output .= '<div class="use-cases">';
                // Loop through the published use cases
                while ($query->have_posts()) {
                    // Get the post
                    $query->the_post();
                    // Get the custom fields
                    
                    $project_name = get_post_meta(get_the_ID(), 'project_name', true);
                    $smart_goal = get_post_meta(get_the_ID(), 'smart_goal', true);
                    $project_image = get_post_meta(get_the_ID(), 'project_image', true); // Get the project_image meta data
                    $post_id = get_the_ID();
                    // Start the output buffer
                    
                    ob_start(); 
                    ?>
                    <div class="use-case">
                        <?php if ($project_image): ?>
                            <img src="<?php echo esc_url($project_image); ?>" alt="Project Image" style="max-width: 100%; height: auto;">
                        <?php endif; ?>
                        <h2><a href="<?php echo esc_url(home_url('/use-case-details/?post_id=' . $post_id)); ?>" target="_blank"><?php echo esc_html($project_name); ?></a></h2>
                        <p><?php echo esc_html($smart_goal); ?></p>
                    </div>
                    <?php
                    $output .= ob_get_clean(); // Get the contents of the output buffer
                }
                $output .= '</div>';
                wp_reset_postdata();
            } else {
                $output .= '<p>No use case found</p>';
            }
            return $output;
        }
    }
}
