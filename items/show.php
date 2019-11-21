<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>

<h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

<?php if ((get_theme_option('Item FileGallery') == 0) && metadata('item', 'has files')): ?>
    <?php echo files_for_item(array('imageSize' => 'fullsize')); ?>
<?php endif; ?>

<?php
$collection = get_collection_for_item();
$collection_title = metadata($collection, ['Dublin Core', 'Title']);
?>

<!-- BEGIN -->

<div id="item_wrap" class="row">

    <!-- ITEMS ON LEFT -->
    <div class="col-md-3">
        <?php echo all_element_texts('item'); ?>

        <!-- The following returns all of the files associated with an item. -->
        <?php if ((get_theme_option('Item FileGallery') == 1) && metadata('item', 'has files')): ?>
            <div id="itemfiles" class="element">
                <h3><?php echo __('Download PDF'); ?></h3>
                <div class="element-text"><?php echo files_for_item(); ?></div>
            </div>
        <?php endif; ?>

    </div>


    <!-- TRANSCRIPT ON RIGHT -->
    <div class="col-md-9 letter-right-col">

        <h3>Transcription</h3>
        <div class="transcript_text">

            <?php echo metadata('item', array('Dublin Core', 'Description')); ?>
        </div>

        <h3>Transcription- back</h3>
        <div class="transcript_text">
            <?php echo metadata('item', array('Dublin Core', 'Extent')); ?>
        </div>

        <h3>Images</h3>
        <?= item_image_gallery([
            'wrapper' => ['class' => 'letter-gallery'],
            'linkWrapper' => ['class' => 'letter-wrapper']
        ], 'thumbnail') ?>

        <!-- If the item belongs to a collection, the following creates a link to the browse items list in that collection. -->
        <?php if (metadata('item', 'Collection Name')): ?>
            <div id="collection" class="element">
                <h3><?php echo __('Collection'); ?></h3>
                <div class="element-text"><a href="/elasticsearch/search?collection=<?= $collection_title ?>"><?= $collection_title ?></a></div>
            </div>
        <?php endif; ?>

        <!-- The following prints a list of all tags associated with the item -->
        <?php if (metadata('item', 'has tags')): ?>
            <div id="item-tags" class="element">
                <h3><?php echo __('Tags'); ?></h3>
                <div class="element-text"><?php echo tag_string('item'); ?></div>
            </div>
        <?php endif; ?>

        <!-- The following prints a citation for this item. -->
        <div id="item-citation" class="element">
            <h3><?php echo __('Citation'); ?></h3>
            <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
        </div>

        <div id="item-output-formats" class="element">
            <h3><?php echo __('Output Formats'); ?></h3>
            <div class="element-text"><?php echo output_format_list(); ?></div>
        </div>

        <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>

        <nav>
            <ul class="item-pagination navigation">
                <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
                <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
            </ul>
        </nav>
    </div>
</div>

<?php echo foot(); ?>
