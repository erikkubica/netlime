<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style><?php echo file_get_contents(get_template_directory() . "/public/dist/css/bootstrap.min.css") ?></style>
        <style><?php echo file_get_contents(get_template_directory() . "/public/dist/css/bootstrap-theme.min.css") ?></style>
        <?php wp_head(); ?>
        <link rel="prefetch prerender" href="https://www.netlime.eu/" as="document">
        <link rel="prefetch prerender" href="https://www.netlime.eu/samsung-galaxy-s6-still-worth-2016/" as="document">
        <link rel="prefetch prerender" href="https://www.netlime.eu/lenovo-ideapad-700-review/" as="document">
        <link rel="prefetch prerender" href="https://www.netlime.eu/ako-byt-uspesny-zarobte-s-pracou-ktoru-mate-radi-tvrda-praca/" as="document">
        <link rel="prefetch prerender" href="https://www.netlime.eu/structure-simple-blog-symfony2-tutorial/" as="document">
        <link rel="prefetch prerender" href="https://www.netlime.eu/my-feelings-about-wordpress-programming-after-two-months/" as="document">
    </head>
    <body>
        <div id="wrapper">
            <?php Theme::getContent("top"); ?>
            <div id="main">
                <main role="main" class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <?php Theme::getContent("left"); ?>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <?php Theme::getContent("content"); ?>
                        </div>
                    </div>
                </main>
            </div>
            <?php Theme::getContent("bottom"); ?>
        </div>
        <script type="text/javascript" src="<?= get_template_directory_uri() ?>/public/js/bootsquery.js?v=2"></script>
        <script type="text/javascript" src="<?= get_template_directory_uri() ?>/public/js/analytics.js" async></script>
        <?php wp_footer(); ?>
    </body>
</html>
