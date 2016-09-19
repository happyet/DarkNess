<?php get_header(); ?>
<?php get_template_part( 'includes/breadcrumbs');?>
<div id="main-wrap">
	<div class="container">
		<div class="row">
			<div class="attachment-panel clearfix">
				<div class="col-md-8 col-md-offset-2 clearfix">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="content">
							<?php lmsim_post_meta(); ?>
							<?php the_tags('<div class="post-tags pull-right"><span class="btn btn-default">','</span><span class="btn btn-default">','</span></div>'); ?>

							<?php if(stripos($post->post_mime_type,'image')==0) { ?>
								<div id="attachment_<?php echo $post->ID; ?>" class="wp-caption">
									<a href="<?php echo $post->guid; ?>" class="prettyPhoto_gall" title="<?php echo $post->post_title; ?>" rel="prettyPhoto[gallery1]">
										<img src="<?php echo $post->guid; ?>" class="aligncenter" alt="<?php echo $post->post_title; ?>">
									</a>
									<p class="wp-caption-text"><?php echo $post->post_title; ?></p>
								</div>
							<?php } ?>

							<div class="sg-act">
								<?php get_template_part('files/like_collect'); ?>
								<?php get_template_part('files/bdshare'); ?>
							</div>
							<?php get_template_part('files/author-info'); ?>
						</div>
						<?php if (comments_open()) comments_template( '', true ); ?>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>