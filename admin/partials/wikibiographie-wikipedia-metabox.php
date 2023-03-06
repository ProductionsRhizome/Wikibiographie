<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.productionsrhizome.org
 * @since      1.0.0
 *
 * @package    Wikibiographie
 * @subpackage Wikibiographie/admin/partials
 */
?>


<?php wp_nonce_field('biographie_metabox_nonce', 'wikipedia_nonce'); ?>

<form action="" id="wiki_add">
    <table id="wikipedia-metabox-table">
        <tr>
            <td>
                <label for="_biographie_custom_wikipedia_url"><?php _e("Wikipedia URL", 'wikibiographie'); ?></label><br>
                <input type="url" pattern="https://.*" name="_biographie_custom_wikipedia_url" value="<?php echo !empty($custom_data['_biographie_custom_wikipedia_url']) ? esc_url($custom_data['_biographie_custom_wikipedia_url']) : '' ?>" style="width: 400px;">
            </td>
        </tr>
        <tr>
            <td>
                <button class="button" id="refresh_wikidata" type="button">
                    <?php _e("Refresh Wikipedia info", 'wikibiographie'); ?>
                    <img src="<?php echo esc_attr(plugin_dir_url(__FILE__) . '../loading.gif') ?>" alt="<?php _e('Loading...', 'wikibiographie'); ?>" class="loading-gif" id="bio-loading">
                </button>
                <p class="error hide"></p>
            </td>
        </tr>
    </table>
</form>