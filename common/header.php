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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
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


<!-- DESKTOP NAV BAR STARTS HERE -->

            <nav id="top-nav" class="navbar desktop-nav" role="navigation">
                <?php echo public_nav_main(); ?>
                <div id="search-container" role="search">
                    <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                    <?php echo search_form(array('show_advanced' => true)); ?>
                    <?php else: ?>
                    <?php echo search_form(); ?>
                    <?php endif; ?>
                </div>
            </nav>

<!-- NAV ENDS -->


<!-- HACKED MOBILE NAV STARTS HERE -->

<div class="mobile-nav-hardcode">
    <nav class="navbar navbar-default" role="navigation">

      <div class="container-fluid">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed burg" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <!--<span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>-->
          </button>
        </div>

        <!-- MENU CONTENT HERE. UPDATE AS NEEDED. -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="/items/browse">Browse Letters</a></li>
                    <li><a href="/collections/browse">Browse by Collection</a></li>
                  </ul>
                </li>

                <li><a href="/board">Scientific Board</a></li>

                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Partners <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="/team">Development Team</a></li>
                  </ul>
                </li>

                <li><a href="/bibliography">Bibliography</a></li>
                <li><a href="/collaborate">Collaborate</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>

            <div id="search-container" role="search">
                <?php if (get_theme_option('use_advanced_search') === null || get_theme_option('use_advanced_search')): ?>
                <?php echo search_form(array('show_advanced' => false)); ?>
                <?php else: ?>
                <?php echo search_form(); ?>
                <?php endif; ?>
            </div>

        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
</div>

<!-- HACK ENDS HERE-->

        </header>

        <article id="content" role="main" tabindex="-1">

            <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
