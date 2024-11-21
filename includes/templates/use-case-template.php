<?php
/* Template Name: Use Case Template */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue the CSS for this template
function enqueue_use_case_template_styles()
{
    if (is_page_template('use-case-template.php')) {
        wp_enqueue_style(
            'use-case-template-style',
            plugin_dir_url(__FILE__) . '../../assets/css/template.css',
            array(),
            '1.0',
            'all'
        );
    }
}

add_action('wp_enqueue_scripts', 'enqueue_use_case_template_styles');

// Get the header of the other existing theme
get_header();


// Check if the post_id is set
if (isset($_GET['post_id'])) {

    // Get the post_id
    $post_id = intval($_GET['post_id']);


    // Get the use case data from the custom table
    global $wpdb;
    $table_name = $wpdb->prefix . 'use_case';
    $use_case = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $post_id));

    // Check if the use case exists
    if ($use_case) {


        // Get the use case data
        $project_name = maybe_unserialize($use_case->project_name);
        $project_image = maybe_unserialize($use_case->project_image);
        $project_phase = maybe_unserialize($use_case->project_phase);
        $themes = maybe_unserialize($use_case->themes);
        $value_chain = maybe_unserialize($use_case->value_chain);
        $w_minor = maybe_unserialize($use_case->w_minor);
        $innovation_sectors = maybe_unserialize($use_case->innovation_sectors);
        $sdgs = maybe_unserialize($use_case->sdgs);
        $creator_name = maybe_unserialize($use_case->name);
        $creator_email = maybe_unserialize($use_case->creator_email);
        $project_background = maybe_unserialize($use_case->project_background);
        $problem = maybe_unserialize($use_case->problem);
        $tech_innovations = maybe_unserialize($use_case->techn_innovations);
        $smart_goal = maybe_unserialize($use_case->smart_goal);
        $positive_impact_sdgs = maybe_unserialize($use_case->positive_impact_sdgs);
        $negative_impact_sdgs = maybe_unserialize($use_case->negative_impact_sdgs);
        $tech_providers = maybe_unserialize($use_case->tech_providers);
        $project_link = maybe_unserialize($use_case->project_link);
        $video_link = maybe_unserialize($use_case->video_link);


        // Display the post meta data with html
        ?>
        <div id="strip">
            <div id="color-strip">
                <h1><?php echo esc_html($project_name); ?></h1>
            </div>
            <?php if ($project_image): ?>
                <div class="image-container">
                    <img id="image-strip" src="<?php echo esc_url($project_image); ?>" alt="Project Image">
                </div>
            <?php endif; ?>
        </div>
        <div id="container">
            <div class="informations">
                <div class="tags">
                    <div class="tags-title"><i class="fa-solid fa-list-check icon-margin"></i>project phase</div>
                    <div class="tags-content"><?php echo esc_html($project_phase); ?></div>
                </div>
                <div class="tags">
                    <div class="tags-title"><i class="fa-solid fa-arrow-trend-up icon-margin"></i>trends</div>
                    <div class="tags-content">
                        <?php
                        $themes_array = (array)$themes;
                        foreach ($themes_array as $theme) {
                            echo '<div>' . esc_html($theme) . '</div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="tags">
                    <div class="tags-title"><i class="fa-solid fa-industry icon-margin"></i>value chain</div>
                    <div class="tags-content">
                        <?php
                        $value_chain_array = (array)$value_chain;
                        foreach ($value_chain_array as $value) {
                            echo '<div>' . esc_html($value) . '</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php if ($w_minor): ?>
                    <div class="tags">
                        <div class="tags-title"><i class="fa-solid fa-graduation-cap icon-margin"></i>windesheim minor
                        </div>
                        <div class="tags-content"><?php echo esc_html($w_minor); ?></div>
                    </div>
                <?php endif; ?>
                <div class="tags">
                    <div class="tags-title"><i class="fa-solid fa-lightbulb icon-margin"></i>innovation sector</div>
                    <div class="tags-content"><?php echo esc_html($innovation_sectors); ?></div>
                </div>
                <div class="tags">
                    <div class="tags-title"><i class="fa-solid fa-table-cells icon-margin"></i>SDGs</div>
                    <div class="tags-content">
                        <?php
                        $sdgs_array = (array)$sdgs;
                        foreach ($sdgs_array as $sdg) {
                            echo '<div>' . esc_html($sdg) . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="project-owner-informations">
                <div class="po-info"><i
                            class="fa-regular fa-user icon-margin"></i>
                    <span><?php echo esc_html($creator_name); ?></span>
                </div>
                <div class="po-info">
                    <i class="fa-regular fa-envelope icon-margin"></i>
                    <span><?php echo esc_html($creator_email); ?></span>
                </div>
                <div class="po-info"><i class="fa-solid fa-link icon-margin"></i><a
                            href="<?php echo esc_url($project_link); ?>"
                            target="_blank"><?php echo esc_html($project_link); ?></a></div>
                <?php if ($video_link): ?>
                    <div class="po-info"><i class="fa-regular fa-circle-play icon-margin"></i><a
                                href="<?php echo esc_url($video_link); ?>"
                                target="_blank"><?php echo esc_html($video_link); ?></a></div>
                <?php endif; ?>
            </div>
            <div id="use-case-details">
                <div class="project-informations">
                    <h3 class="project-informtions-title">Project Background</h3>
                    <p class="project-informations-content"><?php echo esc_html($project_background); ?></p>
                </div>
                <div class="project-informations">
                    <h3 class="project-informtions-title">Problem to Solve</h3>
                    <p class="project-informations-content"><?php echo esc_html($problem); ?></p>
                </div>
                <div class="project-informations">
                    <h3 class="project-informtions-title">Technological Innovations</h3>
                    <p class="project-informations-content"> <?php echo esc_html($tech_innovations); ?></p>
                </div>
                <div class="project-informations">
                    <h3 class="project-informtions-title">Smart Goal</h3>
                    <p class="project-informations-content"><?php echo esc_html($smart_goal); ?></p>
                </div>
                <?php if ($positive_impact_sdgs): ?>
                    <div class="project-informations">
                        <h3 class="project-informtions-title">Positive Impact SDGs</h3>
                        <p class="project-informations-content"><?php echo esc_html($positive_impact_sdgs); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($negative_impact_sdgs): ?>
                    <div class="project-informations">
                        <h3 class="project-informtions-title">Negative Impact SDGs</h3>
                        <p class="project-informations-content"><?php echo esc_html($negative_impact_sdgs); ?></p>
                    </div>
                <?php endif; ?>
                <div class="project-informations">
                    <h3 class="project-informtions-title">Tech providers</h3>
                    <p class="project-informations-content"><?php echo esc_html($tech_providers); ?></p>
                </div>

                <div class="use-case-content">
                    <?php echo apply_filters('the_content', $post->post_content); ?>
                </div>
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