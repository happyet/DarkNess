<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<aside id="secondary" class="sidebar widget-area" role="complementary">
		<div class="sidebar-innder">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	</aside>
<?php endif; ?>
<footer class="site-footer">
	<div class="copy">
			<p>&copy; <a href="<?php echo home_url();?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>"><?php bloginfo( 'name' ); ?></a> | Theme by <a href="http://lms.im" target="_blank" title="自娱自乐，不亦乐乎！">LMS</a></p>
		</div>
</footer>
<div class="fixed-items">
	<div class="back-to-top"><span>▲</span></div>
</div>
</div>
<?php wp_footer();?>
</body>
</html>