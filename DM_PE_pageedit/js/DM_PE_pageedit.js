jQuery(document).ready(function() { 
	$("#editpages tr").each(function() {
		$(this).remove();
	})
	$("#editpages").html($menu);
	
	$(".togglePrivate").live("click", function($e) {
		var dlink = $(this).attr("href");
		var img = $(this).find("img");
		$.ajax({
       type: "GET",
       dataType: "html",
       url: dlink,
       success: function(response){
       		image="";
			if (response=="P1"){
				image="../plugins/DM_PE_pageedit/images/security_off.gif";
				title="Toggle Private On";
			}
			if (response=="P0"){
				image="../plugins/DM_PE_pageedit/images/security.gif";
				title="Toggle Private Off";
			}
			
		        $(img).attr("src",image);
				$(img).attr("title",title);
				$(img).attr("alt",title);
			
	     }
	  });
		return false;
	})
	
	$(".toggleMenu").live("click", function($e) {
		var dlink = $(this).attr("href");
		var img = $(this).find("img");
		$.ajax({
       type: "GET",
       dataType: "html",
       url: dlink,
       success: function(response){
       		image="";
			if (response=="M1"){
				image="../plugins/DM_PE_pageedit/images/menu.gif";
				title="Toggle Menu Off";
			}
			if (response=="M0"){
				image="../plugins/DM_PE_pageedit/images/menu_off.gif";
				title="Toggle Menu On";
			}
			
		        $(img).attr("src",image);
				$(img).attr("title",title);
				$(img).attr("alt",title);
		
	     }
	  });
		return false;
	})
	
})