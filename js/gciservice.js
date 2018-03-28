	$=jQuery;
	if (!$_REQUEST) {
		var $_REQUEST={"lookfor":"","miles":"50","find":"church"};
	}
	if (!$_REQUEST['find']) {
		$_REQUEST['find']="church";
	}
	if (!$_REQUEST['miles']) {
		$_REQUEST['miles']="50";
	}
	var webservice='https://online.gci.org/live/GCICommon/Webservices/GCI_ChurchLocator.ashx';
	var findwhat=$_REQUEST['find'];
	var lookfor=$_REQUEST['lookfor'];
	var miles=$_REQUEST['miles'];
	var sublock=false;
	var isModalNow=false;
	var dataAll;
	var searchInfo;
	var churchList;
	var pastorList;
	var churchInfo='';
	var churchMeetings;
	var cityList;
	var errorMessages;
	var dialoginit=[];
	
	function subLock(locking) {
		return; // locking disabled due to timing issues.
		if (locking) {
			if (sublock) return true;
			sublock=true;
			$('.subhold').attr('disabled','disabled'); // prevent submission until we're ready
		} else {
			sublock=false;
			$('.subhold').removeAttr('disabled'); // allow submission
		}
		return false;
	}

	function startThis() {
		$('#milesselect').val(miles);
		$('#lookfor').val(lookfor);
		showSearch(findwhat);
		doSearch(false);
	}
	$(document).ready(startThis);

	function ajaxError(a,b,c) { 
		alert('Error status='+a.status); 
		//if (debug) $('#debugmsg').html(a.responseText); 
		//alert(a.responseText); 
		//alert(b); 
		//alert(c); 
		closeDialog('WaitMessageDialog');
		subLock(false); // allow submission 
	}


	function loadData(data) {
		var mydata = JSON.parse(data);
		dataAll=mydata;
		var donewaiting=true;
		
		if (mydata.churchlist) {
			churchList = mydata.churchlist;
			$('#resultslist').html('');
			$('#resultsinfobox').html('');
			if (churchList.length>0) {
				donewaiting=false
				subLock(false); // allow submission 
				showChurch(0);
				var rec;
				for (var i=0,r;r=churchList[i];i++) {
					rec=getFormattedItem(r,i);
					$('#resultslist').html($('#resultslist').html()+rec);
				}
			} else {
				$('#resultslist').html('No churches found.');
			}
		}
		
		if (mydata.search&&!mydata.churchlist) {
			$('#resultslist').html('No churches found.');
		}

		if (mydata.pastorlist) {
			pastorList = mydata.pastorlist;
			$('#resultslist').html('');
			$('#resultsinfobox').html('');
			if (pastorList.length>0) {
				var rec;
				var pid='';
				for (var i=0,r;r=pastorList[i];i++) {
					if (pid!=r.ID) {
						pid=r.ID;
						rec=getFormattedContact(r,false);
						$('#resultslist').html($('#resultslist').html()+rec);
						$('#contactjobs'+pid).show();
						$('#contactname'+pid).addClass('bold');
					}
					if (r.rectype=='CH') { //only show "church" jobs
						rec=getFormattedJob(r);
						$('#contactjobs'+pid).html($('#contactjobs'+pid).html()+rec);
					}
				}
			} else {
				$('#resultslist').html('No pastors found.');
			}
		}
		
		if (mydata.churchaddress&&mydata.churchaddress.ChurchID) {
			$('#resultsinfobox').html('');
			var r=mydata.churchaddress;
			var rec=getFormattedAddress(r);
			$('#resultsinfobox').html(rec);
			var w=r.WebSite;
			if (w) {
				rec='<a href="'+w+'" target="_blank">'+w+'</a>';
				w=r.Update_Frequency;
				if (w!='')
					rec+='<br />Updated '+w+'.';
				if (r.Show_Comments)
					rec+='<br />'+r.Comments;
				if (r.Featured_Site)
					rec+='<br />This is a featured web site.';
				$('#resultswebsiteinfo').html(rec);
				$('#resultswebsite').show();
			}
			if (r.LocatorDescription) {
				rec='<p>'+r.LocatorDescription+'</p>';
				$('#resultsdescription').html(rec);
			}
		}
		
		if (mydata.churchmeetings) {
			churchMeetings = mydata.churchmeetings;
			if (churchMeetings.length>0) {
				var rec;
				for (var i=0,r;r=churchMeetings[i];i++) {
					rec=getFormattedMeeting(r, i);
					$('#resultsservices').html($('#resultsservices').html()+rec);
				}
				$('#resultsservicesheader').show();
			} else {
				$('#resultsservices').html('');
				$('#resultsservicesheader').hide();
			}
		}
		
		if (mydata.churchcontact) {
			var rec=false;
			// We now allow an override contact
			if (mydata.churchaddress&&mydata.churchaddress.ChurchID) {
				rec=getOverrideContact(mydata.churchaddress);
			}
			if (!rec) {
				rec=getFormattedContact(mydata.churchcontact,true);
			}
			if (rec) {
				$('#resultscontacts').html($('#resultscontacts').html()+rec);
			}
		}
		
		if (mydata.citylist) {
			cityList = mydata.citylist;
			if (cityList.length>0) {
				var rec;
				var city=mydata.search.City.toTitleCase();
				$('#lookfor').val(city);
				$('#stateselect').html('<option value="">Select a city...</option>');
				for (i=0,r;r=cityList[i];i++) {
					rec='<option value="'+r.Key+'">'+city+', '+r.Value+'</option>';
					$('#stateselect').html($('#stateselect').html()+rec);
				}
			}
			openDialog('stateselectbox',true);
		}
		
		if (donewaiting) {
			closeDialog('WaitMessageDialog');
			subLock(false); // allow submission 
		}

		// adjust vertical separator
		var lh=$('#resultslistbox').height();
		var ih=$('#resultsinfobox').height();
		$('#resultsvsep').height(Math.max(lh,ih));
	}

	function getFormattedItem(r,n) {
		var rec=$('#resultslistentry').html();
		rec=rec.replace(/\$n\$/g,n);
		rec=rec.replace('$name$',r.Name);
		rec=rec.replace('$csz$',r.Location);
		var m='';
		if (r.Distance!=0) {
			m=' ('+r.Distance+' miles)';
		}
		rec=rec.replace('$miles$',m);
		return(rec);
	}

	function getFormattedAddress(r) {
		if (churchInfo=='') {
			churchInfo=$('#resultsinfo').html();
			$('#resultsinfo').html('');
		}
		var rec=churchInfo;
		var isFG=((r.ChurchType=='ChFG')||(r.ChurchType=='ChCFG'));
		var cn=r.Name;
		var sh='Services:';
		var showAddress=1;
		if (isFG) {
			cn=cn+'<br />Fellowship Group*';
			sh='Meeting times:';
			if (!r.LocatorShowAddress) {
				showAddress=0;
			}
		}
		rec=rec.replace('$name$',cn);
		rec=rec.replace('$serviceheader$',sh);
		var a;
		if (!showAddress) {
			a='Please contact the person below for meeting address and more information.';
			rec=rec.replace('$mapstyle$','display:none;');
			rec=rec.replace('$fgnotice$','display:inline-block; margin-top:1em;');
		} else {
			a=r.Address1;
			var m='';
			if (parseInt(a.substring(0,1))==a.substring(0,1)) {
				m+=a.toString().trim();
			}
			if (r.Address2) {
				aa=r.Address2;
				a+='<br />'+aa;
				if (parseInt(aa.substring(0,1))==aa.substring(0,1)) {
					m+=' '+aa.toString().trim();
				}
			}
			if (r.Address3) {
				a+='<br />'+r.Address3;
				m+=' '+r.Address3.toString().trim();
			}
			var zip=r.HomeZip;
			if (zip!=parseInt(zip)) {
				zip=zip.substring(0,5);
			}
			a+='<br />'+r.HomeCity+', '+r.HomeST+' '+zip;
			m+=', '+r.HomeCity+', '+r.HomeST+' '+zip;
			m='https://www.google.com/maps/place/'+m.replace(/ /g,'+');
			var lmu=r.LocatorMapUrl.toString().trim();
			if (lmu!='') {
				m=lmu;
				if (m.substring(0,4).toLowerCase()!='http') {
					m='http://'+m;
				}
			}
			rec=rec.replace('$mapurl$',m);
			rec=rec.replace('$mapstyle$','display:block;');
			rec=rec.replace('$fgnotice$','display:none;');
		}
		rec=rec.replace('$addr$',a);
		return(rec);
	}

	function getFormattedMeeting(r, n) {
		var rec=$('#resultsservice').html();
		rec=rec.replace(/\$n\$/g,n);
		rec=rec.replace('$stype$',r.meeting_type);
		rec=rec.replace('$sfreq$',r.frequency);
		rec=rec.replace('$stime$',r.MEETING_TIME);
		rec=rec.replace('$sday$',r.day_of_week);
		return(rec);
	}

	function getOverrideContact(r) {
		var rec;
		rec=$('#resultscontact').html();
		var name=r.LocatorContact;
		var phone=r.LocatorPhone;
		var email=r.LocatorEmail;
		if (email!='') 
			email='<br />Email: <a href="mailto:'+email+'">'+email+'</a>';
		if (name!='') {
			rec=rec.replace(/\$n\$/g,0);
			rec=rec.replace('$name$',name);
			rec=rec.replace('$phone$',phone);
			rec=rec.replace('$email$',email);
		} else {
			rec=false;
		}
		return(rec);
	}

	function getFormattedContact(r,plink) {
		var rec=$('#resultscontact').html();
		var name;
		if (r.Job=='CP'||r.Job=='PT') {
			name='Pastor '+r.FIRST_NAME+' '+r.LAST_NAME;
		} else if (r.Job=='ASC') {
			name='Associate Pastor '+r.FIRST_NAME+' '+r.LAST_NAME;
		} else {
			name=r.FULL_NAME;
		}
		if (plink) {
			name='<a href="#" onclick="showPastor(\''+r.ID+'\');return(false);">'+name+'</a>';
		} else {
			var rec2=$('#resultslistpastor').html();
			rec=rec2.replace('$contact$',rec);
		}
		var phone;
		if (r.PayType=='FULL') {
			if (trim(r.WORK_PHONE)!='') {
				phone=trim(r.WORK_PHONE);
			} else {
				phone=trim(r.HOME_PHONE);
			}
		} else {
			if (trim(r.HOME_PHONE)!='') {
				phone=trim(r.HOME_PHONE);
			} else {
				phone=trim(r.WORK_PHONE);
			}
		}
		var email='';
		if (r.EMAIL!='') 
			email='<br />Email: <a href="mailto:'+r.EMAIL+'">'+r.EMAIL+'</a>';
		rec=rec.replace('$name$',name);
		rec=rec.replace('$phone$',phone);
		rec=rec.replace('$email$',email);
		rec=rec.replace(/\$id\$/g,r.ID);
		return(rec);
	}

	function getFormattedJob(r) {
		var rec=$('#contactjob').html();
		var job=r.JobDesc;
		if (r.Job=='CP') {
			job='Pastor';
		} else if (r.Job=='ASC') {
			job='Associate Pastor';
		}
		rec=rec.replace('$job$',job);
		rec=rec.replace('$churchid$',r.ChurchID);
		rec=rec.replace('$churchloc$',r.Location);
		rec=rec.replace('$churchname$',r.Name);
		return(rec);
	}

	function showChurch(idx) {
		if (subLock(true)||idx==null|!churchList||churchList.length<=idx) return; // prevent submission until we're ready
		var churchid=churchList[idx].ChurchID;
		$('#WaitMessage').html('Loading church information...');
		openDialog('WaitMessageDialog',true);
		$.ajax({type:'POST',url:'/sites/all/themes/gci/reqrelay.php',
			data:{
				url:webservice,
				'do':'churchinfo',
				churchid:churchid
				},
			success:loadData,
			error:ajaxError,
			dataType:'text'});
	}
	
	function showPastor(lookfor) {
		findwhat='pastor';
		showSearch(findwhat);
		$('#lookfor').val(lookfor);
		doSearch(false);
	}

	function showPastorChurch(churchid) {
		if (subLock(true)) return; // prevent submission until we're ready
		$('#WaitMessage').html('Loading church information...');
		openDialog('WaitMessageDialog',true);
		$.ajax({type:'POST',url:'/sites/all/themes/gci/reqrelay.php',
			data:{
				url:webservice,
				'do':'churchinfo',
				churchid:churchid
				},
			success:loadData,
			error:ajaxError,
			dataType:'text'});
		return(true);
	}
	
	function showSearch(t) {
		var gosearch=false;
		var secfrom='pastor';
		var secto  ='church';
		var resultstitle='Search results:'
		findwhat=t.toLowerCase();
		if (t=='all'||t=='featured') {
			$('#lookfor').val(t);
			gosearch=true;
			if (t=='all') {
				resultstitle='All churches with web sites:';
			} else {
				resultstitle='Churches with featured web sites:';
			}
		} else if (t=='pastor') {
			secto  ='pastor';
			secfrom='church';
			resultstitle='Pastors who match:'
		}
		$('#search'+secfrom).hide();
		var v=$('#lookfor').val();
		var b=$('#lookfor'+secfrom).html();
		$('#lookfor'+secfrom).html('');
		if (b!='')
			$('#lookfor'+secto).html(b);
		$('#lookfor').val(v);
		$('#lookfor').keypress(onEnterSearch);
		$('#search'+secto).show();
		$('#resultstitle').html(resultstitle);
		if (gosearch) doSearch(false);
	}
	
	function doSearch(showerr) {
		lookfor=$('#lookfor').val();
		if (lookfor=='') {
			if (showerr)
				alert('Please type something to search for.');
		} else if (lookfor.length<2&&findwhat=='pastor') {
			if (showerr)
				alert('Please type at least two characters of the last name.');
		} else {
			if (subLock(true)) return; // prevent submission until we're ready
			$('#WaitMessage').html('Searching...');
			var a='search';
			if (findwhat=='pastor')
				a='findpastor';
			openDialog('WaitMessageDialog',true);
			$.ajax({type:'POST',url:'/sites/all/themes/gci/reqrelay.php',
				data:{
					url:webservice,
					'do':a,
					lookfor:$('#lookfor').val(),
					miles:$('#milesselect').val()
					},
				success:loadData,
				error:ajaxError,
				dataType:'text'});
		}
	}
	
	function onEnterSearch(e) {
		if (e) {
			if (e.which==13) {
				doSearch(true);
				return false;
			}
		}
	}
	
	function onStateSearch() {
		$('#lookfor').val($('#lookfor').val()+', '+$('#stateselect').val());
		closeDialog('stateselectbox');
		doSearch(true);
	}
	
	function openDialog(dialogName,isModal) {
		var w=getWindowSize();
		if (isModal&&!isModalNow) {
			var d='#modaloverlay';
			$(d).addClass('dialogmodal');
			$(d).width(w['width']).height(w['height']);
			//$(d).fadeIn('slow');
			$(d).show();
			isModalNow=true;
		}
		var d='#'+dialogName;
		$(d).addClass('dialogbox');
		//var boxheight=$(d).css('height');
		var boxheight=$(d).height();
		//var boxwidth=$(d).css('width');
		var boxwidth=$(d).width();
		var mytop=(w.height-boxheight)/3;
		var myleft=(w.width-boxwidth)/2;
		//alert('V: winheight='+w['height']+', boxheight='+boxheight+', top='+mytop);
		//alert('H: winwidth='+w['width']+', boxwidth='+boxwidth+', left='+myleft);
		$(d).css({top: mytop, left: myleft});
		$(d).show();
	}
	
	function closeDialog(dialogName) {
		$('#'+dialogName).hide();
		$('#modaloverlay').hide();
		setTimeout('isModalNow=false;',500);
	}
	
	function trim(str) {
		return str.replace(/^\s+|\s+$/g,"");
	}

	function getWindowSize() {
		var windowWidth, windowHeight, w;
		if (self.innerHeight) {	// all except Explorer
			if(document.documentElement.clientWidth){
				windowWidth = document.documentElement.clientWidth; 
			} else {
				windowWidth = self.innerWidth;
			}
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}	
		w = new Array();
		w['width']=windowWidth;
		w['height']=windowHeight;
		return w;
	}
	
