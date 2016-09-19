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
	$('.archive-month span').toggle(function(){
		$(this).parent().next().show();
		$(this).text('-');
	},function(){
		$(this).parent().next().hide();
		$(this).text('+');
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
		if (st > 200) {
			backToTop.show(100);
		} else {
			backToTop.hide(100);
		}
	});
	$('.back-to-top span').on('click',function(){
		$("html,body").animate({
			scrollTop: 0
		},
		800);
	});

	var $commentform = $('#commentform'),
	$comments = $('#comments-title'),
	$cancel = $('#cancel-comment-reply-link'),
	cancel_text = $cancel.text();
	$('#commentform').submit(function() {
		$.ajax({
			url: lmsim.ajax_url,
			data: $(this).serialize() + "&action=ajax_comment",
			type: $(this).attr('method'),
			beforeSend:addComment.createButterbar("提交中...."),
			error: function(request) {
				var t = addComment;
				t.createButterbar(request.responseText);
			},
			success: function(data) {
				$('textarea').each(function() {
					this.value = ''
				});
				var t = addComment,
				cancel = t.I('cancel-comment-reply-link'),
				temp = t.I('wp-temp-form-div'),
				respond = t.I(t.respondId),
				post = t.I('comment_post_ID').value,
				parent = t.I('comment_parent').value;
				if (parent != '0') {
					$('#respond').before('<ol class="children">' + data + '</ol>');
				} else {
					$('.comment-respond').before('<ol class="commentlist">' + data + '</ol>');// your comments wrapper
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
	addComment = {
		moveForm: function(commId, parentId, respondId) {
			var t = this,
			div,
			comm = t.I(commId),
			respond = t.I(respondId),
			cancel = t.I('cancel-comment-reply-link'),
			parent = t.I('comment_parent'),
			post = t.I('comment_post_ID');
			$cancel.text(cancel_text);
			t.respondId = respondId;
			if (!t.I('wp-temp-form-div')) {
				div = document.createElement('div');
				div.id = 'wp-temp-form-div';
				div.style.display = 'none';
				respond.parentNode.insertBefore(div, respond)
			} ! comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
			$("body").animate({
				scrollTop: $('#respond').offset().top - 180
			}, 400);
			parent.value = parentId;
			cancel.style.display = '';
			cancel.onclick = function() {
				var t = addComment,
				temp = t.I('wp-temp-form-div'),
				respond = t.I(t.respondId);
				t.I('comment_parent').value = '0';
				if (temp && respond) {
					temp.parentNode.insertBefore(respond, temp);
					temp.parentNode.removeChild(temp);
				}
				this.style.display = 'none';
				this.onclick = null;
				return false;
			};
			try {
				t.I('comment').focus();
			}
			 catch(e) {}
			return false;
		},
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
			$("body").append('<div class="butterBar text-center"><span class="btn btn-default">' + message + '</span></div>');
			setTimeout("jQuery(document).ready(function($) {$('.butterBar').remove();})", 2000);
		}
	};
});