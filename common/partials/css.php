<?php use BCLib\Indipetae\ThemeHelpers; ?>
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,600,600i,700,700i&display=swap" rel="stylesheet">
<link href="/application/views/scripts/css/iconfonts.css?v=2.6.1" media="all" rel="stylesheet" type="text/css">
<link href="<?= ThemeHelpers::CSS_PATH ?>/bcjs.css?v=2.6.1" media="all" rel="stylesheet" type="text/css">
<link href="<?= ThemeHelpers::CSS_PATH ?>/bootstrap.min.css?v=2.6.1" media="all" rel="stylesheet" type="text/css">
<link href="<?= ThemeHelpers::CSS_PATH ?>/style.css?v=2.6.1" media="all" rel="stylesheet" type="text/css">
<link href="<?= ThemeHelpers::CSS_PATH ?>/custom.css?v=2.6.1" media="all" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.css" media="all" rel="stylesheet" type="text/css">

<?php if (ThemeHelpers::isAdvancedSearch()): ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<?php endif; ?>
