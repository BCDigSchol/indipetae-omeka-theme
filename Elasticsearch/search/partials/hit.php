<?php
$record = Elasticsearch_Utils::getRecord($hit);
$record_url = $record ? record_url($record) : '';
$title = $hit['_source']['title'];
$partial_params = [
    'hit' => $hit['_source'],
    'record' => $record,
    'url' => $record_url,
    'maxTextLength' => 1000
]; ?>

<div class="elasticsearch-result">
    <h3 class="elasticsearch-result__title">
        <a href="<?= $record_url ?>" title="<?= htmlspecialchars($title) ?>"><?= $title ?></a>
    </h3>
    <div class="elasticsearch-result__metadata">
        <?= $this->partial('search/partials/results/letter.php', $partial_params) ?>
    </div>
</div>
