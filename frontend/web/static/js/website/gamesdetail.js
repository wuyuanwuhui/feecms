/*
 * author:silva
 * date:2018/06/07
 * 
 * */

var page = {
			data:{
				 $slider : $('.slider'),
				  videoW : 0,
				    imgW : $('.slider li img')[0].width,
				 liCount : $('.slider').find('li').length,
				   video : $("#gameVideo").length,
				MaxWidth : 0
			},
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){
				var _this = this;
				// 点击获取游戏下载链接
				$('#downloadImg').off('click').on('click',function(){
					var fileId = $('#fileId').val()
					if(fileId){
						$.ajax({
							url:'/games/downloadApp/'+fileId,
							type:'GET',
							success:function(data){
								$('#download').attr('href',data.body);
								if($('#download').attr('href')!= ''){
									$('#download span').click()
								}
								$('#downloadImg').unbind('click'); //释放click事件
								// 1分钟后释放click TODO 
								$('#downloadImg').click(function(){
									alert("请刷新后重试")
								})
							},
							error:function(err){
								(err)
							}
						});
					}else{
						alert('敬请期待')
					}
					
				});
				
				// 图片轮播按钮
				 var $slider = $('.slider'),
					  videoW = 0,
					    imgW = $('.slider li img')[0].width+5,
					 liCount = $('.slider').find('li').length,   //li 数量
					   video = $("#gameVideo").length,            //视频是否存在
					  videoW = 0,                               //视频宽度
					 sliderW = $('.slider').width(),
					MaxWidth = 0;
				var  remainderL; //余数
			
				
				// 判断是否有视频
				 if(video){
						videoW = $('.game-video').width()+5; //视频宽度，含右外边距
						MaxWidth=videoW+imgW*(liCount-1); //视频宽度+图片宽度	
				}else{ // 无视频时
					MaxWidth = imgW*liCount;//总宽度为所有图片加右外边距的宽度总和
				}
				
				remainderL = MaxWidth - sliderW;
				//如果图片总宽度小于容器宽度 隐藏按钮
				$('.control-prev').hide();
				if(MaxWidth <= sliderW){
					$('.slider-control').hide();
				}
				
			// 滚动条位置
				//
				// 监听滚动
				var scrollL = $slider[0].scrollLeft;
				$slider.scroll(function(){
					scrollL = $slider[0].scrollLeft;
					if($slider[0].scrollLeft == 0){
						$('.control-prev').hide();
						$('.control-next').show();
					}else if($slider[0].scrollLeft >= $slider[0].scrollWidth-sliderW){
						$('.control-prev').show();
						$('.control-next').hide();
					}else{
						$('.control-prev').show();
						$('.control-next').show();
					}
				})
				// 图片轮播组件点击事件
				$('a.slider-control').off('click').on('click',function(e){	
					if($(e.target).parent().data('slide')=='prev'){//左按键
						if(scrollL <videoW+5){ //左边滚动条宽度小于					
								if(video ){			
									scrollL = 0;
									$slider.animate({scrollLeft:scrollL},1000)
								}
								else if(scrollL<imgW*2){
									scrollL -= imgW
									$slider.animate({scrollLeft:scrollL},1000)
								}						
						}else{
							scrollL -= imgW;
							$slider.animate({scrollLeft:scrollL},1000)		
						}
					}else{ //右按键
						if(scrollL == 0){
							if(video){
								scrollL = videoW;
								$slider.animate({scrollLeft:scrollL},1000)	
							}else{
								scrollL = imgW;
								$slider.animate({scrollLeft:scrollL},1000)
							}
						}
						else{
							scrollL += imgW
							$slider.animate({scrollLeft:scrollL},1000)
						}
					}
				});// 游戏详情图片滚动end
				
			}
			
	}



	$(function(){
		page.init();
		window.onbeforeunload = function(e){
			var e=window.event || e;
			page=null;

		}
		
	})