<!doctype html>
<html lang="en" id="ng-app" data-ng-app="showyBulgariaApp" data-ng-controller="AppController" data-ui-event="{keydown: 'handleGlobalKeydown($event)'}">
  <head>
    <meta charset="utf-8">
    <title>Showy Bulgaria</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta id="viewport" name="viewport" content="width=device-width,initial-scale=1"/>
    <meta id="description" name="description" data-ng-attr-content="{{metaDescription}}" />
    <meta name="robots" content="noodp" />
    <meta name="fragment" content="!" />
    <link rel="icon" type="image/png" href="images/favicon.ico">
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!-- Styles -->

    <link rel="stylesheet" type="text/css" href="<% elixir("css/frontend.css") %>">
    <!-- TODO <% elixir("css/frontend.css") %> -->
    <link href="//fonts.googleapis.com/css?family=Roboto|Montserrat:400,700|Open+Sans:400,300,600" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/animatecss/3.2.0/animate.css" rel="stylesheet">
    <style>
        [data-ng-cloak], [data-preload-resource] {
            display: none !important;
        }
    </style>
    <script>
        (function() {
            var setupNamespace = function() {
                window.showybulgaria = {};
            };

            var doUpdateViewport = function() {
                var minWidth = 450;
                var width = screen.width
                if (width < minWidth) {
                    var viewport = document.getElementById("viewport");
                    viewport.content = "width=" + minWidth;
                    updated = true;
                }
            };

            var updateViewport = function() {
                doUpdateViewport();
                // Workaround for bug http://stackoverflow.com/questions/5021090/screen-width-android
                var userAgent = navigator.userAgent.toLowerCase();
                var isAndroidVersionWithBug = userAgent.indexOf("android 2.2") > -1 || userAgent.indexOf("android 2.3") > -1;
                if (isAndroidVersionWithBug) {
                    setTimeout(doUpdateViewport, 1000);
                }
            };

            var redirectIfOnStatic = function() {
                if (window.location.host === "static.showybulgaria.com") {
                    var path = window.location.pathname;

                    // To account for browser quirks in what is returned from pathname
                    path = path.replace("http://static.showybulgaria.com", "");
                    if (path.length === 0) {
                        path = "";
                    } else if (path[0] !== "/") {
                        path = "/" + path;
                    }
                    window.location.replace("http://showybulgaria.com" + path);
                }
            };

            var removeHashBang = function() {
                // Should never get in this case but Google's mobile index somehow has #!'s which break Angular,
                // hence the workaround.
                if (window.location.hash.indexOf("#!") > -1) {
                    if ("replaceState" in window.history) {
                        window.history.replaceState("", document.title, window.location.pathname + window.location.search);
                    } else {
                        window.location.hash = window.location.hash.replace("#!", "");
                    }
                }
            };

            var setPrivileges = function() {
                window.showybulgaria.userPrivileges = {};
            };

            setupNamespace();
            redirectIfOnStatic();
            updateViewport();
            removeHashBang();
            setPrivileges();
        })();
    </script>
    <!-- TODO, consider removing typekit for mobile browsers to speed up load time -->

    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body data-ui-keydown="{esc: 'isSearchBoxActive = false'}">
    <!--[if lte IE 8]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="container" data-ng-class="{'index-container': navigation.onIndex()}">
        <header data-ng-cloak data-ng-controller="HeaderController">
            <!-- NOSCRIPT_PLACEHOLDER -->
            <div class="header-entries shadow-light">
                <div>
                    <div data-header-entry data-url="/" data-align="left">
                        @include('svg.logo')<img data-ui-if="!Modernizr.svg" data-ng-src="images/logo.png" /></div>
                </div>

                <div>
                    <div data-header-entry data-label="Discover" data-url="/discover" data-align="left">
                        @include('svg.photo-stream')<img data-ui-if="!Modernizr.svg" data-ng-src="images/photo-stream.png" /></div>
                </div>

                <div data-ng-click="ignoreClickIfOnMap($event)">
                    <div data-header-entry data-label="Map" data-url="/map" data-align="left">
                        @include('svg.map')<img data-ui-if="!Modernizr.svg" data-ng-src="images/map.png" /></div>
                </div>

                <div data-ng-click="toggleSearchBox()" data-ng-show="!navigation.onIndex()" data-click-elsewhere="hideSearchBox()" data-is-active="isSearchBoxActive" data-ignore-class="search-dropdown">
                    <div data-header-entry data-label="Search" data-align="right">
                        @include('svg.search')<img data-ui-if="!Modernizr.svg" data-ng-src="images/search.png" /></div>
                </div>

                <div ng-if="isAuthenticated()" data-fancybox=".add-header-link">
                    <a class="add-header-link" href="javascript:;" data-fancybox-href="#add-page" fancybox>
                        <div data-header-entry data-label="Add" data-align="right">
                            @include('svg.add')<img data-ui-if="!Modernizr.svg" data-ng-src="images/add.png" /></div>
                    </a>
                </div>
                <div ng-if="isAuthenticated()">
                    <div  data-header-entry data-label="Profile" data-url="/profile" data-align="left">
                        @include('svg.sign-out')<img data-ui-if="!Modernizr.svg" data-ng-src="images/photo-stream.png" />
                    </div>
                </div>
                <div ng-if="isAuthenticated()">
                    <div data-ng-click="logout()" data-header-entry data-label="Logout" data-align="left">
                        @include('svg.sign-out')<img data-ui-if="!Modernizr.svg" data-ng-src="images/photo-stream.png" />
                    </div>
                </div>
                <div ng-if="!isAuthenticated()">
                    <div data-ng-click="ClickonLogin($event)" data-header-entry data-label="Login" data-url="/login" data-align="left">
                        @include('svg.sign-in')<img data-ui-if="!Modernizr.svg" data-ng-src="images/photo-stream.png" />
                    </div>
                    <div data-ng-click="ClickonSignup($event)" data-header-entry data-label="Sign up" data-url="/signup" data-align="left">
                        @include('svg.sign-out')<img data-ui-if="!Modernizr.svg" data-ng-src="images/photo-stream.png" />
                    </div>
                </div>

                <div ng-if="isAuthenticated()" data-ng-show="navigation.onEntry()">
                    <div data-header-entry data-nofollow="true" data-label="Edit" data-url="{{location.path()}}/edit" data-align="right">@include('svg.edit')<img data-ui-if="!Modernizr.svg" data-ng-src="images/edit.png" /></div>
                </div>

            </div>
            <div class="search-dropdown" data-ng-show="isSearchBoxActive" >
                <div class="header-right shadow-light">
                    <div class="search-bar" data-ng-class="{'search-by-location':preferences.searchBy == 'location','search-by-name':preferences.searchBy == 'name'}">
                        <button class="btn search-type short" type="button" data-ng-bind="preferences.searchBy" data-ng-click="preferences.toggleSearchBy()" data-redirect-focus="input"></button>
                        <div class="input-box">
                            <input class="hide-ie-input-close" type="text" placeholder="{{preferences.searchBy == 'location' &amp;&amp; 'Utah, USA' || 'The Narrows'}}" spellcheck="false" data-give-focus="isSearchBoxActive" data-ng-model="searchQuery" data-ui-keypress="{enter: 'search()'}" >
                            <div data-ui-if="!isSearching" class="search-input-button" data-ng-click="search()">
                                @include('svg.search')<img data-ui-if="!Modernizr.svg" data-ng-src="images/search.png" style="display:inline" />
                            </div>
                            <div data-ui-if="isSearching" class="loading-spinner rotate"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
