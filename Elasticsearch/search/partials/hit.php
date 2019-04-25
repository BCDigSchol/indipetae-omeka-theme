<div class="elasticsearch-result">
    <?php
    //echo '<!--'.json_encode($hit, JSON_PRETTY_PRINT).'-->'; }
    ?>
    <?php $model_template = Inflector::underscore($hit['_source']['model']) . '.php'; ?>
    <?php $record = Elasticsearch_Utils::getRecord($hit); ?>
    <?php $record_url = isset($hit['_source']['url']) ? public_url($hit['_source']['url']) : record_url($record); ?>
    <?php $title = !empty($hit['_source']['title']) ? $hit['_source']['title'] : __('Untitled') . ' ' . $hit['_source']['resulttype']; ?>

    <h3><a href="<?= $record_url ?>" title="<?= htmlspecialchars($title) ?>"><?= $title ?></a></h3>

    <?php
    try {
        echo $this->partial("search/partials/results/$model_template", [
            'hit' => $hit,
            'record' => $record,
            'record_url' => $record_url,
            'maxTextLength' => 1000
        ]);
    } catch (Zend_View_Exception $e) {
        echo "<!-- missing template $model_template -->";
    }
    ?>

    <div class="elasticsearch-result-footer">
        <span title="Elasticsearch Score">Score: <?= $hit['_score'] ?></span>
    </div>

</div>