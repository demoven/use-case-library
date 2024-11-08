<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryDetailsPage')) {
    class UseCaseLibraryDetailsPage
    {
        public function __construct()
        {
            // Add details page
            add_action('admin_menu', array($this, 'add_details_page'));

            // Handle form submissions
            add_action('admin_post_publish_use_case', array($this, 'publish_use_case'));
            add_action('admin_post_unpublish_use_case', array($this, 'unpublish_use_case'));
            add_action('admin_post_delete_use_case', array($this, 'delete_use_case'));
            add_action('admin_enqueue_scripts', array($this, 'load_assets'));
        }

        /**
         * Add details page to the admin use case panel
         */
        public function add_details_page()
        {
            add_submenu_page(
                null, // Pas de menu parent
                'Use Case Details',
                'Use Case Details',
                'manage_options',
                'use-case-details',
                array($this, 'render_details_page') // Method to render the page
            );
        }

        /**
         * Load assets for the library
         */
        public function load_assets()
        {
            // Load CSS and JS files
            wp_enqueue_style(
                'use-case-library-style',
                plugin_dir_url(__FILE__) . '../assets/css/details.css',
                array(),
                '1.0',
                'all'
            );
        }

        /**
         * Render the details page
         */
        public function render_details_page()
        {
            // Check if post_id is set
            if (!isset($_GET['post_id'])) {
                return;
            }

            // Get the post ID
            $post_id = intval($_GET['post_id']); 

            // Get the post object
            $post = get_post($post_id); 

            // Check if post exists
            if (!$post) {
                echo '<div class="wrap"><h1>No use case found</h1></div>';
                return;
            }

            // Get post meta data
            $project_name = get_post_meta($post_id, 'project_name', true);
            $creator_name = get_post_meta($post_id, 'creator_name', true);
            $status = get_post_meta($post_id, 'status', true);
            $w_minor = get_post_meta($post_id, 'w_minor', true); // Get the w_minor meta data
            $email = get_post_meta($post_id, 'creator_email', true); // Get the email meta data
            $project_phase = get_post_meta($post_id, 'project_phase', true); // Get the project_phase meta data
            $value_chain = get_post_meta($post_id, 'value_chain', true); // Get the value_chain meta data
            $tech_innovations = get_post_meta($post_id, 'techn_innovations', true); // Get the techn_innovations meta data
            $tech_providers = get_post_meta($post_id, 'tech_providers', true); // Get the tech_providers meta data
            $themes = get_post_meta($post_id, 'themes', true); // Get the themes meta data
            $sdgs = get_post_meta($post_id, 'sdgs', true); // Get the sdgs meta data
            $positive_impact_sdgs = get_post_meta($post_id, 'positive_impact_sdgs', true); // Get the positive_impact_sdgs meta data
            $negative_impact_sdgs = get_post_meta($post_id, 'negative_impact_sdgs', true); // Get the negative_impact_sdgs meta data
            $project_background = get_post_meta($post_id, 'project_background', true); // Get the project_background meta data
            $problem = get_post_meta($post_id, 'problem', true); // Get the problem meta data
            $smart_goal = get_post_meta($post_id, 'smart_goal', true); // Get the smart_goal meta data
            $project_link = get_post_meta($post_id, 'project_link', true); // Get the project_link meta data
            $video_link = get_post_meta($post_id, 'video_link', true); // Get the video_link meta data
            $project_image = get_post_meta($post_id, 'project_image', true); // Get the project_image meta data
            $innovation_sectors = get_post_meta($post_id, 'innovation_sectors', true); // Get the innovation_sectors meta data

            // Ensure value_chain, themes, sdgs, and innovation_sectors are arrays
            if (!is_array($value_chain)) {
                $value_chain = array($value_chain);
            }
            if (!is_array($themes)) {
                $themes = array($themes);
            }
            if (!is_array($sdgs)) {
                $sdgs = array($sdgs);
            }
            if (!is_array($innovation_sectors)) {
                $innovation_sectors = array($innovation_sectors);
            }

            // Convert the arrays to comma-separated strings
            $value_chain_str = implode(', ', $value_chain);
            $themes_str = implode(', ', $themes);
            $sdgs_str = implode(', ', $sdgs);
            $innovation_sectors_str = implode(', ', $innovation_sectors);

            // Display the use case details
            ?>
            <div class="wrap">
                <h1>Use Case Details</h1>
                <p><strong>Status :</strong> <?php echo esc_html($status); ?></p>
                <p><strong>Project Name :</strong> <?php echo esc_html($project_name); ?></p>
                <p><strong>Project Owner :</strong> <?php echo esc_html($creator_name); ?></p>
                <p><strong>Email :</strong> <?php echo esc_html($email); ?></p> 
                <p><strong>Windesheim Minor :</strong> <?php echo esc_html($w_minor); ?></p> 
                <p><strong>Project Phase :</strong> <?php echo esc_html($project_phase); ?></p> 
                <p><strong>Value Chain :</strong> <?php echo esc_html($value_chain_str); ?></p>
                <p><strong>Technological Innovations :</strong> <?php echo esc_html($tech_innovations); ?></p>
                <p><strong>Technology Providers :</strong> <?php echo esc_html($tech_providers); ?></p>
                <p><strong>Themes :</strong> <?php echo esc_html($themes_str); ?></p>
                <p><strong>Sustainable Development Goals :</strong> <?php echo esc_html($sdgs_str); ?></p>
                <p><strong>Positive Impact on SDGs :</strong> <?php echo esc_html($positive_impact_sdgs); ?></p>
                <p><strong>Negative Impact on SDGs :</strong> <?php echo esc_html($negative_impact_sdgs); ?></p>
                <p><strong>Project Background :</strong> <?php echo esc_html($project_background); ?></p>
                <p><strong>Problem :</strong> <?php echo esc_html($problem); ?></p> <
                <p><strong>SMART Goal :</strong> <?php echo esc_html($smart_goal); ?></p> <
                <p><strong>Project Link :</strong> <?php echo esc_html($project_link); ?></p> 
                <p><strong>Video Link :</strong> <?php echo esc_html($video_link); ?></p> 
                <p><strong>Innovation Sectors :</strong> <?php echo esc_html($innovation_sectors_str); ?></p> 
                <p><strong>Project Image :</strong></p>
                <?php if ($project_image): ?>
                    <img src="<?php echo esc_url($project_image); ?>" alt="Project Image" style="max-width: 100%; height: auto;">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
                <?php

                // Display the unpublish button
                if ($status === 'published') {
                    ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="unpublish_use_case">
                        <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                        <button type="submit" class="button button-secondary">Unpublish</button>
                    </form>
                    <?php
                } else {

                    // Display the publish button
                    ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="publish_use_case">
                        <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                        <button type="submit" class="button button-primary">Publish</button>
                    </form>
                    <?php
                }
                // Display the delete button
                ?>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" onsubmit="return confirmDeletion();">
                    <input type="hidden" name="action" value="delete_use_case">
                    <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                    <button type="submit" class="button button-danger">Delete</button>
                </form>
            </div>
            <script type="text/javascript">
                function confirmDeletion() {
                    return confirm('Are you sure you want to delete this use case?');
                }
            </script>
            <?php
        }

        /**
         * Publish a use case
         */
        public function publish_use_case()
        {
            // Check if post_id is set
            if (!isset($_POST['post_id'])) {

                // Redirect to the details page
                wp_redirect(admin_url('admin.php?page=use-case-details')); 
                exit;
            }

            // Get the post ID
            $post_id = intval($_POST['post_id']);

            // Update the post status
            update_post_meta($post_id, 'status', 'published');

            // Redirect to the details page
            wp_redirect(admin_url('admin.php?page=use-case-details&post_id=' . $post_id));
            exit;
        }

        /**
         * Unpublish a use case
         */
        public function unpublish_use_case()
        {
            // Check if post_id is set
            if (!isset($_POST['post_id'])) {

                // Redirect to the details page
                wp_redirect(admin_url('admin.php?page=use-case-details'));
                exit;
            }
            // Get the post ID
            $post_id = intval($_POST['post_id']);

            // Update the post status to 'on hold'
            update_post_meta($post_id, 'status', 'on hold');

            // Redirect to the details page
            wp_redirect(admin_url('admin.php?page=use-case-details&post_id=' . $post_id));
            exit;
        }

        /**
         * Delete a use case
         */
        public function delete_use_case()
        {
            // Check if post_id is set
            if (!isset($_POST['post_id'])) {

                // Redirect to the details page
                wp_redirect(admin_url('admin.php?page=use-case-details'));
                exit;
            }

            // Get the post ID
            $post_id = intval($_POST['post_id']);

            // Delete the post
            wp_delete_post($post_id, true);
            
            // Redirect to the use case library
            wp_redirect(admin_url('edit.php?post_type=use-case-library'));
            exit;
        }
    }
}