<!--Partials begin -->
        <script type='text/ng-template' id='/partials/[[ $partial or "entry" ]].html'>
        [[ $html ]]
        </script>
<!--Partials end -->
<!--Preload Resource begin -->
        [[ $preload_resource  or "" ]]
<!--Preload Resource end -->
<!--Add begin -->
        [[ $add ]]
<!--Add end -->
<div data-ng-view>
  <noscript><meta http-equiv="refresh" content="0; url=http://static.sb.dev" /></noscript>
</div>

    <footer>
            <a href="https://twitter.com/showybulgaria.com"><h4>TWITTER</h4></a>
            <a data-mailto><h4>EMAIL</h4></a>
            <a href="/hikes"><h4>ALL HIKES</h4></a>
            <a href="/about"><h4>ABOUT US</h4></a>
        </footer>
    </div>




    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular-route.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui/0.4.0/angular-ui.min.js"></script>
    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyCpS42FCQP8EbwF8hHr6C5FCg6V7hFKS34&amp;sensor=true"></script>
    <script src="//www.google.com/jsapi"></script>

    <!-- build:js(.) scripts/vendor.js -->
    <!-- bower:js -->
    <script src="<% url('/') %>/bower_components/modernizr/modernizr.js"></script>
<!--    <script src="<% url('/') %>/bower_components/jquery/dist/jquery.js"></script>
    <script src="<% url('/') %>/bower_components/angular/angular.js"></script>-->
    <script src="<% url('/') %>/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js"></script>
    <script src="<% url('/') %>/bower_components/angular-animate/angular-animate.js"></script>
    <script src="<% url('/') %>/bower_components/angular-cookies/angular-cookies.js"></script>
    <script src="<% url('/') %>/bower_components/angular-messages/angular-messages.js"></script>
    <script src="<% url('/') %>/bower_components/angular-resource/angular-resource.js"></script>
    <!--<script src="<% url('/') %>/bower_components/angular-route/angular-route.js"></script>-->
    <script src="<% url('/') %>/bower_components/angular-sanitize/angular-sanitize.js"></script>
    <script src="<% url('/') %>/bower_components/angular-touch/angular-touch.js"></script>
    <script src="<% url('/') %>/bower_components/satellizer/dist/satellizer.min.js"></script>
    <!-- endbower -->

    <!-- endbuild -->
    <script src="<% url('/') %>/scripts/lib/ExifReader.js"></script>
    <script src="<% url('/') %>/scripts/lib/GeoJSON.js"></script>
    <script src="<% url('/') %>/scripts/lib/angular-seo.js"></script>
    <script src="<% url('/') %>/scripts/lib/bootstrap-button.min.js"></script>
    <script src="<% url('/') %>/scripts/lib/canvas-to-blob.min.js"></script>
    <script src="<% url('/') %>/scripts/lib/ios-orientationchange-fix.js"></script>
    <script src="<% url('/') %>/scripts/lib/jquery.fancybox.pack.js"></script>
    <script src="<% url('/') %>/scripts/lib/jquery.masonry.min.js"></script>
    <script src="<% url('/') %>/scripts/lib/medium-editor.js"></script>
    <script src="<% url('/') %>/scripts/lib/string-polyfill.js"></script>
    <script src="<% url('/') %>/scripts/lib/togeojson.js"></script>
    <script src="<% url('/') %>/scripts/lib/ua-parser.min.js"></script>
    <script src="<% url('/') %>/scripts/lib/angular-toastr.js"></script>
    <script src="<% url('/') %>/scripts/lib/angular-toastr.tpls.js"></script>
        <!-- build:js({.tmp,app}) scripts/scripts.js -->
        <script src="<% url('/') %>/scripts/app.js"></script>
        <script src="<% url('/') %>/scripts/controllers/index-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/about-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/login-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/logout-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/signup-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/profile-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/header-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/add-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/admin-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/all-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/app-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/entry-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/map-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/photo-details-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/photo-stream-controller.js"></script>
        <script src="<% url('/') %>/scripts/controllers/search-controller.js"></script>

        <script src="<% url('/') %>/scripts/services/analytics-service.js"></script>
        <script src="<% url('/') %>/scripts/services/attribution-service.js"></script>
        <script src="<% url('/') %>/scripts/services/capabilities-service.js"></script>
        <script src="<% url('/') %>/scripts/services/config-service.js"></script>
        <script src="<% url('/') %>/scripts/services/conversion-service.js"></script>
        <script src="<% url('/') %>/scripts/services/date-time-service.js"></script>
        <script src="<% url('/') %>/scripts/services/filter-parser-service.js"></script>
        <script src="<% url('/') %>/scripts/services/map-tooltip.js"></script>
        <script src="<% url('/') %>/scripts/services/navigation-service.js"></script>
        <script src="<% url('/') %>/scripts/services/persistent-storage-service.js"></script>
        <script src="<% url('/') %>/scripts/services/preferences-service.js"></script>
        <script src="<% url('/') %>/scripts/services/resource-cache-service.js"></script>
        <script src="<% url('/') %>/scripts/services/route-service.js"></script>
        <script src="<% url('/') %>/scripts/services/search-service.js"></script>
        <script src="<% url('/') %>/scripts/services/selection-service.js"></script>
        <script src="<% url('/') %>/scripts/services/user-privileges-service.js"></script>
        <script src="<% url('/') %>/scripts/services/account-service.js"></script>



        <script src="<% url('/') %>/scripts/directives/click-elsewhere-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/contenteditable-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/conversion-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/fancy-box-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/file-uploader-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/give-focus-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/header-entry-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/mailto-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/meta-description-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/model-filter-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/paste-lat-lng-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/photo-stream-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/preload-resource-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/redirect-focus-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/reset-form-validation-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/password-match-directive.js"></script>
        <script src="<% url('/') %>/scripts/directives/password-strength-directive.js"></script>
        <script src="<% url('/') %>/scripts/filters/capitalize-filter.js"></script>
        <script src="<% url('/') %>/scripts/filters/conversion-filter.js"></script>
        <!-- endbuild -->




</body>
</html>
