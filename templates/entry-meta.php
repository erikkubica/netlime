<h4 class="hidden-xs"><?= __("by") ?>
    <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>" itemprop="author" itemscope itemtype="https://schema.org/Person">
        <span itemprop="name"><?= get_the_author(); ?></span>
    </a>
    &nbsp;<?= __("at") ?>&nbsp;
    <time itemprop="datePublished" datetime="<?= get_the_time("d. M Y") ?>"><?= get_the_time("d. M Y") ?></time>
    <meta itemprop="dateModified" content="<?= get_the_modified_time("d. M Y") ?>"/>
</h4>
<!-- publisher data -->
<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" class="hidden">
    <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
        <img src="<?= get_template_directory_uri() ?>/public/images/logo.png" alt="<?= get_the_author(); ?>"/>
        <meta itemprop="url" content="<?= get_template_directory_uri() ?>/public/images/logo.png">
        <meta itemprop="width" content="845">
        <meta itemprop="height" content="300">
    </div>
    <meta itemprop="name" content="<?= get_the_author(); ?>">
</div>
<!-- end publisher data -->