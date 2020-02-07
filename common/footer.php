<?php

use BCLib\Indipetae\ThemeHelpers;

?>

</div>
</article>

<footer>
    <div id="footer-container" class="container">

        <p class="footer-title">Digital Indipetae Database</p>
        <p>The Digital Indipetae Database is organized by the Institute for Advanced Jesuit Studies in collaboration with the Archivum Romanum Societatis Iesu. Its editorial and scientific boards coordinate the project and promote it within the international community of scholars.</p>

        <div class="row">
            <div class="col-md-3 col-sm-12">
                <img src="<?= ThemeHelpers::IMG_PATH ?>/Logo_300.png" class="footer-logo" alt="Boston College Libraries">
            </div>

            <div class="col-md-9 col-sm-12">
                <p>The Digital Indipetae Database was developed and is maintained in cooperation with Boston College Libraries. Learn more about the
                    <a href="/team">development team</a> and process.</p>
            </div>
        </div>

        <div id="footer-menu">
            <ul class="site-links">
                <li><a href="/about">About</a></li>
                <li><a href="/collaborate">Learn More</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </div>

        <div class='copyright'>
            <p>Copyright Trustees of Boston College</p>
        </div>

    </div>
    <!-- end wrap -->
</footer>

<?= $this->partial('common/partials/javascript.php') ?>

<script>
    jQuery(document).ready(function () {
        Omeka.showAdvancedForm();
        Omeka.skipNav();
        Omeka.megaMenu('#top-nav');
    });
</script>

</body>
</html>
