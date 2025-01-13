<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'UseCaseFormChecking.php';
require_once plugin_dir_path(__FILE__) . 'UseCaseMailer.php';

if (!class_exists('UseCaseLibraryForm')) {
    class UseCaseLibraryForm
    {

        // Private variable to check if the form is already loaded
        private $form_loaded = false;

        /**
         * The PHP constructor function initializes various actions and callbacks for loading assets,
         * adding a shortcode, loading scripts, and registering a REST API in WordPress.
         */
        public function __construct()
        {
            // Load assets
            add_action('wp_enqueue_scripts', array($this, 'load_assets'));

            // Add shortcode
            add_shortcode('form-use-case', array($this, 'load_shortcode'));

            // Load scripts
            add_action('wp_footer', array($this, 'load_scripts'));

            // Register REST API
            add_action('rest_api_init', array($this, 'register_rest_api'));
        }

       
        /**
         * The function `load_assets` checks for a specific shortcode in the content and enqueues CSS
         * and JS files if the shortcode is present.
         */
        public function load_assets()
        {
            // Load CSS and JS files
            if (has_shortcode(get_the_content(), 'form-use-case')) {
                wp_enqueue_style(
                    'form-color-palette',
                    plugin_dir_url(__FILE__) . '../../assets/css/color-palette.css',
                    array(),
                    '1.0',
                    'all'
                );
                wp_enqueue_style(
                    'form-style',
                    plugin_dir_url(__FILE__) . '../../assets/css/form.css',
                    array(),
                    '1.0',
                    'all'
                );
                wp_enqueue_script(
                    'form-script',
                    plugin_dir_url(__FILE__) . '../../assets/js/form.js',
                    array('jquery'),
                    '1.0',
                    true
                );
                error_log('Form assets loaded');
            }
        }

        /**
         * The function `load_shortcode` checks if a form is already loaded, sets it as loaded,
         * includes a form HTML file, and returns the rendered form.
         * 
         * @return The function `load_shortcode()` is returning the output of the function
         * `render_use_case_form()`.
         */
        public function load_shortcode()
        {
            // Check if the form is already loaded
            if ($this->form_loaded) {
                return '';
            }

            // Set the form as loaded
            $this->form_loaded = true;

            // Include the form-page-html.php file
            include_once plugin_dir_path(__FILE__) . 'form-page-html.php';

            // Return the form
            return render_use_case_form();
        }

        /**
         * The function `load_scripts` in PHP creates a nonce for a REST API request and handles form
         * submission using jQuery AJAX to send data to an endpoint, displaying success or error
         * messages accordingly.
         */
        public function load_scripts()
        {
            ?>
            <script>
                // Create a nonce for the REST API request
                var nonce = '<?php echo wp_create_nonce('wp_rest');?>';

                // Dollar sign is un raccourci pour jQuery
                (function ($) {
                    $('#simple-contact-form__form').submit(function (event) {
                        //Prevent the default reload of the page
                        event.preventDefault();

                        // Reset the error messages
                        $('.error-message').hide();
                        $('#success-message').hide();

                        // Send the form data to the REST API endpoint
                        var formData = new FormData(this);

                        $.ajax({
                            method: 'post',
                            url: '<?php echo get_rest_url(null, 'use-case-library/v1/send-use-case'); ?>',
                            headers: {'X-WP-Nonce': nonce},
                            processData: false,
                            contentType: false,
                            data: formData,
                        }).done(function (response) {
                            console.log(response);

                            // Show the success message for 5 seconds
                            $('#success-message').css('display', 'flex').delay(5000).fadeOut();

                            // Reset the form
                            $('#simple-contact-form__form').trigger('reset');

                            // Place the success message in the center of the view
                            document.getElementById('success-message').scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }).fail(function (response) {
                            var errors = response.responseJSON.errors;
                            var firstInvalidElement = null;


                            // Display the error messages
                            $.each(errors, function (field, message) {
                                var div = $('#' + field);
                                var errorMessage = div.find('.error-message');
                                errorMessage.text(message).show();
                                if (!firstInvalidElement) {
                                    firstInvalidElement = div[0];
                                }
                            });

                            // Place the first invalid element in the center of the view
                            if (firstInvalidElement) {
                                firstInvalidElement.scrollIntoView({behavior: 'smooth', block: 'center'});
                            }
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }

        /**
         * The function `register_rest_api` registers a REST API route for sending a use case with a
         * POST method callback.
         */
        public function register_rest_api()
        {

            // Register the REST API route
            register_rest_route('use-case-library/v1', 'send-use-case', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_contact_form') // Handle the form submission
            ));
        }

        /**
         * The function `handle_contact_form` processes and validates form data, inserts it into a
         * custom table in WordPress, handles file uploads, and sends email notifications.
         * 
         * @param data The code you provided is a PHP function that handles a contact form submission.
         * It performs various checks and validations before inserting the form data into a custom
         * table in the WordPress database. Here is a breakdown of the function:
         * 
         * @return The function `handle_contact_form` returns different responses based on the
         * conditions met during its execution. Here are the possible return values:
         */
        public function handle_contact_form($data)
        {
            $headers = $data->get_headers(); // Get the headers
            $params = $data->get_params(); // Get the parameters
            $nonce = $headers['x_wp_nonce'][0]; // Get the nonce

            // Check if the nonce is valid
            if (!wp_verify_nonce($nonce, 'wp_rest')) {

                // Return an error if the nonce is invalid
                return new WP_REST_Response(['error' => 'Message not sent'], 422);
            }

            // Instantiate the UseCaseFormChecking class
            $form_checker = new UseCaseFormChecking();

            // Validate the form data
            $errors = $form_checker->checkForm($params);

            // Check if there are any errors
            if (!empty($errors)) {

                // Return the errors if there are any
                return new WP_REST_Response(['errors' => $errors], 422);
            }


            $required_fields = [
                'project_name',
                'name',
                'creator_email',
                'project_phase',
                'value_chain',
                'techn_innovations',
                'tech_providers',
                'themes',
                'sdgs',
                'project_background',
                'problem',
                'smart_goal',
                'project_link',
                'innovation_sectors'
            ];
            // Check if the required fields are set
            foreach ($required_fields as $field) {
                if (empty($params[$field])) {
                    // Return an error if the required fields are not set
                    return new WP_REST_Response(['error' => 'Missing information'], 400);
                }
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                // Return an error if the request method is not POST
                return new WP_REST_Response(['error' => 'Method not allowed'], 405);
            }
            // Verify if the table exists, if not, create it
            create_use_case_table();

            // Insert the form data into the custom table
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';

            $data = [
                'project_name' => sanitize_text_field($params['project_name']),
                'name' => sanitize_text_field($params['name']),
                'creator_email' => sanitize_email($params['creator_email']),
                'country' => sanitize_text_field($params['country']),
                //if zipcode is not set, set it to null
                'zipcode' => isset($params['zipcode']) ? sanitize_text_field($params['zipcode']) : null,
                // if w_minor is not set, set it to null
                'w_minor' => isset($params['w_minor']) ? sanitize_text_field($params['w_minor']) : null,
                'project_phase' => sanitize_text_field($params['project_phase']),
                'value_chain' => maybe_serialize($params['value_chain']),
                'techn_innovations' => sanitize_textarea_field($params['techn_innovations']),
                'tech_providers' => sanitize_textarea_field($params['tech_providers']),
                'themes' => maybe_serialize($params['themes']),
                'sdgs' => maybe_serialize($params['sdgs']),
                // If positive_impact_sdgs is not set or empty, set it to null
                'positive_impact_sdgs' => isset($params['positive_impact_sdgs']) || !empty($params['positive_impact_sdgs']) ? sanitize_textarea_field($params['positive_impact_sdgs']) : null,
                //If negative_impact_sdgs is not set or empty, set it to null
                'negative_impact_sdgs' => isset($params['negative_impact_sdgs']) || !empty($params['negative_impact_sdgs']) ? sanitize_textarea_field($params['negative_impact_sdgs']) : null,
                'project_background' => sanitize_textarea_field($params['project_background']),
                'problem' => sanitize_textarea_field($params['problem']),
                'smart_goal' => sanitize_textarea_field($params['smart_goal']),
                'project_link' => sanitize_text_field($params['project_link']),
                // If video_link is not set or empty, set it to null
                'video_link' => isset($params['video_link']) || !empty($params['video_link']) ? sanitize_text_field($params['video_link']) : null,
                'innovation_sectors' => sanitize_text_field($params['innovation_sectors']),
            ];

            $wpdb->insert($table_name, $data);

            if ($wpdb->insert_id) {
                $insert_id = $wpdb->insert_id;

                // Check if the image is uploaded
                if (!empty($_FILES['project_image']['name'])) {
                    // Include the file.php WordPress library
                    require_once(ABSPATH . 'wp-admin/includes/file.php');

                    // Include the image.php WordPress library
                    $uploadedfile = $_FILES['project_image'];

                    // Set the upload overrides
                    $upload_overrides = array('test_form' => false);

                    // Check the MIME type of the uploaded file
                    $filetype = wp_check_filetype($uploadedfile['name']);
                    $allowed_mime_types = ['image/jpeg', 'image/png'];

                    if (!in_array($filetype['type'], $allowed_mime_types)) {
                        // Return an error if the MIME type is not allowed
                        return new WP_REST_Response(['error' => 'Invalid image type'], 400);
                    }

                    // Upload the image
                    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                    // Check if the image is uploaded
                    if ($movefile && !isset($movefile['error'])) {
                        // Renommer l'image avec le format use_case_ID
                        $new_filename = sanitize_file_name('use_case_' . $insert_id . '.' . pathinfo($movefile['file'], PATHINFO_EXTENSION));
                        $new_filepath = wp_upload_dir()['path'] . '/' . $new_filename;
                        rename($movefile['file'], $new_filepath);
                        $new_fileurl = wp_upload_dir()['url'] . '/' . $new_filename;

                        // Update the table with the new image URL
                        $wpdb->update(
                            $table_name,
                            ['project_image' => $new_fileurl],
                            ['id' => $insert_id]
                        );
                    } else {
                        // Return an error if the image upload failed
                        return new WP_REST_Response(['error' => 'Image upload failed'], 500);
                    }
                }

                // Instantiate the UseCaseMailer class
                $mailer = new UseCaseMailer();
                $mailer->send_email_confirmation($data['creator_email']);
                $mailer->send_admin_email(get_option('admin_email'), get_site_url() . '/wp-admin/admin.php?page=use-case-details&use_case_id=' . $insert_id);

                return new WP_REST_Response('Message sent', 200);
            } else {
                return new WP_REST_Response(['error' => 'Message not sent'], 500);
            }
        }
    }
}