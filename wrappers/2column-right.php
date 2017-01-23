<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php Theme::getContent("head"); ?>
    </head>
    <body>
        <div id="wrapper">
            <?php Theme::getContent("top"); ?>
            <div id="main">
                <main role="main" class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <?php Theme::getContent("content"); ?>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <?php Theme::getContent("right"); ?>
                        </div>
                    </div>
                </main>
            </div>
            <?php Theme::getContent("bottom"); ?>
        </div>
        <script type="text/javascript" src="<?= get_template_directory_uri() ?>/public/js/bootsquery.js?v=2" async></script>
        <script type="text/javascript" src="<?= get_template_directory_uri() ?>/public/js/analytics.js" async></script>
        <?php wp_footer(); ?>
    </body>
</html>
