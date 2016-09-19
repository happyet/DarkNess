<div class="sidebar-right">
	<div class="widget hot-widget clearfix">
		<?php 
			$post_array = array(
				'post_type'				=> array( 'post' ),
				'showposts'				=> '5',
				'ignore_sticky_posts'	=> true,
				'order'					=> 'dsc',
			);
			if(is_home()){
				$side_title = '热门文章';
				$post_array['orderby'] = 'comment_count';
			}else{
				$side_title = '最新文章';
				$post_array['orderby'] = 'date';
				$post_array['post__not_in'] = array($post->ID);
			}
			echo '<h3 class="headline"><span class="widget-title">' . $side_title . '</span></h3>';
			$popular = new WP_Query($post_array);
		 	while ( $popular->have_posts() ): $popular->the_post(); ?>
				<div class="media">
					<div class="alignleft"><?php my_theme_thumb(96,72); ?></div>
					<div class="media-body">
						<h4 class="media-heading"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
						<div class="post-meta side-meta">
							<span><?php the_time('Y-m-j'); ?></span>
							<span><?php the_category(' / '); ?></span>
						</div>
					</div>
				</div><?php
			 endwhile;
			 wp_reset_query();
		 ?>
	</div>
	<div class="ad-box">
		<?php get_template_part( 'ad/ad1' ); ?>
	</div>
	<div class="widget widget-comments clearfix">
		<h3 class="headline"><span class="widget-title">最新评论</span></h3>
		<ul class="list-unstyled">
			<?php	
				$email = get_bloginfo ('admin_email');
				$comments_args = array(
					'status'=>'approve',
					'post_status'=>'publish',
					'author__not_in' => 1,
					'number' => '10',
					//'post_type' => 'post'
				);
				$new_comments = get_comments($comments_args);
				$rc_comments = '';
				foreach ($new_comments as $rc_comment) {
					$rc_comments .= '
						<li class="media clearfix">
							<div class="alignleft">' . get_avatar( $rc_comment->comment_author_email, 46 ) . '</div>
							<div class="media-body">
								<div class="media-heading"><span>'. $rc_comment->comment_author . '</span> <time><i class="fa fa-clock-o"></i> '. date('Y-m-d H:i',strtotime($rc_comment->comment_date_gmt)) . '</time></div>
								<div class="comment-content">
									<p><a class="text-muted" title="发表在 ' . get_post( $rc_comment->comment_post_ID )->post_title  .'" href="' . htmlspecialchars(get_comment_link( $rc_comment->comment_ID )) .'">' . do_shortcode(convert_smilies(mb_substr(strip_tags($rc_comment->comment_content),0,35))) . '...</a></p>
								</div>
							</div>
						</li>
					';
				}
				echo $rc_comments;
			?>	
		</ul>
	</div>
	<div class="ad-box">
		<?php get_template_part( 'ad/ad2' ); ?>
	</div>
</div>