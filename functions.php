<?php
require get_template_directory() . '/classic-smilies.php';
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'pre_post_update', 'wp_save_post_revision' );
function hide_admin_bar($flag) {
	return false;
}
add_filter( 'show_admin_bar','hide_admin_bar'); 
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
add_filter( 'use_default_gallery_style', '__return_false' );
function lmsim_setup() {
 	register_nav_menu( 'primary-menu', '页面菜单' );
   	register_nav_menu( 'mobile-menu', '手机菜单' );
   	add_theme_support( 'post-thumbnails' );
   	add_theme_support( 'html5', array(
 		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
  	) );
   	add_theme_support( 'post-formats', array(
    	'image', 'quote', 'link', 'status'
    ) );
}
add_action( 'after_setup_theme', 'lmsim_setup' );
function lmsim_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( '底部小工具' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'lmsim_widgets_init' );
/* 阻止站内文章Pingback
 * --------------------- */ 
function lmsim_noself_ping( &$links ) {
  $home = get_option( 'home' );
  foreach ( $links as $l => $link )
  if ( 0 === strpos( $link, $home ) )
  unset($links[$l]);
}
add_action('pre_ping','lmsim_noself_ping');

/* 搜索结果排除所有页面
 * --------------------- */
function search_filter_page($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts','search_filter_page');

function lmsim_load_static_files(){
	$static_dir = get_template_directory_uri() . '/static';
	wp_enqueue_style('lmsim-style', $static_dir . '/css/main.css' , array(), '20160917' , 'screen');
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    wp_enqueue_script( 'lmsim', $static_dir . '/js/main.js' , array( 'jquery' ), '20151122', true );
    wp_localize_script( 'lmsim', 'lmsim', array(
       	'ajax_url'   => admin_url('admin-ajax.php'),
        'order' => get_option('comment_order'),
        'formpostion' => 'top',
    ) );
}
add_action( 'wp_enqueue_scripts', 'lmsim_load_static_files' );

function twentythirteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentythirteen' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentythirteen_wp_title', 10, 2 );

function my_more_link($link){
	$link = preg_replace('/#more-\d+/i', '', $link);
	return $link;
}
add_filter('the_content_more_link','my_more_link');
function remove_open_sans() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );
/**替换Gravatar头像为Cravatar头像**/
function get_cravatar_url( $url ) {
    $sources = array(
        'www.gravatar.com',
        '0.gravatar.com',
        '1.gravatar.com',
        '2.gravatar.com',
        'secure.gravatar.com',
        'cn.gravatar.com'
    );
    return str_replace( $sources, 'cravatar.cn', $url );
}
add_filter( 'get_avatar_url', 'get_cravatar_url', 1 );

/* 评论@父级评论
 * --------------- */
function at_comment_parent($comment_text){
    global $comment;
    if($comment){
        $return = '';
            if($comment->comment_parent > 0){
                $return .= '<a class="at" href="'.htmlspecialchars( get_comment_link( $comment->comment_parent ) ).'">@'.get_comment_author($comment->comment_parent).'</a>';
            }

        $return .= $comment_text;
    }else{
        $return = $comment_text;
    }
    return $return;
}
add_filter('comment_text','at_comment_parent');

function lmsim_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount, $post_id, $comment_depth, $page, $wpdb;
	
	switch ($comment->comment_type) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		• <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)' ), '<span class="edit-link">', '</span>' ); ?>
	<?php
		break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>" itemtype="http://schema.org/Comment" itemscope itemprop="comment">
		<div id="comment-<?php comment_ID(); ?>" class="comment-holder">
			<div class="alignleft">
				<?php if( $comment->comment_parent > 0) { echo get_avatar( $comment->comment_author_email, 36 );}else{ echo get_avatar( $comment->comment_author_email, 64 );} ?>
			</div>
			<div class="comment-body">
				<?php if( $comment->comment_parent > 0) { ?>
					<div class="comment-meta">
						<span class="comment-author" itemprop="author"><?php echo get_comment_author_link(); ?></span>
						<span><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()) ?></span>
						<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
						<span><?php edit_comment_link(__('(Edit)'),'  ','') ?></span>
						<span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="fa fa-reply"></i> 回复', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
					</div>
				<?php }else{ ?>
					<div class="comment-meta">
                        <h4>
						  <span class="comment-author" itemprop="author"><?php echo get_comment_author_link(); ?></span> 
					   </h4>
						<span><?php echo get_comment_date() . ' ' . get_comment_time(); ?></span>
						<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
						<span><?php edit_comment_link(__('(Edit)'),'  ','') ?></span>
					</div>
				<?php } ?>
				<div class="comment-main" itemprop="description">
					<?php comment_text() ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php _e('Your comment is awaiting moderation.') ?></em>
					<?php endif; ?>
				</div>
				<?php if( $comment->comment_parent == 0) { ?>
					<p><span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => ' 回复', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span></p>
				<?php } ?>
			</div>
		</div>
