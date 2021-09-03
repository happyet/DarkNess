jQuery(document).ready(function($) {
	$(".hentry-content pre").addClass("prettyprint linenums");
	$('li.menu-item-has-children').on('hover',function(){
			$(this).toggleClass("on");
	});
	$('.toggle-menu').click(function(){
		if($(this).hasClass('on')){
			$(this).removeClass('on');
			$(this).parent().next().removeClass('on');
		}else{
			$(this).addClass('on');
			$(this).parent().next().addClass('on');
		}
	});
	$('.search-icon a').click(function(){
		$(this).toggleClass('on');
		$('.header-searchform').toggle(300);
		$('.search-field').focus();
		return false;
	});

	$('.like').click(function(){
		var _this = $(this), 
		like_id = _this.data('id'),
		like_txt = _this.children('.like-txt');
		ajax_data = {
			action: 'wpl_callback',
			id: like_id
		}
		if(_this.hasClass('liked')){
			alert('You are Already Liked!')
		}else{
			_this.addClass('liked')
			$.ajax({
				url: lmsim.ajax_url,
				type: "POST",
				data: ajax_data,
				dataType: "json",
				success:function(data){
					if(data.code == 200){
						like_txt.html(data.data);
					}else{
						alert(data.error);
					}
				}
			});
			return false;
		}
	});
	$('.archive-month').each(function(){
    	var num=$(this).next().find('li').size();
    	var text=$(this).find('a').text();
    	$(this).find('a').html(text+'<em> ( '+num+' 篇文章 )</em>');
  	});
	$('.archive-month span').on('click',function(){
		var dd = $(this).parent().next();
		if(dd.css('display') == 'none'){
			dd.show();
			$(this).text(' - ');
		}else{
			dd.hide();
			$(this).text(' + ');
		}
	});
	
	$('.search-trigger').on('hover', function(){
			$('.search-field').focus();
	});	

	$('.at').each(function(){
		$(this).click(function(){
			link = $(this).attr("href");
			cid = link.substring(link.indexOf("#"),link.length);
			$('html,body').animate({scrollTop:$(cid).offset().top - 80}, 500);return false;
		});
	});

	$(window).scroll(function() {
		var st = $(this).scrollTop(),
		backToTop = $('.back-to-top span');
		if (st > 250) {
			$('.site-header').addClass('fixed');
			backToTop.show(100);
		} else {
			$('.site-header').removeClass('fixed');
			backToTop.hide(100);
		}
	});
	$('.back-to-top span').on('click',function(){
		$("html,body").animate({
			scrollTop: 0
		},
		800);
	});

	var __cancel = $('#cancel-comment-reply-link'),
	__cancel_text = __cancel.text(),
	__list = 'commentlist';//your comment wrapprer
	$(document).on("submit", "#commentform", function() {
		$.ajax({
			url: lmsim.ajax_url,
			data: $(this).serialize() + "&action=ajax_comment",
			type: $(this).attr('method'),
			beforeSend: faAjax.createButterbar("提交中...."),
			error: function(request) {
				var t = faAjax;
				t.createButterbar(request.responseText);
			},
			success: function(data) {
				$('textarea').each(function() {
					this.value = ''
				});
				var t = faAjax,
					cancel = t.I('cancel-comment-reply-link'),
					temp = t.I('wp-temp-form-div'),
					respond = t.I(t.respondId),
					post = t.I('comment_post_ID').value,
					parent = t.I('comment_parent').value;
				if (parent != '0') {
					$('#respond').before('<ol class="children">' + data + '</ol>');
				} else if (!$('.' + __list ).length) {
					if (ajaxcomment.formpostion == 'bottom') {
						$('#respond').before('<ol class="' + __list + '">' + data + '</ol>');
					} else {
						$('#respond').after('<ol class="' + __list + '">' + data + '</ol>');
					}

				} else {
					if (ajaxcomment.order == 'asc') {
						$('.' + __list ).append(data); // your comments wrapper
					} else {
						$('.' + __list ).prepend(data); // your comments wrapper
					}
				}
				t.createButterbar("提交成功");
				cancel.style.display = 'none';
				cancel.onclick = null;
				t.I('comment_parent').value = '0';
				if (temp && respond) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp)
				}
			}
		});
		return false;
	});
	faAjax = {
		I: function(e) {
			return document.getElementById(e);
		},
		clearButterbar: function(e) {
			if ($(".butterBar").length > 0) {
				$(".butterBar").remove();
			}
		},
		createButterbar: function(message) {
			var t = this;
			t.clearButterbar();
			$("body").append('<div class="butterBar butterBar--center"><p class="butterBar-message">' + message + '</p></div>');
			setTimeout("jQuery('.butterBar').remove()", 3000);
		}
	};
});