<script type='text/ng-template' id='/partials/index.html'>
    <article class="index-page" data-meta-description="The best place to find beautiful hikes from all over the world. Hike.io is free, open source, and anyone can make an edit. It's filled with gorgeous photos, GPX routes, full screen maps, and detailed trail information.">
            <div class="footer-banner">
                    <h1>showyBulgaria.com</h1>
                    <h2>Find beautiful hikes</h2>
            </div>
            <div class="content">
                    <div class="search-bar" data-ng-class="{'search-by-location':preferences.searchBy == 'location','search-by-name':preferences.searchBy == 'name'}">
                            <button class="btn search-type" type="button" data-ng-bind="'Search by ' + preferences.searchBy" data-ng-click="preferences.toggleSearchBy()" data-redirect-focus="input"></button>
                            <button class="btn search-type short" type="button" data-ng-bind="preferences.searchBy|capitalize" data-ng-click="preferences.toggleSearchBy()" data-redirect-focus="input"></button>
                            <div class="input-box">
                                    <input class="hide-ie-input-close" type="text" placeholder="{{preferences.searchBy == 'location' &amp;&amp; 'Utah, USA' || 'The Narrows'}}" spellcheck="false" data-give-focus="!Modernizr.touch" data-ng-model="searchQuery" data-ui-keypress="{enter: 'search()'}" >
                                    <div class="search-input-button" data-ng-click="search()" data-ng-style="{'opacity': isSearching &amp;&amp; '0', 'cursor': isSearching &amp;&amp; 'auto'} ">
                                            @include('svg.search')<img data-ui-if="!Modernizr.svg" data-ng-src="/images/search.png" style="display:inline" />
                                    </div>
                                    <div data-ui-if="isSearching" class="loading-spinner rotate"></div>
                            </div>
                    </div>
            </div>
    </article>
</script> 

