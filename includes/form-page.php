<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if(!class_exists('UseCaseLibraryForm')) 
{
    class UseCaseLibraryForm {

        public function __construct() {
            // Load assets
            add_action('wp_enqueue_scripts', array($this, 'load_assets'));
            
            // Add shortcode
            add_shortcode('use-case-library', array($this, 'load_shortcode'));
            
            // Load scripts
            add_action('wp_footer', array($this, 'load_scripts'));
            
            // Register REST API
            add_action('rest_api_init', array($this, 'register_rest_api'));
        }

        /**
         * Load assets for the form
         */
        public function load_assets() {
            // Load CSS and JS files
            wp_enqueue_style(
                'form-style',
                plugin_dir_url(__FILE__) . '../assets/css/form.css',
                array(),
                '1.0',
                'all'
            );
            wp_enqueue_script(
                'form-script',
                plugin_dir_url(__FILE__) . '../assets/js/form.js',
                array('jquery'),
                '1.0',
                true
            );
        }

        /**
         * Use case Form 
         */
        public function load_shortcode() {
            ?>
            <form id="simple-contact-form__form">
                <input name="project_name" type="text" placeholder="Project Name"> 
                <br>
                <input name="name" type="text" placeholder="Name">
                <br>
                <input name="email" type="email" placeholder="Email">
                <br>
                <br>
                <textarea name="message" placeholder="Type your message"></textarea>
                <br>
                <button type="submit">Send</button>
            </form>
            <?php
        }

        /**
         * Load scripts for the form  
         */
        public function load_scripts() {
            ?>
            <script>
                // Create a nonce for the REST API request
                var nonce = '<?php echo wp_create_nonce('wp_rest');?>';

                // Dollar sign is a shortcut for jQuerys
                (function($){
                    // Prevent the form from submitting
                    $('#simple-contact-form__form').submit(function(event){
                        // Prevent the form to reload the page or redirect
                        event.preventDefault();

                        // Serialize the form data
                        var form = $(this).serialize();

                        // Send the form data to the REST API
                        $.ajax({
                            method: 'post', 
                            url: '<?php echo get_rest_url(null, 'use-case-library/v1/send-use-case');?>', // URL of the REST API
                            headers: {'X-WP-Nonce': nonce}, // Add the nonce to the headers
                            data: form // Send the form data
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }
        /**
         * Register REST API route
         */
        public function register_rest_api() {
            // Register the REST API route
            register_rest_route('use-case-library/v1', 'send-use-case', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_contact_form') // Handle the form submission
            ));
        }

        /**
         * Handle the form submission 
         */
        public function handle_contact_form($data) {
            $headers = $data->get_headers(); // Get the headers
            $params = $data->get_params(); // Get the parameters
            $nonce = $headers['x_wp_nonce'][0]; // Get the nonce

            // Check if the nonce is valid
            if (!wp_verify_nonce($nonce, 'wp_rest')) {
                // Return an error if the nonce is invalid
                return new WP_REST_Response('Message not sent', 422);
            }

            // Insert the post
            $post_id = wp_insert_post([
                'post_type' => 'use-case-library', // Set the post type to "use-case-library"
                'post_title' => 'New project', // Set the title to "New project"
                'post_status' => 'publish' // Set the status to "publish"
            ]);

            if ($post_id) {
                // Update the post meta
                update_post_meta($post_id, 'project_name', sanitize_text_field($params['project_name']));
                update_post_meta($post_id, 'creator_name', sanitize_text_field($params['name']));
                update_post_meta($post_id, 'status', 'on hold'); // Set status to "on hold"
                return new WP_REST_Response('Message sent', 200);
            }
        }
    }
}