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


<?php wp_nonce_field('biographie_metabox_nonce', 'biographie_nonce'); ?>

<table id="wikibiographie-metabox-table">
    <tr>
        <th>
            <?php _e("Wikipedia", 'wikibiographie'); ?>
        </th>
        <th>
            <?php _e("Custom", 'wikibiographie'); ?>
        </th>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("First name", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_first_name" value="<?php echo !empty($meta_data['_biographie_wiki_first_name']) ? esc_attr($meta_data['_biographie_wiki_first_name']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("First name", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_first_name" value="<?php echo !empty($meta_data['_biographie_custom_first_name']) ? esc_attr($meta_data['_biographie_custom_first_name']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Last name", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_last_name" value="<?php echo !empty($meta_data['_biographie_wiki_last_name']) ? esc_attr($meta_data['_biographie_wiki_last_name']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Last name", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_last_name" value="<?php echo !empty($meta_data['_biographie_custom_last_name']) ? esc_attr($meta_data['_biographie_custom_last_name']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Nickname", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_pseudonym" value="<?php echo !empty($meta_data['_biographie_wiki_pseudonym']) ? esc_attr($meta_data['_biographie_wiki_pseudonym']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Nickname", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_pseudo" value="<?php echo !empty($meta_data['_biographie_custom_pseudo']) ? esc_attr($meta_data['_biographie_custom_pseudo']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Photo", 'wikibiographie'); ?></label>
            <img id="_biographie_wiki_image" src="<?php echo !empty($meta_data['_biographie_wiki_image']) ? esc_url($meta_data['_biographie_wiki_image']) : null ?>" style="max-width: 300px;">
            <input type="hidden" name="_biographie_wiki_image" value="<?php echo !empty($meta_data['_biographie_wiki_image']) ? esc_url($meta_data['_biographie_wiki_image']) : null ?>">
        </td>
        <td>
            <label for=""><?php _e("Photo", 'wikibiographie'); ?></label>
            <div class="info"><?php _e("To customize the photo, set a featured image.", 'wikibiographie') ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Date of birth", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_date_of_birth" value="<?php echo !empty($meta_data['_biographie_wiki_date_of_birth']) ? esc_attr($meta_data['_biographie_wiki_date_of_birth']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Date of birth", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_date_naissance" value="<?php echo !empty($meta_data['_biographie_custom_date_naissance']) ? esc_attr($meta_data['_biographie_custom_date_naissance']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Place of birth", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_place_of_birth" value="<?php echo !empty($meta_data['_biographie_wiki_place_of_birth']) ? esc_attr($meta_data['_biographie_wiki_place_of_birth']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Place of birth", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_lieu_naissance" value="<?php echo !empty($meta_data['_biographie_custom_lieu_naissance']) ? esc_attr($meta_data['_biographie_custom_lieu_naissance']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Date of death", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_date_of_death" value="<?php echo !empty($meta_data['_biographie_wiki_date_of_death']) ? esc_attr($meta_data['_biographie_wiki_date_of_death']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Date of death", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_date_deces" value="<?php echo !empty($meta_data['_biographie_custom_date_deces']) ? esc_attr($meta_data['_biographie_custom_date_deces']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Place of death", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_place_of_death" value="<?php echo !empty($meta_data['_biographie_wiki_place_of_death']) ? esc_attr($meta_data['_biographie_wiki_place_of_death']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Place of death", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_lieu_deces" value="<?php echo !empty($meta_data['_biographie_custom_lieu_deces']) ? esc_attr($meta_data['_biographie_custom_lieu_deces']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Occupation", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_occupation" value="<?php echo !empty($meta_data['_biographie_wiki_occupation']) ? esc_attr($meta_data['_biographie_wiki_occupation']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Occupation", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_occupation" value="<?php echo !empty($meta_data['_biographie_custom_occupation']) ? esc_attr($meta_data['_biographie_custom_occupation']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Official website", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_website" value="<?php echo !empty($meta_data['_biographie_wiki_website']) ? esc_url($meta_data['_biographie_wiki_website']) : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Official website", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_site_officiel" value="<?php echo !empty($meta_data['_biographie_custom_site_officiel']) ? esc_url($meta_data['_biographie_custom_site_officiel']) : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Description", 'wikibiographie'); ?></label>
            <div class="wiki">
                <textarea name="_biographie_wiki_introduction" cols="30" rows="10" readonly><?php echo !empty($meta_data['_biographie_wiki_introduction']) ? esc_attr($meta_data['_biographie_wiki_introduction']) : null ?></textarea>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Description", 'wikibiographie'); ?></label>
            <textarea name="_biographie_custom_description" id="biographie_custom_description" cols="30" rows="10"><?php echo !empty($meta_data['_biographie_custom_description']) ? esc_attr($meta_data['_biographie_custom_description']) : null ?></textarea>

            <label for=""><?php _e("Complementary description (will be added after the description)", 'wikibiographie'); ?></label>
            <textarea name="_biographie_custom_description_complementaire" id="biographie_custom_description_complementaire" cols="30" rows="10"><?php echo !empty($meta_data['_biographie_custom_description_complementaire']) ? esc_attr($meta_data['_biographie_custom_description_complementaire']) : null ?></textarea>
        </td>
    </tr>
</table>
<?php
    // Setup TinyMCE editor
    $args = [
        'media_buttons' => false,
        'quicktags' => false,
    ];
wp_editor('', 'biographie_custom_description', $args);
wp_editor('', 'biographie_custom_description_complementaire', $args);
?>