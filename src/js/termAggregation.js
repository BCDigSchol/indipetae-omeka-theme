// The API URL for fetching facet data.
const fetchAggBaseURL = '/elasticsearch/facet';

// Bind jQuery to $ for convenience.
const $ = jQuery;

/**
 * A term aggregation (facet) "more" menu
 */
class TermAggregation {

    /**
     * Constructor
     *
     * @param $element the JQuery node to build the element aggregation off
     */
    constructor($element) {
        this.$element = $element;
        this.name = $element.data('name');
        this.label = $element.data('label');
        this.field = $element.data('field');
        this.searchQuery = window.location.search;
    }

    /**
     * Fetch aggregation data from the server
     */
    fetch() {

        // Display the modal before waiting for content to fill it.
        this.$element.find('.aggregation__modal').modal();

        // The facet API URL needs the facet to fetch plus the query string used to
        // generate the original search, e.g. /elasticsearch/facet/sender?q=rome
        const fetchURL = `${fetchAggBaseURL}/${this.name}${this.searchQuery}`;

        // Perform the fetch.
        $.get(fetchURL).done(this.showPaginatedResults.bind(this));
    }

    /**
     * Display paginated list of aggregation buckets
     *
     * @param data
     */
    showPaginatedResults(data) {
        this.$element.find('.aggregation__pagination').pagination({
            dataSource: data.buckets, // Build list from data in 'buckets' array of JSON response
            callback: this.buildList.bind(this)
        });
    }

    /**
     * Build the list HTML
     *
     * @param buckets
     */
    buildList(buckets) {

        // Find the results placeholder and add items to it.
        const $resultBox = this.$element.find('.aggregation__result-holder');
        const resultsHTML = buckets.reduce(this.addResult.bind(this), '');
        $resultBox.html(`<ul class="aggregation__list">${resultsHTML}</ul>`);
    }

    /**
     * Reducer to add a single result item
     *
     * @param allResults {string} contains all previous results
     * @param result {object} a single bucket from the API JSON response
     * @return {string}
     */
    addResult(allResults, result) {

        // Be safe!
        const term = encodeURIComponent(`${result.key}`);

        // URL to link to for the faceted search, e.g. '/elasticsearch/search?q=rome&facet_destination=New+Granada
        const url = `/elasticsearch/search${this.searchQuery}&${this.field}=${term}`;

        // Create the link and the count of records in the bucket.
        const bucketLink = `<span class="aggregation__bucket-label"><a href="${url}" class="aggregation__bucket-link">${result.key}</a></span>`;
        const bucketCount = `<span class="aggregation__bucket-count">(${result.doc_count})</span>`;

        // Append to the list of all records
        return `${allResults}<li class="aggregation__list-item">${bucketLink} ${bucketCount}</li>`;
    }
}

/**
 * Add an individual aggregation
 *
 * @param $element
 */
function addAggregation($element) {

    // Create the aggregation.
    const agg = new TermAggregation($element);

    // Make the 'more' button launch the aggregation menu.
    $element.find('.aggregation__more-button').click(() => {
        agg.fetch();
    });
}

/**
 * Create an aggregation for every <div> with the .aggregation class
 */
function addAggregations() {
    $('.aggregation').each(function (i) {
        addAggregation($(this));
    });
}

export {addAggregations};