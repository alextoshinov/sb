
<div class="banner edit-banner" data-static-html-hidden="true" data-ng-show="isEditing" data-ng-class="{'edit-banner-review': isBeingReviewed}">
	<h3 data-ng-show="error" data-ng-bind="error"></h3>
	<h3 data-ng-show="!error &amp;&amp; !isBeingReviewed">You are editing this page.</h3>
	<h3 data-ng-show="!error &amp;&amp; isBeingReviewed">Nice! Your change is being reviewed<span class="live-soon-msg"> and will be live soon</span>.</h3>
	<div class="buttons">
		<button class="save-button btn btn-default loading" data-ng-disabled="!isDirty || isSaving || numPhotosUploading != 0" data-ng-click="save()">{{isSaving &amp;&amp; 'Saving...' || (numPhotosUploading != 0 &amp;&amp; 'Uploading...' || 'Save')}}</button>
		<button class="done-button btn btn-default" data-ng-click="done()">Done</button>
	</div>
</div>
<article class="entry-page" data-ng-show="isLoaded" data-ng-class="{'entry-edit-page': isEditing}">
	<div class="header-landscape" data-ng-class="{'header-landscape-with-image' : !local_photo_landscape}">
		<div>
			<div data-ui-if="isLoaded &amp;&amp; local_photo_landscape">
				<div data-ui-if="isEditing" data-ng-click="removePhoto('landscape')" class="delete-photo-icon">×</div>
				<div data-ui-if="isEditing">
					<img data-ui-if="local_photo_landscape.string_id" data-ng-click="openPhotoDetails(local_photo_landscape)" data-ng-src="{{config.hikeImagesPath}}/{{local_photo_landscape.string_id}}-{{capabilities.isMobile &amp;&amp; 'medium' || 'large'}}.jpg" alt="{{local_photo_landscape.alt}}" />
				</div>
				<div data-ui-if="!isEditing" data-fancybox=".landscape-img-link">
					<a class="landscape-img-link" href="javascript:;" data-attribution-link="{{local_photo_landscape.attribution_link}}" data-fancybox-href="{{config.hikeImagesPath}}/{{local_photo_landscape.string_id}}-{{capabilities.isMobile &amp;&amp; 'medium' || 'large'}}.jpg"><img data-ui-if="local_photo_landscape.string_id" data-ng-click="openPhotoDetails(local_photo_landscape)" data-ng-src="{{config.hikeImagesPath}}/{{local_photo_landscape.string_id}}-{{capabilities.isMobile &amp;&amp; 'medium' || 'large'}}.jpg" alt="{{local_photo_landscape.alt}}" />
					</a>
				</div>
				<div data-ui-if="!local_photo_landscape.string_id" data-ng-click="openPhotoDetails(local_photo_landscape)" data-ng-class="{'img-div':true, 'flipped':local_photo_landscape.rotation == 90 || local_photo_landscape.rotation == 270 }" data-ng-style="{'background-image' : 'url(' + local_photo_landscape.src + ')', 'transform' : 'rotate(' + local_photo_landscape.rotation + 'deg)'}"></div>
				<div class="flipped-placeholder" data-ui-if="local_photo_landscape.rotation == 90 || local_photo_landscape.rotation == 270"></div>
			</div>
			<div data-ui-if="!local_photo_landscape &amp;&amp; isEditing">
				<div data-file-uploader="uploadPhotos(files, 'landscape')" data-accept="image/png, image/jpeg" data-enabled="isEditing" data-description="Upload photo..." data-description-font-size="18">
					<img class="placeholder-img dashed-border" data-ui-if="isLoaded &amp;&amp; !local_photo_landscape &amp;&amp; isEditing" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAABAQAAAAAzmykZAAAAAnRSTlMAAQGU/a4AAAAKSURBVAjXY3gAAADiAOG/+FxCAAAAAElFTkSuQmCC" />
				</div>
			</div>
		</div>
		<div data-ng-class="{'header-hike-name':true, 'header-hike-name-with-image':local_photo_landscape}">
			<h1 data-content-editable="isEditing" data-model="hike.name" data-type="text" data-single-line="true" data-change="isDirty = true"></h1>
		</div>
	</div>
	<div class="content">
		<section class="overview-section">
			<div>
				<div class="overview-facts" itemscope itemtype="http://schema.org/Place/Hike">
					<div class="page-curl shadow-bottom" data-ng-class="{'permit':hike.permit || isEditing}">
						<div class="image-container" data-ui-if="isLoaded &amp;&amp; local_photo_facts">
							<div data-ui-if="isEditing" data-ng-click="removePhoto('facts')" class="delete-photo-icon">×</div>
							<div data-ui-if="isEditing">
								<img data-ui-if="local_photo_facts.string_id" data-ng-click="openPhotoDetails(local_photo_facts)" data-ng-src="{{config.hikeImagesPath}}/{{local_photo_facts.string_id}}-thumb-{{capabilities.isMobile &amp;&amp; 'tiny' || 'small'}}.jpg" alt="{{local_photo_facts.alt}}" />
							</div>
							<div data-ui-if="!isEditing" data-fancybox=".facts-img-link">
								<a class="facts-img-link" href="javascript:;" data-attribution-link="{{local_photo_facts.attribution_link}}" data-fancybox-href="{{config.hikeImagesPath}}/{{local_photo_facts.string_id}}-{{capabilities.isMobile &amp;&amp; 'medium' || 'large'}}.jpg"><img itemprop="image" data-ui-if="local_photo_facts.string_id" data-ng-src="{{config.hikeImagesPath}}/{{local_photo_facts.string_id}}-thumb-{{capabilities.isMobile &amp;&amp; 'tiny' || 'small'}}.jpg" alt="{{local_photo_facts.alt}}" /></a>
							</div>
							<div data-ui-if="!local_photo_facts.string_id" class="img-div" data-ng-click="openPhotoDetails(local_photo_facts)" data-ng-style="{'background-image' : 'url(' + local_photo_facts.src + ')', 'transform' : 'rotate(' + local_photo_facts.rotation + 'deg)' }"></div>
						</div>
						<div data-ui-if="!local_photo_facts &amp;&amp; isEditing">
							<div data-file-uploader="uploadPhotos(files, 'facts')" data-accept="image/png, image/jpeg" data-enabled="isEditing" data-description="Upload photo..." style="float:left">
								<img class="placeholder-img dashed-border" data-ui-if="isLoaded &amp;&amp;!local_photo_facts &amp;&amp; isEditing" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />
							</div>
						</div>
						<table>
							<tr data-redirect-focus=".hike-name">
								<td>Hike</td>
								<td>
									<div class="hike-name" itemprop="name" data-content-editable="isEditing" data-model="hike.name" data-type="text" data-single-line="true" data-change="isDirty = true"></div>
								</td>
							</tr>
							<tr data-ng-show="hike.locality != null || isEditing" data-redirect-focus=".hike-locality">
								<td>Location</td>
								<td>
									<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
										<div class="hike-locality" itemprop="addressRegion" data-content-editable="isEditing" data-model="hike.locality" data-type="text" data-single-line="true" data-change="isDirty = true"></div>
									</div>
								</td>
							</tr>
							<tr data-ng-show="hike.distance != null || isEditing">
								<td data-redirect-focus=".hike-distance">Distance</td>
								<td>
									<div itemprop="distance" itemtype="http://schema.org/Distance">
										<div class="hike-distance" data-content-editable="isEditing" data-model="hike.distance" data-render-view="preferences.useMetric" data-filter-view="conversion:kilometers:miles:1:100:true" data-filter-model="conversion:miles:kilometers:5" data-type="numeric" data-positive="true" data-change="isDirty = true" data-single-line="true"></div>
										<span class="unit-label" data-ng-click="preferences.toggleUseMetric()">{{preferences.useMetric &amp;&amp; 'km' || 'mi'}}</span>
									</div>
								</td>
							</tr>
							<tr data-ng-show="hike.elevation_gain != null || isEditing">
								<td data-redirect-focus=".hike-elevation-gain">Elev. Gain</td>
								<td>
									<div itemprop="elevationGain" itemtype="http://schema.org/Distance">
										<div class="hike-elevation-gain" data-content-editable="isEditing" data-model="hike.elevation_gain" data-render-view="preferences.useMetric" data-filter-view="conversion:meters:feet:0" data-filter-model="conversion:feet:meters:5" data-type="numeric" data-change="isDirty = true" data-single-line="true"></div>
										<span class="unit-label" data-ng-click="preferences.toggleUseMetric()">{{preferences.useMetric &amp;&amp; 'm' || 'ft'}}</span>
									</div>
								</td>
							</tr>
							<tr data-ng-show="hike.elevation_max != null || isEditing" data-redirect-focus=".hike-elevation-max">
								<td>Elev. Max</td>
								<td>
									<div itemprop="elevationMax" itemtype="http://schema.org/Distance">
										<div class="hike-elevation-max" data-content-editable="isEditing" data-model="hike.elevation_max" data-render-view="preferences.useMetric" data-filter-view="conversion:meters:feet:0" data-filter-model="conversion:feet:meters:5" data-type="numeric" data-change="isDirty = true" data-single-line="true"></div>
										<span class="unit-label" data-ng-click="preferences.toggleUseMetric()">{{preferences.useMetric &amp;&amp; 'm' || 'ft'}}</span>
									</div>
								</td>
							</tr>
							<tr data-ng-show="isEditing || (hike.location != null &amp;&amp; hike.location.latitude != null &amp;&amp; hike.location.longitude != null)">
								<td data-redirect-focus=".hike-latitude">Lat. / Lng.</td>
								<td>
									<div class="hike-latitude-and-longitude" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
										<a href="/map?lat={{hike.location.latitude}}&amp;lng={{hike.location.longitude}}&amp;zoom=13" rel="nofollow" itemprop="map" data-ui-if="!isEditing">
											<div style="float:left" data-ng-class="{'hike-latitude-and-label': isEditing}">
												<div class="hike-latitude" itemprop="latitude" data-ng-bind="hike.location.latitude | number:1"></div><span style="float:left" data-redirect-focus=".hike-latitude">°,&nbsp;</span></div>
											<div class="hike-longitude-and-label">
												<div class="hike-longitude" itemprop="longitude" data-ng-bind="hike.location.longitude | number:1"></div><span style="float:left" data-redirect-focus=".hike-longitude">°</span></div>
										</a>
										<div data-ui-if="isEditing">
											<div style="float:left" class="hike-latitude-and-label">
												<div class="hike-latitude" itemprop="latitude" data-content-editable="isEditing" data-model="hike.location.latitude" data-filter-view="number:5" data-filter-model="number:5" data-type="numeric" data-change="isDirty = true" data-single-line="true"></div><span style="float:left" data-redirect-focus=".hike-latitude">°,&nbsp;</span></div>
											<div class="hike-longitude-and-label">
												<div class="hike-longitude" itemprop="longitude" data-content-editable="isEditing" data-model="hike.location.longitude" data-filter-view="number:5" data-filter-model="number:5" data-type="numeric" data-change="isDirty = true" data-single-line="true"></div><span style="float:left" data-redirect-focus=".hike-longitude">°</span></div>
										</div>
										<a data-ng-show="!isEditing" href="http://maps.google.com/?q={{hike.location.latitude}},{{hike.location.longitude}}"><span>
                                                                                                                                                    @include('svg.external-link')<img class="external-link" data-ui-if="!Modernizr.svg" data-ng-src="images/external-link.png" /></span></a>
									</div>
								</td>
							</tr>
							<tr data-ng-show="isEditing || hike.permit" data-static-html-hidden="{{hike.permit == null || hike.permit == ''}}">
								<td>Permits</td>
								<td>
									<div itemprop="permit" itemscope itemtype="http://schema.org/Permit">
										<div class="hike-permit" itemprop="name" data-content-editable="isEditing" data-model="hike.permit" data-single-line="true" data-change="isDirty = true"></div>
									</div>
								</td>
							</tr>
							<tr data-ng-show="isEditing &amp;&amp; canSetHikeIsFeatured()" data-static-html-hidden="true">
								<td>Featured</td>
								<td>
									<input type="checkbox" ng-model="hike.is_featured" ng-change="isDirty = true">
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div data-ng-show="isEditing || hike.description" data-give-focus="hike.description == null || hike.description == ''">
					<div id="overview-description" class="overview-description" data-content-editable="isEditing" data-render-view="preferences.useMetric" data-compile="'[data-conversion]'" data-model="hike.description" data-change="isDirty = true"></div>
				</div>
				<div class="overview-description" data-ng-show="hike &amp;&amp; !hike.description &amp;&amp; !isEditing">
					<p>Uh oh, this hike doesn't yet have a description, but you can fix that by <a href="/hikes/{{hike.string_id}}/edit">editing this page</a>.</p>
				</div>
			</div>
		</section>
		<section class="photos-section" data-ng-show="hike.photos_generic.length > 0 || isEditing" data-ng-style="{'float' : isEditing &amp;&amp; 'none' || 'left'}">
			<h2 data-ui-if="isEditing">Photos</h2>
			<div class="photo-thumb-list" data-fancybox=".fancybox-thumb-link">
				<div data-ui-if="!isEditing">
					<a data-ng-repeat="photo in hike.photos_generic" data-attribution-link="{{photo.attribution_link}}" class="fancybox-thumb-link" href="javascript:;" rel="photos" data-fancybox-href="{{config.hikeImagesPath}}/{{photo.string_id}}-{{capabilities.isMobile &amp;&amp; 'medium' || 'large'}}.jpg">
						<div class="photo-thumb" style="position:relative">
							<img  data-ui-if="isLoaded" data-ng-src="{{config.hikeImagesPath}}/{{photo.string_id}}-thumb-{{capabilities.isMobile &amp;&amp; 'tiny' || 'small'}}.jpg" alt="{{photo.alt}}" />
						</div>
					</a>
				</div>
				<div data-ui-if="isEditing">
					<div data-ng-repeat="photo in local_photos_generic">
						<div class="photo-thumb" style="position:relative">
							<div data-ng-click="removePhoto('generic', $index)" class="delete-photo-icon">×</div>
							<div data-ui-if="!photo.string_id" data-ng-click="openPhotoDetails(photo)" class="img-div" data-ng-style="{'background-image' : 'url(' + photo.src + ')', 'transform' : 'rotate(' + photo.rotation + 'deg)'}"></div>
							<img data-ui-if="photo.string_id" data-ng-click="openPhotoDetails(photo)" data-ng-src="{{config.hikeImagesPath}}/{{photo.string_id}}-thumb-{{capabilities.isMobile &amp;&amp; 'tiny' || 'small'}}.jpg" alt="{{photo.alt}}" />
						</div>
					</div>
				</div>
				<div class="photo-thumb" data-ui-if="isLoaded &amp;&amp; isEditing">
					<div data-file-uploader="uploadPhotos(files, 'generic')" data-accept="image/png, image/jpeg" data-enabled="isEditing" data-multiple="true" data-description="Upload photo...">
						<img class="placeholder-img dashed-border" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />
					</div>
				</div>
			</div>
		</section>
		<section class="map-section">
			<h2 data-ui-if="isEditing">Route</h2>
			<div style="position:relative" data-ng-show="hike.route || !isEditing">
				<div class="map-container" data-ui-map="map" data-ui-options="mapOptions" data-ng-style="{'opacity': mapRefreshing &amp;&amp; '0' || '1'}"></div>
				<div data-ng-click="removeRoute()" class="delete-map-icon" data-ng-show="isEditing">×</div>
			</div>
			<div data-ui-if="isEditing &amp;&amp; !hike.route">
				<div data-file-uploader="uploadRoute(files)" data-accept=".gpx, .geojson" data-enabled="isEditing" data-description="Upload GPX or GeoJSON..." data-description-font-size="18">
					<div class="map-container dashed-border" data-ng-show="!hike.route"></div>
				</div>
			</div>
			<h5 class="map-attribution" data-ui-if="mapAttribution"><a rel="nofollow" href="{{mapAttribution.license_link}}" data-ng-bind="'Route data ' + mapAttribution.author"></a></h5>
			<form method="get" action="{{getGpxUrl(hike.string_id)}}" data-ui-if="capabilities.canDownloadFiles &amp;&amp; !isEditing &amp;&amp; hike.route">
				<button class="btn btn-warning download-gpx">Download GPX</button>
			</form>
		</section>
		<section class="metadata-section" data-ui-if="isEditing">
			<h2>Preview photo</h2>
			<p class="discover-explanation">This photo will show up on the Discover tab. If no photo is provided, then the photo in the facts box will be used.</p>
			<div class="image-container" data-ui-if="isLoaded &amp;&amp; local_photo_preview">
					<div data-ui-if="isEditing" data-ng-click="removePhoto('preview')" class="delete-photo-icon">×</div>
					<img data-ui-if="local_photo_preview.string_id" data-ng-src="{{config.hikeImagesPath}}/{{local_photo_preview.string_id}}-thumb-{{capabilities.isMobile &amp;&amp; 'tiny' || 'small'}}.jpg" alt="{{local_photo_preview.alt}}" data-ng-click="openPhotoDetails(local_photo_preview)" />
					<div data-ui-if="!local_photo_preview.string_id" class="img-div" data-ng-click="openPhotoDetails(local_photo_preview)" data-ng-style="{'background-image' : 'url(' + local_photo_preview.src + ')', 'transform' : 'rotate(' + local_photo_preview.rotation + 'deg)' }"></div>
			</div>
			<div class="photo-thumb-list" data-ui-if="isEditing &amp;&amp; !local_photo_preview">
				<div class="photo-thumb">
					<div data-file-uploader="uploadPhotos(files, 'preview')" data-accept="image/png, image/jpeg" data-enabled="isEditing" data-description="Upload photo...">
						<img class="placeholder-img dashed-border" data-ui-if="isLoaded &amp;&amp; !local_photo_preview &amp;&amp; isEditing" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />
					</div>
				</div>
			</div>
		</section>
	</div>
</article>

