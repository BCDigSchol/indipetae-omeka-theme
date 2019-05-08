<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>

<h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

<?php if ((get_theme_option('Item FileGallery') == 0) && metadata('item', 'has files')): ?>
<?php echo files_for_item(array('imageSize' => 'fullsize')); ?>
<?php endif; ?>

<div id="floatingwrapper">

    <div class="left" style="width: 25%; float: left;">
      <div class="element">
        <h3>Sender</h3>
        <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Creator')); ?>
    </div>
    </div>
    <div class="element">
      <h3>Grade S.J.</h3>
      <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Replaces')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Date</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Date')); ?>
    </div>
  </div>
  <div class="element">
    <h3>From</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Coverage')); ?>
    </div>
  </div>
  <div class="element">
    <h3>To</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Spatial Coverage')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Recipient</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Audience')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Anterior Desire</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Medium')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Destinations</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Publisher')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Models/Saints/Missionaries</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Subject')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Other names</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Relation')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Left for mission lands</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Date Issued')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Language of the letter</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Language')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Links</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Source')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Notes</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Abstract')); ?>
    </div>
  </div>
  <div class="element">
    <h3>Call number</h3>
    <div class="element-text">
      <?php echo metadata('item', array('Dublin Core','Identifier')); ?>
    </div>
  </div>

<!--Placeholder for original code -->

</div>
<h3 class="transcription-heading" style="width:60%; float:right;">Transcription- front</h3>
<div id="right-col-1" style="width:60%; float:right; padding:3%; margin-top:1em;background-color:lightgrey;">

    <?php echo metadata('item', array('Dublin Core','Description')); ?>
</div>
<h3 class="transcription-heading" style="width:60%; float:right;">Transcription- back</h3>
<div id="right-col-2" style="width:60%; float:right; padding:3%; margin-top:1em;background-color:lightgrey;">
    <?php echo metadata('item', array('Dublin Core','Description')); ?>
</div>

</div>
<!-- If the item belongs to a collection, the following creates a link to the browse items list in that collection. -->
<?php if (metadata('item', 'Collection Name')): ?>
<div id="collection" class="element">
    <h3><?php echo __('Collection'); ?></h3>
     <?php /*<div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>*/?>
     <div class="element-text"><?php echo bcl_link_to_browse_collection(get_collection_for_item());?></div>
</div>
<?php endif; ?>

<!-- The following prints a list of all tags associated with the item -->
<?php if (metadata('item', 'has tags')): ?>
<div id="item-tags" class="element">
    <h3><?php echo __('Tags'); ?></h3>
    <div class="element-text"><?php echo tag_string('item'); ?></div>
</div>
<?php endif;?>

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

<?php echo foot(); ?>
