<div id="banner-wrapper">
    <div class="home-search">
        <form id="home-search__form" name="search-form" action="/elasticsearch/search/interceptor" method="get" _lpchecked="1">
            <label for="query" class="home-search__label">Explore the Litterae Indipetae</label>
            <div class="input-group">
                <input type="text" name="query" id="query" value="" class="home-search__input form-control" placeholder="Search..." title="Search"/>
                <?= $this->partial('common/partials/submit-search-button.php') ?>
            </div>
        </form>

        <div class="home-search__adv_search_link">
            <a class="" href="/items/search">Advanced Search</a>
            <a class="" href="/elasticsearch/search">Browse Letters</a>
        </div>
    </div>
</div>