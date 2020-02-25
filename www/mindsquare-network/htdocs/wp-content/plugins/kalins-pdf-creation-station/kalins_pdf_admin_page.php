<?php

  if ( !function_exists( 'add_action' ) ) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
  }

  kalinsPDF_createPDFDir();

  $save_nonce = wp_create_nonce( 'kalins_pdf_admin_save' );
  $reset_nonce = wp_create_nonce( 'kalins_pdf_admin_reset' );
  $create_nonce = wp_create_nonce( 'kalins_pdf_create_all' );

  $adminOptions = kalins_pdf_get_options(KALINS_PDF_ADMIN_OPTIONS_NAME);

  $adminStrings = file_get_contents(WP_PLUGIN_DIR . '/kalins-pdf-creation-station/help/adminStrings.json');
  wp_enqueue_script( 'media-upload' );
  wp_enqueue_media();
  wp_enqueue_style( 'css-select2', plugins_url( '/select2.min.css', __FILE__ ) );
  wp_enqueue_script( 'js-select2', plugins_url( '/select2.full.min.js', __FILE__ ) );

	if( class_exists( 'Pardot_Plugin' ) ) {
		$pardot_form_items = Pardot_Plugin::get_forms();
	}
?>

<script type='text/javascript'>
"use strict";

var app = angular.module('kalinsPDFAdminPage', ['ui.bootstrap', 'kalinsUI']);

app.controller("InputController",["$scope", "$http", "kalinsToggles", "kalinsAlertManager", function($scope, $http, kalinsToggles, kalinsAlertManager) {

  //build our toggle manager for the accordion's toggle all button
  $scope.kalinsToggles = new kalinsToggles([true, true, true, true, true, true], "Close All", "Open All", "kalinsSettingsPageAccordionToggles" );

  //set up the alerts that show under the form buttons
  $scope.kalinsAlertManager = new kalinsAlertManager(4);

  $scope.oHelpStrings = <?php echo $adminStrings ?>;

  var self = this;

  var saveNonce = '<?php echo $save_nonce; //pass a different nonce security string for each possible ajax action?>';
  var resetNonce = '<?php echo $reset_nonce; ?>';
  var createAllNonce = '<?php echo $create_nonce; ?>';

  self.oOptions = <?php echo json_encode($adminOptions); ?>;

  self.saveData = function(){
    //copy our data into new object
    var data = {};
    data.oOptions = self.oOptions;

    $http({method:"POST", url:ajaxurl, params: {action:'kalins_pdf_admin_save', _ajax_nonce:saveNonce },  data:data}).
      success(function(data, status, headers, config) {
        if(data === "success"){
          $scope.kalinsAlertManager.addAlert("Settings saved successfully.", "success");
        }else{
          $scope.kalinsAlertManager.addAlert(data, "danger");
        }
      }).
      error(function(data, status, headers, config) {
        $scope.kalinsAlertManager.addAlert("An error occurred: " + data, "danger");
      });
  }

  self.resetToDefaults = function(){
    if(confirm("Are you sure you want to reset all of your field values? You will lose all the information you have entered and your cache of PDF files will be cleared.")){

      $http({method:"POST", url:ajaxurl, params: {action:'kalins_pdf_reset_admin_defaults', _ajax_nonce:resetNonce }}).
        success(function(data, status, headers, config) {
          self.oOptions = data;
          $scope.kalinsAlertManager.addAlert("Defaults reset successfully.", "success");
        }).
        error(function(data, status, headers, config) {
          $scope.kalinsAlertManager.addAlert("An error occurred: " + data, "danger");
        });
    }
  }

  self.createAll = function(){
    var creationInProcess = false;

    if(!creationInProcess){
      $scope.kalinsAlertManager.addAlert("Creating PDF files for all pages and posts.", "success");
    }

    $http({method:"POST", url:ajaxurl, params: {action:'kalins_pdf_create_all', _ajax_nonce:createAllNonce }}).
      success(function(data, status, headers, config) {
        if(data.status == "success"){
          if(data.existCount >= data.totalCount){
            $scope.kalinsAlertManager.addAlert(data.totalCount  +  " PDF files successfully cached.", "success");
            creationInProcess = false;
          }else{
            $scope.kalinsAlertManager.addAlert(data.existCount + " out of " + data.totalCount  +  " PDF files cached. Now building the next " +  data.createCount + ".", "success");
            creationInProcess = true;
            self.createAll();
          }
        }
      }).
      error(function(data, status, headers, config) {
        $scope.kalinsAlertManager.addAlert("An error occurred: " + data, "danger");
      });
  }
}]);

