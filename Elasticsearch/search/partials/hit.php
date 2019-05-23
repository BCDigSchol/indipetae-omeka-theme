<div class="elasticsearch-result">

    <?php $record = Elasticsearch_Utils::getRecord($hit); ?>
    <?php $record_url = $hit['_source']['url']; ?>
    <?php $title = $hit['_source']['title']; ?>
    <?php $partial_params = [
        'hit' => $hit['_source'],
        'record' => $record,
        'url' => $record_url,
        'maxTextLength' => 1000
    ]; ?>

    <h3><a href="<?= $record_url ?>" title="<?= htmlspecialchars($title) ?>"><?= $title ?></a></h3>

    <?= $this->partial('search/partials/results/letter.php', $partial_params) ?>

    <div class="elasticsearch-result-footer">
        <span title="Elasticsearch Score">Score: <?= $hit['_score'] ?></span>
    </div>

</div>
