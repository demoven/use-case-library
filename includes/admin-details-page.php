<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryDetailsPage'))
{
    class UseCaseLibraryDetailsPage {
        public function __construct() {
            // Add details page
            add_action('admin_menu', array($this, 'add_details_page'));

            // Handle form submissions
            add_action('admin_post_publish_use_case', array($this, 'publish_use_case'));
            add_action('admin_post_unpublish_use_case', array($this, 'unpublish_use_case'));
            add_action('admin_post_delete_use_case', array($this, 'delete_use_case'));
        }

        /**
         * Add details page to the admin use case panel
         */
        public function add_details_page() {
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
         * Render the details page
         */
        
         
        public function render_details_page() {
            // Check if post_id is set
            if (!isset($_GET['post_id'])) {
                return;
            }
            $post_id = intval($_GET['post_id']); // Get the post ID
            $post = get_post($post_id); // Get the post object
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

            // Ensure value_chain is an array
            if (!is_array($value_chain)) {
                $value_chain = array($value_chain);
            }
            if (!is_array($themes)) {
                $themes = array($themes);
            }
            if (!is_array($sdgs)) {
                $sdgs = array($sdgs);
            }

            // Convert the value_chain array to a comma-separated string
            $value_chain_str = implode(', ', $value_chain);
            $themes_str = implode(', ', $themes);
            $sdgs_str = implode(', ', $sdgs);

            // Display the use case details
            ?>
            <div class="wrap">
                <h1>Use Case Details</h1>
                <p><strong>Status :</strong> <?php echo esc_html($status); ?></p>
                <p><strong>Project Name :</strong> <?php echo esc_html($project_name); ?></p>
                <p><strong>Project Owner :</strong> <?php echo esc_html($creator_name); ?></p>
                <p><strong>Email :</strong> <?php echo esc_html($email); ?></p> <!-- Display the email -->
                <p><strong>Windesheim Minor :</strong> <?php echo esc_html($w_minor); ?></p> <!-- Display the w_minor -->
                <p><strong>Project Phase :</strong> <?php echo esc_html($project_phase); ?></p> <!-- Display the project_phase -->
                <p><strong>Value Chain :</strong> <?php echo esc_html($value_chain_str); ?></p> <!-- Display the value_chain as a comma-separated string -->
                <p><strong>Technological Innovations :</strong> <?php echo esc_html($tech_innovations); ?></p> <!-- Display the tech_innovations -->
                <p><strong>Technology Providers :</strong> <?php echo esc_html($tech_providers); ?></p> <!-- Display the tech_providers -->
                <p><strong>Themes :</strong> <?php echo esc_html($themes_str); ?></p> <!-- Display the themes as a comma-separated string -->
                <p><strong>Sustainable Development Goals :</strong> <?php echo esc_html($sdgs_str); ?></p> <!-- Display the sdgs as a comma-separated string -->
                <p><strong>Positive Impact on SDGs :</strong> <?php echo esc_html($positive_impact_sdgs); ?></p> <!-- Display the positive_impact_sdgs -->
                <p><strong>Negative Impact on SDGs :</strong> <?php echo esc_html($negative_impact_sdgs); ?></p> <!-- Display the negative_impact_sdgs -->
                <p><strong>Project Background :</strong> <?php echo esc_html($project_background); ?></p> <!-- Display the project_background -->
                <p><strong>Problem :</strong> <?php echo esc_html($problem); ?></p> <!-- Display the problem -->
                <p><strong>SMART Goal :</strong> <?php echo esc_html($smart_goal); ?></p> <!-- Display the smart_goal -->
                <p><strong>Project Link :</strong> <?php echo esc_html($project_link); ?></p> <!-- Display the project_link -->
                <p><strong>Video Link :</strong> <?php echo esc_html($video_link); ?></p> <!-- Display the video_link -->
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
            ?>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="publish_use_case">
                    <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                    <button type="submit" class="button button-primary">Publish</button>
                </form>
            <?php
            }
            ?>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" onsubmit="return confirmDeletion();">
                    <input type="hidden" name="action" value="delete_use_case">
                    <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                    <button type="submit" class="button button-danger">Delete</button>
                </form>
                <script type="text/javascript">
                    function confirmDeletion() {
                        return confirm('Are you sure you want to delete this use case?');
                    }
                </script>
            </div>
            <?php
        }

        /**
         * Publish a use case
         */
        public function publish_use_case() {
            // Check if post_id is set
            if (!isset($_POST['post_id'])) {
                wp_redirect(admin_url('admin.php?page=use-case-details')); // Redirect to the details page
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
        public function unpublish_use_case() {
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
        public function delete_use_case() {
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
