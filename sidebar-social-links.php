<?php
/**
 * Subpage header graphic.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */
?>
			<div class="share-icons">
				<div class="title">Share This</div>
				<div class="addthis_toolbox dt">
					<div class="custom_images">
						<a class="addthis_button_facebook" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php echo get_the_title() . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-facebook"></i></a>
						<a class="addthis_button_twitter" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php echo get_the_title() . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-twitter"></i></a>
						<a class="addthis_button_email" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php echo get_the_title() . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-envelope"></i></a>
					</div>
				</div>
				<a class="mobile-share" href="javascript:;"><i class="fa fa-share-alt"></i></a>
				<div class="addthis_toolbox mobile">
					<div class="custom_images">
						<a class="addthis_button_facebook" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php echo get_the_title() . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-facebook"></i></a>
						<a class="addthis_button_twitter" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php echo get_the_title() . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-twitter"></i></a>
						<a class="addthis_button_email" addthis:url="<?php the_permalink(); ?>" addthis:title="<?php echo get_the_title() . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-envelope"></i></a>
					</div>
				</div>
			</div><!--end .share-icons-->