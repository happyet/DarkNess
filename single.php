<?php
	get_header();
	$post_id = get_the_ID();
?>
 	<main class="main-content" id="content">
 		<?php while ( have_posts() ) : the_post(); ?>
			<article class="hentry translucent">

	    	<div class="hentry-title">
					<h2 itemprop="headline">
						<?php the_title();?>
					</h2>
					<div class="archive-meta">
						<?php echo get_bluefly_posted_on(); ?>
					</div>
				</div>

			  <div class="hentry-content entry">
			   	<?php if(has_post_thumbnail()):?>
			   		<div class="has-thumb"><?php the_post_thumbnail( 'full' ); ?></div>
					<?php endif;?>
					<?php the_content(); ?>
					<?php wp_link_pages( array(
           			'before'      => '<div class="page-links text-center comment-navigation">',
           			'after'       => '</div>',
           			'link_before' => '<span class="page-link-item">',
           			'link_after'  => '</span>',
           			'pagelink'    => '%',
           			'separator'   => '<span class="screen-reader-text">, </span>',
					) );?>
			  </div>

			  <div class="hentry-footer">
						<div class="post-like">
							<?php
								$like_num = get_post_meta($post_id,'_post_like',true);
								if(empty($like_num)) $like_num = 0;
								$add_class = '';
								if(isset($_COOKIE['_post_like_' . $post_id])) $add_class = ' liked';
							?>
							<span class="like<?php echo $add_class; ?>" data-id = "<?php echo $post_id; ?>">+<i class="like-txt"><?php echo $like_num; ?></i> 人喜欢</span>
						</div>

					
					<div class="post-copyleft">
						<p>除非文中标明，本博客内容均为原创，转载请注明来自 <?php echo get_bloginfo( 'name', 'display' ); ?>。</p>
					</div>
				</div>
				<div class="related-posts">
					<?php	the_tags('<p class="post-tags" itemprop="keywords">与 </span>', ' ', ' 相关的文章有：</p>'); ?>	
					<ul>
						<?php lmsim_posts_related(); ?>
					</ul>
				</div>
			</article>
      
			<?php
				the_post_navigation( array(
					'next_text' => '<span class="post-title">%title</span><span class="meta-nav">下一篇</span>',
					'prev_text' => '<span class="post-title">%title</span><span class="meta-nav">上一篇</span> ',
				) );

				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		<?php endwhile; ?>
    </main>
<?php get_footer();?>