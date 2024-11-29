<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryAdmin')) {
    class UseCaseLibraryAdmin
    {
        public function __construct()
        {
            // Add admin menu
            add_action('admin_menu', array($this, 'add_admin_menu'));
            // Make columns sortable
            add_filter('manage_use-case-library_sortable_columns', array($this, 'set_sortable_columns'));
            add_action('pre_get_posts', array($this, 'custom_column_sorting'));
        }

        public function add_admin_menu()
        {
            // Verify the user has the appropriate capabilities
            add_menu_page(
                __('Use Cases', 'textdomain'), // Page title
                __('Use Cases', 'textdomain'), // Menu title
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
        public function display_use_cases_page()
        {
            // Ensure the user has the right permissions
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'textdomain'));
            }

            global $wpdb;

            // Sanitize and validate GET parameters
            $orderby = isset($_GET['orderby']) ? sanitize_key($_GET['orderby']) : 'id';
            $order = isset($_GET['order']) ? (in_array(strtolower($_GET['order']), ['asc', 'desc']) ? strtolower($_GET['order']) : 'asc') : 'asc';
            $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

            // Limit and offset for pagination
            $limit = 20;
            $offset = ($paged - 1) * $limit;

            // Ensure the table name is sanitized
            $table_name = esc_sql($wpdb->prefix . 'use_case');

            // Get the total number of use cases
            $total_use_cases = $wpdb->get_var("SELECT COUNT(*) FROM `$table_name`");

            // Prepare the query to fetch data securely
            $order_by_clause = $orderby === 'id' ? "published ASC, `$orderby` $order" : "`$orderby` $order";
            $use_cases = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM `$table_name` ORDER BY $order_by_clause LIMIT %d OFFSET %d",
                    $limit,
                    $offset
                )
            );

            // Display the admin page
            echo '<div class="wrap">';
            echo '<h1>' . __('Use Cases', 'textdomain') . '</h1>';
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr>';
            echo $this->render_column_headers($orderby, $order);
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if (!empty($use_cases)) {
                foreach ($use_cases as $use_case) {
                    $this->render_table_row($use_case);
                }
            } else {
                echo '<tr><td colspan="5">' . __('No use cases found.', 'textdomain') . '</td></tr>';
            }
            echo '</tbody>';
            echo '</table>';

            // Pagination
            $this->render_pagination($paged, $total_use_cases, $limit);

            echo '</div>';
        }

        /**
         * Render the column headers with sorting links.
         */
        private function render_column_headers($orderby, $order)
        {
            $columns = [
                'id' => __('ID', 'textdomain'),
                'project_name' => __('Project Name', 'textdomain'),
                'name' => __('Project Owner', 'textdomain'),
                'published' => __('Status', 'textdomain')
            ];
            $headers = '';
            foreach ($columns as $key => $label) {
                $sort_order = ($orderby === $key && $order === 'asc') ? 'desc' : 'asc';
                $headers .= '<th scope="col" class="manage-column sortable ' . esc_attr($order) . '">';
                $headers .= '<a href="' . esc_url(add_query_arg(['orderby' => $key, 'order' => $sort_order])) . '">';
                $headers .= '<span>' . esc_html($label) . '</span><span class="sorting-indicator"></span>';
                $headers .= '</a></th>';
            }
            $headers .= '<th scope="col" class="manage-column">' . __('Details', 'textdomain') . '</th>';
            return $headers;
        }

        /**
         * Render a table row for a use case.
         */
        private function render_table_row($use_case)
        {
            $status = $use_case->published ? __('Published', 'textdomain') : __('On Hold', 'textdomain');
            echo '<tr>';
            echo '<td>' . esc_html($use_case->id) . '</td>';
            echo '<td>' . esc_html($use_case->project_name) . '</td>';
            echo '<td>' . esc_html($use_case->name) . '</td>';
            echo '<td>' . esc_html($status) . '</td>';
            echo '<td><a href="' . esc_url(admin_url('admin.php?page=use-case-details&use_case_id=' . $use_case->id)) . '">' . __('View', 'textdomain') . '</a></td>';
            echo '</tr>';
        }

        /**
         * Render pagination links.
         */
        private function render_pagination($paged, $total_items, $per_page)
        {
            $total_pages = ceil($total_items / $per_page);
            if ($total_pages > 1) {
                echo '<div class="tablenav"><div class="tablenav-pages">';
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;', 'textdomain'),
                    'next_text' => __('&raquo;', 'textdomain'),
                    'total' => $total_pages,
                    'current' => $paged,
                ));
                echo '</div></div>';
            }
        }

        /**
         * Set sortable columns.
         */
        public function set_sortable_columns($columns)
        {
            $columns['id'] = 'id';
            $columns['project_name'] = 'project_name';
            $columns['name'] = 'name';
            $columns['published'] = 'published';
            return $columns;
        }

        /**
         * Handle custom column sorting.
         */
        public function custom_column_sorting($query)
        {
            if (!is_admin() || !$query->is_main_query()) {
                return;
            }

            $orderby = $query->get('orderby');
            $valid_columns = ['id', 'project_name', 'name', 'published'];

            if (in_array($orderby, $valid_columns)) {
                $query->set('orderby', $orderby);
            }
        }
    }
}
