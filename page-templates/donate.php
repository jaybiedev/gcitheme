<?php
/**
 * Template Name: Donate Template
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section donate1">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<?php 
						$genericPageTitle = get_field('generic_page_content_title');
						if ($genericPageTitle) {
							echo '<header>';
								echo '<h1>' . $genericPageTitle . '</h1><hr />';
							echo '</header>' . PHP_EOL;
						}
					?>
					<div class="content">
						<div class="left">
						<?php 
							while ( have_posts() ) : the_post();
								get_template_part( 'content', 'page' ); 
							endwhile; // end of the loop. 
						?>
						</div><!--end .left-->
						<div class="right">
							<form id="donform" name="donform" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<div class="donate-options">
									<div class="donate-option" data-id="5.00">$5</div><div class="donate-option" data-id="10.00">$10</div><div class="donate-option" data-id="25.00">$25</div><div class="donate-option" data-id="50.00">$50</div>
								</div>
								
								<div class="donate-amt">
									<input type="text" id="amount" name="amount" value="" placeholder="0.00" />
								</div>
								
								<div class="donate-apply">
									<label for="applyto">Apply donation to (Optional):</label>
									<input type="text" name="applyto" id="applyto" />
								</div>
								
								<div class="donate-txt">
									<p>All donations will support Grace Communion International unless specified as follows:</p>
									<ol>
										<li>Donate to a local GCI congregation by specifying the church name, city, and state.</li>
										<li>Donate to disaster relief by specifying "GCI Disaster Relief Fund"</li>
									</ol>
								</div>
								
								<input type="hidden" name="cmd" value="_donations">
								<input type="hidden" name="business" value="donations@gci.org">
								<input type="hidden" name="currency_code" value="USD">
								<input type="hidden" name="notify_url" value="https://online.gci.org/live/GCICommon/Webservices/GCI_PayPalIPN.ashx">
								<input type="hidden" name="image_url" value="<?php echo get_template_directory_uri(); ?>/images/logo-gci-black.png">
								<input type="hidden" name="return" value="http://www.gci.org/online-giving/thank-you/">
								<input type="hidden" name="rm" value="1">
								<input type="hidden" name="cbt" value="Return to GCI website">
								<input type="hidden" name="item_number" id="item-number" value="">
								<input type="hidden" name="item_name" id="item-name" value="">
								<input name="custom" type="hidden" value="Source=webcc">
								
								<div class="donate-submit">
									<input type="submit" name="submit" value="Donate" />
									<img src="<?php echo get_template_directory_uri(); ?>/images/credit-cards.gif" alt="PayPal - The safer, easier way to pay online!" />
								</div>
							</form>
						</div><!--end .right-->
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		<script>
			$(document).ready(function() {
				function setApplyName() {
					var applyTo = $('#applyto').val();
					if (!applyTo) {
						applyTo = 'For GCI to use where it is most needed';
					}
					$('#item-name').val(applyTo);
				}
				$('.donate-submit input').mousedown(function() {
					setApplyName();
				});
				
				var itemName = 'Source=webcc';
				$('#item-number').val(itemName);
				
				$('.donate-option').click(function() {
					var donateAmt = $(this).data('id');
					$('.donate-amt input').focus();
					$('.donate-amt input').val(donateAmt);
					$('.donate-option').removeClass('on');
					$(this).addClass('on');
				});
				
				$('.donate-amt input').on('input',function() {
					$('.donate-option').removeClass('on');
				});
				
				jQuery('#donform').validate({
				  rules: {
					amount: {
					  required: true,
					  min: 1
					}
				  }
				});
			});
		</script>

		<section class="section donate2">
			<div class="angle-blue">
				<img src="<?php echo get_template_directory_uri(); ?>/images/gci-bg.png">
			</div>
			<div class="inner">
				<div class="pad">
					<?php 
						$s1Title = get_field('s1_title');
						$s1SubTitle = get_field('s1_sub_title');
						$s1Content = get_field('s1_content');
						if ($genericPageTitle) {
							echo '<header class="light">';
								echo '<h1>' . $s1Title . '</h1><hr />';
								echo '<h2>' . $s1SubTitle . '</h2>';
							echo '</header>' . PHP_EOL;
							echo '<div class="content">' . $s1Content . '</div><!--end .content-->';
						} 
					?>
					<div class="items">
					<?php
						if (get_field('ways_to_give')) {
							while (has_sub_field('ways_to_give')) {
								$giveImg = get_sub_field('image');
								$giveTitle = get_sub_field('title');
								$giveContent = get_sub_field('content');
								echo '<div class="item">';
									echo '<img src="' . $giveImg['url'] . '" />';
									echo '<h1>' . $giveTitle . '</h1><hr />';
									echo $giveContent;
									if (get_sub_field('show_buttons')) {
										if (get_sub_field('buttons')) {
											echo '<div class="btn light">';
											while (has_sub_field('buttons')) {
												$giveBtnTxt = get_sub_field('text');
												$giveBtnLink = get_sub_field('link');
												$giveBtnWin = '';
												if (get_sub_field('new_window')) {
													$giveBtnWin = ' target="_blank"';
												}
												echo '<a href="' . $giveBtnLink . '"' . $giveBtnWin . '>' . $giveBtnTxt . '</a>';
											}
											echo '</div>';
										}
									}
								echo '</div><!--end .item-->';
							}
						}
					?>
					</div><!--end .items-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php get_footer(); ?>