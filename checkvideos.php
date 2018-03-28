<?php

//STANDARD INCLUDES FOR WORDPRESS DATABASE CONNECTIONS / VARIABLES / FUNCTIONS
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';
include_once $path . '/wp-includes/registration.php';

ini_set('max_execution_time', 6000000);

function checkFileExists($url) {
	//$url = urlencode($url);
	
	$code = FALSE;
	$options['http'] = array(
		'method' => "HEAD",
		'ignore_errors' => 1,
		'max_redirects' => 0
	);
	$body = file_get_contents($url, NULL, stream_context_create($options));
	
	sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);

	var_dump($url . ' - ' . $code);
	
	if ($code == 302) {
		return true;
	}
	else {
		return false;
	}
}

function checkFileExistsCloud($url) {
	//$url = urlencode($url);
	
	$code = FALSE;
	$options['http'] = array(
		'method' => "HEAD",
		'ignore_errors' => 1,
		'max_redirects' => 0
	);
	$body = file_get_contents($url, NULL, stream_context_create($options));
	
	sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);

	var_dump($url . ' - ' . $code);
	
	if ($code == 200) {
		return true;
	}
	else {
		return false;
	}
}

function checkFileExists_sss($url) {
	$retVal = false;
	$headers=get_headers($url);
	var_dump($headers);
	$retVal = stripos($headers[0],"200 OK")?true:false;
	$retVal = $retVal || stripos($headers[0],"302 Found")?true:false;
	return $retVal;
}

function checkFileExists_CURL($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_exec($ch);
	var_dump(curl_getinfo($ch));
}

// $checkThis = checkFileExists('http://gcitv.net/dl/SpOL/WCGT011-480.wmv');
// var_dump($checkThis);
// $checkThis = checkFileExists('http://gcitv.net/dl/SpOL/SpOL-480W.mp4');
// var_dump($checkThis);
// $checkThis = checkFileExists('http://gcitv.net/dl/SpOL/SpOL517-640W.wmv');
// var_dump($checkThis);
// // $checkThis = checkFileExists('http://gcitv.net/dl/SpOL/SpOL517aaaa.mp3');
// die();

global $wpdb;


$qDB = "SELECT a.ID, b.meta_value AS videoID, c.meta_value AS youtubeID FROM wp_gci_posts a 
		LEFT JOIN wp_gci_postmeta b ON a.ID = b.post_id AND b.meta_key = 'video_id'
		LEFT JOIN wp_gci_postmeta c ON a.ID = c.post_id AND c.meta_key = 'youtube_id'
		WHERE a.post_type = 'videos'";
		// WHERE a.ID = 1815";
		
$rDB = $wpdb->get_results($qDB);


$vidSize = '-480';
$vidSizeHD = '-640';

