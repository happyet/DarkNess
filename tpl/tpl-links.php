<?php
/*
Template Name: 友情链接
*/
?>
<?php get_header();?>
<main class="main-content" id="content">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="hentry translucent">
            <header class="hentry-title">
				<h2 itemprop="headline"><?php the_title(); ?></h2>
				<div class="archive-meta small">
					<?php echo get_bluefly_posted_on(); ?>
				</div>
			</header>
            <div class="hentry-content entry">
                <?php if(has_post_thumbnail()):?>
                    <div class="has-thumb"><?php the_post_thumbnail( 'full' ); ?></div>
                <?php endif;?>
                <?php the_content();?>
				<div class="blog-rolls">
					<?php
						$link_cats = get_terms( 'link_category' );
						if($link_cats) : 
							foreach($link_cats as $linkcat) :
								echo '<h4 class="headline"><span>' . $linkcat->name . '</span></h4>';
								echo '<div class="blog-roll">';
									$bookmarks = get_bookmarks('orderby=date&category_name=' . $linkcat->name);
									if ( !empty($bookmarks) ) {
										foreach ($bookmarks as $bookmark) {
											echo '<a href="' . $bookmark->link_url . '" target="_blank" ><span>' . $bookmark->link_name . '</span></a>';
										}
									}
								echo '</div>';
							endforeach;
						endif;
					?>
				</div>
            </div>
            <?php wp_link_pages( array(
                'before'      => '<div class="page-links text-center comment-navigation">',
                'after'       => '</div>',
                'link_before' => '<span class="page-link-item">',
                'link_after'  => '</span>',
                'pagelink'    => '%',
                'separator'   => '<span class="screen-reader-text">, </span>',
			) );?>
		</div>
		<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>
	<?php endwhile; ?>
</main>
<?php get_footer();?>