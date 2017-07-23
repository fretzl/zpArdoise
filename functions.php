<?php

// force UTF-8 Ø

/* zpArdoise_printRandomImages
	- use improvements of zenphoto 1.4.2 on printRandomImages and printImageStatistic :
		- http://www.zenphoto.org/trac/ticket/1914,
		- http://www.zenphoto.org/trac/ticket/2020,
		- http://www.zenphoto.org/trac/ticket/2028
	- implements call of colorbox (http://www.zenphoto.org/trac/ticket/1908 and http://www.zenphoto.org/trac/ticket/1909)
*/
function zpArdoise_printRandomImages($number=5, $class=NULL, $option='all', $rootAlbum='', $width=NULL, $height=NULL, $crop=NULL, $fullimagelink=false, $a_class=NULL) {
	if (is_null($crop) && is_null($width) && is_null($height)) {
		$crop = 2;
	} else {
		if (is_null($width)) $width = 85;
		if (is_null($height)) $height = 85;
		if (is_null($crop)) {
			$crop = 1;
		} else {
			$crop = (int) $crop && true;
		}
	}
	if (!empty($class)) {
		$class = ' class="' . $class . '"';
	};
	echo "<ul" . $class . ">";
	for ($i=1; $i<=$number; $i++) {
		echo "<li>\n";
		switch($option) {
			case "all":
				$randomImage = getRandomImages();
				break;
			case "album":
				$randomImage = getRandomImagesAlbum($rootAlbum);
				break;
		}
		if (is_object($randomImage) && $randomImage->exists) {
			$fullimage = getFullImageURL($randomImage);
			if(($fullimagelink) && (!empty($fullimage))) {
				$aa_class = ' class="' . $a_class . '"';
				$randomImageURL = html_encode($fullimage);
			} else {
				$aa_class = NULL;
				$randomImageURL = html_encode(getURL($randomImage));
			}
			echo '<a href="' . $randomImageURL . '"' . $aa_class . ' title="' . html_encode($randomImage->getTitle()) . '">';
			switch ($crop) {
				case 0:
					$html =  "<img src=\"" . pathurlencode($randomImage->getCustomImage($width, NULL, NULL, NULL, NULL, NULL, NULL, TRUE)) . "\" alt=\"" . html_encode($randomImage->getTitle()) . "\" />\n";
					break;
				case 1:
					$html = "<img src=\"" . pathurlencode($randomImage->getCustomImage(NULL, $width, $height, $width, $height, NULL, NULL, TRUE)) . "\" alt=\"" . html_encode($randomImage->getTitle()) . "\" />\n";
					break;
				case 2:
					$html = "<img src=\"" . pathurlencode($randomImage->getThumb()) . "\" alt=\"" . html_encode($randomImage->getTitle()) . "\" />\n";
					break;
			}
			echo zp_apply_filter('custom_image_html', $html, false);
			echo "</a>";
		}
		echo "</li>\n";
	}
	echo "</ul>";
}

/* zpArdoise_printImageStatistic
	- use improvements of zenphoto 1.4.2 on printRandomImages and printImageStatistic (http://www.zenphoto.org/trac/ticket/1914)
	- implements call of colorbox (http://www.zenphoto.org/trac/ticket/1908 and http://www.zenphoto.org/trac/ticket/1909)
*/
function zpArdoise_printImageStatistic($number, $option, $albumfolder='', $showtitle=false, $showdate=false, $showdesc=false, $desclength=40, $showstatistic='', $width=NULL, $height=NULL, $crop=NULL, $collection=false, $fullimagelink=false, $a_class=NULL) {
	$images = getImageStatistic($number, $option, $albumfolder,$collection);
	if (is_null($crop) && is_null($width) && is_null($height)) {
		$crop = 2;
	} else {
		if (is_null($width)) $width = 85;
		if (is_null($height)) $height = 85;
		if (is_null($crop)) $crop = true;
	}
	echo "\n<div id=\"$option\">\n";
	echo "<ul>";
	foreach ($images as $image) {
		$fullimage = getFullImageURL($image);
		if(($fullimagelink) && (!empty($fullimage))) {
			$aa_class = ' class="' . $a_class . '"';
			$imagelink = html_encode($fullimage);
		} else {
			$aa_class = NULL;
			$imagelink = html_encode(getURL($image));
		}
		echo "<li><a href=\"" . $imagelink ."\"" . $aa_class . " title=\"" . html_encode($image->getTitle()) . "\">\n";
		switch ($crop) {
			case 0:
				echo "<img src=\"" . pathurlencode($image->getCustomImage($width, NULL, NULL, NULL, NULL, NULL, NULL, TRUE)) . "\" alt=\"" . html_encode($image->getTitle()) . "\" /></a>\n";
				break;
			case 1:
				echo "<img src=\"" . pathurlencode($image->getCustomImage(NULL, $width, $height, $width, $height, NULL, NULL, TRUE)) . "\" alt=\"" . html_encode($image->getTitle()) . "\" /></a>\n";
				break;
			case 2:
				echo "<img src=\"" . pathurlencode($image->getThumb()) . "\" alt=\"" . html_encode($image->getTitle()) . "\" /></a>\n<br />";
				break;
		}
		if($showtitle) {
			echo "<h3><a href=\"" . html_encode($image->getImageLink()) . "\" title=\"" . html_encode($image->getTitle()) . "\">\n";
			echo $image->getTitle() . "</a></h3>\n";
		}
		if($showdate) {
			echo "<p>" . zpFormattedDate(DATE_FORMAT,strtotime($image->getDateTime())) . "</p>";
		}
		if($showstatistic === "rating" OR $showstatistic === "rating+hitcounter") {
			$votes = $image->get("total_votes");
			$value = $image->get("total_value");
			if($votes != 0) {
				$rating =  round($value/$votes, 1);
			}
			echo "<p>" . sprintf(gettext('Rating: %1$u (Votes: %2$u)'), $rating, $votes) . "</p>";
		}
		if($showstatistic === "hitcounter" OR $showstatistic === "rating+hitcounter") {
			$hitcounter = $image->get("hitcounter");
			if(empty($hitcounter)) { $hitcounter = "0"; }
			echo "<p>" . sprintf(gettext("Views: %u"), $hitcounter) . "</p>";
		}
		if($showdesc) {
			echo shortenContent($image->getDesc(), $desclength,' (...)');
		}
		echo "</li>";
	}
	echo "</ul></div>\n";
}

/* zpArdoise_printEXIF */
function zpardoise_printEXIF() {
	$Meta_data = getImageMetaData();		// put all exif data ina array
	if (!is_null($Meta_data)) {
		$Exifs_list = '';
		if (isset($Meta_data['EXIFModel'])) { $Exifs_list .= html_encode($Meta_data['EXIFModel']); };
		if (isset($Meta_data['EXIFFocalLength'])) { $Exifs_list .= ' &ndash; ' . html_encode($Meta_data['EXIFFocalLength']); };
		if (isset($Meta_data['EXIFFNumber'])) { $Exifs_list .= ' &ndash; ' . html_encode($Meta_data['EXIFFNumber']); };
		if (isset($Meta_data['EXIFExposureTime'])) {$Exifs_list .= ' &ndash; ' . html_encode($Meta_data['EXIFExposureTime']); };
		if (isset($Meta_data['EXIFISOSpeedRatings'])) {$Exifs_list .= ' &ndash; ' . html_encode($Meta_data['EXIFISOSpeedRatings']) . ' ISO'; };
		echo $Exifs_list;
	}
}
?>