<?php
/**
 * Template Name: Participate Template
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section generic participate">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
				<?php
					$i = 0;
					echo '<div class="content-nav">';
					if (get_field('content_group')) {
						while (has_sub_field('content_group')) {
							$i++;
							$title = get_sub_field('title');
							$on = '';
							if ($i == 1) {
								$on = ' class="on"';
							}
							echo '<div class="content-nav-item cni' . $i . '">';
								echo '<a' . $on . ' href="javascript:;" data-id="' . $i . '">' . $title . '</a>';
							echo '</div>';
						}
					}
					echo '</div><!--end .content-nav-->' . PHP_EOL;
					
					$j = 0;
					if (get_field('content_group')) {
						while (has_sub_field('content_group')) {
							$j++;
							echo '<div class="content-group" id="cg' . $j . '">';
								$content = get_sub_field('content');
								echo '<div class="content">';
									echo $content;
								echo '</div>';
								
								if (get_sub_field('use_qa_panel')) {
									echo '<div class="qa-items">';
									if (get_sub_field('qa_item')) {
										while (has_sub_field('qa_item')) {
											$q = get_sub_field('question');
											$a = get_sub_field('answer');
											echo '<div class="qa-item">';
												echo '<div class="item-q">' . $q . '</div>';
												echo '<div class="item-a">' . $a . '</div>';
											echo '</div>';
										}
									}
									echo '</div>';
								}
							echo '</div><!--end .content-group-->';
						}
					}
				?>
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		<script>
			$(document).ready(function() {
				var hash = window.location.hash;
				hash = hash.replace('#','');
				$('.content-nav-item a, .cni' + hash + ' a').click(function() {
					var navID = $(this).data('id');
					if (!$('#cg' + navID).is(':visible')) {
						$('.content-group').animate({'opacity':0},400,function() {
							$(this).css({'z-index':'-1','display':'none'});
							$('#cg' + navID).css({'z-index':'1','display':'block'}).animate({'opacity':'1'},300);
						});
						$('.content-nav-item a').removeClass('on');
						$(this).addClass('on');
					}
				});
				$('.cni' + hash + ' a').trigger('click');
				
				$('.qa-item').each(function() {
					$(this).click(function() {
						if ($(this).children('.item-a').is(':visible')) {
							$(this).removeClass('on');
							$(this).children('.item-a').slideUp();
						} else {
							$('.qa-item').removeClass('on');
							$('.qa-item .item-a').slideUp();
							$(this).addClass('on');
							$(this).children('.item-a').slideDown();
						}
					});
				});
			});
		</script>

<?php get_footer(); ?>