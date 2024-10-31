<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UseCaseLibraryDetailsPage {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_details_page'));
        add_action('admin_post_publish_use_case', array($this, 'publish_use_case'));
        add_action('admin_post_delete_use_case', array($this, 'delete_use_case'));
    }

    public function add_details_page() {
        add_submenu_page(
            null, // Pas de menu parent
            'Détails du Use Case',
            'Détails du Use Case',
            'manage_options',
            'use-case-details',
            array($this, 'render_details_page')
        );
    }

    public function render_details_page() {
        if (!isset($_GET['post_id'])) {
            return;
        }

        $post_id = intval($_GET['post_id']);
        $post = get_post($post_id);

        if (!$post) {
            echo '<div class="wrap"><h1>Use Case non trouvé</h1></div>';
            return;
        }

        $project_name = get_post_meta($post_id, 'project_name', true);
        $creator_name = get_post_meta($post_id, 'creator_name', true);
        $status = get_post_meta($post_id, 'status', true);

        echo '<div class="wrap">';
        echo '<h1>Détails du Use Case</h1>';
        echo '<p><strong>Nom du projet :</strong> ' . esc_html($project_name) . '</p>';
        echo '<p><strong>Propriétaire du projet :</strong> ' . esc_html($creator_name) . '</p>';
        echo '<p><strong>Statut :</strong> ' . esc_html($status) . '</p>';
        echo '<form method="post" action="' . admin_url('admin-post.php') . '">';
        echo '<input type="hidden" name="action" value="publish_use_case">';
        echo '<input type="hidden" name="post_id" value="' . esc_attr($post_id) . '">';
        echo '<button type="submit" class="button button-primary">Publier</button> ';
        echo '</form>';
        echo '<form method="post" action="' . admin_url('admin-post.php') . '">';
        echo '<input type="hidden" name="action" value="delete_use_case">';
        echo '<input type="hidden" name="post_id" value="' . esc_attr($post_id) . '">';
        echo '<button type="submit" class="button button-secondary" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce use case ?\');">Supprimer</button>';
        echo '</form>';
        echo '</div>';
    }

    public function publish_use_case() {
        if (!isset($_POST['post_id'])) {
            wp_redirect(admin_url('admin.php?page=use-case-details'));
            exit;
        }

        $post_id = intval($_POST['post_id']);
        update_post_meta($post_id, 'status', 'published');
        wp_redirect(admin_url('admin.php?page=use-case-details&post_id=' . $post_id));
        exit;
    }

    public function delete_use_case() {
        if (!isset($_POST['post_id'])) {
            wp_redirect(admin_url('admin.php?page=use-case-details'));
            exit;
        }

        $post_id = intval($_POST['post_id']);
        wp_delete_post($post_id, true);
        wp_redirect(admin_url('edit.php?post_type=use-case-library'));
        exit;
    }
}

new UseCaseLibraryDetailsPage();