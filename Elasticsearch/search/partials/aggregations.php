<?php
$aggregation_labels = Elasticsearch_Helper_Aggregations::getAggregationLabels();
$result_facets = Elasticsearch_Helper_Aggregations::getResultFacets($aggregations);

// @todo enable archive facet when there are multiple archives
$result_facets = array_filter($result_facets, function (Elasticsearch_Model_Facet $facet) {
    return $facet->name !== 'archive';
});

$url_without_facets = get_view()->url('/elasticsearch') . '?q=' . urlencode($query['q']);
?>

<div id="elasticsearch-filters">
    <h3>Filters</h3>
    <?php foreach ($result_facets as $facet): ?>
        <div class="aggregation"
             data-label="<?= $facet->label ?>"
             data-field="<?= $facet->field ?>"
             data-name="<?= $facet->name ?>"
             data-total="<?= $facet->total ?>">
            <h4 class="aggregation__facet-label"><?= $facet->label ?></h4>
            <ul class="aggregation__list">
                <?php foreach ($facet->buckets as $bucket): ?>
                    <?php if ($bucket->display_value !== ''): ?>
                        <li class="aggregation__list-item">
                            <span class="aggregation__bucket-label">
                                <a href="<?= $bucket->url() ?>"><?= $bucket->display_value ?></a>
                            </span>
                            <span class="aggregation__bucket-count">(<?= $bucket->count ?>)</span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php if ($facet->total > count($facet->buckets)): ?>
                <button class="aggregation__more-button">more >></button>
                <div class="modal aggregation__modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header aggregation__modal-header">
                                <h5 class="modal-title"><?= $facet->label ?></h5>
                                <button type="button" class="close aggregation__modal-close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="aggregation__result-holder"></div>
                                <div class="aggregation__pagination"></div>
                            </div>
                            <div class="modal-footer sr-only">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>