foreach ($rDB as $rDB_Single){
	$postID = $rDB_Single->ID;
	$videoID = $rDB_Single->videoID;
	$youtubeID = $rDB_Single->youtubeID;
	
	if (strpos($videoID, 'SpOL') === 0){
		$catAbrv = 'SpOL';		
	} else if (strpos($videoID, 'YI') === 0) {
		$catAbrv = 'YI';
	} else if (strpos($videoID, 'DIM') === 0) {
		$catAbrv = 'DIM';
	} else if (strpos($videoID, 'WFO') === 0) {
		$catAbrv = 'PastorVid';
	} else {
		$catAbrv = 'MiscVid';
	}

	$vidSize = '-480';
	$vidSizeHD = '-640';
	if (get_field('widescreen')) {
		$vidSize = '-480W';
		$vidSizeHD = '-854W';
	}
	$noVid = false;
	
	$playVideoFile1 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-480.mp4';
	$playVideoFile2 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-320.mp4';
	$playVideoFile3 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-480.flv';
	$playVideoFile4 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-320.flv';
	
	$fileCode1 = checkFileExistsCloud($playVideoFile1);
	if ($fileCode1) { //check first link
		$videoToPlay = $playVideoFile1;
	} else { //first link does not exist, check second link
		$fileCode2 = checkFileExistsCloud($playVideoFile2);
		if ($fileCode2) { //check first link
			$videoToPlay = $playVideoFile2;
		} else { //second link does not exist, check third link
			$fileCode3 = checkFileExistsCloud($playVideoFile3);
			if ($fileCode3) { //check first link
				$videoToPlay = $playVideoFile3;
			} else { //third link does not exist, check fourth link
				$fileCode4 = checkFileExistsCloud($playVideoFile4);
				if ($fileCode4) { //check first link
					$videoToPlay = $playVideoFile4;
				} else { //fourth link does not exist, show error instead
					$noVid = true;
				}
			}
		}
	}

	if (!$noVid || $youtubeID) {
		$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'video';";
		$rDB = $wpdb->get_results($updateSQL);		
	}

	if ($videoID === '' || $videoID === ' ' || $videoID === 'SpOL' || $videoID === 'YI' || $videoID === 'DIM' || $videoID === 'PastorVid' || $videoID === 'MiscVid') {
		
	}
	else {
		$downloadMP3File = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'.mp3';
		$fileCode = checkFileExists($downloadMP3File);
		if ($fileCode) {
			$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND (meta_key = 'mp3_link' OR meta_key = 'audio');";
			$rDB = $wpdb->get_results($updateSQL);
			echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'.mp3 download.<BR />';
		}

		$downloadMP4File = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480.mp4';
		$fileCode = checkFileExists($downloadMP4File);
		if ($fileCode) {
			$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'mp4_link';";
			$rDB = $wpdb->get_results($updateSQL);
			// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480.mp4 download.<BR />';
		} else {
			$downloadMP4File = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480W.mp4';
			$fileCode = checkFileExists($downloadMP4File);
			if ($fileCode) {
				$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND (meta_key = 'mp4_link' OR meta_key = 'widescreen');";
				$rDB = $wpdb->get_results($updateSQL);
				// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480w.mp4 download.<BR />';
			}
			else {
				$downloadMP4File = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-320.mp4';
				$fileCode = checkFileExists($downloadMP4File);
				if ($fileCode) {
					$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'mp4_link';";
					$rDB = $wpdb->get_results($updateSQL);
					// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480w.mp4 download.<BR />';
				}				
			}
		}

		$downloadWMVFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480.wmv';
		$fileCode = checkFileExists($downloadWMVFile);
		if ($fileCode) {
			$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'wmv_vodcast_link';";
			$rDB = $wpdb->get_results($updateSQL);
			// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480w.wmv download.<BR />';
		} else {
			$downloadWMVFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480W.wmv';
			$fileCode = checkFileExists($downloadWMVFile);
			if ($fileCode) {
				$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND (meta_key = 'wmv_vodcast_link' OR meta_key = 'widescreen');";
				$rDB = $wpdb->get_results($updateSQL);
				// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480.wmv download.<BR />';
			}
			else {
				$downloadWMVFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-320.wmv';
				$fileCode = checkFileExists($downloadWMVFile);
				if ($fileCode) {
					$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'wmv_vodcast_link';";
					$rDB = $wpdb->get_results($updateSQL);
					// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-480.wmv download.<BR />';
				}				
			}
		}	


		$downloadWMVHDFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-640.wmv';
		$fileCode = checkFileExists($downloadWMVHDFile);
		if ($fileCode) {
			$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'wmv_hi_res_link';";
			$rDB = $wpdb->get_results($updateSQL);
			// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-854w.wmv download.<BR />';
		} else {
			$downloadWMVHDFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-854W.wmv';
			$fileCode = checkFileExists($downloadWMVHDFile);
			if ($fileCode) {
				$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND (meta_key = 'wmv_hi_res_link' OR meta_key = 'widescreen');";
				$rDB = $wpdb->get_results($updateSQL);
				// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'-640.wmv download.<BR />';
			}		
		}

		$downloadISOFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'.iso';
		$fileCode = checkFileExists($downloadISOFile);
		if ($fileCode) {
			$updateSQL = "UPDATE gci2017.wp_gci_postmeta SET meta_value = 1 WHERE post_id = $postID AND meta_key = 'iso_dvd_image_link';";
			$rDB = $wpdb->get_results($updateSQL);
			// echo $postID . ' has http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'.iso download.<BR />';
		}
		
		// $playVideoFile1 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $vidID . '-480.mp4';
		// $playVideoFile2 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $vidID . '-320.mp4';
		// $playVideoFile3 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $vidID . '-480.flv';
		// $playVideoFile4 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $vidID . '-320.flv';			
	}
	
	sleep(5);
}


?>