<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryAdmin')) {
    class UseCaseLibraryAdmin {
        public function __construct() {
            // Add admin menu
            add_action('admin_menu', array($this, 'add_admin_menu'));
            // Make columns sortable
            add_filter('manage_use-case-library_sortable_columns', array($this, 'set_sortable_columns'));
            add_action('pre_get_posts', array($this, 'custom_column_sorting'));
        }

        public function add_admin_menu() {
            add_menu_page(
                'Use Cases', // Page title
                'Use Cases', // Menu title
                'manage_options', // Capability
                'use-case-library', // Menu slug
                array($this, 'display_use_cases_page'), // Callback function
                'dashicons-book-alt', // Icon
                6 // Position
            );
        }

        /**
         * Display use cases page.
         */
        public function display_use_cases_page() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'use_case';
            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id';
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'asc';
            $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
            $limit = 20;
            $offset = ($paged - 1) * $limit;

            // Get the total number of use cases
            $total_use_cases = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

            // Get the use cases with limit and offset
            $use_cases = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $limit, $offset));

            echo '<div class="wrap">';
            echo '<h1>Use Cases</h1>';
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col" class="manage-column column-id sortable ' . ($orderby == 'id' ? $order : '') . '"><a href="?page=use-case-library&orderby=id&order=' . ($order == 'asc' ? 'desc' : 'asc') . '"><span>ID</span><span class="sorting-indicator"></span></a></th>';
            echo '<th scope="col" class="manage-column column-project_name sortable ' . ($orderby == 'project_name' ? $order : '') . '"><a href="?page=use-case-library&orderby=project_name&order=' . ($order == 'asc' ? 'desc' : 'asc') . '"><span>Project Name</span><span class="sorting-indicator"></span></a></th>';
            echo '<th scope="col" class="manage-column column-name sortable ' . ($orderby == 'name' ? $order : '') . '"><a href="?page=use-case-library&orderby=name&order=' . ($order == 'asc' ? 'desc' : 'asc') . '"><span>Project Owner</span><span class="sorting-indicator"></span></a></th>';
            echo '<th scope="col" class="manage-column column-status sortable ' . ($orderby == 'published' ? $order : '') . '"><a href="?page=use-case-library&orderby=published&order=' . ($order == 'asc' ? 'desc' : 'asc') . '"><span>Status</span><span class="sorting-indicator"></span></a></th>';
            echo '<th scope="col" class="manage-column column-details">Details</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($use_cases as $use_case) {
                $status = $use_case->published ? 'published' : 'on hold';
                echo '<tr>';
                echo '<td>' . esc_html($use_case->id) . '</td>';
                echo '<td>' . esc_html($use_case->project_name) . '</td>';
                echo '<td>' . esc_html($use_case->name) . '</td>';
                echo '<td>' . esc_html($status) . '</td>';
                echo '<td><a href="' . admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case->id) . '">View</a></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';

            // Pagination
            $total_pages = ceil($total_use_cases / $limit);
            if ($total_pages > 1) {
                $current_page = max(1, $paged);
                $base = add_query_arg('paged', '%#%');
                echo '<div class="tablenav"><div class="tablenav-pages">';
                echo paginate_links(array(
                    'base' => $base,
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $total_pages,
                    'current' => $current_page,
                ));
                echo '</div></div>';
            }

            echo '</div>';
        }

        /**
         * Set sortable columns.
         */
        public function set_sortable_columns($columns) {
            $columns['id'] = 'id';
            $columns['project_name'] = 'project_name';
            $columns['name'] = 'name';
            $columns['published'] = 'published';
            return $columns;
        }

        /**
         * Handle custom column sorting.
         */
        public function custom_column_sorting($query) {
            if (!is_admin()) {
                return;
            }

            $orderby = $query->get('orderby');

            if ('id' == $orderby) {
                $query->set('meta_key', 'id');
                $query->set('orderby', 'meta_value_num');
            } elseif ('project_name' == $orderby) {
                $query->set('meta_key', 'project_name');
                $query->set('orderby', 'meta_value');
            } elseif ('published' == $orderby) {
                $query->set('meta_key', 'published');
                $query->set('orderby', 'meta_value');
            }
        }
    }
}
