<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseLibraryAdmin')) {
    class UseCaseLibraryAdmin
    {
        /**
         * The PHP constructor function sets up admin menu, sortable columns, and custom column sorting
         * for a use-case library.
         */
        public function __construct()
        {
            // Add admin menu
            add_action('admin_menu', array($this, 'add_admin_menu'));
            // Make columns sortable
            add_filter('manage_use-case-library_sortable_columns', array($this, 'set_sortable_columns'));
            add_action('pre_get_posts', array($this, 'custom_column_sorting'));
        }

        /**
         * The function `add_admin_menu` adds a menu page titled "Use Cases" with a corresponding icon
         * and position in the WordPress admin dashboard.
         */
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
         * The function `display_use_cases_page` in PHP displays a paginated list of use cases with
         * sorting options and pagination on an admin page, ensuring user permissions are checked.
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
         * The function `render_column_headers` generates HTML table headers for columns with sorting
         * functionality based on the provided orderby and order parameters.
         * 
         * @param orderby The `orderby` parameter in the `render_column_headers` function is used to
         * determine which column the table should be sorted by. It specifies the key of the column in
         * the `` array that should be used for sorting.
         * @param order The `order` parameter in the `render_column_headers` function is used to
         * determine the sorting order of the column headers. It can have two possible values: 'asc'
         * for ascending order and 'desc' for descending order.
         * 
         * @return The `render_column_headers` function returns a string containing HTML table headers
         * for columns based on the provided `` and `` parameters. The headers include
         * columns for ID, Project Name, Project Owner, Status, and a Details column. Each column
         * header is generated with sorting functionality based on the current order and can be clicked
         * to change the sorting order.
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
         * The function `render_table_row` outputs a table row with information about a use case,
         * including its ID, project name, name, status, and a link to view details.
         * 
         * @param use_case The `render_table_row` function takes a `` object as a parameter
         * and renders a table row with information from the `` object. It displays the ID,
         * project name, name, status (published or on hold), and a link to view more details.
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
         * The render_pagination function generates pagination links based on the current page, total
         * items, and items per page in PHP.
         * 
         * @param paged The `` parameter in the `render_pagination` function represents the
         * current page number of the paginated content being displayed. It is used to determine which
         * page is currently being viewed by the user.
         * @param total_items Total number of items in the dataset or list that you are paginating.
         * @param per_page The `` parameter in the `render_pagination` function represents the
         * number of items to display per page in a paginated list or table. It is used to calculate
         * the total number of pages based on the total number of items and the desired items per page.
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
         * The function `set_sortable_columns` sets the sortable columns for a given data set in PHP.
         * 
         * @param columns The `set_sortable_columns` function is used to define which columns in a
         * table should be sortable. The function takes an array of columns as a parameter and then
         * adds the columns that should be sortable to that array.
         * 
         * @return The `set_sortable_columns` function is returning an array with keys 'id',
         * 'project_name', 'name', and 'published', each mapped to their respective values 'id',
         * 'project_name', 'name', and 'published'.
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
         * The function custom_column_sorting in PHP checks and sets the orderby parameter in the query
         * based on valid columns.
         * 
         * @param query The `query` parameter in the `custom_column_sorting` function is an instance of
         * the WP_Query class. It represents the main query that is being processed. This function is
         * intended to modify the sorting behavior of the main query in the WordPress admin area based
         * on the specified valid columns.
         * 
         * @return If the conditions are met (is_admin() and  is the main query), the function
         * will return the modified  object with the 'orderby' parameter set to the value of the
         * requested column if it is one of the valid columns ('id', 'project_name', 'name',
         * 'published'). Otherwise, nothing will be returned.
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