<?php
		break;
	endswitch;
}
function mytheme_end_comment() {
	echo '</li>';
}
function fa_ajax_comment_callback(){
    $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
    if ( is_wp_error( $comment ) ) {
        $data = $comment->get_error_data();
        if ( ! empty( $data ) ) {
            fa_ajax_comment_err($comment->get_error_message());
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>" itemtype="http://schema.org/Comment" itemscope itemprop="comment">
		<div id="comment-<?php comment_ID(); ?>" class="comment-holder">
			<div class="alignleft">
				<?php echo get_avatar( $comment->comment_author_email, 36 ); ?>
			</div>
			<div class="comment-body">
				<div class="comment-meta">
					<span class="comment-author" itemprop="author"><?php echo get_comment_author_link(); ?></span>
					<span><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()); ?></span>
					<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
				</div>
				<div class="comment-main" itemprop="description">
					<?php comment_text() ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php _e('Your comment is awaiting moderation.') ?></em>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</li>
    <?php die();
}
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');
function fa_ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

function lmsim_comment_nav() {
    // Are there comments to navigate through?
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
    <nav class="navigation comment-navigation text-center clearfix" role="navigation">
        <div class="nav-links">
            <?php
                if ( $prev_link = get_previous_comments_link(  '上一页' ) ) :
                    printf( '<div class="nav-previous alignleft">%s</div>', $prev_link );
                endif;

                if ( $next_link = get_next_comments_link( '下一页' ) ) :
                    printf( '<div class="nav-next alignright">%s</div>', $next_link );
                endif;
            ?>
        </div><!-- .nav-links -->
    </nav><!-- .comment-navigation -->
    <?php
    endif;
}
/* 时间显示方式xx以前
 * -------------------- */
function time_ago( $type = 'commennt', $day = 7 ) {
  $d = $type == 'post' ? 'get_post_time' : 'get_comment_time';
  if (time() - $d('U') > 60*60*24*$day) return;
  echo human_time_diff($d('U'), strtotime(current_time('mysql', 0))), '前';
}

function timeago( $ptime ) {
    date_default_timezone_set ('ETC/GMT');
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $ptime).')',
        30 * 24 * 60 * 60       =>  '个月前 ('.date('m-d', $ptime).')',
        7 * 24 * 60 * 60        =>  '周前 ('.date('m-d', $ptime).')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
/**
 * 作用: 显示日期
 */
function get_bluefly_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ) . '</time>';
	$num_comments = get_comments_number();
	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = '0';
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments;
		} else {
			$comments = '1';
		}
		$post_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
	}else{
    $post_comments = '评论已关闭';
  }
  $categories_list = get_the_category_list( _x( ', ', ' ', 'lmsim' ) );
  $edit_link = get_edit_post_link();
  if($edit_link && is_singular()){
    $edit_link_span = '<span><a href="' . get_edit_post_link() . '">编辑</a></span>';
  }else{
    $edit_link_span = '';
  }
  $post_author = '';
  $post_format = get_post_format();
  if(!is_singular() && ($post_format == 'status' || $post_format == 'quote')){
    $post_author = get_avatar(get_the_author_meta( 'email' ), 30) . '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
  }
	if(is_page()){
    return '<span class="posted-on">' . $time_string . '</span>' . $edit_link_span . '<span class="comment-nums">'. $post_comments . '</span><span class="post-views">' . get_theme_views() . '</span>';
  }else{
    return $post_author . '<span class="posted-on">' . $time_string . '</span><span class="post-cat">' . $categories_list . '</span>' . $edit_link_span . '<span class="comment-nums">'. $post_comments . '</span><span class="post-views">' . get_theme_views() . '</span>';
  }
}
/* 评论回复邮件
 * -------------- */
function comment_mail_notify($comment_id) {
	$comment = get_comment($comment_id);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam')) {
    $admin_email = get_option('admin_email');
    $wp_email = $admin_email; //e-mail 發出點, no-reply 可改為可用的 e-mail.
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '您在 [' . get_option("blogname") . '] 的留言被围观';
    $message = '
    <div style="color: #111; padding: 0 15px;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您在《' . get_the_title($comment->comment_post_ID) . '》的留言:</p>
      <p style="background-color: #eef2fa; border: 1px solid #d8e3e8; color: #111; padding:15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">'. trim(get_comment($parent_id)->comment_content) . '</p>
      <p>被 ' . trim($comment->comment_author) . ' 围观:</p>
      <p style="background-color: #eef2fa; border: 1px solid #d8e3e8; color: #111; padding:15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">' . trim($comment->comment_content) . '<br /></p>
      <p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id, array('type' => 'comment'))) . '">这里查看围观內容。</a></p>
      <p><a href="' . get_option('home') . '">' . get_option('blogname') . '</a> 欢迎您的再度光临！</p>
      <p>(此邮件由系统自动发出, 请勿回复.)</p>
    </div>';
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }	
}
//add_action('comment_post', 'comment_mail_notify');
add_action('wp_insert_comment', 'comment_mail_notify' , 99, 2 );

