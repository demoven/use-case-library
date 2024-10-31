<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if(!class_exists('UseCaseLibraryDisplay')) 
{
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

            // Get the published use cases
            $query = new WP_Query($args);

            // Display the published use cases
            if ($query->have_posts()) 
            {
                // Start the output buffer
                $output = '<div class="use-cases">';

                // Loop through the published use cases
                while ($query->have_posts()) {
                    // Get the post
                    $query->the_post();
                    // Get the custom fields
                    $project_name = get_post_meta(get_the_ID(), 'project_name', true);
                    $creator_name = get_post_meta(get_the_ID(), 'creator_name', true);
                    ob_start(); // Start the output buffer
                    ?>
                    <div class="use-case">
                        <h2><?php echo esc_html($project_name); ?></h2>
                        <p><strong>Propriétaire du projet :</strong> <?php echo esc_html($creator_name); ?></p>
                    </div>
                    <?php
                    $output .= ob_get_clean(); // Get the contents of the output buffer
                }
                // End the output buffer
                $output .= '</div>';
                wp_reset_postdata();
            } else {
                $output = '<p>Aucun use case publié trouvé.</p>';
            }

            return $output;
        }
    }
}

