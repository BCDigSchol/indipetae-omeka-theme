<?php echo head(array('bodyid'=>'home')); ?>

<?php if (get_theme_option('Homepage Text')): ?>
<p class="intro"><?php echo get_theme_option('Homepage Text'); ?></p>
<?php endif; ?>

<section id="founding_partners">
<h2 class="homepage_heading">Founding Partners</h2>
<div>
<div class="partner_logo"><a href="#">Archivum Romanum Societatis Iesu</a></div>
<div class="partner_logo"><a href="#">Scientific Board</a></div>
<div class="partner_logo"><a href="#">Institute for Advanced Jesuit Studies</a></div>
<div class="partner_logo"><a href="#">Other partners</a></div>
</div>
</section>
<section id="project-updates">
<h2 class="homepage_heading">Project Updates</h2>
<p><b>June 2019:</b> 3,000 <i>indipetae</i> of the 19th century have been uploaded.</p>
<p><b>Next deadline, December 2019:</b> all the <i>indipetae of the New Society</i> preserved at ARSI (5,000 letters) have been uploaded.</p>
</section>

<?php endif; ?>

<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>
