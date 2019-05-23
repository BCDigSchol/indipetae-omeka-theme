<?php
$aggregation_labels = Elasticsearch_Helper_Aggregations::getAggregationLabels();
$applied_facets = Elasticsearch_Helper_Aggregations::getAppliedFacets();
$result_facets = Elasticsearch_Helper_Aggregations::getResultFacets($aggregations);
$url_without_facets = get_view()->url('/elasticsearch') . '?q=' . urlencode($query['q']);
?>

<?php if (count($applied_facets) > 0): ?>
    <div id="elasticsearch-filters-active">
        <h3>Applied Filters (<a style="font-size: 80%" href="<?= $url_without_facets ?>">Reset</a>)
        </h3>
        <ul>
            <?php foreach ($applied_facets as $facet): ?>
                <li>
                    <?= $facet->label ?> = <i><?= $facet->value?></i>
                    <a href="<?= $facet->removeUrl() ?>">&#10006;</a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div id="elasticsearch-filters">
    <h3>Filters</h3>
    <?php foreach ($result_facets as $facet): ?>
            <h4><?= $facet->label ?></h4>
            <ul>
                <?php foreach ($facet->buckets as $bucket): ?>
                    <li>
                        <a href="<?= $bucket->url() ?>"><?= $bucket->value ?></a> (<?= $bucket->count ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
    <?php endforeach; ?>
</div>
