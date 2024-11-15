<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryDetailsPage')) {
    class UseCaseLibraryDetailsPage {
        public function __construct() {
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
         * Load assets for the library
         */
        public function load_assets() {
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
        public function render_details_page() {
            // Check if use_case_id is set
            if (!isset($_GET['use_case_id'])) {
                return;
            }

            // Get the use case ID
            $use_case_id = intval($_GET['use_case_id']);

            // Get the use case data from the custom table
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';
            $use_case = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $use_case_id));

            // Check if use case exists
            if (!$use_case) {
                echo '<div class="wrap"><h1>No use case found</h1></div>';
                return;
            }

            // Convert serialized fields back to arrays
            $value_chain = maybe_unserialize($use_case->value_chain);
            $themes = maybe_unserialize($use_case->themes);
            $sdgs = maybe_unserialize($use_case->sdgs);

            // Convert the arrays to comma-separated strings
            $value_chain_str = implode(', ', $value_chain);
            $themes_str = implode(', ', $themes);
            $sdgs_str = implode(', ', $sdgs);
            $innovation_sectors_str = $use_case->innovation_sectors;

            // Display the use case details
            ?>
            <div class="wrap">
                <h1>Use Case Details</h1>
                <p><strong>Status :</strong> <?php echo esc_html($use_case->published ? 'published' : 'on hold'); ?></p>
                <p><strong>Project Name :</strong> <?php echo esc_html($use_case->project_name); ?></p>
                <p><strong>Project Owner :</strong> <?php echo esc_html($use_case->name); ?></p>
                <p><strong>Email :</strong> <?php echo esc_html($use_case->creator_email); ?></p>
                <p><strong>Windesheim Minor :</strong> <?php echo esc_html($use_case->w_minor); ?></p>
                <p><strong>Project Phase :</strong> <?php echo esc_html($use_case->project_phase); ?></p>
                <p><strong>Value Chain :</strong> <?php echo esc_html($value_chain_str); ?></p>
                <p><strong>Technological Innovations :</strong> <?php echo esc_html($use_case->techn_innovations); ?></p>
                <p><strong>Technology Providers :</strong> <?php echo esc_html($use_case->tech_providers); ?></p>
                <p><strong>Themes :</strong> <?php echo esc_html($themes_str); ?></p>
                <p><strong>Sustainable Development Goals :</strong> <?php echo esc_html($sdgs_str); ?></p>
                <p><strong>Positive Impact on SDGs :</strong> <?php echo esc_html($use_case->positive_impact_sdgs); ?></p>
                <p><strong>Negative Impact on SDGs :</strong> <?php echo esc_html($use_case->negative_impact_sdgs); ?></p>
                <p><strong>Project Background :</strong> <?php echo esc_html($use_case->project_background); ?></p>
                <p><strong>Problem :</strong> <?php echo esc_html($use_case->problem); ?></p>
                <p><strong>SMART Goal :</strong> <?php echo esc_html($use_case->smart_goal); ?></p>
                <p><strong>Project Link :</strong> <?php echo esc_html($use_case->project_link); ?></p>
                <p><strong>Video Link :</strong> <?php echo esc_html($use_case->video_link); ?></p>
                <p><strong>Innovation Sectors :</strong> <?php echo esc_html($innovation_sectors_str); ?></p>
                <p><strong>Project Image :</strong></p>
                <?php if ($use_case->project_image): ?>
                    <img src="<?php echo esc_url($use_case->project_image); ?>" alt="Project Image" style="max-width: 100%; height: auto;">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
                <?php

                // Display the unpublish button
                if ($use_case->published) {
                    ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="unpublish_use_case">
                        <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                        <button type="submit" class="button button-secondary">Unpublish</button>
                    </form>
                    <?php
                } else {
                    // Display the publish button
                    ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="publish_use_case">
                        <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                        <button type="submit" class="button button-primary">Publish</button>
                    </form>
                    <?php
                }
                // Display the delete button
                ?>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" onsubmit="return confirmDeletion();">
                    <input type="hidden" name="action" value="delete_use_case">
                    <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
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
        public function publish_use_case() {
            // Check if use_case_id is set
            if (!isset($_POST['use_case_id'])) {
                // Redirect to the details page
                wp_redirect(admin_url('admin.php?page=use-case-details'));
                exit;
            }

            // Get the use case ID
            $use_case_id = intval($_POST['use_case_id']);

            // Update the use case status to published
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';
            $wpdb->update($table_name, ['published' => 1], ['id' => $use_case_id]);

            // Redirect to the details page
            wp_redirect(admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case_id));
            exit;
        }

        /**
         * Unpublish a use case
         */
        public function unpublish_use_case() {
            // Check if use_case_id is set
            if (!isset($_POST['use_case_id'])) {
                // Redirect to the details page
                wp_redirect(admin_url('admin.php?page=use-case-details'));
                exit;
            }

            // Get the use case ID
            $use_case_id = intval($_POST['use_case_id']);

            // Update the use case status to on hold
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';
            $wpdb->update($table_name, ['published' => 0], ['id' => $use_case_id]);

            // Redirect to the details page
            wp_redirect(admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case_id));
            exit;
        }

        /**
         * Delete a use case
         */
        public function delete_use_case() {
            // Check if use_case_id is set
            if (!isset($_POST['use_case_id'])) {
                // Redirect to the details page
                wp_redirect(admin_url('admin.php?page=use-case-details'));
                exit;
            }

            // Get the use case ID
            $use_case_id = intval($_POST['use_case_id']);

            // Delete the use case from the custom table
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';
            $wpdb->delete($table_name, ['id' => $use_case_id]);

            // Redirect to the use case library
            wp_redirect(admin_url('admin.php?page=use-case-library'));
            exit;
        }
    }
}