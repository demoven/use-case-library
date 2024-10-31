<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

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

    public function load_assets() {
        wp_enqueue_style(
            'use-case-library-style',
            plugin_dir_url(__FILE__) . '../css/use-case-library.css',
            array(),
            '1.0',
            'all'
        );
        wp_enqueue_script(
            'use-case-library-script',
            plugin_dir_url(__FILE__) . '../js/use-case-library.js',
            array('jquery'),
            '1.0',
            true
        );
    }

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

    public function load_scripts() {
        ?>
        <script>
            var nonce = '<?php echo wp_create_nonce('wp_rest');?>';
            (function($){
                $('#simple-contact-form__form').submit(function(event){
                    event.preventDefault();
                    var form = $(this).serialize();
                    $.ajax({
                        method: 'post', 
                        url: '<?php echo get_rest_url(null, 'use-case-library/v1/send-email');?>',
                        headers: {'X-WP-Nonce': nonce},
                        data: form
                    });
                });
            })(jQuery);
        </script>
        <?php
    }

    public function register_rest_api() {
        register_rest_route('use-case-library/v1', 'send-email', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_contact_form')
        ));
    }

    public function handle_contact_form($data) {
        $headers = $data->get_headers();
        $params = $data->get_params();
        $nonce = $headers['x_wp_nonce'][0];

        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_REST_Response('Message not sent', 422);
        }

        $post_id = wp_insert_post([
            'post_type' => 'use-case-library',
            'post_title' => 'New project',
            'post_status' => 'publish'
        ]);

        if ($post_id) {
            update_post_meta($post_id, 'project_name', sanitize_text_field($params['project_name']));
            update_post_meta($post_id, 'creator_name', sanitize_text_field($params['name']));
            update_post_meta($post_id, 'status', 'on hold'); // Set status to "on hold"
            return new WP_REST_Response('Message sent', 200);
        }
    }
}