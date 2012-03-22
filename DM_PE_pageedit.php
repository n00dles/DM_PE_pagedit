<?php
/*
Plugin Name: Page Edit Options
Description: Adds extra options to the Pages Overview 
Version: 1.1
Author: Mike Swan
Author URI: http://www.digimute.com/
*/

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, 										# ID of plugin, should be filename minus php
	'Page Edit Options', 							# Title of plugin
	'1.4', 											# Version of plugin
	'Mike Swan',									# Author of plugin
	'http://www.digimute.com/', 					# Author URL
	'Adds extra options to the Page View Screen', 	# Plugin Description
	'plugins', 										# Page type of plugin
	''  											# Function that displays content
);

# activate hooks


$bt = debug_backtrace();
$shift=count($bt) -1;	
if (pathinfo_filename($bt[$shift]['file'])=="pages"){
	add_action('footer','DM_PE_addPageOptions',array()); 
	register_style('DM_Pageedit', $SITEURL."plugins/DM_PE_Pageedit/style.css", '1.1',  'screen',FALSE);
	queue_style('DM_Pageedit',GSBACK);
}



function DM_PE_addPageOptions() {

	$delNonce=get_nonce("delete","deletefile.php");
	$cloneNonce=get_nonce("clone","pages.php");
	
	$menu="<tr><th>Pages</th><th>Date</th><th>Options</th></tr>";
	$menu.=DM_PE_getPages('','',0);
	echo '<script type="text/javascript">
	$("#editpages tr").each(function() {
		$(this).remove();
	})
	$(".hover").hover(
	  function () {
	  	alert(1);
	  	//if (!$(this).hasClass("selected")){
	    	$(this).css("background-color","#d2e7f0");
	    	$(this).find("div.dropdown").show();
	   	//}
	  },
	  function () {
	  	//if (!$(this).hasClass("selected")){
	    	$(this).css("background-color","#ecf8fd");
	    	$(this).find("div.dropdown").hide();
	  	//}
	  }
	);

	$("#editpages").html(\''.$menu.'\');
	
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
	</script>';
}

function DM_PE_getPages($parent, $menu,$level) {
	global $pagesSorted;
	$items=array();
	foreach ($pagesSorted as $page) {
		if ($page['parent']==$parent){
			$items[(string)$page['url']]=$page;
		}	
	}	
	
	$toggleMenu=get_nonce("menu","toggle.php");
	$togglePrivate=get_nonce("private","toggle.php");
	
	$gsVersion=get_site_version(false);
	
	
	if (count($items)>0){
		foreach ($items as $page) {
		  	$dash="";
		  	if ($page['parent'] != '') {
	  			$page['parent'] = $page['parent']."/";
	  		}
			for ($i=0;$i<=$level-1;$i++){
				if ($i!=$level-1){
	  				$dash .= '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
				} else {
					$dash .= '<span>&nbsp;&nbsp;&ndash;&nbsp;&nbsp;&nbsp;</span>';
				}
			} 
			$delNonce=get_nonce("delete","deletefile.php");
			$cloneNonce=get_nonce("clone","pages.php");
			$menuNonce= get_nonce("menu","toggle.php");
			$privateNonce= get_nonce("private","toggle.php");
			
			$menu .= '<tr class="hover" id="tr-'.$page['url'] .'" >';
			
			
			if ($page['title'] == '' ) { $page['title'] = '[No Title] &nbsp;&raquo;&nbsp; <em>'. $page['url'] .'</em>'; }
			if ($page['menuStatus'] != '' ) { $page['menuStatus'] = ' <sup>['.i18n_r('MENUITEM_SUBTITLE').']</sup>'; } else { $page['menuStatus'] = ''; }
			if ($page['private'] != '' ) { $page['private'] = ' <sup>['.i18n_r('PRIVATE_SUBTITLE').']</sup>'; } else { $page['private'] = ''; }
			if ($page['url'] == 'index' ) { $homepage = ' <sup>['.i18n_r('HOMEPAGE_SUBTITLE').']</sup>'; } else { $homepage = ''; }
			$menu .= '<td class="pagetitle">'. $dash .'<a title="'.i18n_r('EDITPAGE_TITLE').': '. cl($page['title']) .'" href="edit.php?id='. $page['url'] .'" >'. cl($page['title']) .'</a><span class="showstatus toggle" >'. $homepage . $page['menuStatus'] . $page['private'] .'</span></td>';
			$menu .= '<td style="width:80px;text-align:left;" ><span>'. shtDate($page['pubDate']) .'</span></td>';
			
			$menu .= '<td style="width:24px;text-align:right;" >';
			/**
			$menu .= '<a href="edit.php?id='.$page['url'].'"><img src="../plugins/DM_PE_pageedit/images/document_edit.gif" title="Edit Page" alt="Edit Page" /></a>&nbsp;'; 
			$menu .= '<a href="edit.php?parent='.$page['url'].'"><img src="../plugins/DM_PE_pageedit/images/document_right.gif" title="Create Child Page" alt="Create Child Page" /></a>&nbsp;'; 
			$menu .= '<a href="pages.php?id='.$page['url'].'&action=clone&nonce='.$cloneNonce.'"><img src="../plugins/DM_PE_pageedit/images/documents_duplicate.gif" title="Clone Page" alt="Clone Page" /></a>&nbsp;';
				 
			
			if ($page['menuStatus'] != '' ) {
				$menu .= '<a href="../plugins/DM_PE_pageedit/toggle.php?func=menu&id='.$page['url'].'&nonce='.$menuNonce.'" class="toggleMenu"><img src="../plugins/DM_PE_pageedit/images/menu.gif" title="Toggle Menu Off" alt="Toggle Menu Off" /></a>&nbsp;'; 	
			} else {
				$menu .= '<a href="../plugins/DM_PE_pageedit/toggle.php?func=menu&id='.$page['url'].'&nonce='.$menuNonce.'" class="toggleMenu"><img src="../plugins/DM_PE_pageedit/images/menu_off.gif" title="Toggle Menu On" alt="Toggle Menu On" /></a>&nbsp;'; 					
			}
			
			if ($page['private'] != '' ) {
				$menu .= '<a href="../plugins/DM_PE_pageedit/toggle.php?func=private&id='.$page['url'].'&nonce='.$privateNonce.'" class="togglePrivate"><img src="../plugins/DM_PE_pageedit/images/security_off.gif" title="Toggle Private On" alt="Toggle Private On" /></a>&nbsp;'; 	
			} else {
				$menu .= '<a href="../plugins/DM_PE_pageedit/toggle.php?func=private&id='.$page['url'].'&nonce='.$privateNonce.'" class="togglePrivate"><img src="../plugins/DM_PE_pageedit/images/security.gif" title="Toggle Private Off" alt="Toggle Private Off" /></a>&nbsp;'; 					
			}
			$menu .= '<a title="'.i18n_r('VIEWPAGE_TITLE').': '. cl($page['title']) .'" target="_blank" href="'. find_url($page['url'],$page['parent']) .'"><img src="../plugins/DM_PE_pageedit/images/theme.gif" title="View Page" alt="View Page" /></a>&nbsp;'; 					
			
			if ($page['url'] != 'index' ) {
			$menu .= '<a href="deletefile.php?id='.$page['url'].'&nonce='.$delNonce.'"  class="delconfirm" title="Delete Page: '.$page['url'].'"><img src="../plugins/DM_PE_pageedit/images/trash.gif" title="Delete Page" alt="Clone Page" /></a>'; 
			} else {
				$menu .= '';
			}
			**/
			
			$menu .= '<div class="dropdown">';
			$menu .= '<img src="images/icon_spacer.gif" alt="" class="sprite icon dropdownIcon ">';
			/*
			$menu .= '<ul class="dropdownmenu">';
			$menu .= '<li id="save-close" ><a href="#" >Save &amp; Close</a></li>';
			$menu .= '<li><a href="pages.php?id=test&amp;action=clone&amp;nonce=16992ac218003b7b85b536aa5523af78468ae4c2" >Clone</a></li>';
			$menu .= '<li id="cancel-updates" class="alertme"><a href="pages.php?cancel" >Cancel</a></li>';
			$menu .= '<li class="alertme" ><a href="deletefile.php?id=test&amp;nonce=b6ed4bcaf21d3a569ca66db6d2a39b9a30b5e8cb" >Delete</a></li>';
			$menu .= '</ul>';
			*/
			$menu .= '</div>';
			
			$menu .= '</td>';
			
			
			$menu .= '</tr>';
			$menu = DM_PE_getPages((string)$page['url'], $menu,$level+1);	  	
		}
	}
	return $menu;
}