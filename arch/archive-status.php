<article class="hentry-archive format-status translucent">
	<div class="archive-main">
	 	<div class="archive-content" itemprop="about">
			<?php	
				$content = get_the_content();
				$content = wp_trim_words($content,320);
				echo '<p><a href="' . get_permalink() . '">' . $content . '</a></p>';
			?>
	 	</div>
	 	<div class="archive-meta">
				<?php echo get_bluefly_posted_on(); ?>
		</div>
	</div>
</article>