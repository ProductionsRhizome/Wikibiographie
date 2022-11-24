<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.productionsrhizome.org
 * @since      1.0.0
 *
 * @package    Wikibiographie
 * @subpackage Wikibiographie/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wikibiographie
 * @subpackage Wikibiographie/includes
 * @author     Productions Rhizome <info@productionsrhizome.org>
 */
class Wikibiographie_Activator {

    /**
     * Setup default values for the plugin options.
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        $options = get_option('wikibiographie_options');
        if (empty($options)) {
            update_option('wikibiographie_options', array(
                '_wikibiographie_cache_expiration_in_seconds' => MONTH_IN_SECONDS,
                '_wikibiographie_maximum_description_length_in_characters' => 5000,
                'display_name' => true,
                'display_pseudonym' => true,
                'display_photo' => true,
                'display_date_of_birth' => true,
                'display_place_of_birth' => true,
                'display_date_of_death' => true,
                'display_place_of_death' => true,
                'display_occupation' => true,
                'display_website' => true,
                'display_description' => true
            ));
        }
    }

}
