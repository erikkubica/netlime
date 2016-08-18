<?php
/**
 * @author Erik Kubica Netlime
 */
use Symfony\Component\Yaml\Yaml;

abstract class Theme
{
    private static $configs = ["config", "navigation", "sidebars", "wrappers", "sections", "templates"];
    private static $config = [];
    private static $base_path = "";
    private static $config_path = "";
    private static $current_template = "";

    public static function init()
    {
        self::initConfig();
        self::addSupports();
        self::registerNavigation();
        self::registerSidebars();
        self::titleToHead();
        self::setupVirtualTemplates();
        self::setupWrapper();
    }

    /**
     * Initializes theme configuration
     */
    public static function initConfig()
    {
        self::$base_path = get_template_directory();
        self::$config_path = self::$base_path . "/app/etc/";
        foreach (self::$configs as $c):
            $parsed = Yaml::parse(file_get_contents(self::$config_path . $c . ".yaml"));
            if (is_array($parsed)):
                self::$config = array_merge($parsed, self::$config);
            endif;
        endforeach;
    }

    /**
     * Add theme features
     */
    public static function addSupports()
    {
        add_theme_support('post-thumbnails');
        add_theme_support('widgets');
    }

    /**
     * Register navigation menus
     */
    public static function registerNavigation()
    {
        foreach (self::$config["navigation"] as $name => $description):
            register_nav_menu($name, $description);
        endforeach;
    }

    /**
     * Register sidebars
     */
    public static function registerSidebars()
    {
        foreach (self::$config["sidebars"] as $id => $sidebar):
            add_action("widgets_init", function () use ($id, $sidebar) {
                register_sidebar(array(
                    'name' => __($sidebar["name"], 'sections'),
                    'id' => $id,
                    'description' => __($sidebar["description"], 'sections'),
                    'before_widget' => '<' . $sidebar["html-tag"] . ' class="' . $sidebar["html-class"] . '">',
                    'after_widget' => '</' . $sidebar["html-tag"] . '>',
                    'before_title' => '<' . $sidebar["heading-tag"] . ' class="' . $sidebar["heading-class"] . '">',
                    'after_title' => '</' . $sidebar["heading-tag"] . '>',
                ));
            });
        endforeach;
    }

    /**
     * Add wp_title() to wp_head()
     */
    public static function titleToHead()
    {
        add_action("wp_head", function () {
            echo '<title>';
            wp_title();
            echo '</title>';
        });
    }

    /**
     * Setup virtual templates, that simulates template-*.php files
     */
    public static function setupVirtualTemplates()
    {
        #  Make fake virtual templates to avoid creating template-*.php files in theme root
        add_action('edit_form_after_editor', ["Theme", "custom_page_templates_init"]);
        add_action('load-post.php', ["Theme", 'custom_page_templates_init_post']);
        add_action('load-post-new.php', ["Theme", 'custom_page_templates_init_post']);

        #  Add templates from config to wordpress templates
        add_filter('custom_page_templates', function ($now_templates) {
            foreach (self::$config["templates"] as $key => $template):
                if ($template["is_page_template"] == false):
                    continue;
                endif;
                $now_templates[$key] = $template["name"];
            endforeach;
            return $now_templates;
        });
    }

    /**
     * Wraps template around
     */
    public static function setupWrapper()
    {
        add_filter('template_include', function ($main) {

            $basename = false;
            $basename_alt = false;

            if (is_search()):
                $basename = "search";
            endif;

            if (is_category() && !$basename):
                $basename = "category";
                $c = get_the_category();
                $basename_alt = "category-" . $c[0]->slug;
            endif;

            if (is_home() && !is_page() && !$basename):
                $basename = "index";
            endif;

            if (is_page() || is_single()):
                the_post();
                $basename = get_post_type();
            endif;

            if (is_archive() && !$basename):
                $basename = "archive";
                $basename_alt = "archive-" . get_post_type();
            endif;

            if (is_404() || !$basename):
                $basename = "404";
            endif;

            if (isset(self::$config["templates"][$basename_alt])):
                $basename = $basename_alt;
            endif;

            if ($basename):
                #  Set current template to use it in other functions
                Theme::$current_template = self::$config["templates"][$basename];

                #  Get the wrapper of template, and search it inside registered wrappers
                #  if found, set the wrapper if not throw 404 template
                $wrapper = self::$config["templates"][$basename]["wrapper"];
                if (isset(self::$config["wrappers"][$wrapper])):
                    return get_template_directory() . "/" . self::$config["wrappers"][$wrapper]["template"];
                endif;
            endif;

            # In case if something fails return WordPress default
            return $main;
        }, 99);
    }

