<?php if (isset(get_view()->item)): //check if this looks like an item show page ?>

    <?php

//dig through the elements for display that are passed into this file
//put it all into a new array of just the elements we want
//this should let you collect the elements you want in the order you want
//follow this pattern to get more or change the order

    $wanted = [
        \BCLib\Indipetae\FIELD_SENDER,
        \BCLib\Indipetae\FIELD_GRADE,
        \BCLib\Indipetae\FIELD_YEAR,
        \BCLib\Indipetae\FIELD_MONTH,
        \BCLib\Indipetae\FIELD_DAY,
        \BCLib\Indipetae\FIELD_FROM,
        \BCLib\Indipetae\FIELD_FROM_INSTITUTION,
        \BCLib\Indipetae\FIELD_TO,
        \BCLib\Indipetae\FIELD_RECIPIENT,
        \BCLib\Indipetae\FIELD_ANTERIOR_DESIRE,
        \BCLib\Indipetae\FIELD_DESTINATIONS,
        \BCLib\Indipetae\FIELD_MODEL,
        \BCLib\Indipetae\FIELD_OTHER_NAMES,
        \BCLib\Indipetae\FIELD_LEFT_FOR_MISSION,
        \BCLib\Indipetae\FIELD_LANGUAGE,
        \BCLib\Indipetae\FIELD_LINKS,
        \BCLib\Indipetae\FIELD_NOTES,
        \BCLib\Indipetae\FIELD_ARCHIVE,
        \BCLib\Indipetae\FIELD_COLLECTION,
        \BCLib\Indipetae\FIELD_FOLDER,
        \BCLib\Indipetae\FIELD_NUMBER,
    ];

    $display_elements = \BCLib\Indipetae\ThemeHelpers::elementsToDisplay($wanted, $elementsForDisplay['Dublin Core']);

    $current_item = get_current_record('item', false);
    ?>

    <div class="element-set">
        <?php foreach ($display_elements as $element): ?>
            <div id="<?= text_to_id(html_escape($element->label)) ?>" class="element">
                <h3><?= html_escape(__($element->label)) ?></h3>
                <?php foreach ($element->getTexts($current_item) as $text): ?>
                    <div class="element-text">
                        <?php if ($element->is_linked): ?>
                            <a href="<?= $element->getSearchLink($text) ?>"><?= $text ?></a>
                        <?php else: ?>
                            <?= $text ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div><!-- end element -->
        <?php endforeach; ?>
    </div><!-- end element-set -->

<?php else: ?>

    <?php foreach ($elementsForDisplay as $setName => $setElements): ?>
        <div class="element-set">
            <h2><?php echo html_escape(__($setName)); ?></h2>
            <?php foreach ($setElements as $elementName => $elementInfo): ?>
                <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element">
                    <h3><?php echo html_escape(__($elementName)); ?></h3>
                    <?php foreach ($elementInfo['texts'] as $text): ?>
                        <div class="element-text"><?php echo $text; ?></div>
                    <?php endforeach; ?>
                </div><!-- end element -->
            <?php endforeach; ?>
        </div><!-- end element-set -->
    <?php endforeach; ?>

<?php endif; ?>
