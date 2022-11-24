<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.productionsrhizome.org
 * @since      1.0.0
 *
 * @package    Wikibiographie
 * @subpackage Wikibiographie/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wikibiographie
 * @subpackage Wikibiographie/includes
 * @author     Productions Rhizome <info@productionsrhizome.org>
 */
class Wikibiographie
{
    public const DEFAULT_USERNAME = 'ProductionsRhizome';
    public const DEFAULT_REPOSITORY = 'Wikibiographie';

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wikibiographie_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    public const POST_TYPE = 'biographie';

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WIKIBIOGRAPHIE_VERSION')) {
            $this->version = WIKIBIOGRAPHIE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'wikibiographie';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->init_updater();

        $this->loader->add_action('init', $this, 'register_biographie_post_type');
        $this->loader->add_action('wp_ajax_fetch_wikidata', $this, 'ajax_fetch_wikidata');
        $this->loader->add_action('pre_get_posts', $this, 'order_archive_by_title');

        $this->loader->add_filter('single_template', $this, 'load_biographie_single_template');
        $this->loader->add_filter('archive_template', $this, 'load_biographie_archive_template');

        add_action('init', function () {
            global $wp;
            $wp->add_query_var('biographie_s');
        });
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wikibiographie_Loader. Orchestrates the hooks of the plugin.
     * - Wikibiographie_i18n. Defines internationalization functionality.
     * - Wikibiographie_Admin. Defines all hooks for the admin area.
     * - Wikibiographie_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wikibiographie-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wikibiographie-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wikibiographie-admin.php';

        /**
         * The class responsible creating the plugin settings page.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wikibiographie-settings.php';

        /**
         * The class responsible for fetching updates of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wikibiographie-updater.php';

        $this->loader = new Wikibiographie_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wikibiographie_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Wikibiographie_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Wikibiographie_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_action('add_meta_boxes_biographie', $this, 'register_biographie_meta_box');
        $this->loader->add_action('save_post', $this, 'save_biographie_meta_box_data');
    }

    /**
     * Initialize the updater of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function init_updater()
    {
        $plugin_updater = new Wikibiographie_Updater(plugin_dir_path(dirname(__FILE__)) . '/wikibiographie.php');
        $plugin_updater->set_username(self::DEFAULT_USERNAME);
        $plugin_updater->set_repository(self::DEFAULT_REPOSITORY);
        $plugin_updater->initialize();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Wikibiographie_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    public function ajax_fetch_wikidata()
    {
        $url = $_POST['wikipediaUrl'];

        try {
            if (empty($url)) {
                throw new Exception('Vous devez fournir un URL Wikipédia valide.');
            }

            $data = $this->fetch_wikidata($url);
        } catch (\Exception $e) {
            wp_send_json(['error' => $e->getMessage()], 422);
        }

        wp_send_json($data);
    }

    public function fetch_wikidata($url)
    {
        require_once plugin_dir_path(__FILE__) . '../service/WikiDataService.php';
        return WikiDataService::query($url);
    }

    /**
     * Register the Biographie custom post type.
     *
     * @return void
     */
    public function register_biographie_post_type()
    {
        $args = [
            'label'  => esc_html__('Biographies', 'text-domain'),
            'labels' => [
                'menu_name'          => esc_html__('Biographies', 'wikibiographie'),
                'name_admin_bar'     => esc_html__('Biographie', 'wikibiographie'),
                'add_new'            => esc_html__('Ajouter une biographie', 'wikibiographie'),
                'add_new_item'       => esc_html__('Nouvelle biographie', 'wikibiographie'),
                'new_item'           => esc_html__('Nouvelle biographie', 'wikibiographie'),
                'edit_item'          => esc_html__('Modifier la biographie', 'wikibiographie'),
                'view_item'          => esc_html__('Voir la biographie', 'wikibiographie'),
                'update_item'        => esc_html__('Modifier la biographie', 'wikibiographie'),
                'all_items'          => esc_html__('Toutes les biographies', 'wikibiographie'),
                'search_items'       => esc_html__('Rechercher une biographie', 'wikibiographie'),
                'parent_item_colon'  => esc_html__('Biographie parente', 'wikibiographie'),
                'not_found'          => esc_html__('Aucune biographie trouvée', 'wikibiographie'),
                'not_found_in_trash' => esc_html__('Aucune biographie dans la corbeille', 'wikibiographie'),
                'name'               => esc_html__('Biographies', 'wikibiographie'),
                'singular_name'      => esc_html__('Biographie', 'wikibiographie'),
            ],
            'public'              => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite_no_front'    => false,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-id-alt',
            'supports' => [
                'title',
                'thumbnail',
                'revisions',
            ],
            'rewrite' => true
        ];

        register_post_type(self::POST_TYPE, $args);
        add_theme_support('post-thumbnails', array( self::POST_TYPE ));
    }

