<?php

use BCLib\Indipetae;
use BCLib\Indipetae\ThemeHelpers;

?>

    <form <?= ThemeHelpers::advSearchFormAttributes($formAttributes) ?>>

        <fieldset class="advanced-search-fieldset">
            <legend>Names</legend>
            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_SENDER) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_RECIPIENT) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_MODEL) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_OTHER_NAMES) ?>
        </fieldset>

        <fieldset class="advanced-search-fieldset">
            <legend>Places</legend>
            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_FROM) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_TO) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_DESTINATIONS) ?>
        </fieldset>

        <fieldset class="advanced-search-fieldset">
            <legend>Dates</legend>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_DATE) ?>
        </fieldset>

        <fieldset class="advanced-search-fieldset">
            <legend>Other</legend>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_GRADE) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_ANTERIOR_DESIRE) ?>

            <?= ThemeHelpers::advSearchInput(Indipetae\FIELD_LEFT_FOR_MISSION) ?>

            <h2>PLACEHOLDER FOR ARCHIVE FIELD</h2>
        </fieldset>

        <input type="submit">

    </form>

<?= js_tag('indipetae.bundle') ?>