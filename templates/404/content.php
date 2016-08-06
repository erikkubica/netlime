<article>
    <?php $image = "https://www.netlime.eu/wp-content/uploads/2015/12/Wallpaper-Nature-8B71-845x300.jpg"; ?>
    <header style="background-image: url('<?= $image ?>');">
        <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
            <img src="<?= $image ?>" class="img-responsive" style="visibility: hidden;"/>
            <meta itemprop="url" content="<?= $image ?>"/>
            <meta itemprop="width" content="845">
            <meta itemprop="height" content="300">
        </div>
        <div class="heading-bg">
            <h1 itemprop="headline">
                <?php echo sprintf(__('404 Page not found', 'sections'), get_search_query()) ?>
            </h1>
            <div class="hidden">
                <?php get_template_part('templates/entry-meta'); ?>
            </div>
        </div>
    </header>
    <div class="content nofter">
        <p>
            <?php _e('Sorry, but the page you were trying to view does not exist.', 'sections'); ?>
        </p>
    </div>
</article>