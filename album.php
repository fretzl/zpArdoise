<?php include ('header.php'); ?>

		<div id="headline" class="clearfix">
			<h3><?php printHomeLink('', ' &raquo; '); ?>
			<?php if (gettext(getOption('zenpage_homepage')) == gettext('none')) { ?>
				<a href="<?php echo htmlspecialchars(getGalleryIndexURL()); ?>" title="<?php echo gettext('Albums Index'); ?>"><?php echo getGalleryTitle(); ?></a>
			<?php } else { ?>
				<?php printCustomPageURL(getGalleryTitle(), 'gallery'); ?>
			<?php } ?>
			<?php printParentBreadcrumb(' &raquo; ', ' &raquo; ', ' &raquo; '); ?><?php printAlbumTitle(true); ?></h3>

			<div class="headline-text"><?php printAlbumDesc(true); ?></div>
		</div>

		<?php if ((function_exists('printSlideShowLink')) && (!getOption('use_galleriffic'))) { ?>
		<div class="control-nav">
			<div class="control-slide">
				<?php printSlideShowLink(gettext('Slideshow')); ?>
			</div>
		</div>
		<?php } ?>

		<?php if (!getOption('use_galleriffic')) { ?>
		<div class="pagination-nogal clearfix">
			<?php printPageListWithNav(' &laquo; ', ' &raquo; ', false, true, 'clearfix', NULL, true, 7); ?>
		</div>
		<?php } ?>

		<?php if (isAlbumPage()) { ?>
			<?php include('print_album_thumb.php'); ?>
		<?php } ?>

		<?php if (getNumImages() > 0) { ?>
			<?php if (getOption('use_galleriffic')) { ?>

			<div id="galleriffic-wrap" class="clearfix">
				<div id="gallery" class="content">
					<div id="controls" class="controls"></div>
					<div class="slideshow-container">
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<div id="caption" class="caption-container"></div>
				</div>
				<div id="thumbs" class="navigation">
					<ul class="thumbs">
						<?php while (next_image(true)): ?>
						<li>
							<?php if (isImageVideo()) { ?>
								<a class="thumb" href="<?php echo $_zp_themeroot; ?>/images/video-placeholder.jpg" title="<?php echo getBareImageTitle(); ?>">
							<?php } else { ?>
								<a class="thumb" href="<?php echo getDefaultSizedImage(); ?>" title="<?php echo getBareImageTitle(); ?>">
							<?php } ?>
							<?php printImageThumb(getAnnotatedImageTitle()); ?></a>
							<a <?php if(getOption('use_colorbox_album')) { ?>rel="zoom"<?php } ?> href="<?php echo htmlspecialchars(getUnprotectedImageURL()); ?>" title="<?php echo getBareImageTitle(); ?>"></a>
							<div class="caption">
								<div class="detail-download">
									<?php if(!getOption('use_colorbox_album')) { ?>
										<a href="<?php echo htmlspecialchars(getImageLinkURL()); ?>" title="<?php echo gettext('Image'); ?> : <?php echo getImageTitle(); ?>"><?php echo gettext('Image'); ?></a>
									<?php } ?>
								</div>
								<div class="image-title"><?php printImageTitle(false); ?></div>
							</div>
						</li>
						<?php endwhile; ?>
					</ul>
				</div>
			</div>
			<!-- If javascript is disabled in the users browser, the following version of the album page will display  -->
			<noscript>

				<?php include('print_image_thumb.php'); ?>

				<div class="pagination-nogal clearfix">
					<?php printPageListWithNav(' &laquo; ', ' &raquo; ', false, true, 'clearfix', NULL, true, 7); ?>
				</div>

			</noscript>
			<!-- End of noscript display -->

			<?php } else { ?>

				<?php include('print_image_thumb.php'); ?>

				<div class="pagination-nogal clearfix">
					<?php printPageListWithNav(' &laquo; ', ' &raquo; ', false, true, 'clearfix', NULL, true, 7); ?>
				</div>

			<?php } ?>
		<?php } ?>

		<?php if (getOption('show_tag') == true) { ?>
			<div class="headline-tags"><?php printTags('links', '', 'hor-list'); ?></div>
		<?php } ?>

		<?php if (function_exists('printGoogleMap')) { ?>
			<div class="googlemap"><?php printGoogleMap(NULL, 'googlemap'); ?></div>
		<?php } ?>

		<?php include('print_comment.php'); ?>

<?php include('footer.php'); ?>