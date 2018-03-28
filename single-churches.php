<?php
/**
 * Template for displaying church pages.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-content/plugins/pjs-gci-service/service/gci-search-service.php';

	function GetStateExceptions($stateName) {
		$retVal = $stateName;
		switch ($stateName) {
			case "new york":
				$retVal = "new york, NY";
				break;
		}
		
		return $retVal;
	}
	
	function GetStateAbbr($stateAbbr) {
		$states = array(
			'5262'=>'AL',
			'5265'=>'AK',
			'5263'=>'AZ',
			'5264'=>'AR',
			'211'=>'CA',
			'5266'=>'CO',
			'5267'=>'CT',
			'5268'=>'DE',
			'5435'=>'DC',
			'5269'=>'FL',
			'5270'=>'GA',
			'5271'=>'HI',
			'5272'=>'ID',
			'5273'=>'IL',
			'5274'=>'IN',
			'5275'=>'IA',
			'5276'=>'KS',
			'5277'=>'KY',
			'5278'=>'LA',
			'5279'=>'ME',
			'5280'=>'MD',
			'5281'=>'MA',
			'5282'=>'MI',
			'5283'=>'MN',
			'5284'=>'MS',
			'5285'=>'MO',
			'5286'=>'MT',
			'5287'=>'NE',
			'212'=>'NV',
			'5289'=>'NH',
			'5290'=>'NJ',
			'5291'=>'NM',
			'5292'=>'NY',
			'5293'=>'NC',
			'5294'=>'ND',
			'5295'=>'OH',
			'5296'=>'OK',
			'5297'=>'OR',
			'5298'=>'PA',
			'5299'=>'RI',
			'5300'=>'SC',
			'5301'=>'SD',
			'5302'=>'TN',
			'5303'=>'TX',
			'5304'=>'UT',
			'5305'=>'VT',
			'5306'=>'VA',
			'5307'=>'WA',
			'5308'=>'WV',
			'5309'=>'WI',
			'5310'=>'WY',
		);
		return $states[$stateAbbr];
	}
	
	get_header();
	
	$regionName = '';
	
	$terms = get_the_terms( $post->ID, 'church-regions' ); 
    foreach($terms as $term) {
		$regionName = $term->name;
    }
	
	//replace commas with two dashes to match the actual name in the database
	if (strpos($regionName,',') !== false) {
		$regionName = str_replace(', ','--',$regionName);
	}
	
	$getHeaderImg = get_field('header_graphic');
	if ($getHeaderImg) {
		$headerImg = $getHeaderImg['url'];
	} else {
		$headerImg = get_template_directory_uri() . '/images/header-generic.jpg';
	}
?>
		<section class="section banner sub">
			<div class="angle"></div>
			<div class="inner">
				<div class="shadow"></div>
				<div class="img"><img src="<?php echo $headerImg; ?>" /></div>
				<div class="txt">
					<span><?php echo get_the_title(); ?></span>
					<div class="banner-dropdowns">
						<div class="banner-dropdown">
							<div class="selected">By Region<i class="icon"></i></div>
							<ul><?php
								$regions = get_terms(array(
									'taxonomy' => 'church-regions',
									'hide_empty' => false,
									'orderby' => 'id'
								));
								if ( ! is_wp_error( $regions ) ){
									foreach ( $regions as $region ) {
										echo '<li><a href="' . esc_url( get_term_link( $region ) ) . '">' . $region->name . '</a></li>';
									}
								}
							?></ul>
						</div><!--end .banner-dropdown-->
						<div class="banner-dropdown">
						<?php if ('United States' == $regionName) { ?>
							<div class="selected">By State<i class="icon"></i></div>
						<?php } else { ?>
							<div class="selected">By Country<i class="icon"></i></div>
						<?php } ?>
							<ul><?php
								$regionargs = array(
									'post_type' => 'churches',
									'tax_query' => array(
										array(
											'taxonomy' => 'church-regions',
											'field' => 'name',
											'terms' => $regionName
										),
									),
									'orderby' => 'title',
									'order' => 'ASC',
									'posts_per_page' => '-1'
								);
								$regionDDItem = new WP_Query( $regionargs );
								if ($regionDDItem->have_posts() ) : while ( $regionDDItem->have_posts() ) : $regionDDItem->the_post();
									$regionDDLink = get_the_permalink();
									$regionDDTitle = get_the_title();
									echo '<li><a href="' . $regionDDLink . '">' . $regionDDTitle . '</a></li>';
								endwhile;
								endif;
								wp_reset_query();
							?></ul>
						</div><!--end .banner-dropdown-->
					</div><!--end .banner-dropdowns-->
				</div>
			</div><!--end .inner-->
			<?php get_sidebar('social-links'); ?>
		</section><!--end .section-->

		<section class="section generic church-detail">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
				<?php
					while ( have_posts() ) : the_post();
						echo '<div class="church-content">';
							get_template_part( 'content', 'page' );
						echo '</div><!--end .church-content-->';
					endwhile; // end of the loop.
					
					//for churches in the United States
					if ('United States' == $regionName) {
						$uri = $_SERVER["REQUEST_URI"];
						$uri_array = explode('/',$uri);
						$uri_first = $uri_array[1];
						end($uri_array);
						// $stateName = $uri_array[key($uri_array)-1];
						// $stateName = str_replace("-"," ", $stateName);
						// $stateName = GetStateExceptions($stateName);
						$pageId = $post->ID;
						$stateName = GetStateAbbr($pageId);
						echo '<div class="churches-list us">';

						$GCI = new PJSGCIService();
						$miles = 50;
						$searchResults = $GCI->searchChurches($stateName, $miles);						
						
						// var_dump($searchResults);
						// die();
						
						if ($searchResults->churchlist) {
							foreach ($searchResults->churchlist as $aChurch) {
								// $churchDetail = $GCI->getChurchDetails($aChurch->ChurchID);
								
								/**********************
								** START Church Info
								** need to filter so only churches that belong to current state are shown
								**********************/
								$cChurchID = $aChurch->ChurchID;
								$cTitle = $aChurch->Name;
								$cLoc = $aChurch->Location;
								$cAddy = nl2br($aChurch->Full_Address);
								$cAddyLink = preg_replace('/\r|\n/','',$cAddy);
								$cAddyLink = str_replace('<br>',' ',str_replace('<br />',' ',$cAddyLink));
								$cURL = $aChurch->WebSite;
								
								echo '<div id="' . $cChurchID . '" class="church-item">';
									echo '<div class="top" data-churchid="' . $cChurchID . '">';
										echo '<div class="icon"></div>';
										echo '<h2>' . $cTitle . '</h2>';
										if ($cLoc) { echo '<div class="loc"><i class="fa fa-map-marker"></i>' . $cLoc . '</div>'; }
									echo '</div>';
									echo '<div class="loader-gif" style="display: none;"><img src="' .  get_template_directory_uri() . '/images/ajax-loader-gray.gif" /></div>';
									if ($cAddy || $cURL) {
										echo '<div class="detail">';
									}
									if ($cAddy) { echo '<div class="group"><strong>Address</strong><br /><a href="https://www.google.com/maps/place/' . $cAddyLink . '" target="_blank">' . $cAddy . '</a></div>'; }
									if ($cURL) { echo '<div class="group"><strong>Website</strong><br /><a href="' . $cURL . '" target="_blank">' . $cURL . '</a></div>'; }
									if ($cAddy || $cURL) {
										echo '</div>';
									}
								echo '</div><!--end .church-item-->';
								/**********************
								** END Church Info
								**********************/
							}
						}
						
							echo '<div class="church-notice">';
								echo '<p><strong>Note:</strong> This locator only lists standard meeting times. We recommend you confirm location and time with the contact before visiting. Also, many of our congregations rent or share facilities. In these cases, the facility name should not be taken to imply any affiliation with Grace Communion International.</p>';
							echo '</div><!--end .church-notice-->';
						echo '</div><!--end .churches-list-->';
						
					//for churches outside the United States
					} else {
						$locInfo = get_field('location_information');
						echo '<div class="churches-list int">';
							if ($locInfo) {
								echo '<div class="church-item">';
									echo $locInfo;
								echo '</div>';
							}
						echo '</div>';
					}
				?>
					<div class="btn">
						<a href="/participate/#2">Become a GCI Church - Join Now</a>
					</div>
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->

		<script>
			$(document).ready(function() {
				var cServices = "";
				var cContact = "";
				var cNotice = "";
				var churchDetail = "";
				var post_url = "/wp-admin/admin-ajax.php";
				$('.churches-list.us .church-item .top').each(function() {
					$(this).click(function() {
						var thisChurch = $(this);
						if (thisChurch.parent().children('.detail').is(':visible')) {
							thisChurch.parent().removeClass('on');
							thisChurch.parent().children('.detail').slideUp();
						} else {
							if (thisChurch.parent().children('.detail').children('#divServices').length || thisChurch.parent().children('.detail').children('#divContact').length) {
								$('.churches-list.us .church-item').removeClass('on');
								$('.churches-list.us .church-item .detail').slideUp();
								thisChurch.parent().addClass('on');
								thisChurch.parent().children('.detail').slideDown();															
							}
							else {
								// thisChurch.parent().find('.loader-gif').show();
								thisChurch.parent().children('.loader-gif').show();
								$.ajax({
									method: "POST",
									url: post_url,
									data:{
										action: "getchurchdetails",
										churchID: $(this).data("churchid")
									}
								})
								.done(function( results ) {
									churchDetail = jQuery.parseJSON(results);
									//churchDetail = results;
									//alert(results);
									if (churchDetail != null) {									
										cServices = churchDetail[0];
										cContact = churchDetail[1];
										cNotice = churchDetail[2];
										//alert(cContact);
										cNoticeShow = false;
										if (cNotice == 'ChFG') {
											cNoticeShow = true;
										}
										if (cServices != ""){
											cServices = '<div id="divServices" class="group"><strong>Services</strong><br />' + cServices + '</div>';											
											thisChurch.parent().children('.detail').append(cServices);
										}
										if (cContact != ""){
											cContact = '<div id="divContact" class="group"><strong>Contact</strong><br />' + cContact + '</div>';
											thisChurch.parent().children('.detail').append(cContact);											
										}
										if (cNoticeShow) {
											cNotice = '<div class="church-content">*Some of the smallest of our congregations operate as what we refer to as a “Fellowship Group.” These small groups usually have less than 15 people in attendance. They gather for worship and fellowship in informal, relaxed settings (sometimes in homes, often with meals). Their worship tends to be interactive, often featuring group discussions on biblical topics rather than traditional preaching.</div>';
											thisChurch.parent().children('.detail').append(cNotice);
										}
										$('.churches-list.us .church-item').removeClass('on');
										$('.churches-list.us .church-item .detail').slideUp();
										thisChurch.parent().addClass('on');
										thisChurch.parent().children('.detail').slideDown();
										thisChurch.parent().children('.loader-gif').hide();
									}
								});													
							}
						}
					});
				});
			});
		</script>
<?php 
	get_sidebar('search');
	get_footer();
?>