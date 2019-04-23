<?php

require_once __DIR__ . '/../ThemeHelpers.php';

use BCLib\Indipetae\ThemeHelpers;

if (!empty($formActionUri)):
    $formAttributes['action'] = $formActionUri;
else:
    $formAttributes['action'] = url([
        'controller' => 'items',
        'action' => 'browse'
    ]);
endif;
$formAttributes['method'] = 'GET';

// If the form has been submitted, retain the number of search
// fields used and rebuild the form
if (!empty($_GET['advanced'])) {
    $search = $_GET['advanced'];
} else {
    $search = [['field' => '', 'type' => '', 'value' => '']];
}

?>

<form <?= tag_attributes($formAttributes) ?>>
    <div id="search-keywords" class="field">
        <?= $this->formLabel('keyword-search', __('Search for Keywords')) ?>
        <div class="inputs">
            <?= $this->formText(
                'search',
                @$_REQUEST['search'],
                ['id' => 'keyword-search', 'size' => '40']
            )
            ?>
        </div>
    </div>

    <div id="search-narrow-by-fields" class="field">
        <div class="label"><?= __('Narrow by Specific Fields') ?></div>
        <div class="inputs">
            <?php foreach ($search as $i => $rows): ?>
                <div class="search-entry">
                    <?= ThemeHelpers::joinerSelect($this, $i, $rows) ?>
                    <?= ThemeHelpers::fieldSelect($this, $i, $rows) ?>
                    <?= ThemeHelpers::searchTypeSelectBox($this, $i, $rows) ?>
                    <?= ThemeHelpers::searchTextInput($this, $i, $rows) ?>
                    <button type="button" class="remove_search" disabled="disabled" style="display: none;"><?= __('Remove field') ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add_search"><?= __('Add a Field') ?></button>
    </div>

    <div class="field">
        <?= $this->formLabel('collection-search', __('Search By Collection')) ?>
        <div class="inputs">
            <?php
            echo $this->formSelect(
                'collection',
                @$_REQUEST['collection'],
                ['id' => 'collection-search'],
                get_table_options('Collection', null, ['include_no_collection' => true])
            );
            ?>
        </div>
    </div>

    <?php fire_plugin_hook('public_items_search', ['view' => $this]); ?>
    <div>
        <?php if (!isset($buttonText)) {
            $buttonText = __('Search for items');
        } ?>
        <input type="submit" class="submit" name="submit_search" id="submit_search_advanced" value="<?= $buttonText ?>">
    </div>
</form>

<?= js_tag('items-search') ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
    });
</script>
