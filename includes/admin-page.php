<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

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
    }

    public function create_custom_post_type() {
        $args = array(
            'public' => true,
            'has_archive' => true,
            'supports' => array(),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
            'labels' => array(
                'name' => 'Use Cases',
                'singular_name' => 'Use Case',
            ),
            'menu_icon' => 'dashicons-book-alt',
        );
        register_post_type('use-case-library', $args);
    }

    public function set_custom_columns($columns) {
        unset($columns['title']);
        $columns['project_name'] = 'Project Name';
        $columns['creator_name'] = 'Project Owner';
        $columns['status'] = 'Statut';
        $columns['details'] = 'Détails';
        return $columns;
    }

    public function custom_column_data($column, $post_id) {
        switch ($column) {
            case 'project_name':
                echo get_post_meta($post_id, 'project_name', true);
                break;
            case 'creator_name':
                echo get_post_meta($post_id, 'creator_name', true);
                break;
            case 'status':
                echo get_post_meta($post_id, 'status', true);
                break;
            case 'details':
                $url = admin_url('admin.php?page=use-case-details&post_id=' . $post_id);
                echo '<a href="' . $url . '" target="_blank">Détails</a>';
                break;
        }
    }

    public function set_sortable_columns($columns) {
        $columns['project_name'] = 'project_name';
        $columns['creator_name'] = 'creator_name';
        return $columns;
    }

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
}