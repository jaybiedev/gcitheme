<?php
/**
 * Template Name: Newsletter Subscription
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section generic">
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
					<div class="content subs">
					<?php 
						while ( have_posts() ) : the_post();
							get_template_part( 'content', 'page' ); 
						endwhile; // end of the loop. 
						
						if (is_page(458)) {
							if (isset($_REQUEST['subscribe_email']) && $_REQUEST['subscribe_email'] != "") {
								include('_lib/MailManLists.php');
								mmSub('webupdate',$_REQUEST['subscribe_email']);
								 
								echo "<p><br />You have subscribed to the GCI Website weekly email using the following email address:</p><h5>" . $_REQUEST['subscribe_email'] . "</h5><p>You can <a href=/newsletter/unsubscribe>unsubscribe</a> from this newsletter at any time.</p>";
							} else {
								echo "<p><br />You must enter a valid email address.</p><form action='/newsletter/subscribe' method='POST'><p><input name='subscribe_email' size='20' type='text' /></p><p><input name='Submit' type='submit' value='Subscribe' /></p></form>";
							}
						}
						if (is_page(460)) {
							if (isset($_REQUEST['subscribe_email']) && $_REQUEST['subscribe_email'] != "") {
								echo "<p><br />Attempting to unsubcribe you from our email list...</p>\n";
								include('_lib/MailManLists.php');
								mmUnsub('webupdate',$_REQUEST['subscribe_email']);

								echo '<p><br />If we were unsuccessful in unsubscribing you, then you probably used a different address to subscribe. Click the back button on your browser and try another email address or visit <a href="http://lists.gci.org/listinfo.cgi/webupdate-gci.org">http://lists.gci.org/listinfo.cgi/webupdate-gci.org</a> and access your subscription options directly.</p><p>You can <a href=/newsletter/subscribe>subscribe</a> to this newsletter again at any time.</p>';
							} else {
					?>
						<form action="/newsletter/unsubscribe" method="POST">
							<p><br />We're sorry to hear that you want to unsubscribe from the GCI web site update email list. To do that, please enter your email address below and click the button.</p>
							<p>Email address: <input name='subscribe_email' size='20' type='text' /></p><p><input name='Submit' type='submit' value='Unsubscribe' /></p>
						</form>
					<?php
							}
						}
					?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php get_footer(); ?>