    /**
     * Register the Biographie meta box that we add to the custom post type.
     *
     * @return void
     */
    public function register_biographie_meta_box()
    {
        add_meta_box(
            'biographie',
            __('Wikipédia', 'wikibiographie'),
            [$this, 'wikipedia_meta_box_callback']
        );
        add_meta_box(
            'wikipedia',
            __('Informations biographiques', 'wikibiographie'),
            [$this, 'wikibiographie_meta_box_callback']
        );
    }

    /**
     * Load the form and data of the Biographie meta box.
     *
     * @param $post
     *
     * @return void
     */
    public function wikibiographie_meta_box_callback($post)
    {
        // Add a nonce field so we can check for it later.
        wp_nonce_field('wikibiographie_nonce', 'wikibiographie_nonce');

        // Get all the meta data of the Biographie so that we can fill the form with it.
        $meta_data = $this->get_biographie_custom_data($post->ID);

        // Require the metabox form template
        require_once __DIR__ . '/../admin/partials/wikibiographie-biographie-metabox.php';
    }

    /**
     * Load the form and data of the Biographie meta box.
     *
     * @param $post
     *
     * @return void
     */
    public function wikipedia_meta_box_callback($post)
    {
        // Add a nonce field so we can check for it later.
        wp_nonce_field('wikipedia_nonce', 'wikipedia_nonce');

        // Get all the meta data of the Biographie so that we can fill the form with it.
        $custom_data = $this->get_biographie_custom_data($post->ID);

        // Require the metabox form template
        require_once __DIR__ . '/../admin/partials/wikibiographie-wikipedia-metabox.php';
    }

