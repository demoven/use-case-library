<?php
/** 
*Plugin Name: Use Case Library
*Description: Description de mon plugin.
*Version: 1.0
*Author: Théo
*/
// Exit if accessed directly by absolute path
if (!defined('ABSPATH')) {
    echo 'What are you trying to do?';
    die; // Stop the script
}


class UseCaseLibrary {

  
    public function __construct() 
    {
        // Add custom post type
        add_action('init', array($this, 'create_custom_post_type'));
        
        // Load assets
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));

        // Add shortcode
        add_shortcode('use-case-library', array($this, 'load_shortcode'));

        // Load scripts
        add_action('wp_footer', array($this, 'load_scripts'));

        // Register REST API
        add_action('rest_api_init', array($this, 'register_rest_api'));

        // Add custom columns
        add_filter('manage_use-case-library_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_use-case-library_posts_custom_column', array($this, 'custom_column_data'), 10, 2);

        // Make columns sortable
        add_filter('manage_edit-use-case-library_sortable_columns', array($this, 'set_sortable_columns'));

        // Define custom sorting logic
        add_action('pre_get_posts', array($this, 'custom_column_sorting'));
    }

    /**
     * The function `create_custom_post_type` registers a custom post type named "Use Cases" with
     * specific settings and labels in WordPress.
     */
    public function create_custom_post_type()
    {
        $args = array(
            'public' => true, // Display in admin
            'has_archive' => true, // Display in front-end
            'supports' => array(), // No support for now
            'exclude_from_search' => true, 
            'publicly_queryable' => false, // No query in front-end
            'capability' => 'manage_options', // Only admin can manage
            'labels' => array( // Custom labels
                'name' => 'Use Cases', // Name in the admin menu
                'singular_name' => 'Use Case', 
            ),
            'menu_icon' => 'dashicons-book-alt', // Icon for the admin menu
        );
        register_post_type('use-case-library', $args); 
    }

    /**
     * The function `set_custom_columns` modifies an array of columns by removing 'title' and adding
     * 'project_name', 'creator_name', and 'status' with corresponding labels.
     * 
     * @param columns The `set_custom_columns` function is used to modify the columns displayed in a
     * table or list. In this function, the following changes are made to the columns array:
     * 
     * @return The `set_custom_columns` function is returning an updated array of columns with the
     * following keys and values:
     * - 'project_name' => 'Project Name'
     * - 'creator_name' => 'Project Owner'
     * - 'status' => 'Statut'
     */
    public function set_custom_columns($columns)
    {
        unset($columns['title']);
        $columns['project_name'] = 'Project Name';
        $columns['creator_name'] = 'Project Owner';
        $columns['status'] = 'Statut';

        return $columns;
    }

    /**
     * The function `custom_column_data` retrieves and displays custom column data for a post based on
     * the specified column name.
     * 
     * @param column The `column` parameter in the `custom_column_data` function represents the name of
     * the column for which you want to retrieve and display custom data. In your function, you are
     * using it to determine which custom field data to display based on the column name provided.
     * @param post_id The `post_id` parameter in the `custom_column_data` function is the ID of the
     * post for which you want to retrieve custom meta data. It is used to fetch the specific custom
     * meta values associated with that post.
     */
    public function custom_column_data($column, $post_id)
    {
        switch ($column) {
            case 'project_name':
                echo get_post_meta($post_id, 'project_name', true); // Display the project name
                break;
            case 'creator_name':
                echo get_post_meta($post_id, 'creator_name', true); // Display the creator name
                break;
            case 'status':
                echo get_post_meta($post_id, 'status', true); // Display the status
                break;
        }
    }

    // Make columns sortable
    public function set_sortable_columns($columns)
    {
        $columns['project_name'] = 'project_name'; // Define the custom field name
        $columns['creator_name'] = 'creator_name'; // Define the custom field name
        return $columns;
    }

    // Custom sorting logic for columns
    public function custom_column_sorting($query)
    {
        // Check if the query is for an admin page and the main query
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        /**
         * Check if the orderby parameter is set to 'project_name' or 'creator_name' and modify the
         * query accordingly to sort by the corresponding meta key.
         */
        if ($query->get('orderby') == 'project_name') {
            $query->set('meta_key', 'project_name');
            $query->set('orderby', 'meta_value');
        }

        if ($query->get('orderby') == 'creator_name') {
            $query->set('meta_key', 'creator_name');
            $query->set('orderby', 'meta_value');
        }
    }
    /**
     * The function `load_assets` enqueues the plugin's CSS and JavaScript files to be loaded on the
     * front-end of the website.
     */
    public function load_assets()
    {
        wp_enqueue_style(
            'use-case-library-style',
            plugin_dir_url(__FILE__) . 'css/use-case-library.css',
            array(),
            '1.0',
            'all'
        );
        wp_enqueue_script(
            'use-case-library-script',
            plugin_dir_url(__FILE__) . 'js/use-case-library.js',
            array('jquery'),
            '1.0',
            true
        );
    }

    /**
     * The function `load_shortcode` defines the HTML structure of the shortcode `[use-case-library]`
     * and includes a form with input fields
     */
    public function load_shortcode()
    {?>
        <form id="simple-contact-form__form">
            <input name="project_name" type="text" placeholder="Project Name"> 
            <br>
            <input name="name" type="text" placeholder="Name">
            <br>
            <input name="email" type="email" placeholder="Email">
            <br>
            <br>
            <input type="radio" id="concept" name="project_type" value="concept">
            <label for="concept">Concept</label>
            <input type="radio" id="data" name="project_type" value="data">
            <label for="data">Data</label>
            <br>
            <br>
            <input type="checkbox" id="marketing" name="project_status[]" value="marketing">
            <label for="marketing">Marketing</label>
            <input type="checkbox" id="operations" name="project_status[]" value="operations">
            <label for="operations">Operations</label>
            <input type="checkbox" id="logistic" name="project_status[]" value="logistic">
            <label for="logistic">Logistic</label>
            <br>
            <br>
            <textarea name="message" placeholder="Type your message"></textarea>
            <br>
            <button type="submit">Send</button>
        </form>
    <?php }

    /**
     * The function `load_scripts` enqueues a JavaScript script that handles the form submission
     * using AJAX and the WordPress REST API.
     */
    public function load_scripts()
    {?>
        <script>
            /**
             * The following script uses jQuery to handle the form submission using AJAX and the
             * WordPress REST API. It prevents the default form submission behavior, serializes the
             * form data, and sends a POST request to the specified REST API endpoint with the form
             * data and the X-WP-Nonce header for authentication.
             */
            var nonce = '<?php echo wp_create_nonce('wp_rest');?>';

            // The dollar sign is a shortcut to the jQuery object
            (function($){
            // The submit event is triggered when the form is submitted
            $('#simple-contact-form__form').submit( function(event){
                // Prevent the default form submission behavior to avoid page reload
                event.preventDefault();
                // Serialize the form data into a query string
                var form = $(this).serialize();

                // Send a POST request to the specified REST API endpoint
                $.ajax({

                    method: 'post', 
                    url: '<?php echo get_rest_url(null, 'use-case-library/v1/send-email');?>', // REST API endpoint
                    headers: {'X-WP-Nonce': nonce}, // Authentication header
                    data: form // Form data

                })

            });

        })(jQuery);

        </script>

    <?php }

    public function register_rest_api()
    {
        // Register REST API endpoint for sending email
        register_rest_route('use-case-library/v1', 'send-email', array(

            'methods' => 'POST', // The 'methods' key specifies the HTTP method(s) accepted by the endpoint
            'callback' => array($this, 'handle_contact_form') // The 'callback' key specifies the function to be called when the endpoint is accessed

        ));
    }

    /**
     * The function `handle_contact_form` processes the form data received from the REST API request
     * and inserts it into a custom post type named "Use Cases" in WordPress.
     */
    public function handle_contact_form($data)
    {
        $headers = $data->get_headers(); // Get the request headers 
        $params = $data->get_params(); // Get the request parameters 
        $nonce = $headers['x_wp_nonce'][0]; // Get the X-WP-Nonce header value
    
        if (!wp_verify_nonce($nonce, 'wp_rest')) // Verify the nonce
        {
            return new WP_REST_Response('Message not sent', 422);
        }
        // Insert the form data into the custom post type
        $post_id = wp_insert_post([
            'post_type' => 'use-case-library', // Custom post type name
            'post_title' => 'New project', // Default title for the post
            'post_status' => 'publish' // Post status
        ]);
    
        if ($post_id) {
            // Update the custom fields with the form data values
            update_post_meta($post_id, 'project_name', sanitize_text_field($params['project_name']));
            update_post_meta($post_id, 'creator_name', sanitize_text_field($params['name']));
            
            // Combine the selected project statuses into a single string
            return new WP_REST_Response('Message sent', 200);
        }
    }
    
    

}
// Instantiate the UseCaseLibrary class
new UseCaseLibrary();