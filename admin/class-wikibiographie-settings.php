<?php

/**
 * Class that handle the configuration of the plugin
 */
class WikiBiographie_Settings_Page
{
    public const DISPLAY_OPTIONS = [
        'display_first_name',
        'display_last_name',
        'display_pseudonym',
        'display_photo',
        'display_date_of_birth',
        'display_place_of_birth',
        'display_date_of_death',
        'display_place_of_death',
        'display_occupation',
        'display_website',
        'display_description',
    ];

    public function __construct()
    {
        add_action('admin_menu', array( $this, 'wph_create_settings' ));
        add_action('admin_init', array( $this, 'wph_setup_sections' ));
        add_action('admin_init', array( $this, 'wph_setup_fields' ));
        add_action('wp_ajax_empty_cache', array( $this, 'ajax_empty_cache' ));
    }

    public function wph_create_settings()
    {
        $page_title = __('WikiBiographie configuration', 'wikibiographie');
        $menu_title = __('WikiBiographie', 'wikibiographie');
        $capability = 'manage_options';
        $slug = 'wikibiographie';
        $callback = array($this, 'wph_settings_content');
        add_options_page($page_title, $menu_title, $capability, $slug, $callback);
    }

    public function wph_settings_content()
    { ?>
        <div class="wrap">
            <h1>WikiBiographie</h1>
            <form method="POST" action="options.php">
                <?php
                    settings_fields('wikibiographie');
                    do_settings_sections('wikibiographie');
                    submit_button();
                ?>
            </form>
        </div> <?php
    }

    public function wph_setup_sections()
    {
        add_settings_section('wikibiographie_reset_cache_section', 'Actions', array(), 'wikibiographie');
        add_settings_section('wikibiographie_section', __('WikiBiographie parameters', 'wikibiographie'), array(), 'wikibiographie');
    }

    public function wph_setup_fields()
    {
        $fields = array(
            array(
                'section' => 'wikibiographie_section',
                'label' => __('Cache duration', 'wikibiographie'),
                'id' => '_wikibiographie_cache_expiration_in_seconds',
                'type' => 'select',
                'options' => array(
                    DAY_IN_SECONDS => __('1 day', 'wikibiographie'),
                    WEEK_IN_SECONDS => __('1 week (7 days)', 'wikibiographie'),
                    MONTH_IN_SECONDS => __('1 month (30 days)', 'wikibiographie'),
                    3 * MONTH_IN_SECONDS => __('3 months (90 days)', 'wikibiographie'),
                    6 * MONTH_IN_SECONDS => __('6 months (180 days)', 'wikibiographie'),
                ),
                'callback' => 'dropdown_callback',
                'tip' => __('The choosen duration won\'t affect current biographies until they are expired or manually refreshed.', 'wikibiographie'),
            ),
            array(
                'section' => 'wikibiographie_section',
                'label' => __('Maximum characters for descriptions', 'wikibiographie'),
                'id' => '_wikibiographie_maximum_description_length_in_characters',
                'type' => 'input',
                'callback' => 'input_callback',
                'tip' => __('Enter 0 for no limit.', 'wikibiographie'),
            ),
            array(
                'section' => 'wikibiographie_section',
                'label' => __('Choose information do display', 'wikibiographie'),
                'id' => '_wikibiographie_displayed_data',
                'options' => array(
                    'display_first_name' => __('First name', 'wikibiographie'),
                    'display_last_name' => __('Last name', 'wikibiographie'),
                    'display_pseudonym' => __('Nickname', 'wikibiographie'),
                    'display_photo' => __('Photo', 'wikibiographie'),
                    'display_date_of_birth' => __('Date of birth', 'wikibiographie'),
                    'display_place_of_birth' => __('Place of birth', 'wikibiographie'),
                    'display_date_of_death' => __('Date of death', 'wikibiographie'),
                    'display_place_of_death' => __('Place of death', 'wikibiographie'),
                    'display_occupation' => __('Occupation', 'wikibiographie'),
                    'display_website' => __('Official website', 'wikibiographie'),
                    'display_description' => __('Description and complimentary description', 'wikibiographie'),
                ),
                'callback' => 'checkbox_callback',
                'tip' => __('Only the selected information will be displayed in the biography.', 'wikibiographie'),
            ),
            array(
                'section' => 'wikibiographie_reset_cache_section',
                'label' => __('Cached biographies', 'wikibiographie'),
                'id' => '_wikibiographie_erase_cache',
                'type' => 'button',
                'callback' => 'button_callback',
                'tip' => __('The biography data will be fetched and put into cache upon the next visit.', 'wikibiographie'),
            ),
        );

        register_setting('wikibiographie', 'wikibiographie_options', [
            'type'              => 'array',
            'sanitize_callback' => [$this, 'prefix_sanitize_options'],
        ]);

        foreach ($fields as $field) {
            add_settings_field($field['id'], $field['label'], array( $this, $field['callback'] ), 'wikibiographie', $field['section'], $field);
        }
    }