    /**
     * Used to include sections of given location inside wrapper
     *
     * @param $location
     */
    public static function getContent($location)
    {
        foreach (self::$current_template["sections"] as $key => $section):

            # skip if section is not in given location
            if ($section["location"] != $location):continue;endif;

            # Get the template
            $sectionTemplate = self::$config["sections"][$key]["template"];

            # Check if cache is enabled for this section
            $is_cache_section_enabled = self::$config["sections"][$key]["cache"];

            # Check if ajax request
            $is_ajax = defined('DOING_AJAX') && DOING_AJAX;

            # Check if post request
            $is_post_req = $_SERVER['REQUEST_METHOD'] == 'POST';

            # If cache is enabled and runtime is production then do cache
            if ($is_cache_section_enabled && self::$config["runtime"] == "prod" && !is_user_logged_in() && !$is_post_req && !$is_ajax):

                # Get cached content if false it does not exists
                $cache = self::getCache($sectionTemplate);

                # check for false in case of empty string
                if ($cache !== false):
                    echo $cache;
                else:
                    echo self::doCache($sectionTemplate);
                endif;
            else:
                include get_template_directory() . "/" . $sectionTemplate;
            endif;
        endforeach;
    }

    /**
     * Get absolute path of cache file
     *
     * @param $sectionTemplate
     * @return string
     */
    protected static function getCacheFile($sectionTemplate)
    {
        $filename = md5($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "-" . $sectionTemplate);
        return dirname(__FILE__) . "/cache/" . $filename;
    }

    /**
     * Create the cache
     *
     * @param $sectionTemplate string Name of the template
     * @return string Cached content
     */
    private static function doCache($sectionTemplate)
    {
        # Get cache file absolute path
        $file = self::getCacheFile($sectionTemplate);

        # Start buffering output
        ob_start();

        # Render the template
        include get_template_directory() . "/" . $sectionTemplate;

        # Minify the output
        if (self::$config["cache"]["minify"]):
            $cache = str_replace(["  ", "\n", "\t"], ["", "", ""], ob_get_clean());
        else:
            $cache = ob_get_clean();
        endif;

        # Write output to memory or file
        if (self::$config["cache"]["type"] == "wp_cache"):
            wp_cache_add(basename($file), $cache, "sections_cache", self::$config["cache"]["lifetime"]);
        else:
            # Delete file if already exists
            if (file_exists($file)):
                unlink($file);
            endif;

            # Prevents creating empty cache file
            if (!empty($cache) || $cache != ""):
                file_put_contents($file, $cache);
            endif;
        endif;

        # Return output
        return $cache;
    }

    /**
     * Get cached html
     *
     * @param $sectionTemplate
     * @return bool|mixed|string
     */
    protected static function getCache($sectionTemplate)
    {
        # Get cache file absolute path
        $file = self::getCacheFile($sectionTemplate);

        # Define variable with false
        $cache = false;

        # TODO: When not redis, than wp_cache wont load from cache
        # Get cached html depending on cache type
        if (self::$config["cache"]["type"] == "wp_cache"):
            $cache = wp_cache_get(basename($file), "sections_cache");
        else:
            if (file_exists($file) && (time() - filemtime($file) < self::$config["cache"]["lifetime"])):
                $cache = file_get_contents($file);
            endif;
        endif;

        # Return cached html
        return $cache;
    }


    /**
     * Used for template faking
     *
     * @return mixed|void
     * @author http://wordpress.stackexchange.com/users/35541/gmazzap
     */
    public static function get_custom_page_templates()
    {
        $templates = [];
        return apply_filters('custom_page_templates', $templates);
    }

    /**
     * Used for template faking
     *
     * @return mixed|void
     * @author http://wordpress.stackexchange.com/users/35541/gmazzap
     */
    public static function custom_page_templates_init()
    {
        remove_action(current_filter(), __FUNCTION__);
        if (is_admin() && get_current_screen()->post_type === 'page') {
            $templates = self::get_custom_page_templates();
            if (!empty($templates)) {
                self::set_custom_page_templates($templates);
            }
        }
    }

    /**
     * Used for template faking
     *
     * @return mixed|void
     * @author http://wordpress.stackexchange.com/users/35541/gmazzap
     */
    public static function custom_page_templates_init_post()
    {
        remove_action(current_filter(), __FUNCTION__);
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
        if (empty($method) || strtoupper($method) !== 'POST') return;
        if (get_current_screen()->post_type === 'page') {
            self::custom_page_templates_init();
        }
    }

    /**
     * Used for template faking
     *
     * @param array $templates
     * @author http://wordpress.stackexchange.com/users/35541/gmazzap
     */
    public static function set_custom_page_templates($templates = array())
    {
        if (!is_array($templates) || empty($templates)) return;
        $core = array_flip((array)get_page_templates()); #  Templates defined by file
        $data = array_filter(array_merge($core, $templates));
        ksort($data);
        $stylesheet = get_stylesheet();
        $hash = md5(get_theme_root($stylesheet) . '/' . $stylesheet);
        $persistently = apply_filters('wp_cache_themes_persistently', false, 'WP_Theme');
        $exp = is_int($persistently) ? $persistently : 1800;
        wp_cache_set('page_templates-' . $hash, $data, 'themes', $exp);
    }

}
