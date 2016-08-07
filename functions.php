<?php
$includes = [
    "/app/lib/yaml/Yaml.php",
    "/app/lib/yaml/Parser.php",
    "/app/lib/yaml/Escaper.php",
    "/app/lib/yaml/Unescaper.php",
    "/app/lib/yaml/Dumper.php",
    "/app/lib/yaml/Inline.php",
    "/app/lib/wp_bootstrap_navwalker.php",
    "/app/lib/wp_bootstrap_pagination.php",
    "/app/Theme.php"
];

foreach ($includes as $include):
    require_once dirname(__FILE__) . $include;
endforeach;

Theme::init();

function mytheme_comment($comment, $args, $depth)
{
    ?>
<li <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body box">
        <div class="row" style="margin-bottom:0;">
            <div class="avatar">
                <?php if ($args['avatar_size'] != 0) echo netlime_get_avatar($comment, $args['avatar_size']); ?>
            </div>
            <div class="comment-data">
                <div class="comment-author-meta">
                    <h3 class="commenter-name" style="margin:0;"><?= get_comment_author_link() ?></h3>
                    <div class="comment-edit">
                        <small>
                            <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                                <?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()); ?>
                            </a>
                            <?php edit_comment_link(__('(Edit)'), '  ', ''); ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="comment-text">
                    <?php if ($comment->comment_approved == '0') { ?>
                        <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></em>
                        <br/>
                    <?php } ?>
                    <?php comment_text(); ?>
                </div>
                <div>
                    <?php comment_reply_link(array_merge($args, array('add_below' => "div-comment", 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function netlime_get_avatar($id_or_email, $size = 96, $default = '', $alt = '', $args = null)
{
    $defaults = array(
        // get_avatar_data() args.
        'size' => 96,
        'height' => null,
        'width' => null,
        'default' => get_option('avatar_default', 'mystery'),
        'force_default' => false,
        'rating' => get_option('avatar_rating'),
        'scheme' => null,
        'alt' => '',
        'class' => "img-responsive",
        'force_display' => false,
        'extra_attr' => '',
    );

    if (empty($args)) {
        $args = array();
    }

    $args['size'] = (int)$size;
    $args['default'] = $default;
    $args['alt'] = $alt;

    $args = wp_parse_args($args, $defaults);

    if (empty($args['height'])) {
        $args['height'] = $args['size'];
    }
    if (empty($args['width'])) {
        $args['width'] = $args['size'];
    }

    /**
     * Filter whether to retrieve the avatar URL early.
     *
     * Passing a non-null value will effectively short-circuit get_avatar(), passing
     * the value through the {@see 'pre_get_avatar'} filter and returning early.
     *
     * @since 4.2.0
     *
     * @param string $avatar HTML for the user's avatar. Default null.
     * @param int|object|string $id_or_email A user ID, email address, or comment object.
     * @param array $args Arguments passed to get_avatar_url(), after processing.
     */
    $avatar = apply_filters('pre_get_avatar', null, $id_or_email, $args);

    if (!is_null($avatar)) {
        /** This filter is documented in wp-includes/pluggable.php */
        return apply_filters('get_avatar', $avatar, $id_or_email, $args['size'], $args['default'], $args['alt'], $args);
    }

    if (!$args['force_display'] && !get_option('show_avatars')) {
        return false;
    }

    $url2x = get_avatar_url($id_or_email, array_merge($args, array('size' => $args['size'] * 2)));

    $args = get_avatar_data($id_or_email, $args);

    $url = $args['url'];

    if (!$url || is_wp_error($url)) {
        return false;
    }

    $class = array('avatar', 'avatar-' . (int)$args['size'], 'photo');

    if (!$args['found_avatar'] || $args['force_default']) {
        $class[] = 'avatar-default';
    }

    if ($args['class']) {
        if (is_array($args['class'])) {
            $class = array_merge($class, $args['class']);
        } else {
            $class[] = $args['class'];
        }
    }

    $avatar = sprintf(
        "<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
        esc_attr($args['alt']),
        esc_url($url),
        esc_attr("$url2x 2x"),
        esc_attr(join(' ', $class)),
        (int)$args['height'],
        (int)$args['width'],
        $args['extra_attr']
    );

    /**
     * Filter the avatar to retrieve.
     *
     * @since 2.5.0
     * @since 4.2.0 The `$args` parameter was added.
     *
     * @param string $avatar &lt;img&gt; tag for the user's avatar.
     * @param int|object|string $id_or_email A user ID, email address, or comment object.
     * @param int $size Square avatar width and height in pixels to retrieve.
     * @param string $alt Alternative text to use in the avatar image tag.
     *                                       Default empty.
     * @param array $args Arguments passed to get_avatar_data(), after processing.
     */
    return apply_filters('get_avatar', $avatar, $id_or_email, $args['size'], $args['default'], $args['alt'], $args);
}
