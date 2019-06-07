<?php queue_css_file('elasticsearch-results'); ?>
<?php queue_js_file('elasticsearch'); ?>
<?php queue_js_string('ElasticsearchPlugin.setupSearchResults();'); ?>
<?php echo head(['title' => __('Elasticsearch')]); ?>

<?php $totalResults = isset($results['hits']['total']) ? $results['hits']['total'] . ' ' . __('results') : null; ?>

<div class="container">

    <h1><?= __('Search') . " ($totalResults)" ?></h1>

    <div id="elasticsearch-search">
        <form id="elasticsearch-search-form">
            <input type="text" title="<?= __('Search keywords') ?>" name="q" value="<?= htmlspecialchars(array_key_exists('q',
                $_GET) ? $_GET['q'] : '', ENT_QUOTES) ?>"/>
            <?php foreach ($query['facets'] as $facet_name => $facet_values): ?>
                <?php if (is_array($facet_values)): ?>
                    <?php foreach ($facet_values as $facet_value): ?>
                        <input type="hidden" name="<?= "facet_{$facet_name}[]" ?>" value="<?= $facet_value ?>">
                    <?php endforeach; ?>
                <?php else: ?>
                    <input type="hidden" name="<?= "facet_{$facet_name}" ?>" value="<?= $facet_values ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="submit" value="Search"/>
            <br>
            <a href="javascript:void(0);" id="elasticsearch-help-btn" style="display:block;clear:both;"><?= __('Search Help') ?></a>
        </form>
    </div>

    <div id="elasticsearch-help" style="display:none;">
        <?= $this->partial('search/partials/help.php') ?>
    </div>

    <!-- RESULTS -->
    <?php
    //echo "<!--".json_encode($results, JSON_PRETTY_PRINT)."-->";
    ?>

    <?php if ($results): ?>
        <div class="row">
            <section id="elasticsearch-sidebar" class="col-md-3">
                <?= $this->partial('search/partials/aggregations.php',
                    ['query' => $query, 'aggregations' => $aggregations])
                ?>
            </section>

            <section id="elasticsearch-results" class="col-md-9">

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

                <?= pagination_links() ?>
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

<?= js_tag('indipetae.bundle') ?>
<script>
    OmekaElasticsearch.resultSorter();
</script>