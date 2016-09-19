<article class="hentry-archive format-quote translucent">
	<div class="archive-thumb">
		<?php my_theme_thumb($type='full'); ?>
	</div>
	<div class="archive-main">
	 	<div class="archive-title">
			<h2 itemprop="headline">
				<a href="<?php the_permalink();?>"><?php the_title();?></a>
			</h2>
		</div>
	 	<div class="archive-content" itemprop="about">
			<?php the_content(); ?>
	 	</div>
	 	<div class="archive-meta">
				<?php echo get_bluefly_posted_on(); ?>
		</div>
	</div>
</article>