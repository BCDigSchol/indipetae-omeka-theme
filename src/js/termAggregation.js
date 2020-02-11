const fetchAggURL = '/elasticsearch/facet';
const aggregations = [];

const $ = jQuery;

class TermAggregation {
    constructor($element) {
        this.$element = $element;
        this.name = $element.data('name');
        this.label = $element.data('label');
        this.field = $element.data('field');
        this.totalBuckets = $element.data('total');
        this.searchQuery = window.location.search;
    }

    fetch() {
        const agg = this;
        const $paginationHolder = agg.$element.find('.aggregation__pagination');
        const $resultBox = agg.$element.find('.aggregation__result-holder');
        agg.$element.find('.aggregation__modal').modal();

        $.get(`${fetchAggURL}/${this.name}${this.searchQuery}`)
            .done(function (data) {
                $paginationHolder.pagination({
                    dataSource: data.buckets,
                    callback: function (data, pagination) {
                        const dataHTML = buildList(data, agg);
                        $resultBox.html(dataHTML);
                    }
                })
            });
    }
}

function buildList(results, agg) {
    let resultsHTML = '';
    results.forEach(function (result) {
        resultsHTML = `${resultsHTML}${buildResult(result, agg)}`;
    });
    return `<ul class="aggregation__list">${resultsHTML}</ul>`
}

function buildResult(result, agg) {
    const term = encodeURIComponent(`${result.key}`);

    const url = `/elasticsearch/search${agg.searchQuery}&${agg.field}=${term}`;
    const bucketLink = `<span class="aggregation__bucket-label"><a href="${url}" class="aggregation__bucket-link">${result.key}</a></span>`;
    const bucketCount = `<span class="aggregation__bucket-count">(${result.doc_count})</span>`;

    return `<li class="aggregation__list-item">${bucketLink} ${bucketCount}</li>`;
}

function addAggregation($element) {
    const agg = new TermAggregation($element);
    $element.find('.aggregation__more-button').click(() => {
        agg.fetch();
    });
    aggregations.push(agg);
}

function addAggregations() {
    $('.aggregation').each(function (i) {
        addAggregation($(this));
    });
}

export {addAggregations};