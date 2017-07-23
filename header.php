<?php

// force UTF-8 �

if ( !defined('WEBPATH') ) die();
if ((getOption('use_galleriffic')) && !(($_zp_gallery_page == 'image.php') || ($_zp_gallery_page == 'search.php'))) {
	setOption('image_size', '525', false);
	setOption('image_use_side', 'longest', false);
	setOption('thumb_size', '75', false);
	setOption('thumb_crop', '1', false);
	setOption('thumb_crop_width', '75', false);
	setOption('thumb_crop_height', '75', false);
}
setOption('personnal_thumb_width', '267', false);
setOption('personnal_thumb_height', '133', false);

setOption('zenpage_zp_index_news', false, false);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
	<?php
	echo getMainSiteName();
	if ($_zp_gallery_page == 'album.php') {echo ' | ' . getBareAlbumTitle(); }
	if ($_zp_gallery_page == 'image.php') {echo ' | ' . getBareAlbumTitle() . ' | ' . getBareImageTitle(); }
	if ($_zp_gallery_page == 'contact.php') {echo ' | ' . gettext('Contact'); }
	if ($_zp_gallery_page == 'archive.php') {echo ' | ' . gettext('Archive View'); }
	if ($_zp_gallery_page == 'password.php') {echo ' | ' . gettext('Password Required...'); }
	if ($_zp_gallery_page == '404.php') {echo ' | ' . gettext('404 Not Found...'); }
	if ($_zp_gallery_page == 'search.php') {echo ' | ' . gettext('Search'); }
	if ($_zp_gallery_page == 'pages.php') {echo ' | ' . getBarePageTitle(); }
	if ($_zp_gallery_page == 'news.php') {echo ' | ' . gettext('News'); }
	if (($_zp_gallery_page == 'news.php') && (is_NewsArticle())) {echo ' | ' . getBareNewsTitle(); }
	?>
	</title>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
	<?php printRSSHeaderLink('Gallery', gettext('Latest images RSS')); ?>
	<?php if (function_exists('printZenpageRSSHeaderLink')) { ?>
		<?php printZenpageRSSHeaderLink('NewsWithImages', '', gettext('News and Gallery RSS'), ''); ?>
	<?php } ?>
	<link rel="stylesheet" href="<?php echo $_zp_themeroot; ?>/css/screen.css" type="text/css" media="screen"/>
	<?php if (getOption('color_style') != 'default') { ?>
		<link rel="stylesheet" href="<?php echo $_zp_themeroot; ?>/css/<?php echo getOption('color_style'); ?>.css" type="text/css" media="screen"/>
	<?php } ?>
	<link rel="shortcut icon" href="<?php echo $_zp_themeroot; ?>/images/favicon.ico" />
	<?php zp_apply_filter('theme_head'); ?>

	<script type="text/javascript" src="<?php echo $_zp_themeroot; ?>/js/fadeSliderToggle.js"></script>
	<script type="text/javascript" src="<?php echo $_zp_themeroot; ?>/js/jquery.opacityrollover.js"></script>
	<script type="text/javascript" src="<?php echo $_zp_themeroot; ?>/js/zpardoise.js"></script>
	<?php if (($_zp_gallery_page == 'album.php') && (getOption('use_galleriffic'))) { ?>
		<script type="text/javascript" src="<?php echo $_zp_themeroot; ?>/js/jquery.history.js"></script>
		<script type="text/javascript" src="<?php echo $_zp_themeroot; ?>/js/jquery.galleriffic.js"></script>
		<script type="text/javascript">
			//<![CDATA[

			jQuery(document).ready(function($) {

				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '300px', 'float' : 'left'});
				$('div.content').css('display', 'block');
				$('#galleriffic-wrap').css('display', 'block');

				// Initially set opacity on thumbs
				var onMouseOutOpacity = 0.75;

				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                <?php if (is_numeric(getOption('galleriffic_delai'))) { echo getOption('galleriffic_delai'); } else { echo '3000'; } ?>,
					numThumbs:            18,
					preloadAhead:         18,
					enableTopPager:       true,
					enableBottomPager:    true,
					maxPagesToShow:       4,
					imageContainerSel:    '#slideshow',
					controlsContainerSel: '#controls',
					captionContainerSel:  '#caption',
					loadingContainerSel:  '#loading',
					renderSSControls:     true,
					renderNavControls:    true,
					playLinkText:         '<?php echo gettext('Slideshow'); ?>',
					pauseLinkText:        '<?php echo gettext_th('Stop'); ?>',
					prevLinkText:         '&laquo; <?php echo gettext('prev'); ?>',
					nextLinkText:         '<?php echo gettext('next'); ?> &raquo;',
					nextPageLinkText:     '&raquo;',
					prevPageLinkText:     '&laquo;',
					enableHistory:        true,
					autoStart:            false,
					syncTransitions:      true,
					defaultTransitionDuration:600,

					onSlideChange:       function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut: function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:  function() {
						this.fadeTo('fast', 1.0);
					}
				});

				/**** Functions to support integration of galleriffic with the jquery.history plugin ****/
				// PageLoad function
				// This function is called when:
				// 1. after calling $.historyInit();
				// 2. after calling $.historyLoad();
				// 3. after pushing "Go Back" button of a browser
				function pageload(hash) {
					// alert("pageload: " + hash);
					// hash doesn't contain the first # character.
					if(hash) {
						$.galleriffic.gotoImage(hash);
					} else {
						gallery.gotoIndex(0);
					}
				}

				// Initialize history plugin.
				// The callback is called at once by present location.hash.
				$.historyInit(pageload, "advanced.html");

				// set onlick event for buttons using the jQuery 1.3 live method
				$("a[rel='history']").live('click', function(e) {
					if (e.button != 0) return true;

					var hash = this.href;
					hash = hash.replace(/^.*#/, '');

					// moves to a new page.
					// pageload is called at once.
					// hash don't contain "#", "?"
					$.historyLoad(hash);

					return false;
				});

			});
			//]]>
		</script>
	<?php } ?>

	<?php if (($_zp_gallery_page == 'image.php') || ((function_exists('is_NewsArticle')) && (is_NewsArticle()))) { ?>
	<script type="text/javascript">
		//<![CDATA[

		<?php $NextURL = $PrevURL = false; ?>
		<?php if ($_zp_gallery_page == 'image.php') { ?>
			<?php if (hasNextImage()) { ?>var nextURL = "<? echo getNextImageURL(); $NextURL = true; ?>";<?php } ?>
			<?php if (hasPrevImage()) { ?>var prevURL = "<? echo getPrevImageURL(); $PrevURL = true; ?>";<?php } ?>
		<?php } else { ?>
			<?php if ((function_exists('checkForPage')) && (is_NewsArticle())) { ?>
				<?php if (getNextNewsURL()) { $article_url = getNextNewsURL(); ?>var nextURL = "<?php echo zp_html_decode($article_url['link']); $NextURL = true; ?>";<?php } ?>
				<?php if (getPrevNewsURL()) { $article_url = getPrevNewsURL(); ?>var prevURL = "<?php echo zp_html_decode($article_url['link']); $PrevURL = true; ?>";<?php } ?>
			<?php } ?>
		<?php } ?>

		var ColorboxActive = false;				// cohabitation entre script de navigation et colorbox

		function keyboardNavigation(e){

			if (ColorboxActive) return true;	// cohabitation entre script de navigation et colorbox

			if (!e) e = window.event;
			if (e.altKey) return true;
			var target = e.target || e.srcElement;
			if (target && target.type) return true;		//an input editable element
			var keyCode = e.keyCode || e.which;
			var docElem = document.documentElement;
			switch(keyCode) {
				case 63235: case 39:
					if (e.ctrlKey || (docElem.scrollLeft == docElem.scrollWidth-docElem.clientWidth)) {
						<?php if ($NextURL) { ?>window.location.href = nextURL; <?php } ?>return false; }
					break;
				case 63234: case 37:
					if (e.ctrlKey || (docElem.scrollLeft == 0)) {
						<?php if ($PrevURL) { ?>window.location.href = prevURL; <?php } ?>return false; }
					break;
			}
			return true;
		}

		document.onkeydown = keyboardNavigation;

		// cohabitation entre script de navigation et colorbox
		$(document).bind('cbox_open', function() {ColorboxActive = true;})
		$(document).bind('cbox_closed', function() {ColorboxActive = false;});

		//]]>
	</script>
	<?php } ?>

	<?php if((getOption('use_colorbox_album')) || (getOption('use_colorbox_image'))) { ?>
	<script type="text/javascript">
		//<![CDATA[

		$(document).ready(function(){
			$("a[rel='zoom']").colorbox({slideshow:true, slideshowSpeed:3000, slideshowAuto:false, maxWidth:"98%", maxHeight:"98%", photo:true});
		});

		//]]>
	</script>
	<?php } ?>

	<!--
	<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>
	-->

