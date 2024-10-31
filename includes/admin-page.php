<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryAdmin')) 
{
    class UseCaseLibraryAdmin {
        public function __construct() {
            // Add custom post type
            add_action('init', array($this, 'create_custom_post_type'));
            
            // Add custom columns
            add_filter('manage_use-case-library_posts_columns', array($this, 'set_custom_columns'));
            add_action('manage_use-case-library_posts_custom_column', array($this, 'custom_column_data'), 10, 2);
            
            // Make columns sortable
            add_filter('manage_edit-use-case-library_sortable_columns', array($this, 'set_sortable_columns'));
            add_action('pre_get_posts', array($this, 'custom_column_sorting'));

            // Hook to detect post restoration
            add_action('untrash_post', array($this, 'restore_post_status'), 10, 1);

            // Remove unwanted options
            add_filter('views_edit-use-case-library', array($this, 'remove_views'));
            add_filter('bulk_actions-edit-use-case-library', array($this, 'remove_bulk_actions'));
            add_filter('disable_months_dropdown', '__return_true');

            // Add search filter
            add_filter('posts_search', array($this, 'search_by_project_name'), 10, 2);
        }

        /**
         * Create custom post type. 
         */
        public function create_custom_post_type() {
            $args = array(
                'public' => true, // Show in admin
                'has_archive' => true, // Show in archive
                'supports' => array(), // No support
                'exclude_from_search' => true, // Exclude from search
                'publicly_queryable' => false, // Disable query
                'capabilities' => array(
                    'create_posts' => 'do_not_allow', // Disable post creation
                ),
                'labels' => array(  // Custom labels
                    'name' => 'Use Cases', // Admin menu name
                    'singular_name' => 'Use Case',  
                ),
                'menu_icon' => 'dashicons-book-alt', // Admin menu icon
            );
            register_post_type('use-case-library', $args);
        }

        /**
         * Remove default columns and add custom columns.
         */
        public function set_custom_columns($columns) {
            
            // Remove default columns
            unset($columns['title']); // Remove title column
            unset($columns['date']); // Remove date column
            unset($columns['cb']); // Remove checkbox column

            // Add custom columns
            $columns['project_name'] = 'Project Name'; // Add project name column
            $columns['creator_name'] = 'Project Owner'; // Add creator name column
            $columns['status'] = 'Status'; // Add status column
            $columns['details'] = 'Details'; // Add details column
            return $columns;
        }

        /**
         * Display custom column data.
         */
        public function custom_column_data($column, $post_id) {
            switch ($column) {
                case 'project_name':
                    echo get_post_meta($post_id, 'project_name', true); // Display project name
                    break;
                case 'creator_name':
                    echo get_post_meta($post_id, 'creator_name', true); // Display creator name
                    break;
                case 'status':
                    echo get_post_meta($post_id, 'status', true); // Display status
                    break;
                case 'details':
                    $url = admin_url('admin.php?page=use-case-details&post_id=' . $post_id); // Create details URL
                    echo '<a href="' . $url . '" target="_blank">Details</a>'; // Display details link
                    break;
            }
        }

        /**
         * Make custom columns sortable.
         */
        public function set_sortable_columns($columns) {
            $columns['project_name'] = 'project_name';
            $columns['creator_name'] = 'creator_name';
            return $columns;
        }

        /**
         * Sort custom columns.
         */
        public function custom_column_sorting($query) {
            if (!is_admin() || !$query->is_main_query()) {
                return;
            }
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
         * Restore post status to "on hold" when a post is restored from trash.
         *
         * @param int $post_id The ID of the post being restored.
         */
        public function restore_post_status($post_id) {
            $post = get_post($post_id);
            if ($post->post_type == 'use-case-library') {
                update_post_meta($post_id, 'status', 'on hold');
            }
        }

        /**
         * Remove unwanted views (All, Trash, Published).
         *
         * @param array $views The existing views.
         * @return array The modified views.
         */
        public function remove_views($views) {
            unset($views['all']);
            unset($views['trash']);
            unset($views['publish']);
            return $views;
        }

        /**
         * Remove bulk actions.
         *
         * @param array $actions The existing bulk actions.
         * @return array The modified bulk actions.
         */
        public function remove_bulk_actions($actions) {
            return array();
        }

        /**
         * Add search filter by project name.
         */

        public function search_by_project_name($search, $query) {
            global $wpdb;
        
            if (!$query->is_main_query() || $query->get('post_type') !== 'use-case-library') {
                return $search;
            }
        
            $search_term = $query->get('s');
            if ($search_term) {
                $search = $wpdb->prepare(" AND EXISTS (
                    SELECT 1 FROM {$wpdb->postmeta} 
                    WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID 
                    AND {$wpdb->postmeta}.meta_key = 'project_name' 
                    AND {$wpdb->postmeta}.meta_value LIKE %s
                )", '%' . $wpdb->esc_like($search_term) . '%');
            }
        
            return $search;
        }
    }
}