    public function prefix_sanitize_options($data)
    {
        $old_options = $this->get_options();
        $default_checkbox_options = [];
        foreach (self::DISPLAY_OPTIONS as $option) {
            $default_checkbox_options[$option] = false;
        }

        if (!is_numeric($data['_wikibiographie_maximum_description_length_in_characters'])) {
            add_settings_error('wikibiographie_max_char_errors', 'wikibiographie_message_max_char_numeric', __('The maximum description length must be a number.', 'wikibiographie'), 'error');
            return $old_options;
        }

        if ((int) $data['_wikibiographie_maximum_description_length_in_characters'] < 0 || (int) $data['_wikibiographie_maximum_description_length_in_characters'] > 5000) {
            add_settings_error('wikibiographie_max_char_errors', 'wikibiographie_message_max_char_values', __('The number of characters must be greater than 0 and less than 5000.', 'wikibiographie'), 'error');
            return $old_options;
        }
        foreach (array_keys($data) as $k) {
            if ($data[$k] === 'true') {
                $data[$k] = true;
            } elseif ($data[$k] === 'false') {
                $data[$k] = false;
            }
        }
        return array_merge($default_checkbox_options, $data);
    }

    public function input_callback($field)
    {
        $options = $this->get_options();
        $errors = get_settings_errors('wikibiographie_max_char_errors');
        echo sprintf('<input name="wikibiographie_options[%s]" id="%s" type="text" value="%s" />', esc_attr($field['id']), esc_attr($field['id']), esc_attr($options[$field['id']]));

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo sprintf('<p class="error">%s</p>', esc_attr($error['message']));
            }
        }

        if (!empty($field['tip'])) {
            echo sprintf('<p class="description">%s</p>', wp_kses_post($field['tip']));
        }
    }

    public function button_callback($field)
    { ?>
        <div id="empty_cache_section">
            <button class="button" id="empty_cache" type="button">
                <?php _e("Empty cache data", 'wikibiographie'); ?>
                <img src="<?php echo plugin_dir_url(__FILE__) . 'loading.gif' ?>" alt="<?php _e('Loading...', 'wikibiographie'); ?>" class="loading-gif" id="empty_cache_loading">
            </button>
            <span class="result_msg" class="hide"></span>
        </div>
        <?php
        if (!empty($field['tip'])) {
            echo sprintf('<p class="description">%s</p>', esc_attr($field['tip']));
        }
    }

    public function checkbox_callback($field)
    {
        $options = $this->get_options();
        if (! empty($field['options']) && is_array($field['options'])) {
            $attr = '';
            $choices = '';
            foreach ($field['options'] as $key => $label) {
                $choices.= printf(
                    '<input type="checkbox" name="wikibiographie_options[%1$s]" value="true" %2$s><label for="%1$s">%3$s</label><br>',
                    esc_attr($key),
                    checked($options[$key], true, false),
                    esc_attr($label)
                );
            }
            if (!empty($field['tip'])) {
                echo sprintf('<p class="description">%s</p>', esc_attr($field['tip']));
            }
        }
    }

    public function dropdown_callback($field)
    {
        $options = $this->get_options();
        if (! empty($field['options']) && is_array($field['options'])) {
            $attr = '';
            $choices = '';
            foreach ($field['options'] as $key => $label) {
                $choices.= sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($key),
                    selected($options[$field['id']], $key, false),
                    esc_attr($label)
                );
            }
            if ($field['type'] === 'multiselect') {
                $attr = ' multiple="multiple" ';
            }
            printf(
                '<select name="wikibiographie_options[%1$s]" id="%1$s" %2$s>%3$s</select>',
                esc_attr($field['id']),
                esc_attr($attr),
                $choices
            );
            if (!empty($field['tip'])) {
                echo sprintf('<p class="description">%s</p>', esc_attr($field['tip']));
            }
        }
    }

    public function get_options()
    {
        $default_options = array(
            '_wikibiographie_cache_expiration_in_seconds' => MONTH_IN_SECONDS,
            '_wikibiographie_maximum_description_length_in_characters' => 5000,
        );
        foreach (self::DISPLAY_OPTIONS as $option) {
            $default_options[$option] = true;
        }
        $current_options = get_option('wikibiographie_options');
        if (false === $current_options) {
            $current_options = [];
        }
        return array_merge($default_options, $current_options);
    }

    public function ajax_empty_cache()
    {
        try {
            $biographies = get_posts(array('post_type' => 'biographie'));
            foreach ($biographies as $biography) {
                $wikiUrl = get_post_meta($biography->ID, '_biographie_custom_wikipedia_url');
                if (!empty($wikiUrl)) {
                    delete_transient('_biographie_wiki_'.$biography->ID);
                }
            }
            delete_expired_transients();
            wp_send_json('success');
        } catch (\Exception $e) {
            wp_send_json(['error' => $e->getMessage()], 422);
        }
    }
}
new WikiBiographie_Settings_Page();
