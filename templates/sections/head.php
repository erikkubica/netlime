<?php
/*
Good for render block but adds +50ms to time to first byte aka waiting time
<style><?php echo file_get_contents(get_template_directory_uri() . "/public/dist/css/bootstrap.min.css") ?></style>
<style><?php echo file_get_contents(get_template_directory_uri() . "/public/dist/css/bootstrap-theme.min.css") ?></style>*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/public/dist/css/bootstrap.min.css" ?>" async/>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/public/dist/css/bootstrap-theme.min.css" ?>" async/>
<?php wp_head(); ?>