var common = {
			init :function(){
				// this.loadSiteConfig()

			},
			checkInput:function(value,type){

				value = $.trim(value);
				// verify not Null
				if(type == "require"){
					return !!value;
				}
				// verify  phone number
				if(type == "phone"){
				 return /^[1][3,4,5,7,8][0-9]{9}$/.test(value);
				};
				// verify email
				if(type == "email"){
				 return /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/.test(value);
					
				};
				if(type == "name"){
					 return /^[0-9a-zA-Z\u4e00-\u9fa5_]{2,20}$/.test(value);
						
					};
		// verify password  6-20 places
				if(type == "password"){
					return /^[0-9a-zA-Z!@#$.^]{6,18}$/.test(value)

				};
			},
			
			// // 获取网站SEO配置
			// loadSiteConfig:function(){
			// 	$.ajax({
			// 		   url:'/index/getConfig',
			// 		   type:'GET',
			// 		   success:function(data){
			// 			   document.title=data.body.title;
			// 			   var html =''
			// 			   html += '<meta name="keywords" content="'+data.body.keywords+'" />';
			// 			   html += '<meta name="description" content="'+data.body.description+'" />'
			// 			   $('head').append(html)
			// 		   },
			// 		   error:function(){}
			// 	   })
			// }
		};

		$(function(){
			common.init();			
		})