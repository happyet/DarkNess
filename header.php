<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,minimal-ui" />
    <title><?php wp_title( '-', true, 'right' ); ?></title>
    <?php wp_head();?>
</head>
<body <?php body_class();?>>
<div class="page-wrapper">
	<header class="site-header translucent">
		<span class="toggle-menu"><i></i></span>
		<div class="site-logo">
			<a href="<?php echo home_url();?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>"><?php echo get_avatar( get_option('admin_email') , '96'); ?></a>
		</div>
		<h1 class="site-title">
			<a href="<?php echo home_url();?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>"><?php bloginfo( 'name' ); ?></a>
		</h1>
		<?php $description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; ?></p>
		<?php endif; ?>
	</header>
	<div class="nav-area">
		<nav class="header-menu" role="navigation">
			<?php
				wp_nav_menu( array( 
					'theme_location' => 'primary-menu', 
					'container' => '', 
					'depth' => '2',
					'items_wrap' => '<ul class="primary-nav">%3$s<li class="search-icon"><a href="#">Search</a></li></ul>' 
				));
			?>
		</nav>
		<div class="header-searchform"><?php get_search_form(); ?></div>
	</div>