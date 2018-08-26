<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */
?><!DOCTYPE html>

<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css?v=08" type="text/css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.11.3.min.js"></script>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	
	<?php wp_head(); ?>
	
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/css/swiper.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/savvy.resources.css">

	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-7454035-3']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>

</head>
<body <?php body_class(); ?>>
<?php
/*
$locations = get_nav_menu_locations();
var_dump($locations);
$menu = wp_get_nav_menu_object( $locations[ 'primary' ] );
$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );

echo "<pre>";

// @todo -- make recusrive
$menu = array();
foreach ($menuitems as $key=>$item) {
    $item->submenu = [];
    if (empty($item->menu_item_parent) || $item->menu_item_parent == $item->ID) {
        $_item_key  = 'item_' . $item->ID;
        $menu[$_item_key] = $item;
    }
    else {
        $_item_key  = 'item_' . $item->menu_item_parent;
        foreach ($menu as $_key=>$sitem) {
            echo "KEY " . $_key . "  " . $_item_key;
            if ($_key == $_item_key) {
                $menu[$_key]->submenu[] = $item;
                break;
            }
            else {
                foreach ($sitem->submenu as $tkey=>$titem) {
                    if ($tkey == $_item_key) {
                        $menu[$key]->submenu[] = $item;
                        break 2;
                    }
                }

            }
        }
    }
}
*/

?>
	<div class="modal-bg"></div>
	<div id="modalDialog"></div>	

	<div class="container">
		
		<header class="header top">
			<div class="inner">
				<nav class="navbar">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-mobile-nav-override" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="fa fa-bars fa-2x"></span>
                            </button>
                            <a class="navbar-brand" href="/" title="<?php echo get_bloginfo('title'); ?>">
                                <img class="img-responsive" src="<?php echo get_header_image();?>" alt="<?php echo get_bloginfo('title'); ?>" />
                            </a>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                            <div class="pull-right font-color-theme search-top" title="Search...">
                                <i class="fa fa-search font-color-white"></i>
                                <div class="search-box">
                                    <form action="/">
                                        <input type="text" name="s" value="" placeholder="Search" />
                                    </form>
                                </div>
                            </div>
                            <?php
                            wp_nav_menu( array(
                                    'theme_location' => 'primary',
                                    'menu_class'     => 'nav navbar-nav pull-right hidden',
                                    'container'		 => 'container-fluid',
                                    'menu_id' => 'menu-main-menu'
                                )
                            );
                            ?>
                        </div>
                    </div>
				</nav><!--end .main-nav-->
			</div><!--end .inner-->
		</header><!--end .header-->

		<div class="mobile-nav">
			<div class="scroll">
				<div class="search-box">
					<form action="/">
						<input type="text" name="s" value="" placeholder="Search" /><input type="submit" value="&#xf002;" />
					</form>
				</div>
				<?php
					$mobileNavSub = wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => 'mobile-menu',
						'container'		 => false,
						'echo'			 => false
					 ) );
					echo $mobileNavSub;
				?>
			</div>
		</div><!--end .mobile-nav-->