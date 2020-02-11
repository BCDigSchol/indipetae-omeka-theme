<?php
try {
    $result_img = record_image($record, 'thumbnail', ['class' => 'elasticsearch-result-image']);
} catch (Exception $e) {
}

$elements = [];
if (isset($hit['elements'])) {
    $elements = array_reduce($hit['elements'], static function ($hash, $element) {
        $name = $element['name'];
        $hash[$name] = $element['text'];
        return $hash;
    }, $elements);
}

$recipients = isset($elements['audience']) ? implode(', ', $elements['audience']) : null;
$destinations = isset($elements['publisher']) ? implode(', ', $elements['publisher']) : null;

$recipients_heading = $recipients && count($elements['audience']) > 1 ? 'Recipients' : 'Recipient';
$destinations_heading = $destinations && count($elements['publisher']) > 1 ? 'Destinations' : 'Destination';

?>
<ul class="elasticsearch-result__metadata-list">
    <?php if ($recipients): ?>
        <li title="recipient" class="elasticsearch-result__metadata-row">
            <span class="elasticsearch-result__metadata-header"><?= $recipients_heading ?>:</span>
            <?= $recipients ?>
        </li>
    <?php endif; ?>

    <?php if ($destinations && count($destinations)): ?>
        <li title="destination" class="elasticsearch-result__metadata-row">
            <span class="elasticsearch-result__metadata-header"><?= $destinations_heading ?>:</span>
            <?= $destinations ?>
        </li>
    <?php endif; ?>
</ul>