    /**
     * Retrieve and format the post meta for the Biographie.
     *
     * @param $post_id
     *
     * @return array
     */
    public function get_biographie_custom_data($post_id)
    {
        $meta = get_post_meta($post_id, '');

        $meta = array_filter($meta, function ($key) {
            return strpos($key, '_biographie') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $meta = array_map(function ($item) {
            return $item[0];
        }, $meta);

        $thumbnail = get_the_post_thumbnail_url($post_id);

        if (!empty($thumbnail)) {
            $meta['_biographie_thumbnail'] = $thumbnail;
        }
        $wiki_data = null;
        if (!is_null($meta['_biographie_custom_wikipedia_url'] ?? null)) {
            $wiki_data = $this->get_cached_wiki_data($post_id, $meta['_biographie_custom_wikipedia_url']);
        }
        if (!empty($wiki_data)) {
            $meta = array_merge($meta, $wiki_data);
        }

        return $meta;
    }

    protected function set_biographie_featured_image($post_id, $image_url)
    {
        $image_path_info = pathinfo($image_url);
        // looking for an already attached featured image
        $id = get_post_meta($post_id, '_biographie_attached_featured_image_id', true);
        if (false !== $id) {
            $old_image_path_info = pathinfo(get_attached_file($id) ?? '');
            // comparing extension and filename of tha attached featured image and the new image
            if (isset($old_image_path_info['extension']) && $old_image_path_info['extension'] === $image_path_info['extension']
            && false !== strpos($old_image_path_info['filename'], $image_path_info['filename'])) {
                // the images look the same, juste reuse the old one
                set_post_thumbnail($post_id, $id);
                return;
            }
        }

        $image_name = $image_path_info['basename'] ?? null;
        if (is_null($image_name)) {
            throw new \Exception('Could not retrieve image name from its url');
        }
        $upload_dir = wp_upload_dir(); // Set upload folder
        $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
        $filename = basename($unique_file_name); // Create image file name

        // Check folder permission and define file location
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        ini_set('user_agent', 'Wikibiographie/1.0 (https://productionsrhizome.org; wikibiographie@productionsrhizome.org)');
        $image_data = file_get_contents($image_url); // Get image data
        // Create the image  file on the server
        file_put_contents($file, $image_data);

        // Check image file type
        $wp_filetype = wp_check_filetype($filename, null);
        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment($attachment, $file, $post_id, true);
        if (!is_numeric($attach_id)) {
            return;
        }

        update_post_meta($post_id, '_biographie_attached_featured_image_id', $attach_id);

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);

        // And finally assign featured image to post
        set_post_thumbnail($post_id, $attach_id);
    }

    private function preview($text, $maxChars)
    {
        // If maxChars equals 0, then do not limit the number of characters
        if ($maxChars == 0) {
            return $text;
        }

        // If the text is shorter than the maximum number of characters, return the whole text
        if (mb_strlen($text) <= $maxChars) {
            return $text;
        }

        $text_truncated = mb_substr($text, 0, $maxChars);

        // If there is a dot in the truncated text, return the text up until the last sentence
        if (false !== mb_strpos($text_truncated, '.')) {
            return trim(pathinfo($text_truncated, PATHINFO_FILENAME), '.') . '.';
        }

        // If there is a spaces in the truncated text, return the text up until the last space
        if (false !== mb_strpos($text_truncated, ' ')) {
            $exploded = explode(' ', $text_truncated);
            array_pop($exploded);
            return implode(' ', $exploded) . '…';
        }

        return $text_truncated . '…';
    }

    public function get_biographie($post_id)
    {
        $meta = $this->get_biographie_custom_data($post_id);
        $options = get_option('wikibiographie_options');

        $wiki = [
            'first_name' => $meta['_biographie_wiki_first_name'] ?? null,
            'last_name' => $meta['_biographie_wiki_last_name'] ?? null,
            'image' => $meta['_biographie_wiki_image'] ?? null,
            'pseudo' => $meta['_biographie_wiki_pseudonym'] ?? null,
            'dob' => $meta['_biographie_wiki_date_of_birth'] ?? null,
            'pob' => $meta['_biographie_wiki_place_of_birth'] ?? null,
            'dod' => $meta['_biographie_wiki_date_of_death'] ?? null,
            'pod' => $meta['_biographie_wiki_place_of_death'] ?? null,
            'occupation' => $meta['_biographie_wiki_occupation'] ?? null,
            'website' => $meta['_biographie_wiki_website'] ?? null,
            'introduction' => $this->preview($meta['_biographie_wiki_introduction'] ?? null, $options['_wikibiographie_maximum_description_length_in_characters']),
            'introduction_complement' => null,
        ];

        $custom = [
            'first_name' => $meta['_biographie_custom_first_name'] ?? null,
            'last_name' => $meta['_biographie_custom_last_name'] ?? null,
            'image' => get_the_post_thumbnail_url(),
            'pseudo' => $meta['_biographie_custom_pseudo'] ?? null,
            'dob' => $meta['_biographie_custom_date_naissance'] ?? null,
            'pob' => $meta['_biographie_custom_lieu_naissance'] ?? null,
            'dod' => $meta['_biographie_custom_date_deces'] ?? null,
            'pod' => $meta['_biographie_custom_lieu_deces'] ?? null,
            'occupation' => $meta['_biographie_custom_occupation'] ?? null,
            'website' => $meta['_biographie_custom_site_officiel'] ?? null,
            'introduction' => $meta['_biographie_custom_description'] ?? null,
            'introduction_complement' => $meta['_biographie_custom_description_complementaire'] ?? null,
        ];

        $mergeBio = function ($wiki, $custom) {
            $bio = [];
            foreach ($wiki as $key => $value) {
                if (!empty($custom[$key])) {
                    $bio[$key] = $custom[$key];
                } else {
                    $bio[$key] = $wiki[$key];
                }
            }
            return $bio;
        };

        $bio = $mergeBio($wiki, $custom);

        $settings_mapping = [
            'first_name' => 'display_first_name',
            'last_name' => 'display_last_name',
            'image' => 'display_photo',
            'pseudo' => 'display_pseudonym',
            'dob' => 'display_date_of_birth',
            'pob' => 'display_place_of_birth',
            'dod' => 'display_date_of_death',
            'pod' => 'display_place_of_death',
            'occupation' => 'display_occupation',
            'website' => 'display_website',
            'introduction' => 'display_description',
            'introduction_complement' => 'display_description',
        ];

        foreach ($settings_mapping as $attr => $setting) {
            if (!($options[$setting] ?? true)) {
                unset($bio[$attr]);
            }
        }

        if (!empty($meta['_biographie_custom_wikipedia_url'])) {
            $bio['wikipedia_url'] = $meta['_biographie_custom_wikipedia_url'];
        }

        return $bio;
    }

    /**
     * Save the content of the Biographie meta box in the database.
     *
     * @param $post_id
     *
     * @return void
     */
    public function save_biographie_meta_box_data($post_id)
    {
        // Check if our nonce is set.
        if (! isset($_POST['wikibiographie_nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if (! wp_verify_nonce($_POST['wikibiographie_nonce'], 'wikibiographie_nonce')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if (! current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (! current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        $old_url = get_post_meta($post_id, '_biographie_custom_wikipedia_url', true);
        $new_url = wp_strip_all_tags($_POST['_biographie_custom_wikipedia_url']);
        if ($old_url !== $new_url) {
            // the URL has changed, need to empty all cached data from Wikipedia in post meta
            delete_post_meta($post_id, '_biographie_wiki_first_name');
            delete_post_meta($post_id, '_biographie_wiki_last_name');
            delete_post_meta($post_id, '_biographie_wiki_pseudonym');
            delete_post_meta($post_id, '_biographie_wiki_date_of_birth');
            delete_post_meta($post_id, '_biographie_wiki_place_of_birth');
            delete_post_meta($post_id, '_biographie_wiki_date_of_death');
            delete_post_meta($post_id, '_biographie_wiki_place_of_death');
            delete_post_meta($post_id, '_biographie_wiki_occupation');
            delete_post_meta($post_id, '_biographie_wiki_website');
            delete_post_meta($post_id, '_biographie_wiki_introduction');
            delete_post_meta($post_id, '_biographie_wiki_image');
        }

        $this->fetch_and_cache_wiki_data($post_id, $_POST['_biographie_custom_wikipedia_url']);

        // Sanitize user input.
        $custom_values = [
            '_biographie_custom_first_name' => sanitize_text_field($_POST['_biographie_custom_first_name']),
            '_biographie_custom_last_name' => sanitize_text_field($_POST['_biographie_custom_last_name']),
            '_biographie_custom_pseudo' => sanitize_text_field($_POST['_biographie_custom_pseudo']),
            '_biographie_custom_date_naissance' => sanitize_text_field($_POST['_biographie_custom_date_naissance']),
            '_biographie_custom_lieu_naissance' => sanitize_text_field($_POST['_biographie_custom_lieu_naissance']),
            '_biographie_custom_date_deces' => sanitize_text_field($_POST['_biographie_custom_date_deces']),
            '_biographie_custom_lieu_deces' => sanitize_text_field($_POST['_biographie_custom_lieu_deces']),
            '_biographie_custom_occupation' => sanitize_text_field($_POST['_biographie_custom_occupation']),
            '_biographie_custom_site_officiel' => sanitize_text_field($_POST['_biographie_custom_site_officiel']),
            '_biographie_custom_description' => sanitize_text_field($_POST['_biographie_custom_description']),
            '_biographie_custom_description_complementaire' => sanitize_text_field($_POST['_biographie_custom_description_complementaire']),
        ];

        foreach ($custom_values as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }

        update_post_meta($post_id, '_biographie_custom_wikipedia_url', $new_url);

        if (!has_post_thumbnail($post_id)) {
            $wiki_image_url = get_post_meta($post_id, '_biographie_wiki_image', true);
            if ($wiki_image_url) {
                $this->set_biographie_featured_image($post_id, $wiki_image_url);
            }
        }
    }

    public function get_cached_wiki_data($post_id, $url = null)
    {
        $cached_data = get_transient('_biographie_wiki_'.$post_id);
        if (false === $cached_data && !is_null($url)) {
            $cached = $this->fetch_and_cache_wiki_data($post_id, $url);
            if (!$cached) {
                return [];
            }
            $cached_data = get_transient('_biographie_wiki_'.$post_id);
        }
        return $cached_data;
    }

    public function fetch_and_cache_wiki_data($post_id, $url): bool
    {
        $fresh_data = $this->fetch_wikidata($url);
        if (is_null($fresh_data['firstName'])) {
            // if firstName is null, call to wikidata APIs probably didn't work
            // do not cache fresh data, return
            return false;
        }
        $this->cache_wiki_data($post_id, $fresh_data);
        return true;
    }

    public function cache_wiki_data($post_id, $wiki_data)
    {
        if (!is_array($wiki_data)) {
            throw new Exception('Le type des données Wiki à mettre en cache doit être \'array\'');
        }
        $wiki_values = [
            '_biographie_wiki_first_name' => $wiki_data['firstName'],
            '_biographie_wiki_last_name' => $wiki_data['lastName'],
            '_biographie_wiki_pseudonym' => $wiki_data['pseudonym'],
            '_biographie_wiki_date_of_birth' => $wiki_data['dateOfBirth'],
            '_biographie_wiki_place_of_birth' => $wiki_data['placeOfBirth'],
            '_biographie_wiki_date_of_death' => $wiki_data['dateOfDeath'],
            '_biographie_wiki_place_of_death' => $wiki_data['placeOfDeath'],
            '_biographie_wiki_occupation' => $wiki_data['occupation'],
            '_biographie_wiki_website' => $wiki_data['website'],
            '_biographie_wiki_introduction' => $wiki_data['introduction'],
            '_biographie_wiki_image' => $wiki_data['image'],
        ];

        foreach ($wiki_values as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }

        set_transient(
            '_biographie_wiki_'.$post_id,
            $wiki_data,
            get_option('wikibiographie_options')['_wikibiographie_cache_expiration_in_seconds'] ?? MONTH_IN_SECONDS
        );
    }

    /**
     * Load un single template de Biographie
     * Les templates peuvent être "overridé" dans le thème en créant un fichier single-biographie.php
     * À la racine du thème ou dans un dossier "templates/"
     *
     * @param $template
     *
     * @return mixed|string
     */
    public function load_biographie_single_template($template)
    {
        if (is_single() && get_query_var('post_type') === self::POST_TYPE) {
            $templates = [
                'single-biographie.php',
                'wikibiographie/single-biographie.php'
            ];
            $template = locate_template($templates);
            if (!$template) {
                $template = __DIR__ . '/../templates/single-biographie.php';
            }
        }
        return $template;
    }

    /**
     * Load un archive template de Biographie
     * Les templates peuvent être "overridé" dans le thème en créant un fichier archive-biographie.php
     * À la racine du thème ou dans un dossier "templates/"
     *
     * @param $template
     *
     * @return mixed|string
     */
    public function load_biographie_archive_template($template)
    {
        if (is_archive() && get_query_var('post_type') === self::POST_TYPE) {
            $templates = [
                'archive-biographie.php',
                'wikibiographie/archive-biographie.php'
            ];
            $template = locate_template($templates);
            if (!$template) {
                $template = __DIR__ . '/../templates/archive-biographie.php';
            }
        }

        return $template;
    }

    public function order_archive_by_title($query)
    {
        if (is_archive() && get_query_var('post_type') === self::POST_TYPE) {
            $query->set('order', 'ASC');
            $query->set('orderby', 'title');

            if (!empty(get_query_var('biographie_s'))) {
                $query->set('s', get_query_var('biographie_s'));
            }
        }
    }
}
