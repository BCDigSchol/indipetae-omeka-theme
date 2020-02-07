<div class="top-search">
    <div class="row justify-content-start">
        <form class="top-search__form col-12 col-md-8" name="search-form" action="/elasticsearch/search/interceptor" method="get" _lpchecked="1">
            <label for="query" class="top-search__label">Search</label>
            <div class="input-group">
                <input type="text" name="query" id="query" value="" class="top-search__input form-control" placeholder="Search..." title="Search">
                <?= $this->partial('common/partials/submit-search-button.php') ?>
            </div>
        </form>
        <a href="/items/search" class="advanced-search-link col-4">Advanced Search</a>
    </div>
</div>