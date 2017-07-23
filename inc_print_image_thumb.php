	<div id="thumbs-nogal">
		<ul class="clearfix thumbs-nogal" id="no-gal-ul">
		<?php $x = 1; while (next_image()): $lastcol = ''; ?>
			<?php if ($x == 5) { ?>
				<li class="no-gal-li-lastimg">
				<?php $x = 0; ?>
			<?php } else { ?>
				<li class="no-gal-li">
			<?php } ?>
			<?php $fullimage = getFullImageURL(); ?>
			<?php if ((getOption('use_colorbox_album')) && (!empty($fullimage))) { ?>
				<a class="thumb colorbox" href="<?php echo html_encode($fullimage); ?>" title="<?php echo getBareImageTitle(); ?>"><?php printImageThumb(getAnnotatedImageTitle()); ?></a>
			<?php } else { ?>
				<a class="thumb" href="<?php echo html_encode(getImageLinkURL()); ?>" title="<?php echo getBareImageTitle(); ?>"><?php printImageThumb(getAnnotatedImageTitle()); ?></a>
			<?php } ?>
			</li>
		<?php $x++; endwhile; ?>
		</ul>
	</div>