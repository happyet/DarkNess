<?php
if ( post_password_required() )
    return;
?>
<div id="comments" class="comment-area translucent">
	<?php 
		if(comments_open()) :
			global $aria_req;
			$commenter = wp_get_current_commenter();
			$fields =  array(
			  'author' => '<div class="comment-inputs"><div><input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' placeholder="昵称' . ( $req ? '*' : '' ) .'" /></div>',
			  'email' => '<div><input id="email" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' placeholder="邮箱' . ( $req ? '*' : '' ) . '" /></div>',
			  'url' => '<div><input id="url" class="form-control" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="网址" /></div></div>',
			);
			comment_form(array(
				'title_reply' => '<span>发表评论</span>',
				'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title headline">',
				'comment_notes_before' => '',
				'comment_notes_after'=>'',
				'comment_field' =>  '<textarea id="comment" name="comment" class="form-control" rows="3" aria-required="true"></textarea>',
				'fields' => apply_filters( 'comment_form_default_fields', $fields ),
				'class_submit' => 'btn btn-info',				
			));
		endif;
	?>
	<?php if(have_comments()): ?>
		<meta content="UserComments:<?php echo number_format_i18n( get_comments_number() );?>" itemprop="interactionCount">
		<h3 class="comments-title headline"><span><em class="commentCount"><?php echo number_format_i18n( get_comments_number() );?></em> 条评论</span></h3>
		<ol class="commentlist">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
					'callback' => 'lmsim_comment',
					'end-callback' => 'mytheme_end_comment'
				) );
			?>
		</ol>
		<div class="posts-nav comment-nav"><?php paginate_comments_links('prev_text=«&next_text=»'); ?></div>
	<?php endif; ?>
</div>