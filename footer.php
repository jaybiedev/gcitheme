<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */
    include_once(get_template_directory() . "/lib/classes/Utilities.php");

    $footer_content = null;

    $footerpage = get_page_by_title('Footer');
    $footer_content = $footerpage->post_content;

    if (empty($footer_content)) {
        $footer_content = getDefaultFooter();
    }

    $footer_content = Utilities::replaceTags($footer_content)
?>
    <a href="#0" class="cd-top" title="Scroll to Top"><i class="fa fa-chevron-up"></i></a>

		<footer class="footer">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<div class="main">
                        <?php echo $footer_content;?>
					</div><!--end .main-->
					<div class="btm">
						<div class="right">
							<span>&copy; <?php echo date('Y') . ' '?> Grace Communion International.</span> All Rights Reserved
						</div><!--end .right-->
						<div class="left">
							<a href="https://resources.gci.org" target="_blank" title="Grace Communion International"><?=get_bloginfo('title');?></a>
						</div><!--end .left-->
					</div><!--end .btm-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</footer><!--end .footer-->
		
	</div><!--end .container-->
	<div id="outdated"></div>

    <script src="<?php echo get_template_directory_uri(); ?>/js/readmore.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/savvy.resources.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/js/swiper.jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.validate.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/additional-methods.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/main.js?v=05" type="text/javascript"></script>

	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-593f19f2a4214e9a"></script>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/outdatedbrowser/outdatedbrowser.min.css">
    <script src="<?php echo get_template_directory_uri(); ?>/outdatedbrowser/outdatedbrowser.min.js"></script>
	<?php wp_footer(); ?>
</body>
</html>

<?php
function getDefaultFooter() {

    ob_start();
    wp_nav_menu( array(
            'menu'  => 'Footer Menu',
            'container'	 => false
        )
    );
    $menu = ob_get_contents();
    ob_end_clean();

    $title = get_bloginfo('title');
    $logo_url = get_template_directory_uri() . "/images/gci-logo.png";

    $html =<<<HTML
    <div class="right">
        <div class="col">
            <?php if (false) {?>
            <div class="item">
                <div class="title"><h5>Member Login</h5><hr /></div>
                <a href="https://online.gci.org/live/home">Click here</a> to log in or 
                <a href="https://online.gci.org/live/iCore/Contacts/Create_Account_no_Sign_In.aspx?WebsiteKey=47388c87-15c8-4a8c-91a8-8006d4a7127e&returnprev=t">Create a new account</a>
            </div>
            <?}?>
            <div class="item">
                <div class="title"><h5>Links</h5><hr /></div>
                {$menu}
            </div>
        </div><!--end .col-->
        <div class="col">
            <div class="item">
                <div class="title"><h5>GCI Newsletter</h5><hr /></div>
                Subscribe to the<br />
                GCI email newsletter
                <div class="newsletter-form">
                    <form method="POST" action="/newsletter/subscribe/">
                        <input type="text" name="subscribe_email" placeholder="E-mail" value="" />
                        <input type="submit" value="Submit" />
                    </form>
                </div>
            </div>
        </div><!--end .col-->
    </div><!--end .right-->
    <div class="left">
        <img src="{$logo_url}" alt="{$title}" />
    </div><!--end .left-->
HTML;

    return $html;
}