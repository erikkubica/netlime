<article itemscope itemtype="http://schema.org/ScholarlyArticle">
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="https://google.com/article"/>
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
                <?php echo sprintf(__('Search Results for "%s"', 'sections'), get_search_query()) ?>
            </h1>
            <div class="hidden">
                <?php get_template_part('templates/entry-meta'); ?>
            </div>
        </div>
    </header>
    <?php if (!have_posts()) : ?>
        <div class="content nofter" itemprop="articleBody">
            <p>
                <?php _e('Sorry, no results were found.', 'sage'); ?>
            </p>
        </div>
    <?php endif; ?>
</article>




