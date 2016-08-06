<?php
$imgData = wp_get_attachment_image_src(get_post_thumbnail_id(), "home-thumbnail");
$image = false;
if ($imgData && is_array($imgData) && isset($imgData[0]) && $imgData[0]):
    $image = $imgData[0];
endif;
if (!$image):
    $image = "https://www.netlime.eu/wp-content/uploads/2015/12/Wallpaper-Nature-8B71-845x300.jpg";
endif;
?>
<article itemscope itemtype="http://schema.org/ScholarlyArticle">
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="https://google.com/article"/>
    <header style="background-image: url('<?= $image ?>');">
        <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
            <img src="<?= $image ?>" class="img-responsive" style="visibility: hidden;"/>
            <meta itemprop="url" content="<?= $image ?>"/>
            <meta itemprop="width" content="845">
            <meta itemprop="height" content="300">
        </div>
        <div class="heading-bg">
            <h1 itemprop="headline"><?= get_the_title(); ?></h1>
            <?php get_template_part('templates/entry-meta'); ?>
        </div>
    </header>
    <div class="hidden" itemprop="description">
        <?php the_excerpt() ?>
    </div>
    <div class="content nofter" itemprop="articleBody">
        <?php the_content(); ?>
    </div>
    <footer>
        <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>
</article>