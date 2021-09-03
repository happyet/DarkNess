<?php
/*
Template Name: 随机文章
*/
?>
<?php $rand_post=get_posts('numberposts=1&orderby=rand'); foreach($rand_post as $post) : ?>
<script> location="<?php the_permalink(); ?>";</script>
<?php endforeach; ?>