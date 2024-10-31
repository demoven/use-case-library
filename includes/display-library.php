<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UseCaseLibraryDisplay {

    public function __construct() {
        // Add shortcode
        add_shortcode('display_use_cases', array($this, 'display_use_cases_shortcode'));
    }

    /**
     * Shortcode to display published use cases.
     *
     * @return string HTML output of the published use cases.
     */
    public function display_use_cases_shortcode() {
        $args = array(
            'post_type' => 'use-case-library',
            'post_status' => 'publish',
            'meta_key' => 'status',
            'meta_value' => 'published',
            'posts_per_page' => -1
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $output = '<div class="use-cases">';
            while ($query->have_posts()) {
                $query->the_post();
                $project_name = get_post_meta(get_the_ID(), 'project_name', true);
                $creator_name = get_post_meta(get_the_ID(), 'creator_name', true);
                $output .= '<div class="use-case">';
                $output .= '<h2>' . esc_html($project_name) . '</h2>';
                $output .= '<p><strong>Propriétaire du projet :</strong> ' . esc_html($creator_name) . '</p>';
                $output .= '</div>';
            }
            $output .= '</div>';
            wp_reset_postdata();
        } else {
            $output = '<p>Aucun use case publié trouvé.</p>';
        }

        return $output;
    }
}

new UseCaseLibraryDisplay();