</head>

<body>
<?php zp_apply_filter('theme_body_open'); ?>

<div id="page">
	<?php if (($_zp_gallery_page != 'image.php') || (getOption('show_image_logo_on_image'))){ ?>
	<div id="site-title" class="clearfix">
		<!-- banniere -->
		<a href="<?php echo htmlspecialchars(getGalleryIndexURL()); ?>" title="<?php echo getGalleryTitle(); ?>"><img id="zplogo" src="<?php echo $_zp_themeroot; ?>/images/<?php echo getOption('use_image_logo_filename'); ?>" alt="<?php echo getGalleryTitle(); ?>" /></a>
	</div>
	<?php } ?>

	<div id="main-menu">
	<?php if	(($_zp_gallery_page == 'index.php') ||
				($_zp_gallery_page == 'gallery.php') ||
				($_zp_gallery_page == 'album.php') ||
				($_zp_gallery_page == 'image.php') ||
				($_zp_gallery_page == 'search.php'))
		{ $galleryactive = 1; }
	?>

		<ul>
		<?php if (gettext(getOption('zenpage_homepage')) == gettext('none')) { ?>
			<li <?php if ($galleryactive) { ?>class="active"<?php } ?>><a href="<?php echo getGalleryIndexURL(false); ?>"><?php echo gettext('Gallery'); ?></a></li>
		<?php } else { ?>
			<?php if (function_exists('getPageTitleLink')) { ?>
				<li <?php if ( getOption('zenpage_homepage') == getPageTitleLink() ) { ?>class="active"<?php } ?>><a href="<?php echo getGalleryIndexURL(false); ?>"><?php echo gettext('Home'); ?></a></li>
			<?php } ?>
			<li <?php if ($galleryactive) { ?>class="active"<?php } ?>><?php printCustomPageURL(gettext('Gallery'), 'gallery'); ?></li>
		<?php } ?>
		<?php if ((function_exists('getNewsIndexURL')) && ((getNumNews()) > 0)) { ?>
			<li <?php if ($_zp_gallery_page == 'news.php') { ?>class="active"<?php } ?>><?php printNewsIndexURL(gettext('News')); ?></li>
		<?php } ?>
		</ul>
		<?php if (function_exists('printPageMenu')) { printPageMenu('list-top', '', 'active', 'active', '', ''); } ?>
		<ul>
		<?php if (getOption('show_archive')) { ?>
			<li <?php if ($_zp_gallery_page == 'archive.php') { ?>class="active"<?php } ?>><?php printCustomPageURL(gettext('Archive View'), 'archive'); ?></li>
		<?php } ?>
		<?php if (function_exists('printContactForm')) { ?>
			<li <?php if ($_zp_gallery_page == 'contact.php') { ?>class="active"<?php } ?>><?php printCustomPageURL(gettext('Contact'), 'contact'); ?></li>
		<?php } ?>
		</ul>

	</div>		<!-- END #MAIN-MENU -->

	<div id="container" class="clearfix">