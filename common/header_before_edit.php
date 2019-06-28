<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_url('//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
    queue_css_file(array('iconfonts', 'bcjs', 'bootstrap.min', 'style', 'custom'));
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php
    queue_js_file(array('jquery-accessibleMegaMenu', 'minimalist', 'globals'));
    echo head_js();
    ?>
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>

<!-- BEFORE EDITS -->
    <a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
          <header class="header">
            <div class="hidden-xs">
              <div class="row">
                <div class="top-menu">
                  <span class="upper"><a href="https://www.jesuitportal.bc.edu/" target="new" class="iajs">The Portal to Jesuit Studies</a></span>
                </div>
              </div>
              <a href="https://indipetae.bc.edu">
                <div class="row brand-backgnd brand">
                  <div class="header-brand center-col">
                    <div class="row">
                      <div class="col-sm-1">
                        <img alt="Institute for Advanced Jesuit Studies" src="https://www.jesuitportal.bc.edu/wp-content/themes/bciajs/imgs/bciajs_logo_transparent.png" class="logo">
                      </div>
                      <div class="pull-right col-sm-10">
                        <h1 class="upper header-title">Digital Indipetae Database</h1>
                        <h4 class="upper header-sub-title">Petitions for the Jesuit Missions (16th-20th centuries)</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </header>
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>

            <?php echo theme_header_image(); ?>


<!-- NAV BAR STARTS HERE -->

            <nav id="top-nav" class="navbar" role="navigation">
                <?php echo public_nav_main()->setUlClass('custom-class'); ?>
                <div id="search-container" role="search">
                    <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                    <?php echo search_form(array('show_advanced' => true)); ?>
                    <?php else: ?>
                    <?php echo search_form(); ?>
                    <?php endif; ?>
                </div>
            </nav>
            
<!-- NAV ENDS -->

        </header>

        <article id="content" role="main" tabindex="-1">

            <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
