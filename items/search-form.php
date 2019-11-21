<?php

use BCLib\Indipetae;
use BCLib\Indipetae\ThemeHelpers;

$date_range = ThemeHelpers::getMinMaxYears();

?>

<div class="row">
    <div class="col-md-2 advanced-search-selectors">
        <?= $this->partial('items/partials/advanced-search-selector.php') ?>
    </div>

    <div class="col-md-10">
        <form <?= ThemeHelpers::advSearchFormAttributes($formAttributes) ?>>
            <div id="applied-fields">
                <div class="advanced-search-field row">
                    <div class="advanced-search-field__label-row col-md-2">
                        <label class="advanced-search-field__label" for="q">Keyword</label>
                    </div>
                    <div class="col-md-10">
                        <input class="advanced-search-field__input" type="text" id="q" name="q">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="indipetae-advanced-search-form__buttons col-md-10 offset-md-2">
                    <input type="submit" id="indipetae-advanced-search-form__submit-button">
                    <button id="indipetae-advanced-search-form__reset-button">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_SENDER) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_RECIPIENT) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_MODEL) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_OTHER_NAMES) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_FROM) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_TO) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_DESTINATIONS) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_DATE_RANGE) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_MONTH) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_DAY) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_GRADE) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_ANTERIOR_DESIRE) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_LEFT_FOR_MISSION) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_LANGUAGE) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_ARCHIVE) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_CALL_NUMBER) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_FOLDER) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_NUMBER) ?>

<?= ThemeHelpers::advSearchInput(Indipetae\FIELD_CONTRIBUTOR) ?>

<span style="display: none" id="min-date-holder"><?= $date_range->min ?></span>
<span style="display: none" id="max-date-holder"><?= $date_range->max ?></span>

<?= js_tag('indipetae.bundle') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var $ = jQuery;
        $('.dropdown-toggle').dropdown();
    }, false);
    OmekaElasticsearch.advancedSearch();
</script>