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
            <?php _e("Wikipédia", 'wikibiographie'); ?>
        </th>
        <th>
            <?php _e("Personnalisé", 'wikibiographie'); ?>
        </th>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Prénom", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_first_name" value="<?php echo !empty($meta_data['_biographie_wiki_first_name']) ? $meta_data['_biographie_wiki_first_name'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Prénom", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_first_name" value="<?php echo !empty($meta_data['_biographie_custom_first_name']) ? $meta_data['_biographie_custom_first_name'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Nom", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_last_name" value="<?php echo !empty($meta_data['_biographie_wiki_last_name']) ? $meta_data['_biographie_wiki_last_name'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Nom", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_last_name" value="<?php echo !empty($meta_data['_biographie_custom_last_name']) ? $meta_data['_biographie_custom_last_name'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Pseudo", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_pseudonym" value="<?php echo !empty($meta_data['_biographie_wiki_pseudonym']) ? $meta_data['_biographie_wiki_pseudonym'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Pseudo", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_pseudo" value="<?php echo !empty($meta_data['_biographie_custom_pseudo']) ? $meta_data['_biographie_custom_pseudo'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Photo", 'wikibiographie'); ?></label>
            <img id="_biographie_wiki_image" src="<?php echo !empty($meta_data['_biographie_wiki_image']) ? $meta_data['_biographie_wiki_image'] : null ?>" style="max-width: 300px;">
            <input type="hidden" name="_biographie_wiki_image" value="<?php echo !empty($meta_data['_biographie_wiki_image']) ? $meta_data['_biographie_wiki_image'] : null ?>">
        </td>
        <td>
            <label for=""><?php _e("Photo", 'wikibiographie'); ?></label>
            <div class="info"><?php _e("Pour personnaliser la photo principale de la biographie, veuillez définir une image mise en avant.", 'wikibiographie') ?></div>
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Date de naissance", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_date_of_birth" value="<?php echo !empty($meta_data['_biographie_wiki_date_of_birth']) ? $meta_data['_biographie_wiki_date_of_birth'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Date de naissance", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_date_naissance" value="<?php echo !empty($meta_data['_biographie_custom_date_naissance']) ? $meta_data['_biographie_custom_date_naissance'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Lieu de naissance", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_place_of_birth" value="<?php echo !empty($meta_data['_biographie_wiki_place_of_birth']) ? $meta_data['_biographie_wiki_place_of_birth'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Lieu de naissance", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_lieu_naissance" value="<?php echo !empty($meta_data['_biographie_custom_lieu_naissance']) ? $meta_data['_biographie_custom_lieu_naissance'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Date de décès", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_date_of_death" value="<?php echo !empty($meta_data['_biographie_wiki_date_of_death']) ? $meta_data['_biographie_wiki_date_of_death'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Date de décès", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_date_deces" value="<?php echo !empty($meta_data['_biographie_custom_date_deces']) ? $meta_data['_biographie_custom_date_deces'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Lieu de décès", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_place_of_death" value="<?php echo !empty($meta_data['_biographie_wiki_place_of_death']) ? $meta_data['_biographie_wiki_place_of_death'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Lieu de décès", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_lieu_deces" value="<?php echo !empty($meta_data['_biographie_custom_lieu_deces']) ? $meta_data['_biographie_custom_lieu_deces'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Occupation", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_occupation" value="<?php echo !empty($meta_data['_biographie_wiki_occupation']) ? $meta_data['_biographie_wiki_occupation'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Occupation", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_occupation" value="<?php echo !empty($meta_data['_biographie_custom_occupation']) ? $meta_data['_biographie_custom_occupation'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Site officiel", 'wikibiographie'); ?></label>
            <div class="wiki">
                <input type="text" name="_biographie_wiki_website" value="<?php echo !empty($meta_data['_biographie_wiki_website']) ? $meta_data['_biographie_wiki_website'] : null ?>" readonly>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Site officiel", 'wikibiographie'); ?></label>
            <input type="text" name="_biographie_custom_site_officiel" value="<?php echo !empty($meta_data['_biographie_custom_site_officiel']) ? $meta_data['_biographie_custom_site_officiel'] : null ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for=""><?php _e("Description", 'wikibiographie'); ?></label>
            <div class="wiki">
                <textarea name="_biographie_wiki_introduction" cols="30" rows="10" readonly><?php echo !empty($meta_data['_biographie_wiki_introduction']) ? $meta_data['_biographie_wiki_introduction'] : null ?></textarea>
            </div>
        </td>
        <td>
            <label for=""><?php _e("Description", 'wikibiographie'); ?></label>
            <textarea name="_biographie_custom_description" cols="30" rows="10"><?php echo !empty($meta_data['_biographie_custom_description']) ? $meta_data['_biographie_custom_description'] : null ?></textarea>

            <label for=""><?php _e("Description complémentaire (s'affichera à la suite de la description)", 'wikibiographie'); ?></label>
            <textarea name="_biographie_custom_description_complementaire" cols="30" rows="10"><?php echo !empty($meta_data['_biographie_custom_description_complementaire']) ? $meta_data['_biographie_custom_description_complementaire'] : null ?></textarea>
        </td>
    </tr>
</table>