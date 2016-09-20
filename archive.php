<?php get_header();?>
<main class="main-content" id="content">
	<div class="post-archives">
		<?php if (have_posts()):
			echo '<div class="taxonomy">';
			the_archive_title('<h2 class="headline">','</h2>');
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
			echo '</div>';
			while (have_posts()): the_post();
				get_template_part('arch/archive', get_post_format());
			endwhile;
		endif;?>
	</div>
	<?php
		the_posts_pagination( array(
      'prev_text'          => __( 'Previous page', 'twentysixteen' ),
      'next_text'          => __( 'Next page', 'twentysixteen' ),
      'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>'
     ) );
	?>
</main>
<?php get_footer();?>