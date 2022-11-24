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
        $page_title = 'Configuration de WikiBiographie';
        $menu_title = 'WikiBiographie';
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
        add_settings_section('wikibiographie_section', 'Paramètres de WikiBiographie', array(), 'wikibiographie');
    }

    public function wph_setup_fields()
    {
        $fields = array(
            array(
                'section' => 'wikibiographie_section',
                'label' => __('Durée de mise en cache des biographies provenant de Wikipédia', 'wikibiographie'),
                'id' => '_wikibiographie_cache_expiration_in_seconds',
                'type' => 'select',
                'options' => array(
                    DAY_IN_SECONDS => '1 jour',
                    WEEK_IN_SECONDS => '1 semaine (7 jours)',
                    MONTH_IN_SECONDS => '1 mois (30 jours)',
                    3 * MONTH_IN_SECONDS => '3 mois (90 jours)',
                    6 * MONTH_IN_SECONDS => '6 mois (180 jours)',
                ),
                'callback' => 'dropdown_callback',
                'tip' => 'La durée choisie n\'affectera pas les biographies déjà mises en cache tant qu\'elles ne seront pas expirées ou manuellement rafraîchies.',
            ),
            array(
                'section' => 'wikibiographie_section',
                'label' => __('Nombre maximum de caractères à afficher pour les descriptions', 'wikibiographie'),
                'id' => '_wikibiographie_maximum_description_length_in_characters',
                'type' => 'input',
                'callback' => 'input_callback',
                'tip' => 'Saisir 0 pour ne pas limiter le nombre de caractères.<br>WikiBiographie essaie dans la mesure du possible de tronquer le texte en fin de phrase.<br>À noter que si la première phrase est plus longue que le nombre de caractères choisi, la troncature s\'effectue plutôt à la fin d\'un mot.',
            ),
            array(
                'section' => 'wikibiographie_section',
                'label' => __('Choix des informations à afficher', 'wikibiographie'),
                'id' => '_wikibiographie_displayed_data',
                'options' => array(
                    'display_first_name' => 'Prénom',
                    'display_last_name' => 'Nom',
                    'display_pseudonym' => 'Pseudonyme',
                    'display_photo' => 'Photo',
                    'display_date_of_birth' => 'Date de naissance',
                    'display_place_of_birth' => 'Lieu de naissance',
                    'display_date_of_death' => 'Date de décès',
                    'display_place_of_death' => 'Lieu de décès',
                    'display_occupation' => 'Occupation',
                    'display_website' => 'Site officiel',
                    'display_description' => 'Description et description complémentaire',
                ),
                'callback' => 'checkbox_callback',
                'tip' => 'Seules les informations sélectionnées s\'affichent sur la page d\'une biographie',
            ),
            array(
                'section' => 'wikibiographie_reset_cache_section',
                'label' => __('Biographies Wikipédia en cache', 'wikibiographie'),
                'id' => '_wikibiographie_erase_cache',
                'type' => 'button',
                'callback' => 'button_callback',
                'tip' => 'Les données de chaque biographie Wikipédia seront récupérées et remises en cache à la prochaine visite.',
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
            add_settings_error('wikibiographie_max_char_errors', 'wikibiographie_message_max_char_numeric', __('Le nombre de caractères maximum pour les descriptions doit être un nombre.', 'wikibiographie'), 'error');
            return $old_options;
        }

        if ((int) $data['_wikibiographie_maximum_description_length_in_characters'] < 0 || (int) $data['_wikibiographie_maximum_description_length_in_characters'] > 5000) {
            add_settings_error('wikibiographie_max_char_errors', 'wikibiographie_message_max_char_values', __('Le nombre de caractères maximum pour les descriptions doit être plus grand ou égal à 0 et plus petit que 5000.', 'wikibiographie'), 'error');
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
        echo sprintf('<input name="wikibiographie_options[%s]" id="%s" type="text" value="%s" />', $field['id'], $field['id'], $options[$field['id']]);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo sprintf('<p class="error">%s</p>', $error['message']);
            }
        }

        if (!empty($field['tip'])) {
            echo sprintf('<p class="description">%s</p>', $field['tip']);
        }
    }

    public function button_callback($field)
    { ?>
        <div id="empty_cache_section">
            <button class="button" id="empty_cache" type="button">
                <?php _e("Vider les données mises en cache", 'wikibiographie'); ?>
                <img src="<?php echo plugin_dir_url(__FILE__) . 'loading.gif' ?>" alt="Loading..." class="loading-gif" id="empty_cache_loading">
            </button>
            <span class="result_msg" class="hide"></span>
        </div>
        <?php
        if (!empty($field['tip'])) {
            echo sprintf('<p class="description">%s</p>', $field['tip']);
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
                    $key,
                    checked($options[$key], true, false),
                    $label
                );
            }
            if (!empty($field['tip'])) {
                echo sprintf('<p class="description">%s</p>', $field['tip']);
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
                    $key,
                    selected($options[$field['id']], $key, false),
                    $label
                );
            }
            if ($field['type'] === 'multiselect') {
                $attr = ' multiple="multiple" ';
            }
            printf(
                '<select name="wikibiographie_options[%1$s]" id="%1$s" %2$s>%3$s</select>',
                $field['id'],
                $attr,
                $choices
            );
            if (!empty($field['tip'])) {
                echo sprintf('<p class="description">%s</p>', $field['tip']);
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
