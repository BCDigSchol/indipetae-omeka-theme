<?php queue_css_file('elasticsearch-results'); ?>
<?php queue_js_file('elasticsearch'); ?>
<?php queue_js_string('ElasticsearchPlugin.setupSearchResults();'); ?>
<?php echo head(['title' => __('Elasticsearch')]); ?>

<?php $totalResults = $results['hits']['total'] ?? 0; ?>

<?php

$facets = $query['facets'] ?? [];

?>

<div class="container">

    <h2 class="search-results-heading">Search results</h2>

    <div id="elasticsearch-help" style="display:none;">
        <?= $this->partial('search/partials/help.php') ?>
    </div>

    <?php if ($results): ?>
        <div class="row">
            <section id="elasticsearch-sidebar" class="col-md-3">
                <?= $this->partial('search/partials/aggregations.php',
                    ['query' => $query, 'aggregations' => $aggregations])
                ?>
            </section>

            <section id="elasticsearch-results" class="col-md-9">

                <?php if (isset($constraint_list) && $constraint_list->hasSearchConstraints()): ?>
                    <?= $this->partial('search/partials/constraints.php', ['constraint_list' => $constraint_list]) ?>
                <?php endif; ?>

                <div class="tot-results-found">
                    <span class="tot-results-found__total"><?= $totalResults ?></span> results found
                </div>

                <!-- Sort drop-down select box. -->
                <div class="elasticsearch-sort-select">
                    <?= $this->partial('search/partials/sort-select.php', ['sorts' => $sorts]) ?>
                </div>

                <?php if (count($results['hits']['hits']) > 0): ?>
                    <?php foreach ($results['hits']['hits'] as $hit): ?>
                        <?= $this->partial('search/partials/hit.php', ['hit' => $hit]) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?= __('Search did not return any results.') ?>
                <?php endif; ?>
            </section>

        </div>
    <?php else: ?>
        <section>
            <h2><?= __('Search failed') ?></h2>
            <p><?= __('The search query could not be executed. Please check your search query and try again.') ?></p>
        </section>
    <?php endif; ?>

</div>

<?= foot() ?>

<?= js_tag('indipetae.bundle', $dir = 'javascripts', $version = '2020021101') ?>
<script>
    OmekaElasticsearch.addAggregations();
    OmekaElasticsearch.resultSorter();
</script>