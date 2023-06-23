<?php use BCLib\Indipetae\ThemeHelpers; ?>

<?= head_js() ?>

<?php if (ThemeHelpers::isAdvancedSearch()): ?>
    <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.js" type="text/javascript"></script>

<script src="<?= ThemeHelpers::JS_PATH ?>/jquery-accessibleMegaMenu.js?v=2.6.1" type="text/javascript"></script>
<script src="<?= ThemeHelpers::JS_PATH ?>/minimalist.js?v=2.6.1" type="text/javascript"></script>

<?php //@todo move JS in globals.js to something local ?>
<script src="/application/views/scripts/javascripts/globals.js?v=2.6.1" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" type="text/javascript"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N09R25J157"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-N09R25J157');
</script>