add_action('wp_ajax_nopriv_wpl_callback', 'wpl_callback');
add_action('wp_ajax_wpl_callback', 'wpl_callback');
function wpl_callback(){
    $id = $_POST["id"];
    $like_num = get_post_meta($id,'_post_like',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
    setcookie('_post_like_'.$id,$id,$expire,'/',$domain,false);
    if (!$like_num || !is_numeric($like_num)) {
        update_post_meta($id, '_post_like', 1);
    }else{
        update_post_meta($id, '_post_like', ($like_num + 1));
    }
    $like_num = get_post_meta($id,'_post_like',true);
    echo json_encode(array('code'=>200,'data'=>$like_num));
    die;
}

//views备胎
function happyet_record_views() {
    if (is_singular()) {
        global $post, $user_ID;
        $post_ID = $post->ID;
        if (empty($_COOKIE[USER_COOKIE]) && intval($user_ID) == 0) {
            if ($post_ID) {
                $post_views = (int)get_post_meta($post_ID, 'views', true);
                if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
                    add_post_meta($post_ID, 'views', 1, true);
                }
            }
        }
    }
}
add_action('wp_head', 'happyet_record_views');
function post_views($echo = true, $before = '', $after = '') {
    global $post;
    $post_ID = $post->ID;
    $views = number_format((int)get_post_meta($post_ID, 'views', true));
    if ($echo) {
        echo $before, $views , $after;
    } else {
        return $views;
    }
}
function get_theme_views(){
    if(function_exists('the_views')) { 
        return the_views(false); 
    }else{ 
        return post_views(false);
    }
}

function lmsim_thumbnail(){
    if ( has_post_thumbnail() ){
        $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
        $imgsrc = $large_image_url[0];
        return $imgsrc;
    }
}
/* 抓取第一张图片或自定义外链图片 -------------------- */
function catch_first_image(){
    global $post, $posts;
    $first_img = get_post_meta($post->ID, 'pre_image', true);
    if(!$first_img){
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        $first_img = isset($matches [1] [0]) ? $matches [1] [0] : '';
    }
    return $first_img;
}

/* 缩略图来源 ------------ */
function lmsim_thumb_source(){
    global $post;
    $thumb_url = lmsim_thumbnail();
    $first_url = catch_first_image();
    if($thumb_url){
        $img_url = $thumb_url;
    }elseif($first_url){
        $img_url = $first_url;
    }else{
        $img_url = '';
    }
    return $img_url;
}

function my_theme_thumb($type='full',$w='375',$h='250',$re=''){
    
    $img_src = lmsim_thumb_source();
    if($img_src) {
      $imgtype = substr($img_src,strrpos($img_src,'.'));

      if( $imgtype=='.gif' || $type=='full' ){
          $img_src = $img_src;
      }else{
          $img_src = $img_src . '?imageView2/1/w/'.$w.'/h/'.$h.'/q/100';
      }

      
      if($re){
        return $img_src;
      }else{
        $theme_thumb = '<a href="' . get_permalink() . '" title="' . get_the_title() . '"><img src="' . $img_src . '" data-original="' . $img_src . '" alt="' . get_the_title() . '" /></a>';
        echo $theme_thumb;
      }
    }
}

function lmsim_posts_related($limit = 4){
    global $post;
    $exclude_id = $post->ID;
    $post_date = $post->post_date;
    $month = gmdate("M",strtotime($post_date));
    $date = gmdate("D",strtotime($post_date));
    $posttags = get_the_tags();
    $i = 0;
    if ($posttags) {
        $tags = "";

        foreach ($posttags as $tag ) {
            $tags .= $tag->name . ",";
        }

        $args = array("post_status" => "publish", "tag_slug__in" => explode(",", $tags), "post__not_in" => explode(",", $exclude_id), "ignore_sticky_posts" => 1, "orderby" => "comment_date", "posts_per_page" => $limit);
        query_posts($args);
            
        while (have_posts()) {
            the_post();
            echo '<li>';
                echo '<a href="' . get_permalink() . '" rel="bookmark" style="background-image:url(' . my_theme_thumb(0,375,250,1) . ')"><span>' . $post->post_title . '</span></a>';
            echo '</li>';
            $exclude_id .= "," . $post->ID;
            $i++;
        }
        wp_reset_query();
    }

    if ($i < $limit) {
        $cats = "";

        foreach (get_the_category() as $cat ) {
            $cats .= $cat->cat_ID . ",";
        }

        $args = array("category__in" => explode(",", $cats), "post__not_in" => explode(",", $exclude_id), "ignore_sticky_posts" => 1, "orderby" => "comment_date", "posts_per_page" => $limit - $i);
        query_posts($args);

        while (have_posts()) {
            the_post();
            echo '<li>';
                echo '<a href="' . get_permalink() . '" rel="bookmark" style="background-image:url(' . my_theme_thumb(0,375,250,1) . ')"><span>' . $post->post_title . '</span></a>';
            echo '</li>';
            $i++;
        }

        wp_reset_query();
    }

    if ($i == 0) {
        return false;
    }
}