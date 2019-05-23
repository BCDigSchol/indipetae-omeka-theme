<?php
try {
    $result_img = record_image($record, 'thumbnail', ['class' => 'elasticsearch-result-image']);
} catch (Exception $e) {
}
?>
<?php if ($result_img): ?><a href="<?= $url ?>"><?= $result_img ?></a><?php endif; ?>

<ul>
    <li title="resulttype"><b>Result Type:</b> <?= $hit['resulttype'] ?></li>

    <?php if (isset($hit['itemtype'])): ?>
        <li title="itemtype"><b>Item Type:</b> <?= $hit['itemtype'] ?></li>
    <?php endif; ?>

    <?php if (isset($hit['collection'])): ?>
        <li title="collection"><b>Collection:</b> <?= $hit['collection'] ?></li>
    <?php endif; ?>

    <?php if (isset($hit['elements'], $hit['element'])): ?>
        <?php $elements = $hit['elements']; ?>
        <?php foreach ($elements as $element): ?>
            <li class="elasticsearch-element" title="element.<?= $element['name'] ?>">
                <b><?= __($element['displayName']) ?>:</b>
                <?php if (is_array($element['text'])): ?>
                    <?php if (count($element['text']) === 1): ?>
                        <?= $element['text'][0] ?>
                    <?php elseif (count($element['text']) > 1): ?>
                        <ul class="elasticsearch-element-texts">
                            <?php foreach ($element['text'] as $text): ?>
                                <li><?= $text ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php else: ?>
                    <?= $element['text'] ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($hit['tags']) && count($hit['tags']) > 0): ?>
        <li title="tags"><b>Tags:</b> <?= implode(', ', $hit['tags']) ?></li>
    <?php endif; ?>

    <li title="created"><b>Record Created: </b> <?= Elasticsearch_Utils::formatDate($hit['created']) ?></li>
    <li title="updated"><b>Record Updated: </b> <?= Elasticsearch_Utils::formatDate($hit['updated']) ?></li>
</ul>

