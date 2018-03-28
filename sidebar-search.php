<?php
/**
 * Search Panel.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */
 
?>
		<section class="section search-panel">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header>
						<h1>Find a Church / Pastor</h1><hr />
						<h2>Connect in Your Area</h2>
					</header>
					<div class="content">
						<div class="search-filter group1">
							<div class="label">I'm looking for</div><div class="unique-option pastor">A Pastor</div><div class="unique-option church on">A Church</div>
						</div><!--end .search-filter-->
						<div class="search-filter group2">
							<div class="label">Location</div><div class="dropdown wide country">
								<div class="selected"><a href="javascript:;">United States</a></div><div class="arrow"></div>
								<ul>
								<?php
									$regions = get_terms(array(
										'taxonomy' => 'church-regions',
										'hide_empty' => false,
										'orderby' => 'id'
									));
									if ( ! is_wp_error( $regions ) ){
										foreach ( $regions as $region ) {
											// echo '<li><a href="' . esc_url( get_term_link( $region ) ) . '">' . $region->name . '</a></li>';
											echo '<li data-region="' . $region->slug . '"><a href="javascript: void(0);">' . $region->name . '</a></li>';
										}
									}
								?>
								</ul>
							</div>
						</div><!--end .search-filter-->
						<div class="search-field">
							<div class="search-text church">
								To find a U.S. church, enter one of the following: zip code, state, city, or part of the church name; then click the Search button.
							</div>
							<div class="search-text pastor">
								To find a pastor, type a few characters of the last name and click the Search button.
							</div>
							<form action="<?php echo home_url( '/' ); ?>" id="frmChurchSearch" name="frmChurchSearch">
								<input type="text" id="s" name="s" value="" />
								<input type="hidden" id="searchType" name="searchType" value="church" />
								<input type="hidden" id="searchRegion" name="searchRegion" value="united-states" />
								<input type="submit" value="Search" />
								<div class="search-loader" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" /></div>
							</form>
						</div><!--end .search-field-->
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		<script>
			$(document).ready(function() {
				$('#modalDialog').dialog({
					modal: true,
					autoOpen:false,
					title: 'Please Select a City',
					width: 400,
					height: 'auto'
				});
				
				$("#frmChurchSearch").submit(function(e){
					$('.search-loader').show();
					e.preventDefault();
						
					var form = this;
					// Make AJAX call to search in case multiple cities are returned
					var post_url = "/wp-admin/admin-ajax.php";
					var background = jQuery(".pjssi-overlay-basic");
					background.css("height","100%");
					background.css("opacity","100");
					jQuery.ajax({
						method: "POST",
						url: post_url,
						data:{
							action: "checkcities",
							searchTerm: $("#s").val()
						}
					})
					.done(function( results ) {
						var searchResults = jQuery.parseJSON(results);
						if (searchResults != null) {
							var selectCities = "";
							for (i = 0; i < searchResults.length; ++i) {
								if (selectCities == ""){
									selectCities = "<a href='/?s=" + $("#s").val() + ", " + searchResults[i].Key + "&searchType=church'>" + $("#s").val() + ", " + searchResults[i].Key + "</a>";
								} else {
									selectCities = selectCities + "<BR /><a href='/?s=" + $("#s").val() + ", " + searchResults[i].Key + "&searchType=church'>" + $("#s").val() + ", " + searchResults[i].Key + "</a>";
								}
							}
							$('#modalDialog').html(selectCities);
							$('#modalDialog').dialog('open');
							$('.search-panel .search-loader').hide();
						}
						else {
							form.submit(); // submit bypassing the jQuery bound event
						}
					});
				});
				
				$('.search-panel .unique-option').click(function() {
					$('.search-panel .unique-option').removeClass('on');
					$(this).addClass('on');
				});
				
				$('.search-panel .unique-option').click(function() {
					if ($(this).hasClass('pastor')) {
						$('.search-panel .unique-option.church').removeClass('on');
						$('.search-panel .search-text.church').hide();
						$('.search-panel .search-text.pastor').show();
						//$('.search-panel .dropdown.country').addClass('disabled');
						$('.search-panel .search-filter.group2').hide();
						$('#searchType').val('pastor');
					} else {
						$('.search-panel .unique-option.pastor').removeClass('on');
						$('.search-panel .search-text.pastor').hide();
						$('.search-panel .search-text.church').show();
						//$('.search-panel .dropdown.country').removeClass('disabled');
						$('.search-panel .search-filter.group2').show();
						$('#searchType').val('church');
					}
					$(this).addClass('on');
				});
				
				$('.search-panel .dropdown').click(function() {
					if ($(this).children('ul').is(':visible')) {
						$('.search-panel .dropdown ul').slideUp();
					} else {
						$(this).children('ul').slideDown();
					}
				});
				$('.search-panel .dropdown li').click(function() {
					var itemName = $(this).html();
					var itemValue = $(this).data('region');
					$('#searchRegion').val(itemValue);
					$(this).parents('.dropdown').children('.selected').html(itemName);
					$(this).parent().slideUp();
				});
				
				//close dropdown if clicking outside the dropdown
				$('.container').click(function(e) {
					if (!$(e.target).closest('.search-panel .dropdown .selected, .search-panel .dropdown .arrow').length) {
						$('.search-panel .dropdown ul').slideUp();
					}
				});
				
				//close dropdown with esc button
				$(document).keyup(function(e) {
					if (e.keyCode == 27) {
						if ($('.search-panel .dropdown ul').is(':visible')) {
							$('.search-panel .dropdown ul').slideUp();
						}
					}
				});
			});
		</script>