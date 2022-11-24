<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.productionsrhizome.org
 * @since      1.0.0
 *
 * @package    Wikibiographie
 * @subpackage Wikibiographie/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wikibiographie
 * @subpackage Wikibiographie/includes
 * @author     Productions Rhizome <info@productionsrhizome.org>
 */
class Wikibiographie_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'wikibiographie',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}
