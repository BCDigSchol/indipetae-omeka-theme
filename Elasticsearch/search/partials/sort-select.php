<?php
/**
 * Select box for sort options
 *
 * Input parameters are
 *
 *  $sorts - an array of sort options
 *      $option->url - the URL of the sort option
 *      $option->label - the display text
 *      $option->selected - 'selected' if option is the current sort method
 */
$sorts = $sorts ?? [];
?>
<label for="indipetae-sort-select">Sort by</label>
<select id="indipetae-sort-select">
    <?php foreach ($sorts as $option): ?>
        <option value="<?= $option->url ?>" <?= $option->selected ?>><?= $option->label ?></option>
    <?php endforeach; ?>
</select>