jQuery( document ).ready( function( $ ) {
	$( document ).on( 'click', '.kalins-pdf-tcpdf-img-upload', function( event ) {
		event.preventDefault();
		var file_frame = wp.media({
			title: 'Bild auswählen',
			library: {
				type: 'image'
			},
			button: {
				text: 'hinzufügen'
			},
			multiple: false
		});
		file_frame.on( 'select', function() {
			let attachement = file_frame.state().get( 'selection' ).first().toJSON();
			let $scope = angular.element('[ng-app="kalinsPDFAdminPage"]').scope();
			$scope.InputCtrl.oOptions.contactImg = attachement.url;
			$scope.$apply();
		});
		file_frame.open();
	});
	$( document ).on( 'click', '.kalins-pdf-tcpdf-img-upload-titlepageBGImg', function( event ) {
		event.preventDefault();
		var file_frame = wp.media({
			title: 'Bild auswählen',
			library: {
				type: 'image'
			},
			button: {
				text: 'hinzufügen'
			},
			multiple: false
		});
		file_frame.on( 'select', function() {
			let attachement = file_frame.state().get( 'selection' ).first().toJSON();
			let $scope = angular.element('[ng-app="kalinsPDFAdminPage"]').scope();
			$scope.InputCtrl.oOptions.titlepageBGImg = attachement.url;
			$scope.$apply();
		});
		file_frame.open();
	});
	$( '#pardotForm' ).select2();
	$( '#pardotForm-knowhows' ).select2();
});

