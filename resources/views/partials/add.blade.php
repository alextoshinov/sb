<div class="add-page-container" data-ng-controller="AddController" data-ng-cloak data-static-html-hidden="true" data-ng-style="{'z-index' : locatingLatLng &amp;&amp; '100000' || '-1'}">
    <article id="add-page" class="add-page modal-input-dialog">
        <h2 data-ui-if="isLoaded">Add a hike</h2>
        <form data-ng-submit="add()" action="javascript:void(0);" onkeyup="if (event.keyCode == '27') $.fancybox.close()" data-ng-class="{'submitted': isSubmitted}" data-ui-if="isLoaded" data-reset-form-validation="!capabilities.isPrepopulatingFormsSupported &amp;&amp; isLoaded">
            <div>
                <div>
                        <label for="name">Name</label>
                        <input id="name" type="text" required="true" autocomplete="off" data-ng-model="hike.name" data-give-focus="isLoaded" placeholder="The Narrows" />
                </div>
                <div>
                        <label for="locality">Location</label>
                        <input id="locality" type="text" required="true" autocomplete="off" data-ng-model="hike.locality" placeholder="Utah, USA" />
                </div>
                <div>
                        <label for="distance">Distance</label>
                        <input id="distance" type="number" step="any" min="0" required="true" autocomplete="off" data-model="hike.distance" data-model-filter="conversion:miles:kilometers:5" data-view-filter="conversion:kilometers:miles:1" data-update-on="preferences.useMetric" data-keep-current-model-on-update="hike.route" placeholder="{{preferences.useMetric &amp;&amp; 24.9 || 15.5}}" /><span class="units" data-ng-click="preferences.toggleUseMetric()">{{preferences.useMetric &amp;&amp; 'kilometers' || 'miles'}}</span>
                </div>
                <div>
                        <label for="elevationGain" data-ng-show="capabilities.isMobile">Elev. Gain</label><label for="elevationGain" data-ng-show="!capabilities.isMobile">Elevation Gain</label>
                        <input id="elevationGain" type="number" step="any" min="0" required="true" autocomplete="off" data-model="hike.elevation_gain" data-model-filter="conversion:feet:meters:5" data-view-filter="conversion:meters:feet" data-update-on="preferences.useMetric" data-keep-current-model-on-update="hike.route" placeholder="{{preferences.useMetric &amp;&amp; 50 || 163}}" /><span class="units" data-ng-click="preferences.toggleUseMetric()">{{preferences.useMetric &amp;&amp; 'meters' || 'feet'}}</span>
                </div>
                <div>
                        <label for="elevationMax" data-ng-show="capabilities.isMobile">Elev. Max</label><label for="elevationMax" data-ng-show="!capabilities.isMobile">Elevation Max</label>
                        <input id="elevationMax" type="number" step="any" min="0" required="true" autocomplete="off" data-model="hike.elevation_max" data-model-filter="conversion:feet:meters:5" data-view-filter="conversion:meters:feet" data-update-on="preferences.useMetric" data-keep-current-model-on-update="hike.route" placeholder="{{preferences.useMetric &amp;&amp; 1798 || 5900}}" /><span class="units" data-ng-click="preferences.toggleUseMetric()">{{preferences.useMetric &amp;&amp; 'meters' || 'feet'}}</span>
                </div>
                <div class="latitude-and-longitude">
                        <label for="latitude">Lat. / Lng.</label>
                        <input id="latitude" type="number" step="any" min="-90" max="90" autocomplete="off" data-paste-lat-lng="lat" data-ng-model="hike.location.latitude" placeholder="{{capabilities.isMobile &amp;&amp; '37.3066' || '37.30669'}}" />
                        <input id="longitude" type="number" step="any" min="-180" max="180" autocomplete="off" data-paste-lat-lng="lng" data-ng-model="hike.location.longitude" placeholder="{{capabilities.isMobile &amp;&amp; '-112.9474' || '-112.94745'}}" />
                        <button type="button" class="locate-btn btn" data-ng-click="locateLatLng()">
                                @include('svg.map')<img data-ui-if="!Modernizr.svg" data-ng-src="images/map.png" />
                        </button>
                </div>
                <div class="gpx">Or, <a data-ng-click="readGPX()">upload GPX</a> to autocomplete fields</div>
                <span>
                        <div data-ng-bind="error" class="error"></div>
                        <input type="submit" class="btn" value="Add Hike" data-ng-click="attemptSubmit()" />
                </span>
            </div>
        </form>
    </article>
	<div class="add-page-map" data-ui-if="locatingLatLng">
		<div class="banner">
			<h3>Select a primary location<span class="banner-extra-text"> for the hike</span>.</h3>
			<div class="buttons">
				<button class="save-button btn" data-ng-click="saveLatLng()" data-ng-disabled="!mapMarker">Save</button>
				<button class="cancel-button btn" data-ng-click="cancelLatLng()">Cancel</button>
			</div>
		</div>

		<div class="map-container" data-ui-options="mapOptions" data-ui-map="map" data-ui-event="{'map-click': 'addMarker($event, map)'}"></div>
	</div> 
</div>


