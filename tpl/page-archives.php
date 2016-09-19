<?php
/*
Template Name: 文章归档
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
				<?php 
					$num_posts1 = wp_count_posts( 'post' );
					$num_posts2 = wp_count_posts( 'theme' );
					$post_num = $num_posts1->publish;
					$theme_num = $num_posts2->publish;
					$num_posts = ($post_num + $theme_num);
					echo '<h4 class="headline"><span>总共 ' . number_format_i18n( $num_posts ) . ' 篇文章</span></h4>';
					$args = array(
						'post_type' => array('post', 'theme') , //如果你有多个 post type，可以这样 array('post', 'product', 'news')  
						'posts_per_page' => -1, //全部 posts
						'ignore_sticky_posts' => 1 //忽略 sticky posts
					);
					$the_query = new WP_Query( $args );
					$posts_rebuild = array();
					while ( $the_query->have_posts() ) : $the_query->the_post();
						$post_year = get_the_time('Y');
						$post_mon = get_the_time('m');
						$posts_rebuild[$post_year][$post_mon][] = '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a> <em>('. get_comments_number('0', '1', '%') .')</em></li>';
					endwhile;
					wp_reset_postdata();
					$output = '';
					foreach ($posts_rebuild as $key => $value) {
						$output .= '<div class="archive-box"><h3>' . $key . '</h3><dl class="dl-horizontal">';
						$year = $key;
						foreach ($value as $key_m => $value_m) {
							$output .= '<dt class="archive-month"><a href="' . get_month_link( $year, $key_m ) . '">' . $key_m . ' 月</a> <span>+</span></dt><dd><ol class="fancy-ul">';
							foreach ($value_m as $key => $value_d) {
								$output .=  $value_d;
							}
							$output .= '</ol></dd>';
						}
						$output .='</dl></div>';
					}
					echo $output; 
				?>
		</div>
    <?php wp_link_pages( array(
	    'before'      => '<div class="page-links text-center comment-navigation">',
      'after'       => '</div>',
      'link_before' => '<span class="page-link-item">',
      'link_after'  => '</span>',
      'pagelink'    => '%',
      'separator'   => '<span class="screen-reader-text">, </span>',
		) );?>
		<?php
			if ( comments_open() || get_comments_number() ) :
					comments_template();
			endif;
		?>
	<?php endwhile; ?>
</main>
<?php get_footer();?>
