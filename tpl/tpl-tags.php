<?php
/*
Template Name: 标签云集
*/
	get_header();
?>
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
                    <div class="page-tags">
											<?php wp_tag_cloud('order=DESC&orderby=count&smallest=12&largest=12&unit=px&number=5000');?>
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
                get_template_part( 'ad/ad3' );
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		<?php endwhile; ?>
    </main>
<?php get_footer();?>