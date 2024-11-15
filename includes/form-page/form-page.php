<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('UseCaseLibraryForm')) 
{
    class UseCaseLibraryForm {

		// Private variable to check if the form is already loaded
	    private $form_loaded = false;

        public function __construct() {
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
		 * Load assets for the form
		 */
		public function load_assets() {
			// Load CSS and JS files
			wp_enqueue_style(
				'form-style',
				plugin_dir_url( __FILE__ ) . '../../assets/css/form.css',
				array(),
				'1.0',
				'all'
			);
			wp_enqueue_script(
				'form-script',
				plugin_dir_url( __FILE__ ) . '../../assets/js/form.js',
				array( 'jquery' ),
				'1.0',
				true
			);
		}

		/**
		 * Use case Form
		 */
		public function load_shortcode() {
			// Check if the form is already loaded
			if ( $this->form_loaded ) {
				return '';
			}

			// Set the form as loaded
			$this->form_loaded = true;

			// Include the form-page-html.php file
			include_once plugin_dir_path( __FILE__ ) . 'form-page-html.php';

			// Return the form
			return render_use_case_form();
		}

		/**
		 * Load scripts for the form
		 */
		public function load_scripts() {
			?>
            <script>
                // Create a nonce for the REST API request
                var nonce = '<?php echo wp_create_nonce( 'wp_rest' );?>';

                // Dollar sign is un raccourci pour jQuery
                (function ($) {
                    $('#simple-contact-form__form').submit(function (event) {
                        //Prevent the default reload of the page
                        event.preventDefault();

                        // Reset the error messages
                        $('.error-message').hide();
                        $('#success-message').hide(); 

                        var isValid = true;
                        var firstInvalidElement = null;

                        $('#simple-contact-form__form input, #simple-contact-form__form textarea').each(function () {
                            var type = $(this).attr('type');

                            if (type === 'file') {

								// Ignore the file input
                                return true; 
                            }
                            if ((type === 'checkbox' || type === 'radio') && !$('input[name="' + $(this).attr('name') + '"]:checked').length) {

								// Show the error message if the checkbox or radio button is not checked
                                $(this).closest('div').find('.error-message').show();
                                isValid = false;
                                if (!firstInvalidElement) {
                                    firstInvalidElement = this;
                                }
                            } else if ($(this).is('textarea') && $(this).val().trim() === '') {

								// Show the error message if the textarea is empty
                                $(this).next('.error-message').show();
                                isValid = false;
                                if (!firstInvalidElement) {
                                    firstInvalidElement = this;
                                }
                            } else if ($(this).val().trim() === '') {

								// Show the error message if the input is empty
                                $(this).next('.error-message').show();
                                isValid = false;
                                if (!firstInvalidElement) {
                                    firstInvalidElement = this;
                                }
                            }
                        });

                        if (!isValid) {
                            
							// Place the first invalid element in the center of the view
                            firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }

                        // Send the form data to the REST API endpoint
                        var formData = new FormData(this);

                        $.ajax({
                            method: 'post',
                            url: '<?php echo get_rest_url(null, 'use-case-library/v1/send-use-case'); ?>', 
                            headers: {'X-WP-Nonce': nonce},
                            processData: false, 
                            contentType: false,
                            data: formData, 
                        }).done(function(response) {
                            console.log(response);
                            
							// Show the success message for 5 seconds
							$('#success-message').css('display', 'flex').delay(5000).fadeOut();

							// Reset the form
							$('#simple-contact-form__form').trigger('reset'); 
                            
							// Place the success message in the center of the view
                            document.getElementById('success-message').scrollIntoView({ behavior: 'smooth', block: 'center' });
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
			register_rest_route( 'use-case-library/v1', 'send-use-case', array(
				'methods'  => 'POST',
				'callback' => array( $this, 'handle_contact_form' ) // Handle the form submission
			) );
		}

		/**
		 * Handle the form submission
		 */
		public function handle_contact_form( $data ) {
			$headers = $data->get_headers(); // Get the headers
			$params  = $data->get_params(); // Get the parameters
			$nonce   = $headers['x_wp_nonce'][0]; // Get the nonce

			// Check if the nonce is valid
			if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {

				// Return an error if the nonce is invalid
				return new WP_REST_Response( 'Message not sent', 422 );
			}
			$required_fields = [
				'project_name',
				'name',
				'creator_email',
				'w_minor',
				'project_phase',
				'value_chain',
				'techn_innovations',
				'tech_providers',
				'themes',
				'sdgs',
				'positive_impact_sdgs',
				'negative_impact_sdgs',
				'project_background',
				'problem',
				'smart_goal',
				'project_link',
				'video_link',
				'innovation_sectors'
			];
			// Check if the required fields are set
			foreach ($required_fields as $field) {
				if (empty($params[$field])) {
					// Return an error if the required fields are not set
					return new WP_REST_Response('Missing information', 400);
				}
			}

			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				// Return an error if the request method is not POST
				return new WP_REST_Response('Method not allowed', 405);
			}
			// Vérifiez si la table existe et créez-la si nécessaire
			create_use_case_table();

			// Insérez les données dans la table personnalisée
			global $wpdb;
			$table_name = $wpdb->prefix . 'use_case';

			$data = [
				'project_name' => sanitize_text_field($params['project_name']),
				'name' => sanitize_text_field($params['name']),
				'creator_email' => sanitize_email($params['creator_email']),
				'w_minor' => sanitize_text_field($params['w_minor']),
				'project_phase' => sanitize_text_field($params['project_phase']),
				'value_chain' => maybe_serialize($params['value_chain']),
				'techn_innovations' => sanitize_textarea_field($params['techn_innovations']),
				'tech_providers' => sanitize_textarea_field($params['tech_providers']),
				'themes' => maybe_serialize($params['themes']),
				'sdgs' => maybe_serialize($params['sdgs']),
				'positive_impact_sdgs' => sanitize_textarea_field($params['positive_impact_sdgs']),
				'negative_impact_sdgs' => sanitize_textarea_field($params['negative_impact_sdgs']),
				'project_background' => sanitize_textarea_field($params['project_background']),
				'problem' => sanitize_textarea_field($params['problem']),
				'smart_goal' => sanitize_textarea_field($params['smart_goal']),
				'project_link' => sanitize_text_field($params['project_link']),
				'video_link' => sanitize_text_field($params['video_link']),
				'innovation_sectors' => sanitize_text_field($params['innovation_sectors']),
			];

			// Insérer les données initiales dans la table
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

					// Upload the image
					$movefile = wp_handle_upload($uploadedfile, $upload_overrides);

					// Check if the image is uploaded
					if ($movefile && !isset($movefile['error'])) {
						// Renommer l'image avec le format use_case_ID
						$filetype = wp_check_filetype($movefile['file']);
						$new_filename = 'use_case_' . $insert_id . '.' . $filetype['ext'];
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
						return new WP_REST_Response('Image upload failed', 500);
					}
				}

				return new WP_REST_Response('Message sent', 200);
			} else {
				return new WP_REST_Response('Message not sent', 500);
			}
		}
	}
}