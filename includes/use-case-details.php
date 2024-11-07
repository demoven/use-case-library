<?php
/* Template Name: Use Case Details */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);

    if ($post) {
        $project_name = get_post_meta($post_id, 'project_name', true);
        $creator_name = get_post_meta($post_id, 'creator_name', true);
        ?>
        <div class="use-case-details">
            <h1><?php echo esc_html($project_name); ?></h1>
            <p><strong>Project Owner :</strong> <?php echo esc_html($creator_name); ?></p>
            <div class="use-case-content">
                <?php echo apply_filters('the_content', $post->post_content); ?>
            </div>
        </div>
        <?php
    } else {
        echo '<p>No use case found</p>';
    }
} else {
    echo '<p>ID not found</p>';
}

get_footer();