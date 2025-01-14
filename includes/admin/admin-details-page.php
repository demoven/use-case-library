<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryDetailsPage')) {
    class UseCaseLibraryDetailsPage
    {
        /**
         * The constructor function sets up various actions for handling use case details in a
         * WordPress admin panel.
         */
        public function __construct()
        {
            // Add details page
            add_action('admin_menu', array($this, 'add_details_page'));
            // Handle form submissions
            add_action('admin_post_publish_use_case', array($this, 'publish_use_case'));
            add_action('admin_post_unpublish_use_case', array($this, 'unpublish_use_case'));
            add_action('admin_post_delete_use_case', array($this, 'delete_use_case'));
            add_action('admin_enqueue_scripts', array($this, 'load_assets'));
            add_action('admin_post_delete_use_case_image', array($this, 'delete_use_case_image'));
            add_action('admin_post_upload_use_case_image', array($this, 'upload_use_case_image'));
        }

       
        /**
         * The function `add_details_page` adds a submenu page for displaying use case details in a
         * WordPress admin menu.
         */
        public function add_details_page()
        {
            add_submenu_page(
                null,
                'Use Case Details',
                'Use Case Details',
                'manage_options',
                'use-case-details',
                array($this, 'render_details_page') // Method to render the page
            );
        }

        /**
         * The function `load_assets` in PHP loads CSS and JS files for a WordPress plugin
         */
        public function load_assets()
        {
            // Load CSS and JS files
            wp_enqueue_style(
                'use-case-library-color-palette',
                plugin_dir_url(__FILE__) . '../../assets/css/color-palette.css',
                array(),
                '1.0',
                'all'
            );
            wp_enqueue_style(
                'use-case-library-style',
                plugin_dir_url(__FILE__) . '../../assets/css/details.css',
                array(),
                '1.0',
                'all'
            );
        }
        /**
         * The `render_details_page` function in PHP renders the details of a use case, including
         * displaying use case data, managing images, and providing options to publish, unpublish, or
         * delete the use case.
         * 
         * @return The `render_details_page` function is responsible for rendering the details of a
         * specific use case. It first checks if the current user has the necessary permissions to
         * access the page. Then, it checks if the `use_case_id` is set in the URL parameters. If not
         * set, the function returns early.
         */
        public function render_details_page()
        {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'textdomain'));
            }
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

            $nonce = wp_create_nonce('use_case_nonce' . $use_case_id);

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
                <div class="use-case-image">
                    <?php if ($use_case->project_image): ?>
                        <img src="<?php echo esc_url($use_case->project_image); ?>" alt="Project Image"
                             style="max-width: 100%; height: auto;">
                    <?php else: ?>
                        <p>No image uploaded.</p>
                    <?php endif; ?>
                </div>
                <?php if ($use_case->project_image): ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <?php wp_nonce_field('delete_use_case_image', 'delete_use_case_image_nonce'); ?>
                        <input type="hidden" name="action" value="delete_use_case_image">
                        <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                        <button type="submit" class="button button-danger">Delete image</button>
                    </form>
                    <!-- If there is no image, the button will be transform to an adding image button -->
                <?php else: ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>"
                          enctype="multipart/form-data">
                        <?php wp_nonce_field('upload_use_case_image', 'upload_use_case_image_nonce'); ?>
                        <input type="hidden" name="action" value="upload_use_case_image">
                        <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                        <div class="upload-image">
                            <input type="file" name="use_case_image" accept="image/PNG, image/JPEG" required>
                            <button type="submit" class="button button-primary">Add image</button>
                        </div>
                    </form>
                <?php endif; ?>
                <div class="use-case-content">
                    <p><strong>Status :</strong> <?php echo esc_html($use_case->published ? 'published' : 'on hold'); ?>
                    </p>
                    <p><strong>Project Name :</strong> <?php echo esc_html($use_case->project_name); ?></p>
                    <p><strong>Project Owner :</strong> <?php echo esc_html($use_case->name); ?></p>
                    <p><strong>Email :</strong> <?php echo esc_html($use_case->creator_email); ?></p>
                    <p><strong>Windesheim Minor :</strong> <?php echo esc_html($use_case->w_minor); ?></p>
                    <p><strong>Project Phase :</strong> <?php echo esc_html($use_case->project_phase); ?></p>
                    <p><strong>Value Chain :</strong> <?php echo esc_html($value_chain_str); ?></p>
                    <p><strong>Technological Innovations
                            :</strong> <?php echo esc_html($use_case->techn_innovations); ?></p>
                    <p><strong>Technology Providers :</strong> <?php echo esc_html($use_case->tech_providers); ?></p>
                    <p><strong>Themes :</strong> <?php echo esc_html($themes_str); ?></p>
                    <p><strong>Innovation Sectors :</strong> <?php echo esc_html($innovation_sectors_str); ?></p>
                    <p><strong>Sustainable Development Goals :</strong> <?php echo esc_html($sdgs_str); ?></p>
                    <p><strong>Positive Impact on SDGs
                            :</strong> <?php echo esc_html($use_case->positive_impact_sdgs); ?></p>
                    <p><strong>Negative Impact on SDGs
                            :</strong> <?php echo esc_html($use_case->negative_impact_sdgs); ?></p>
                    <p><strong>Project Background :</strong> <?php echo esc_html($use_case->project_background); ?></p>
                    <p><strong>Problem :</strong> <?php echo esc_html($use_case->problem); ?></p>
                    <p><strong>SMART Goal :</strong> <?php echo esc_html($use_case->smart_goal); ?></p>
                    <p><strong>Project Link :</strong> <a href="<?php echo esc_url($use_case->project_link); ?>"
                                                          target="_blank"><?php echo esc_html($use_case->project_link); ?></a>
                    </p>
                    <p><strong>Video Link :</strong> <a href="<?php echo esc_url($use_case->video_link); ?>"
                                                        target="_blank"><?php echo esc_html($use_case->video_link); ?></a>
                    </p>
                </div>

                <div class="actions">
                    <?php

                    // Display the unpublish button
                    if ($use_case->published) {
                        // use the nonce for the action
                        ?>
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <?php wp_nonce_field('unpublish_use_case', 'unpublish_use_case_nonce'); ?>
                            <input type="hidden" name="action" value="unpublish_use_case">
                            <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                            <button type="submit" class="button button-secondary">Unpublish</button>
                        </form>
                        <?php
                    } else {
                        // Display the publish button
                        // use the nonce for the action
                        ?>
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <?php wp_nonce_field('publish_use_case', 'publish_use_case_nonce'); ?>
                            <input type="hidden" name="action" value="publish_use_case">
                            <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                            <button type="submit" class="button button-primary">Publish</button>
                        </form>
                        <?php
                    }
                    // Display the delete button
                    // use the nonce for the action
                    ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>"
                          onsubmit="return confirmDeletion();">
                        <?php wp_nonce_field('delete_use_case', 'delete_use_case_nonce'); ?>
                        <input type="hidden" name="action" value="delete_use_case">
                        <input type="hidden" name="use_case_id" value="<?php echo esc_attr($use_case_id); ?>">
                        <button type="submit" class="button button-danger">Delete</button>
                    </form>
                </div>
            </div>
            <script type="text/javascript">
                function confirmDeletion() {
                    return confirm('Are you sure you want to delete this use case?');
                }
            </script>
            <?php
        }

        /**
         * The function `publish_use_case` in PHP handles the publishing of a use case after verifying
         * a nonce.
         */
        public function publish_use_case()
        {
            // Nonce verification
            if (!isset($_POST['publish_use_case_nonce']) || !wp_verify_nonce($_POST['publish_use_case_nonce'], 'publish_use_case')) {
                wp_die(__('Nonce verification failed', 'textdomain'));
            }
            $this->handle_action('publish_use_case', 1);
        }

        /**
         * The PHP function `unpublish_use_case` handles the action of unpublishing a use case after
         * verifying a nonce.
         */
        public function unpublish_use_case()
        {
            // Nonce verification
            if (!isset($_POST['unpublish_use_case_nonce']) || !wp_verify_nonce($_POST['unpublish_use_case_nonce'], 'unpublish_use_case')) {
                wp_die(__('Nonce verification failed', 'textdomain'));
            }
            $this->handle_action('unpublish_use_case', 0);
        }

        /**
         * The function `delete_use_case` checks for nonce verification and then handles the action of
         * deleting a use case.
         */
        public function delete_use_case()
        {
            // Nonce verification
            if (!isset($_POST['delete_use_case_nonce']) || !wp_verify_nonce($_POST['delete_use_case_nonce'], 'delete_use_case')) {
                wp_die(__('Nonce verification failed', 'textdomain'));
            }
            $this->handle_action('delete_use_case', null);
        }

        /**
         * The function `delete_use_case_image` deletes an image associated with a specific use case in
         * WordPress after verifying nonces and use case ID.
         */
        public function delete_use_case_image()
        {
            // Nonce verification
            if (!isset($_POST['delete_use_case_image_nonce']) || !wp_verify_nonce($_POST['delete_use_case_image_nonce'], 'delete_use_case_image')) {
                wp_die(__('Nonce verification failed', 'textdomain'));
            }

            // Check if use_case_id is set
            if (!isset($_POST['use_case_id'])) {
                wp_die(__('No use case ID provided', 'textdomain'));
            }

            // Get the use case ID
            $use_case_id = intval($_POST['use_case_id']);

            // Get the image path
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';
            $use_case = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $use_case_id));
            $image_path = $use_case->project_image;
            // Delete the image file
            if ($image_path) {
                $upload_dir = wp_upload_dir();
                $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_path); 
                unlink($image_path);
                $wpdb->update($table_name, ['project_image' => null], ['id' => $use_case_id]);
            }
            wp_redirect(admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case_id));
            exit;
        }

        /**
         * The function `upload_use_case_image` handles the uploading and processing of an image for a
         * specific use case in WordPress.
         */
        public function upload_use_case_image()
        {
            // Nonce verification
            if (!isset($_POST['upload_use_case_image_nonce']) || !wp_verify_nonce($_POST['upload_use_case_image_nonce'], 'upload_use_case_image')) {
                wp_die(__('Nonce verification failed', 'textdomain'));
            }
            // Check if use_case_id is set
            if (!isset($_POST['use_case_id'])) {
                wp_die(__('No use case ID provided', 'textdomain'));
            }

            $use_case_id = intval($_POST['use_case_id']);

            // Check if the image is uploaded
            if (!empty($_FILES['use_case_image']['name'])) {
                // Include the file.php WordPress library
                require_once(ABSPATH . 'wp-admin/includes/file.php');

                // Include the image.php WordPress library
                $uploadedfile = $_FILES['use_case_image'];

                // Set the upload overrides
                $upload_overrides = array('test_form' => false);

                // Check the MIME type of the uploaded file
                $filetype = wp_check_filetype($uploadedfile['name']);
                $allowed_mime_types = ['image/jpeg', 'image/png'];

                if (!in_array($filetype['type'], $allowed_mime_types)) {
                    wp_die(__('Invalid image type', 'textdomain'));
                }

                // Upload the image
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                // Check if the image is uploaded
                if ($movefile && !isset($movefile['error'])) {
                    // Rename the image with the format use_case_ID
                    $new_filename = sanitize_file_name('use_case_' . $use_case_id . '.' . pathinfo($movefile['file'], PATHINFO_EXTENSION));
                    $new_filepath = wp_upload_dir()['path'] . '/' . $new_filename;
                    rename($movefile['file'], $new_filepath);
                    $new_fileurl = wp_upload_dir()['url'] . '/' . $new_filename;

                    // Update the table with the new image URL
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'use_case';
                    $wpdb->update(
                        $table_name,
                        ['project_image' => $new_fileurl],
                        ['id' => $use_case_id]
                    );
                } else {
                    wp_die(__('Image upload failed', 'textdomain'));
                }
            }

            wp_redirect(admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case_id));
            exit;
        }

        /**
         * The function `handle_action` processes actions related to a use case, such as deleting the
         * use case and updating its publish status, and then redirects to the use case details page.
         * 
         * @param action The `action` parameter in the `handle_action` function is used to determine
         * the specific action to be taken. In the provided code snippet, there are two possible
         * actions: `delete_use_case` and updating the `published` status of a use case.
         * @param status The `status` parameter in the `handle_action` function represents the status
         * that will be assigned to a specific use case. This status will be updated in the database
         * for the use case identified by the `use_case_id`. The function checks if the action is to
         * delete a use case or update its
         */
        private function handle_action($action, $status)
        {
            if (!isset($_POST['use_case_id'])) {
                wp_die(__('No use case ID provided', 'textdomain'));
            }

            $use_case_id = intval($_POST['use_case_id']);

            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';

            if ($action === 'delete_use_case') {
                //Delete the image file 
                $use_case = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $use_case_id));
                $image_path = $use_case->project_image;
                if ($image_path) {
                    $upload_dir = wp_upload_dir();
                    $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_path);
                    unlink($image_path);
                }
                $wpdb->delete($table_name, ['id' => $use_case_id]);
            } else {
                $wpdb->update($table_name, ['published' => $status], ['id' => $use_case_id]);
            }

            wp_redirect(admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case_id));
            exit;
        }
    }
}