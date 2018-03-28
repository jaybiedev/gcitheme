function create_mail(eNaam, eDomain, eTLD, eLabel) {
	var wMail = "";
	if (eLabel == "show") {
		eLabel = eNaam + '&#64;' + eDomain + '.' + eTLD;
	}
	wMail += '<a href="' + 'ma' + 'il' + 'to:' + eNaam;
	wMail += '&#64;' + eDomain + '.' + eTLD;
	wMail += '">' + eLabel + '<' + '/a>';
	document.write(wMail);
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

var headerSearchOpen = false;
var mobileOpen = false;
var shareOpen = false;

function openHeaderSearch() {
	$('.header .search-box').show(1,function() {
		$('.header .search-box').css('opacity','1');
		$('.header .search-input').animate({'left':'0'},100,function() {
			$(this).find('input').focus();
		});
	});
	headerSearchOpen = true;
}
function closeHeaderSearch() {
	$('.header .search-input').animate({'left':'95%'},200,function(){
		$('.header .search-box').css('opacity','0');
		$('.header .search-box').hide();
	});
	headerSearchOpen = false;
}

function openMobile() {
	$('.hamburger').addClass('is-active');
	$('.mobile-nav').show();
	mobileOpen = true;
}
function closeMobile() {
	$('.hamburger').removeClass('is-active');
	$('.mobile-nav').hide();
	$('.mobile-nav .sub-menu').hide();
	mobileOpen = false;
}

function showMainNavBG() {
	$('.header').addClass('scroll').removeClass('unscroll');
}
function hideMainNavBG() {
	$('.header').removeClass('scroll').addClass('unscroll');
}

function showMobileShare() {
	$('.share-icons .addthis_toolbox.mobile').slideDown();
	shareOpen = true;
}
function hideMobileShare(c) {
	if (c == 1) {
		$('.share-icons .addthis_toolbox.mobile').hide();
	} else {
		$('.share-icons .addthis_toolbox.mobile').slideUp();
	}
	shareOpen = false;
}

function footerCheck() {
	var winPos = $(window).scrollTop();
	var space = $(window).height() - $('.footer').height();
	var scrollArea = winPos + space;
	var footerStart = $('.footer').offset().top;
	if (winPos == 0 && (scrollArea > footerStart)) {
		$('.footer').css({'position':'fixed','bottom':'0'});
	} else {
		$('.footer').css({'position':'relative','bottom':'auto'});
	}
}

//adjust styles on browser resize
function resizeCheck() {
	var browserWidth = $(window).width();
	var browserHeight = $(window).height();
	
	//mobile nav
	var mobileNavHeight = browserHeight - $('.header').height() - 53;
	var mobileMainNavHeight = mobileNavHeight - 20;
	$('.mobile-nav').css('height',mobileNavHeight + 'px');
	$('.mobile-nav .scroll, .mobile-nav .scroll .sub-menu').css('height',mobileMainNavHeight + 'px');
	
	if (browserWidth > 1180) {
		$('body').removeClass('mobile');
		if ($('.mobile-nav').is(':visible')) {
			closeMobile();
		}
		if (shareOpen) {
			hideMobileShare(1);
		}
	} else {
		$('body').addClass('mobile');
	}

    if ($('.section.banner.main').length > 0) {
        $('.section.banner.main').css('height', (browserHeight * 0.75) + 'px');
    }

	/*
	//set width of header search box
	var mainNavWidth = $('.header .main-nav > ul').width() + 10;
	$('.header .search-box').css('width',mainNavWidth + 'px');
	//close header search if browser is resized
	if (headerSearchOpen) {
		closeHeaderSearch();
	}
	
	//main nav dropdown positioning
	$('.header .main-nav > ul > li .sub-menu').each(function() {
		var mainNavDDWidth = $(this).width()/2;
		$(this).css('margin-left','-' + mainNavDDWidth + 'px');
	});

	*/
	
	//homepage banner
	/*
	if ($('.section.banner.main').length > 0) {
		$('.section.banner.main').css('height',browserHeight + 'px');
		var bannerTxtPos = browserHeight*0.4;
		$('.section.banner.main .txt').css('top',bannerTxtPos + 'px');
	}
	//header banners
	var headerAnglePos;
	if (browserWidth < 1900) {
		if (browserWidth > 1433) {
			headerAnglePos = $('.section.banner').height() * 0.75;
		} else {
			if ($('body').hasClass('home')) {
				headerAnglePos = $('.section.banner').height() * 0.75;
			} else {
				headerAnglePos = $('.section.banner').height() * 0.75;
			}
		}
	} else {
		if (browserWidth < 2700) {
			headerAnglePos = $('.section.banner').height() * 0.75;
		} else {
			headerAnglePos = $('.section.banner').height() * 0.75;
		}
	}
	$('.section.banner > .angle').css('top',headerAnglePos + 'px').delay(1000).animate({'opacity':1}, 200);

	//header dropdown positioning
	$('.section.banner.sub .banner-dropdown').each(function() {
		var bannerSubDDLeft = $(this).children('ul').width()/2;
		$(this).children('ul').css('margin-left','-' + bannerSubDDLeft + 'px');
	});
	
	//header title positioning
	if ($('.section.banner.sub .txt').length > 0) {
		if (!$('.section.banner.sub .txt .banner-dropdowns').length) {
			$('.section.banner.sub .txt').addClass('no-dd');
		}
	}
	*/


    //header share icons
	if ($('.section.banner.sub .share-icons').length > 0) {
		var headerBannerHeight = $('.section.banner.sub .img').height();
		if (browserWidth > 990) {
			var headerSharePos = headerBannerHeight - 180;
		} else if (browserWidth < 990 && browserWidth > 767) {
			var headerSharePos = headerBannerHeight - 140;
		} else {
			var headerSharePos = headerBannerHeight - 35;
		}
		$('.section.banner.sub .share-icons').css('top',headerSharePos + 'px');
	}
	
	//check footer placement
	footerCheck();
}

$(document).ready(function() {
	//mobile menu
	$('.mobile-btn').click(function() {
		if (mobileOpen) {
			closeMobile();
		} else {
			openMobile();
		}
	});
	$('.mobile-nav .sub-menu').each(function() {
		$(this).prepend('<div class="back"><a class="a-back" href="javascript:;"><span class="arrow">&lt;</span>Back</a></div>');
	});
	$('.mobile-nav .back').each(function() {
		var parentLink = $(this).parents('li').find('a').attr('href');
		var parentTxt = $(this).parents('li').find('a').html();
		$(this).after('<li class="parent-item"><a href="' + parentLink + '">' + parentTxt + '</a></li>');
	});
	//mobile nav functionality
	$('.mobile-menu a').click(function(e) {
		if ($(this).hasClass('a-back')){
			$('.sub-menu').hide();
		} else {
			e.preventDefault();
			var mNavLink = $(this).attr('href');
			var mNavSub = $(this).parent().find('.sub-menu');
			if (mNavSub.length > 0) {
				mNavSub.show();
			} else {
				window.location = mNavLink;
			}
		}
	});
	
	//show header nav bg when scrolled
	$(document).on('scroll',function () {
		var currentScrollTop = window.pageXOffset ? window.pageXOffset : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
		if (currentScrollTop > 30) {
			showMainNavBG();
			if (headerSearchOpen) {
				closeHeaderSearch();
			}
		} else {
			hideMainNavBG();
		}
	});
	var loadTop = $('.container').position().top;
	if (loadTop > 30) {
		showMainNavBG();
		if (headerSearchOpen) {
			closeHeaderSearch();
		}
	}
	
	//main nav dropdowns
	// if (!$('body').hasClass('mobile')) {
		// $('.header .main-nav > ul > li').mouseenter(function() {
			// $('.header .main-nav > ul > li').css('z-index','1');
			// $(this).css('z-index','2');
			// $(this).children('.sub-menu').show(1,function() {
				// $(this).parent().addClass('over');	
			// });
		// }).mouseleave(function() {
			// $(this).children('.sub-menu').stop(true,true).hide(250,function() {
				// $(this).parent().removeClass('over');
			// });
		// });
	// }
	TweenMax.to($('.main-nav > ul > li .sub-menu'), .10, {opacity:0, height:"auto", display:"none"});
	$('.main-nav .menu-item-has-children').mouseenter(function(e) {
		TweenMax.to($(this).find('.sub-menu'), .50, {opacity:1, ease: Expo.easeOut, height:"auto", display:"block", delay: 0.15});
		$(this).css('z-index','2');
	});
	$('.main-nav .menu-item-has-children').mouseleave(function() {
		TweenMax.to($(this).find('.sub-menu'), .50, {opacity:0, ease: Expo.easeOut, height:"0%", display:"none", delay: 0.25});
		$(this).css('z-index','1');
	});
	
	//header search bar
	$('.header .search-btn').click(function() {
		if (headerSearchOpen) {
			closeHeaderSearch();
		} else {
			openHeaderSearch();
		}
	});
	
	//subpage header banner dropdown
	$('.banner-dropdown').click(function() {
		if ($(this).children('ul').is(':visible')) {
			$(this).children('ul').slideUp();
		} else {
			$('.banner-dropdown ul').slideUp();
			$(this).children('ul').slideDown();
		}
	});
	
	$('.container').click(function(e) {
		if (!$(e.target).closest('.search-box').length && !$(e.target).closest('.header .search-btn').length) {
			closeHeaderSearch();
		}
		if (!$(e.target).closest('.banner-dropdown .selected').length) {
			$('.banner-dropdown ul').slideUp();
		}
		if (!$(e.target).closest('.link.options').length) {
			$('.link.options ul').slideUp();
		}
	});
	
	//close modals if escape is pressed
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			if (mobileOpen) {
				closeMobile();
			}
			if (headerSearchOpen) {
				closeHeaderSearch();
			}
			if (shareOpen) {
				hideMobileShare(0);
			}
			if ($('.modal-bg').is(':visible')) {
				$('.modal-bg').hide();
			}
			if ($('.modal').is(':visible')) {
				$('.modal').hide();
			}
			if ($('.banner-dropdown ul').is(':visible')) {
				$('.banner-dropdown ul').slideUp();
			}
			if ($('.media-recent .link.options ul').is(':visible')) {
				$('.media-recent .link.options ul').slideUp();
			}
		}
	});
	
	//close modals
	$('.modal .close-btn').click(function() {
		$('.modal-bg').hide();
		$(this).parents('.modal').hide();
	});
	
	//share icons
	$('.mobile-share').click(function() {
		if (shareOpen) {
			hideMobileShare(0);
		} else {
			showMobileShare();
		}
	});
	
	//if window is resized
	$(window).resize(function() {
		resizeCheck();
	});
	resizeCheck();
	
});

$(window).load(function() {
	var topPos = window.pageXOffset ? window.pageXOffset : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
	if (topPos > 70) {
		showMainNavBG();
	}
	resizeCheck();
	
	//outdated browser check
	outdatedBrowser({
		bgColor: '#f25648',
		color: '#ffffff',
		lowerThan: 'transform',
		languagePath: 'en.html'
	});

});