/* To Title Case 1.1.1
 * David Gouch <http://individed.com>
 * 23 May 2008
 * License: http://individed.com/code/to-title-case/license.txt
 *
 * In response to John Gruber's call for a Javascript version of his script: 
 * http://daringfireball.net/2008/05/title_case
 */

String.prototype.toTitleCase = function() {
    return this.replace(/([\w&`'??"?.@:\/\{\(\[<>_]+-? *)/g, function(match, p1, index, title) {
        if (index > 0 && title.charAt(index - 2) !== ":" &&
        	match.search(/^(a(nd?|s|t)?|b(ut|y)|en|for|i[fn]|o[fnr]|t(he|o)|vs?\.?|via)[ \-]/i) > -1)
            return match.toLowerCase();
        if (title.substring(index - 1, index + 1).search(/['"_{(\[]/) > -1)
            return match.charAt(0) + match.charAt(1).toUpperCase() + match.substr(2);
        if (match.substr(1).search(/[A-Z]+|&|[\w]+[._][\w]+/) > -1 || 
        	title.substring(index - 1, index + 1).search(/[\])}]/) > -1)
            return match;
        return match.charAt(0).toUpperCase() + match.substr(1);
    });
};	
;
(function ($) {

  Drupal.behaviors.captcha = {
    attach: function (context) {

      // Turn off autocompletion for the CAPTCHA response field.
      // We do it here with JavaScript (instead of directly in the markup)
      // because this autocomplete attribute is not standard and
      // it would break (X)HTML compliance.
      $("#edit-captcha-response").attr("autocomplete", "off");

    }
  };

  Drupal.behaviors.captchaAdmin = {
    attach: function (context) {
      // Add onclick handler to checkbox for adding a CAPTCHA description
      // so that the textfields for the CAPTCHA description are hidden
      // when no description should be added.
      // @todo: div.form-item-captcha-description depends on theming, maybe
      // it's better to add our own wrapper with id (instead of a class).
      $("#edit-captcha-add-captcha-description").click(function() {
        if ($("#edit-captcha-add-captcha-description").is(":checked")) {
          // Show the CAPTCHA description textfield(s).
          $("div.form-item-captcha-description").show('slow');
        }
        else {
          // Hide the CAPTCHA description textfield(s).
          $("div.form-item-captcha-description").hide('slow');
        }
      });
      // Hide the CAPTCHA description textfields if option is disabled on page load.
      if (!$("#edit-captcha-add-captcha-description").is(":checked")) {
        $("div.form-item-captcha-description").hide();
      }
    }

  };

})(jQuery);
;
(function ($) {

Drupal.googleanalytics = {};

$(document).ready(function() {

  // Attach mousedown, keyup, touchstart events to document only and catch
  // clicks on all elements.
  $(document.body).bind("mousedown keyup touchstart", function(event) {

    // Catch the closest surrounding link of a clicked element.
    $(event.target).closest("a,area").each(function() {

      // Is the clicked URL internal?
      if (Drupal.googleanalytics.isInternal(this.href)) {
        // Skip 'click' tracking, if custom tracking events are bound.
        if ($(this).is('.colorbox') && (Drupal.settings.googleanalytics.trackColorbox)) {
          // Do nothing here. The custom event will handle all tracking.
          //console.info("Click on .colorbox item has been detected.");
        }
        // Is download tracking activated and the file extension configured for download tracking?
        else if (Drupal.settings.googleanalytics.trackDownload && Drupal.googleanalytics.isDownload(this.href)) {
          // Download link clicked.
          ga("send", {
            "hitType": "event",
            "eventCategory": "Downloads",
            "eventAction": Drupal.googleanalytics.getDownloadExtension(this.href).toUpperCase(),
            "eventLabel": Drupal.googleanalytics.getPageUrl(this.href),
            "transport": "beacon"
          });
        }
        else if (Drupal.googleanalytics.isInternalSpecial(this.href)) {
          // Keep the internal URL for Google Analytics website overlay intact.
          ga("send", {
            "hitType": "pageview",
            "page": Drupal.googleanalytics.getPageUrl(this.href),
            "transport": "beacon"
          });
        }
      }
      else {
        if (Drupal.settings.googleanalytics.trackMailto && $(this).is("a[href^='mailto:'],area[href^='mailto:']")) {
          // Mailto link clicked.
          ga("send", {
            "hitType": "event",
            "eventCategory": "Mails",
            "eventAction": "Click",
            "eventLabel": this.href.substring(7),
            "transport": "beacon"
          });
        }
        else if (Drupal.settings.googleanalytics.trackOutbound && this.href.match(/^\w+:\/\//i)) {
          if (Drupal.settings.googleanalytics.trackDomainMode !== 2 || (Drupal.settings.googleanalytics.trackDomainMode === 2 && !Drupal.googleanalytics.isCrossDomain(this.hostname, Drupal.settings.googleanalytics.trackCrossDomains))) {
            // External link clicked / No top-level cross domain clicked.
            ga("send", {
              "hitType": "event",
              "eventCategory": "Outbound links",
              "eventAction": "Click",
              "eventLabel": this.href,
              "transport": "beacon"
            });
          }
        }
      }
    });
  });

  // Track hash changes as unique pageviews, if this option has been enabled.
  if (Drupal.settings.googleanalytics.trackUrlFragments) {
    window.onhashchange = function() {
      ga("send", {
        "hitType": "pageview",
        "page": location.pathname + location.search + location.hash
      });
    };
  }

  // Colorbox: This event triggers when the transition has completed and the
  // newly loaded content has been revealed.
  if (Drupal.settings.googleanalytics.trackColorbox) {
    $(document).bind("cbox_complete", function () {
      var href = $.colorbox.element().attr("href");
      if (href) {
        ga("send", {
          "hitType": "pageview",
          "page": Drupal.googleanalytics.getPageUrl(href)
        });
      }
    });
  }

});

/**
 * Check whether the hostname is part of the cross domains or not.
 *
 * @param string hostname
 *   The hostname of the clicked URL.
 * @param array crossDomains
 *   All cross domain hostnames as JS array.
 *
 * @return boolean
 */
Drupal.googleanalytics.isCrossDomain = function (hostname, crossDomains) {
  /**
   * jQuery < 1.6.3 bug: $.inArray crushes IE6 and Chrome if second argument is
   * `null` or `undefined`, http://bugs.jquery.com/ticket/10076,
   * https://github.com/jquery/jquery/commit/a839af034db2bd934e4d4fa6758a3fed8de74174
   *
   * @todo: Remove/Refactor in D8
   */
  if (!crossDomains) {
    return false;
  }
  else {
    return $.inArray(hostname, crossDomains) > -1 ? true : false;
  }
};

/**
 * Check whether this is a download URL or not.
 *
 * @param string url
 *   The web url to check.
 *
 * @return boolean
 */
Drupal.googleanalytics.isDownload = function (url) {
  var isDownload = new RegExp("\\.(" + Drupal.settings.googleanalytics.trackDownloadExtensions + ")([\?#].*)?$", "i");
  return isDownload.test(url);
};

/**
 * Check whether this is an absolute internal URL or not.
 *
 * @param string url
 *   The web url to check.
 *
 * @return boolean
 */
Drupal.googleanalytics.isInternal = function (url) {
  var isInternal = new RegExp("^(https?):\/\/" + window.location.host, "i");
  return isInternal.test(url);
};

/**
 * Check whether this is a special URL or not.
 *
 * URL types:
 *  - gotwo.module /go/* links.
 *
 * @param string url
 *   The web url to check.
 *
 * @return boolean
 */
Drupal.googleanalytics.isInternalSpecial = function (url) {
  var isInternalSpecial = new RegExp("(\/go\/.*)$", "i");
  return isInternalSpecial.test(url);
};

/**
 * Extract the relative internal URL from an absolute internal URL.
 *
 * Examples:
 * - http://mydomain.com/node/1 -> /node/1
 * - http://example.com/foo/bar -> http://example.com/foo/bar
 *
 * @param string url
 *   The web url to check.
 *
 * @return string
 *   Internal website URL
 */
Drupal.googleanalytics.getPageUrl = function (url) {
  var extractInternalUrl = new RegExp("^(https?):\/\/" + window.location.host, "i");
  return url.replace(extractInternalUrl, '');
};

/**
 * Extract the download file extension from the URL.
 *
 * @param string url
 *   The web url to check.
 *
 * @return string
 *   The file extension of the passed url. e.g. "zip", "txt"
 */
Drupal.googleanalytics.getDownloadExtension = function (url) {
  var extractDownloadextension = new RegExp("\\.(" + Drupal.settings.googleanalytics.trackDownloadExtensions + ")([\?#].*)?$", "i");
  var extension = extractDownloadextension.exec(url);
  return (extension === null) ? '' : extension[1];
};

})(jQuery);
;