</script>

  <div ng-app="kalinsPDFAdminPage" ng-controller="InputController as InputCtrl" class="kContainer" ng-strict-di>

    <h2>PDF Creation Station</h2>
    <h3>by Kalin Ringkvist - <a href="http://kalinbooks.com/">kalinbooks.com</a></h3>
    <p>Settings for creating PDF files on individual pages and posts. For more information, click the help tab to the upper right.</p>
    <p><a href="http://kalinbooks.com/pdf-creation-station/">Plugin page</a> | <a href="http://kalinbooks.com/pdf-creation-station/known-bugs/">Report bug</a></p>

    <p><a href="#" ng-click="showVideo = !showVideo">Watch a tutorial video</a></p>
    <div class="text-center" ng-show="showVideo">
      <hr>
      <iframe width="420" height="315" src="//www.youtube.com/embed/OAi1W-77S9g" frameborder="0" allowfullscreen></iframe>
      <p><a href="#" ng-click="showVideo = false">Close video</a></p>
      <hr>
    </div>

    <div class="form-group text-right">
      <button class="btn btn-info" ng-click="kalinsToggles.toggleAll();">{{kalinsToggles.sToggleAll}}</button>
    </div>

    <accordion close-others="false">
	<accordion-group is-open="kalinsToggles.aBooleans[3]">
		<accordion-heading>
			<div><strong>Options </strong><k-help str="{{oHelpStrings['h_adminOptions']}}"></k-help><i class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': kalinsToggles.aBooleans[3], 'glyphicon-chevron-right': !kalinsToggles.aBooleans[3]}"></i></div>
		</accordion-heading>
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label for="txtHeaderTitle" class="control-label col-xs-2">Header title:</label>
				<div class="col-xs-10">
					<input id="txtHeaderTitle" type='text' class="form-control" ng-model="InputCtrl.oOptions.headerTitle"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="txtHeaderSub" class="control-label col-xs-2">Header sub title:</label>
				<div class="col-xs-10">
					<input id="txtHeaderSub" type='text' class="form-control" ng-model="InputCtrl.oOptions.headerSub"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="titlepageBGImg" class="control-label col-xs-2">Hintergrundbild der Titleseite:</label>
				<div class="col-xs-10">
					<img src="{{InputCtrl.oOptions.titlepageBGImg}}" ng-if="InputCtrl.oOptions.titlepageBGImg" style="width:150px;display:inline-block;margin-right:20px;"/>
					<div ng-if="!InputCtrl.oOptions.titlepageBGImg" style="padding-top:7px;padding-right:20px;display:inline-block">Kein Bild ausgewählt</div>
					<input type="hidden" ng-model="InputCtrl.oOptions.titlepageBGImg"></input>
					<a href="" ng-if="InputCtrl.oOptions.titlepageBGImg" ng-click="InputCtrl.oOptions.titlepageBGImg = ''">Bild entfernen</a>
					<button class="kalins-pdf-tcpdf-img-upload-titlepageBGImg button button-primary" ng-if="!InputCtrl.oOptions.titlepageBGImg" data-for="{{InputCtrl.oOptions.titlepageBGImg}}">Bild auswählen</button>
				</div>
			</div>
			<div class="form-group col-md-6 col-xs-12" >
				<div class="checkbox col-md-offset-2">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.includeImages"></input> Include Images</label>
				</div>
				<div class="checkbox col-md-offset-2">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.runShortcodes"></input> Run other plugin shortcodes,</label>
				</div>
				<div class="checkbox col-md-offset-2">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.runFilters"></input> and content filters</label>
				</div>
			</div>
			<div class="form-group col-md-6 col-xs-12" >
				<k-help str="{{oHelpStrings['h_convertLinks']}}"></k-help><b> Convert videos to links:</b>
				<div class="checkbox">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.convertYoutube"></input> YouTube,</label>
				</div>
				<div class="checkbox">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.convertVimeo"></input> Vimeo,</label>
				</div>
				<div class="checkbox">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.convertTed"></input> Ted Talks</label>
				</div>
			</div>

			<?php if( !empty( $pardot_form_items ) ): ?>
				<?php if ( get_current_blog_id() !== 37 ) : ?>
					<div class="form-group">
						<label for="pardotForm" class="control-label col-xs-2"> Pardot-Formular (Blogbereich):</label>
						<div class="col-xs-10">
							<select class="form-control" id="pardotForm" ng-model="InputCtrl.oOptions.pardotForm">
								<?php foreach( $pardot_form_items as $pardot_form_item ): ?>
									<option value="<?php echo $pardot_form_item->id; ?>"><?php echo $pardot_form_item->name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<label for="pardotForm-knowhows" class="control-label col-xs-2"> Pardot-Formular (Knowhows):</label>
					<div class="col-xs-10">
						<select class="form-control" id="pardotForm-knowhows" ng-model="InputCtrl.oOptions.pardotFormKnowhows">
							<?php foreach( $pardot_form_items as $pardot_form_item ): ?>
								<option value="<?php echo $pardot_form_item->id; ?>"><?php echo $pardot_form_item->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>

			<div class="form-group">
				<label for="boxHeadline" class="control-label col-xs-2"> Überschrift der Download-Box:</label>
				<div class="col-xs-10">
					<input type="text" id="boxHeadline" class="form-control" ng-model="InputCtrl.oOptions.boxHeadline"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="boxText" class="control-label col-xs-2"> Text der Download-Box:</label>
				<div class="col-xs-10">
					<textarea id="boxText" class="form-control" ng-model="InputCtrl.oOptions.boxText" rows="4"></textarea>
				</div>
			</div>

			<div class="form-group">
				<label for="buttonText" class="control-label col-xs-2"> Text vom Button:</label>
				<div class="col-xs-10">
					<input type="text" id="buttonText" class="form-control" ng-model="InputCtrl.oOptions.buttonText"></input>
				</div>
			</div>

			<div class="form-group">
				<p class="col-md-offset-1"><k-help str="{{oHelpStrings['h_defaultLinkPlacement']}}"></k-help><b> Default Link Placement</b> (can be overwritten in post edit page):</p>
				<div class="btn-group col-md-offset-1" data-toggle="buttons" >
					<label class="btn btn-success" ng-class="{ 'active': InputCtrl.oOptions.showLink == 'show'}"><input type="radio" value="show" ng-model="InputCtrl.oOptions.showLink" /> Add download link </label>
					<label class="btn btn-success" ng-class="{ 'active': InputCtrl.oOptions.showLink == 'none'}"><input type="radio" value="none" ng-model="InputCtrl.oOptions.showLink" /> Do not generate PDF </label>
				</div>
			</div>

			<div class="form-group">
				<k-help str="{{oHelpStrings['h_minWordCount']}}"></k-help><label for="numWordCount" class="control-label col-xs-2"> Minimum post word count:</label>
				<div class="col-xs-4 col-sm-2">
					<input type="number" ng-model="InputCtrl.oOptions.wordCount" id="numWordCount" class="form-control" />
				</div>
			</div>

			<div class="form-group col-xs-12" >
				<div class="checkbox col-md-offset-1">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.filenameByTitle"></input> Use post slug for PDF filename instead of ID</label>
				</div>
				<div class="checkbox col-md-offset-1">
					<label><input type='checkbox' class="form-control" ng-model="InputCtrl.oOptions.autoGenerate"></input> Automatically generate PDFs on publish and update</label>
				</div>
			</div>

			<div class="form-group text-center">
				<k-help str="{{oHelpStrings['h_mainButtons']}}"></k-help>
				<button ng-click="InputCtrl.saveData()" class="btn btn-success">Save Settings</button>
				<button ng-click="InputCtrl.resetToDefaults()" class="btn btn-warning">Reset Defaults</button>
				<button ng-click="InputCtrl.createAll()" class="btn btn-success">Create All</button>
			</div>

			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<alert ng-repeat="alert in kalinsAlertManager.aAlerts" type="{{alert.type}}" close="kalinsAlertManager.closeAlert($index)">{{alert.index}} - {{alert.msg}}</alert>
				</div>
			</div>
		</form>
	</accordion-group>

	<accordion-group is-open="kalinsToggles.aBooleans[5]">
		<accordion-heading>
			<div><strong>Ansprechpartner</strong><i class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': kalinsToggles.aBooleans[5], 'glyphicon-chevron-right': !kalinsToggles.aBooleans[5]}"></i></div>
		</accordion-heading>

		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label for="contactImg" class="control-label col-xs-2">Bild:</label>
				<div class="col-xs-10">
					<img src="{{InputCtrl.oOptions.contactImg}}" ng-if="InputCtrl.oOptions.contactImg" style="width:150px;display:inline-block;margin-right:20px;"/>
					<div ng-if="!InputCtrl.oOptions.contactImg" style="padding-top:7px;padding-right:20px;display:inline-block">Kein Bild ausgewählt</div>
					<input type="hidden" ng-model="InputCtrl.oOptions.contactImg"></input>
					<a href="" ng-if="InputCtrl.oOptions.contactImg" ng-click="InputCtrl.oOptions.contactImg = ''">Bild entfernen</a>
					<button class="kalins-pdf-tcpdf-img-upload button button-primary" ng-if="!InputCtrl.oOptions.contactImg" data-for="{{InputCtrl.oOptions.contactImg}}">Bild auswählen</button>
				</div>
			</div>
			<div class="form-group">
				<label for="contactName" class="control-label col-xs-2">Name:</label>
				<div class="col-xs-10">
					<input id="contactName" type='text' class="form-control" ng-model="InputCtrl.oOptions.contactName"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="contactPosition" class="control-label col-xs-2">Position:</label>
				<div class="col-xs-10">
					<input id="contactPosition" type='text' class="form-control" ng-model="InputCtrl.oOptions.contactPosition"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="contactTel" class="control-label col-xs-2">Telefonnummer:</label>
				<div class="col-xs-10">
					<input id="contactTel" type='text' class="form-control" ng-model="InputCtrl.oOptions.contactTel"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="contactEmail" class="control-label col-xs-2">E-Mail-Adresse:</label>
				<div class="col-xs-10">
					<input id="contactEmail" type='text' class="form-control" ng-model="InputCtrl.oOptions.contactEmail"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="blogName" class="control-label col-xs-2">Blognamen:</label>
				<div class="col-xs-10">
					<input id="blogName" type='text' class="form-control" ng-model="InputCtrl.oOptions.blogName"></input>
				</div>
			</div>
			<div class="form-group">
				<label for="blogUrl" class="control-label col-xs-2">URL-Adresse:</label>
				<div class="col-xs-10">
					<input id="blogUrl" type='text' class="form-control" ng-model="InputCtrl.oOptions.blogUrl"></input>
				</div>
			</div>
		</form>
	</accordion-group>

	<accordion-group is-open="kalinsToggles.aBooleans[0]">
		<accordion-heading>
			<div><strong>Insert HTML before page or post </strong><k-help str="{{oHelpStrings['h_insertBefore']}}"></k-help><i class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': kalinsToggles.aBooleans[0], 'glyphicon-chevron-right': !kalinsToggles.aBooleans[0]}"></i></div>
		</accordion-heading>
		<b>HTML to insert before page:</b><br />
		<textarea class="form-control" rows='3' ng-model="InputCtrl.oOptions.beforePage"></textarea>
		<b>HTML to insert before post:</b><br />
		<textarea class="form-control" rows='3' ng-model="InputCtrl.oOptions.beforePost"></textarea>
	</accordion-group>

	<accordion-group is-open="kalinsToggles.aBooleans[1]">
		<accordion-heading>
			<div><strong>Insert HTML after page or post </strong><k-help str="{{oHelpStrings['h_insertAfter']}}"></k-help><i class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': kalinsToggles.aBooleans[1], 'glyphicon-chevron-right': !kalinsToggles.aBooleans[1]}"></i></div>
		</accordion-heading>
		<b>HTML to insert after page:</b><br />
		<textarea class="form-control" rows='3' ng-model="InputCtrl.oOptions.afterPage"></textarea>
		<b>HTML to insert after post:</b><br />
		<textarea class="form-control" rows='3' ng-model="InputCtrl.oOptions.afterPost"></textarea>
	</accordion-group>

	<accordion-group is-open="kalinsToggles.aBooleans[2]">
		<accordion-heading>
			<div><strong>Insert HTML for title and final pages </strong><k-help str="{{oHelpStrings['h_insertTitleFinal']}}"></k-help><i class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': kalinsToggles.aBooleans[2], 'glyphicon-chevron-right': !kalinsToggles.aBooleans[2]}"></i></div>
		</accordion-heading>
		<b>HTML to insert for title page:</b><br />
		<textarea class="form-control" rows='3' ng-model="InputCtrl.oOptions.titlePage"></textarea>
		<b>HTML to insert for final page:</b><br />
		<textarea class="form-control" rows='3' ng-model="InputCtrl.oOptions.finalPage"></textarea>
	</accordion-group>

	<accordion-group is-open="kalinsToggles.aBooleans[4]">
		<accordion-heading>
			<div><strong>Shortcodes</strong><i class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': kalinsToggles.aBooleans[4], 'glyphicon-chevron-right': !kalinsToggles.aBooleans[4]}"></i></div>
		</accordion-heading>
		<b>shortcodes:</b> Use these codes anywhere in the above form to insert blog or page information.
		<p><ul>
			<li><b>[current_time format="m-d-Y"]</b> -  PDF creation date/time <b>*</b></li>
			<li><b>[blog_name]</b> -  the name of the blog</li>
			<li><b>[blog_description]</b> - description of the blog</li>
			<li><b>[blog_url]</b> - blog base url</li>
			<li><b>[ID]</b> - the ID number of the page/post</li>
			<li><b>[post_author type="display_name"]</b> - post author information. Possible types: ID, user_login, user_pass, user_nicename, user_email, user_url, display_name, user_firstname, user_lastname, nickname, description, primary_blog</li>
			<li><b>[post_permalink]</b> - the page permalink</li>
			<li><b>[post_date format="m-d-Y"]</b> - date page/post was created <b>*</b></li>
			<li><b>[post_date_gmt format="d-m-Y"]</b> - date page/post was created in gmt time <b>*</b></li>
			<li><b>[post_title]</b> - page/post title</li>
			<li><b>[post_excerpt length="250"]</b> - page/post excerpt (note the optional character 'length' parameter)</li>
			<li><b>[post_name]</b> - page/post slug name</li>
			<li><b>[post_modified format="m-d-Y"]</b> - date page/post was last modified <b>*</b></li>
			<li><b>[post_modified_gmt format="m-d-Y"]</b> - date page/post was last modified in gmt time <b>*</b></li>
			<li><b>[guid]</b> - url of the page/post</li>
			<li><b>[comment_count]</b> - number of comments posted for this post/page</li>
			<li><b>[post_meta name="custom_field_name"]</b> - page/post custom field value. Correct 'name' parameter required</li>
			<li><b>[post_tags delimeter=", " links="true"]</b> - post tags list. Optional 'delimiter' parameter sets separator text. Use optional 'links' parameter to turn off links to tag pages</li>
			<li><b>[post_categories delimeter=", " links="true"]</b> - post categories list. Parameters work like tag shortcode.</li>
			<li><b>[post_parent link="true"]</b> - post parent. Use optional 'link' parameter to turn off link</li>
			<li><b>[post_comments before="" after=""]</b> - post comments. Parameters represent text/HTML that will be inserted before and after comment list but will not be displayed if there are no comments. PHP coders: <a href="http://kalinbooks.com/2011/customize-comments-pdf-creation-station">learn how to customize comment display.</a></li>
			<li><b>[post_thumb size="full" extract="none"]</b> - URL to the page/post's featured image (requires theme support). Possible size paramaters: "thumbnail", "medium", "large" or "full". Possible extract prameters: "on" or "force". Setting extract to "on" will cause the shortcode to attempt to pull the first image from within the post if it cannot find a featured image. Using "force" will cause it to ignore the featured image altogether. Extracted images always return at the same size they appear in the post.</li>
		</ul></p>
		<p><b>*</b> Time shortcodes have an optional format parameter. Format your dates using these possible tokens: m=month, M=text month, F=full text month, d=day, D=short text Day Y=4 digit year, y=2 digit year, H=hour, i=minute, s=seconds. More tokens listed here: <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">http://codex.wordpress.org/Formatting_Date_and_Time.</a> </p>

		<p><b>Note: these shortcodes only work on this page.</b></p>

		<hr/>

		<p><b>The following tags are supported wherever HTML is allowed (according to TCPDF documentation):</b><br /> a, b, blockquote, br, dd, del, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6, hr, i, img, li, ol, p, pre, small, span, strong, sub, sup, table, tcpdf, td, th, thead, tr, tt, u, ul. Also supports some XHTML, CSS, JavaScript and forms.</p>
		<p>Please use double quotes (") in HTML attributes such as font size or href, due to a bug with single quotes.</p>
	</accordion-group>
    </accordion>
  </div>
</html>
