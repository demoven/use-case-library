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

            // Display the use case details
            ?>
            <div class="wrap">
                <h1>Use Case Details</h1>
                <p><strong>Project Name :</strong> <?php echo esc_html($project_name); ?></p>
                <p><strong>Project Owner :</strong> <?php echo esc_html($creator_name); ?></p>
                <p><strong>Status :</strong> <?php echo esc_html($status); ?></p>
            <?php

            // Display the unpublish button
            if ($status === 'published') 
            {
            ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="unpublish_use_case">
                        <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                        <button type="submit" class="button button-secondary">Unpublish</button>
                    </form>
            <?php
            // Display the publish button
            } else {
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
