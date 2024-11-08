<?php
/* Template Name: Use Case Template */

if (!defined('ABSPATH')) {
    exit;
}

// Get the header of the other existing theme
get_header();


// Check if the post_id is set
if (isset($_GET['post_id'])) {

    // Get the post_id
    $post_id = intval($_GET['post_id']); 
    $post = get_post($post_id);

    // Check if the post exists
    if ($post) {
        
        // Get the post meta data
        $project_name = get_post_meta($post_id, 'project_name', true);
        $creator_name = get_post_meta($post_id, 'creator_name', true);
        $creator_email = get_post_meta($post_id, 'creator_email', true);
        $w_minor = get_post_meta($post_id, 'w_minor', true);
        $project_phase = get_post_meta($post_id, 'project_phase', true);
        $value_chain = get_post_meta($post_id, 'value_chain', true);
        $tech_innovations = get_post_meta($post_id, 'techn_innovations', true);
        $tech_providers = get_post_meta($post_id, 'tech_providers', true);
        $themes = get_post_meta($post_id, 'themes', true);
        $sdgs = get_post_meta($post_id, 'sdgs', true);
        $positive_impact_sdgs = get_post_meta($post_id, 'positive_impact_sdgs', true);
        $negative_impact_sdgs = get_post_meta($post_id, 'negative_impact_sdgs', true);
        $project_background = get_post_meta($post_id, 'project_background', true);
        $problem = get_post_meta($post_id, 'problem', true);
        $smart_goal = get_post_meta($post_id, 'smart_goal', true);
        $project_link = get_post_meta($post_id, 'project_link', true);
        $video_link = get_post_meta($post_id, 'video_link', true);
        $project_image = get_post_meta($post_id, 'project_image', true); 
        $innovation_sectors = get_post_meta($post_id, 'innovation_sectors', true);

        // Display the post meta data with html
        ?>
        <div class="use-case-details">
            <h1><?php echo esc_html($project_name); ?></h1>
            <p><strong>Project Owner :</strong> <?php echo esc_html($creator_name); ?></p>
            <p><strong>Email :</strong> <?php echo esc_html($creator_email); ?></p>
            <p><strong>Windesheim Minor :</strong> <?php echo esc_html($w_minor); ?></p>
            <p><strong>Project Phase :</strong> <?php echo esc_html($project_phase); ?></p>
            <p><strong>Value Chain :</strong> <?php echo esc_html(implode(', ', (array)$value_chain)); ?></p>
            <p><strong>Technological Innovations :</strong> <?php echo esc_html($tech_innovations); ?></p>
            <p><strong>Tech providers :</strong> <?php echo esc_html($tech_providers); ?></p>
            <p><strong>Innovation Sectors :</strong> <?php echo esc_html($innovation_sectors); ?></p>
            <p><strong>Themes :</strong> <?php echo esc_html(implode(', ', (array)$themes)); ?></p>
            <p><strong>SDGs :</strong> <?php echo esc_html(implode(', ', (array)$sdgs)); ?></p>
            <p><strong>Positive Impact SDGs :</strong> <?php echo esc_html($positive_impact_sdgs); ?></p>
            <p><strong>Negative Impact SDGs:</strong> <?php echo esc_html($negative_impact_sdgs); ?></p>
            <p><strong>Project Background :</strong> <?php echo esc_html($project_background); ?></p>
            <p><strong>Problem to Solve :</strong> <?php echo esc_html($problem); ?></p>
            <p><strong>Smart Goal :</strong> <?php echo esc_html($smart_goal); ?></p>
            <p><strong>Project Link:</strong> <a href="<?php echo esc_url($project_link); ?>" target="_blank"><?php echo esc_html($project_link); ?></a></p>
            <p><strong>Video Link :</strong> <a href="<?php echo esc_url($video_link); ?>" target="_blank"><?php echo esc_html($video_link); ?></a></p>
            <?php if ($project_image): ?>
                <!-- Style to change here -->
                <img src="<?php echo esc_url($project_image); ?>" alt="Project Image" style="max-width: 100%; height: auto;"> 
            <?php endif; ?>
            <div class="use-case-content">
                <?php echo apply_filters('the_content', $post->post_content); ?>
            </div>
        </div>
        <?php

    } else {
        echo '<p>No use case found</p>';
    }
} else {
    echo '<p>Missing ID</p>';
}

// Get the footer of the other existing theme
get_footer();