<?php
$version = 'ezgenerator search 1.70';  
/*
  search.php 
  http://www.ezgenerator.com
  Copyright (c) 2004-2008 Image-line
*/
error_reporting(E_ALL);
include_once ('../ezg_data/functions.php');
$sitemap_fname='../sitemap.php';
$site_languages='English|';
$site_languages_array=explode('|',$site_languages);
array_pop($site_languages_array);
$php_pages_ids=array('20','21','130','140','133','136','137','138','143','144');    
$max_line_chars=25000;
$res_bgcolor='#E2E2E2';
$internal_use=false;	// for FL and EZG use 
$internal_fl_use=false; // for FL studio use only
$alternative_db_folder='flstudio7';		//  'fl7order'  // for FL studio use only --> DOES NOT work
$ext_indexing_dir='../help/';				// for FL and EZG use only
$ext_indexing_fname='Index_Frame_Left.htm'; // for FL and EZG use only
$more_dirs_to_index=array('help','help/html'); 
$template_source_page='../documents/template_source.html';
$http_pref='http://';
$gt_page=f_define_source_page();

// -------------------------------------------------------
function checkfor_php_code($template_content)  // inserted php code handler
{
	$template_content='?'.'>'.trim($template_content); 
	$template_content=preg_replace("'<\?xml(.*?)/>'si",'',$template_content);
	$rnd=f_GFSAbi($template_content,'<!--rnd-->','<!--endrnd-->');    //miro
	$template_content=str_replace($rnd,'',$template_content);
	eval($template_content);
}
function get_page_area($content)
{
	global $template_source_page;

	if(file_exists($template_source_page) && strpos($content,'%CONTENT%')!==false) { $content='%CONTENT%'; }
	elseif(strpos($content,'<!--page-->')!==false )			{ $content=f_GFS($content,'<!--page-->','<!--/page-->'); }
	else
	{
		$content=str_replace(array('<BODY','</BODY'),array('<body','</body'),$content);
		$pattern=f_GFSAbi($content,'<body','</body>');	
		$body_start_tag=substr($pattern,0,strpos($pattern,'>')+1);
		$content=f_GFSAbi($content, $body_start_tag, '</body>');
	}
	return $content;
}
function get_page_content($fname)
{	
	$content=f_read_file($fname);	
	$content=get_page_area($content);			
	return $content;
}
function GT($fname_buffer,$html_output,$search_string='',$id='',$page_charset='') 
{
	global $internal_fl_use, $http_pref, $f_br, $f_ct;
	
	$content='';
	$search_part='';
	$indir=(strpos($fname_buffer, '../')===false);
	$prefix=($indir)?'../':'';
	$fname_buffer_f=$prefix.$fname_buffer; 
	$content=f_read_file($fname_buffer_f);

	if(!empty($id))		
	{
		$content=str_replace(f_GFS($content,'charset=','"'),$page_charset,$content);
		if(strpos($content,'<!--search-->')!==false)
		{
			$search_part=$f_br.f_GFS($content,'<!--search-->','<!--/search-->');
			$search_part=str_replace('name="string"','name="string" value="'.str_replace(array('\\"','"'),array('&#34;','&#34;'),f_un_esc($search_string)).'"',$search_part);
		}
	}
  $pattern=get_page_area($content);
	if($search_part!='') $html_output=$search_part.$html_output;

	if($internal_fl_use==true) 	$html_output='<table class="main-p" width="952px" cellpadding="0" cellspacing="0"><tr valign="top"><td><h1>Search Result</h1></td></tr></table><table class="main-l" width="952px" cellpadding="0" cellspacing="16"><tr valign="top"><td width="100%"><p>'.$html_output.'</p></td></tr></table><p><img src="images/cap_main.gif" id="cap_main" title="" alt="" style="vertical-align: bottom; width: 952px; height: 8px;"'.$f_ct.'</p>';
  $content=str_replace($pattern,$html_output,$content);	
	if($indir) 
	{ 
		$base_dir=(isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']: $_SERVER['PHP_SELF']);	
		$content=str_replace('</title>','</title> <base href="'.$http_pref.$_SERVER['HTTP_HOST'].str_replace('documents', '', dirname($base_dir)).'">', $content); 
	}
  return $content;
}
function build_nav_bar($page,$search_string,$show_results,$n_pages,$l_page,$l_results,$l_from,$l_search,$id,$search_in_all,$gt_page) 
{
	$dir=(strpos($gt_page,'../')===false)?'documents/':'../documents/';

	$body_section='<span class="rvts8">'.$l_page.'</span>&nbsp;';
	for($i=1; $i<=$n_pages; $i++) 
	{
		if($i==$page)	{$body_section.="<span class='rvts8'>$i</span>";}
		else 
		{
			$body_section.=' <a class="rvts12" href="'.$dir.'search.php?action=search' .(isset($id)?'&amp;id='.$id:'')
			.'&amp;string='.$search_string.'&amp;mr='.$show_results .'&amp;lr='.$l_results.'&amp;lp='.$l_page.'&amp;lf='.$l_from.'&amp;ls='
			.$l_search.'&amp;page='.$i.'&amp;sa='.$search_in_all.'">'.($i)."</a> ";
		}
	} 					
	return $body_section;
}
function clear_html_tags($html)
{	
	$search_main=array("'<\?php.*?\?>'si","'<script[^>]*?>.*?</script>'si","'<!--footer-->.*?<!--/footer-->'si", "'<!--search-->.*?<!--/search-->'si", "'<!--counter-->.*?<!--/counter-->'si","'<!--mmenu-->.*?<!--/mmenu-->'si","'<!--smenu-->.*?<!--/smenu-->'si","'<!--ssmenu-->.*?<!--/ssmenu-->'si");
	$result=preg_replace($search_main,array("","","","","","","",""),$html);

	$search_more=array ("'<img.*?>'si", "'<a .*?>'si", "'<embed.*?</embed>'si", "'<object.*?</object>'si", "'<select[^>]*?>.*?</select>'si","'<[/!]*?[^<>]*?>'si","'\n'","'\r\n'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i", "'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(d+);'e","'%%USER.*?%%'si", "'%%HIDDEN.*?HIDDEN%%'si","'%%DLINE.*?DLINE%%'si","'%%KEYW.*?%%'si");
	$replace_more=array ("","","","", ""," ","","","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\1)","","","","");
	$result=preg_replace($search_more,$replace_more,$result);
	$result=str_replace('%%TEMPLATE1%%','',$result);
	return f_esc($result); 
}
function clear_macros($content, $id, $fields=array()) 
{
	if($id=='136')	//calendar
	{	
		$result=preg_replace(array("'%CALENDAR_OBJECT\(.*?\)%'si","'%CALENDAR_EVENTS\(.*?\)%'si","'%CALENDAR_.*?%'si"),array('','',''),$content);	
	}
	elseif($id=='137')	//blog
	{	
		$result=preg_replace(array ("'%BLOG_OBJECT\(.*?\)%'si","'%BLOG_ARCHIVE\(.*?\)%'si","'%BLOG_RECENT_COMMENTS\(.*?\)%'si","'%BLOG_RECENT_ENTRIES\(.*?\)%'si","'%BLOG_CATEGORY_FILTER\(.*?\)%'si", "'%BLOG_.*?%'si"),array ('','','','','',''),$content);					
	}
	elseif($id=='138')  //photoblog
	{	
		$result=preg_replace(array("'%BLOG_OBJECT\(.*?\)%'si","'%BLOG_EXIF_INFO\(.*?\)%'si","'%ARCHIVE_.*?%'si","'%BLOG_.*?%'si", "'%PERIOD_.*?%'si", "'%CATEGORY_.*?%'si"),array ('','','','','',''),$content);
	}
	elseif($id=='143') //podcast  
	{
		$result=preg_replace(array ("'%PODCAST_OBJECT\(.*?\)%'si","'%PODCAST_ARCHIVE\(.*?\)%'si","'%PODCAST_RECENT_COMMENTS\(.*?\)%'si","'%PODCAST_RECENT_EPISODES\(.*?\)%'si","'%PODCAST_CATEGORY_FILTER\(.*?\)%'si","'%PODCAST_OBJECT\(.*?\)%'si","'%PODCAST_.*?%'si"),array('','','','','','',''),$content);			
	}
	elseif($id=='144')  //guestbook
	{
		$content=preg_replace(array("'%GUESTBOOK_OBJECT\(.*?\)%'si","'%GUESTBOOK_ARCHIVE\(.*?\)%'si","'%GUESTBOOK_ARCHIVE_VER\(.*?\)%'si", "'%GUESTBOOK_.*?%'si"),array('','','',''),$content);
		$result=str_replace(array('%HOME_LINK%','%HOME_URL%'),array('',''),$content);				
	}
	elseif(in_array($id,array('21','130','140')))  //lister 
	{
		$a=array_fill(0,17,'');
		$content=preg_replace(array ("'%HASH\(.*?\)%'si","'%ITEMS\(.*?\)%'si","'%SCALE\(.*?\)%'si","'%SHOP_ITEM_DOWNLOAD_LINK\(.*?\)%'si","'%SHOP_CATEGORYCOMBO\(.*?\)%'si","'%SHOP_PREVIOUS\(.*?\)%'si","'%SHOP_NEXT\(.*?\)%'si","'%LISTER_CATEGORYCOMBO\(.*?\)%'si","'%LISTER_PREVIOUS\(.*?\)%'si","'%LISTER_NEXT\(.*?\)%'si","'<!--menu_java-->.*?<!--/menu_java-->'si","'<!--scripts2-->.*?<!--endscripts-->'si","'<!--<pagelink>/.*?</pagelink>-->'si","'<LISTER_BODY>.*?</LISTER_BODY>'si", "'<LISTERSEARCH>.*?</LISTERSEARCH>'si","'<SHOP_BODY>.*?</SHOP_BODY>'si", "'<SHOPSEARCH>.*?</SHOPSEARCH>'si","'%SHOP_.*?%'si","'%LISTER_.*?%'si","'%SLIDESHOWCAPTION_.*?%'si"),$a,$content); 
		$content=str_replace(array ('%ERRORS%','%IDEAL_VALID%','%QUANTITY%','%LINETOTAL%','%LINETOTAL%','%URL=Detailpage%','%CATEGORY_COUNT%','%SEARCHSTRING%'),array ('','','','','','','',''),$content);
		
		$a=array_fill(0,40,'');
		$result=str_replace(array ('<ITEM_VARS>','</ITEM_VARS>','<ITEM_VARS_LINE>','</ITEM_VARS_LINE>','<ITEM_HASHVARS>','</ITEM_HASHVARS>','<SHOP_DELETE_BUTTON>','</SHOP_DELETE_BUTTON>','<MINI_CART>','</MINI_CART>','<SHOP_BUY_BUTTON>','</SHOP_BUY_BUTTON>','<QUANTITY>','<RANDOM>','</RANDOM>','<SHOP>','</SHOP>','<LISTER>','</LISTER>','<ITEM_INDEX>','<ITEM_ID>','<ITEM_QUANTITY>','<ITEM_AMOUNT>','<ITEM_AMOUNT_IDEAL>','<ITEM_VAT>','<ITEM_SHIPPING>','<ITEM_CODE>','<ITEM_SUBNAME>','<ITEM_SUBNAME1>','<ITEM_SUBNAME2>','<ITEM_NAME>','<ITEM_CATEGORY>','<ITEM_VARS>','</ITEM_VARS>','<SHOP_URL>','<BANKWIRE>','</BANKWIRE>','<CATEGORY_HEADER>','</CATEGORY_HEADER>','<FROMCART>'),$a,$content);	

		if(!empty($fields))
		{
			foreach($fields as $k=>$v)	$result=str_replace('%'.$v.'%','',$result);
		}				
	} 
	$result=str_replace('%LINK_TO_ADMIN%','',$result);	
	return $result;
}	
function preg_pos($sPattern,$sSubject,&$occurances) 
{
	if(strpos($sPattern,'*')!==false)	{ $wildcardPos=strpos($sPattern,'*'); $wc='*'; }
	elseif(strpos($sPattern,'?')!==false) { $wildcardPos=strpos($sPattern,'?'); $wc='?'; }
	else $wildcardPos=false;
	
	if($wildcardPos!==false && $wildcardPos==strlen($sPattern)-1)	$sPattern_='/\W('.str_replace($wc,'',$sPattern).')/i';
	elseif($wildcardPos!==false && $wildcardPos==0)					$sPattern_='/('.str_replace($wc,'',$sPattern).')\W/i';
	elseif($wildcardPos!==false)									$sPattern_='/('.str_replace($wc,'.\w*?',$sPattern).')\W/i';
	else															$sPattern_='/('.$sPattern.')/i';
	
	$occurances=@preg_match_all($sPattern_,$sSubject,$aMat);
	if(@preg_match($sPattern_,$sSubject,$aMatches,PREG_OFFSET_CAPTURE)>0)  { return $aMatches[0][1]; }
	else {	return false; }	
}
function cut_result($haystack,$needle_pos,$key_words_s)
{		
 global $res_bgcolor;
	if(strlen($haystack)>400) 
	{
		$x=0; $y=400;     
		while(($needle_pos-$x>0) && (substr($haystack,$needle_pos-$x-1, 1)!='.') && (substr($haystack,$needle_pos-$x-1, 1)!='!') && (substr($haystack,$needle_pos-$x-1, 1)!='?') )		{ $x += 1; }  
		while((substr($haystack,$needle_pos+$y, 1)!=' ') && ($needle_pos+$y>$needle_pos) )  { $y-=1; }  										
		$res_block=substr($haystack,$needle_pos-$x, $x+$y);	
	}
	else { $res_block=$haystack; } 

	if(strpos($key_words_s,'*')!==false)	{ $wildcardPos=strpos($key_words_s,'*'); $wc='*'; $key_words_s=str_replace($wc,'.\w*?',$key_words_s); }
	elseif(strpos($key_words_s,'?')!==false) { $wildcardPos=strpos($key_words_s,'?'); $wc='?'; $key_words_s=str_replace($wc,'.\w*?',$key_words_s); }
	else $wildcardPos=false;

	$res_block=preg_replace('/\b('.$key_words_s.')\b/i',' \0 ',$res_block);
	$res_block=preg_replace('/\b('.$key_words_s.')\W/i',' \0 ',$res_block);
	$res_block=preg_replace('/\W('.$key_words_s.')\b/i',' \0 ',$res_block);
	$res_block=preg_replace('/\W('.$key_words_s.')\W/i',' <span style="background:'.$res_bgcolor.';"><b>\0</b></span> ',$res_block);	
	if($wildcardPos!==false)	
	{ 
		$res_block=preg_replace('/\W('.str_replace($wc,'',$key_words_s).')\W/i',' <span style="background:'.$res_bgcolor.';"><b>\0</b></span> ',$res_block);	
	}
	$res_block=$res_block.(strlen($haystack)>100?" <b>...</b> ":" "); 							
	return $res_block;	
}	
function extract_records($fname, $id, $entry_id='') 
{
	global $max_line_chars;
		$records=array();
	
	if(file_exists($fname))
	{		
		if($id=='144') 
		{
			$records_str=f_read_file($fname);
			if($records_str!='') {$records_str=f_GFS($records_str,'<entries>','</entries>'); $records=format_in_array2($records_str);}
			
			if($entry_id!='' && !empty($records)) // when extracting single record
			{
				foreach($records as $k=>$v) { if(in_array($entry_id,$v)) {$temp[]=$v; break;} }
				$records=$temp;
			}
		}
		else 
		{	
			if(filesize($fname)>0)
			{
				$fp=fopen($fname,'r');
				$php_start=fgetcsv($fp,2048);  
				$db_field_names=fgetcsv($fp,2048);
				while($data=fgetcsv($fp,$max_line_chars)) 
				{ 
					if($data[0]!="*/ ?>") 
					{
						if($entry_id!='') // when extracting single record
						{
							if ($data[0]==$entry_id) { $records[]=format_in_array1($data,$db_field_names); break; }
							else { continue; }
						}
						else {$records[]=format_in_array1($data,$db_field_names);}
					}					
				}
				fclose($fp);
			}
		}
	}		
	return $records;
} 
function format_in_array1($value,$key) 
{
	$output=array();
	foreach($key as $k=>$v) 
	{
		$output[$v]=current($value);
		next($value);
	}
	return $output;
}
function format_in_array2($records) 
{
	$entries_array=array();
	$i=1;
	
	while(strpos($records, '<entry id="'.$i.'">')!==false) 
	{
		$comments_buff=array();
		$main_buffer['id']=$i;

		$record='<entry id="'.$i.'">'. f_GFS($records, '<entry id="'.$i.'">', '</entry>').'</entry>';
		$entry_part=f_GFS($record, '<entry id="'.$i.'">', '<comments_data>');
		$comments_part=f_GFS($record, '<comments_data>', '</comments_data>');
		$entry_timetsamp=f_GFS ($entry_part, "<timestamp>", "</timestamp>");

		while(strpos($entry_part, '<')!==false) 
		{
			$element_name=f_GFS ($entry_part, '<', '>');
			$element_value=f_GFS ($entry_part, "<$element_name>", "</$element_name>");			
			$main_buffer [$element_name]=$element_value;
			$entry_part=str_replace("<$element_name>$element_value</$element_name>", '',$entry_part);
		}
		$j=1;
		while(strpos($comments_part, '<comment id="'.$j.'">')!==false) 
		{
			$buff=array();
			$comment_str=f_GFS($comments_part, '<comment id="'.$j.'">', '</comment>');
			while(strpos($comment_str, '<')!==false) 
			{
				$element_name=f_GFS ($comment_str, '<', '>');
				$element_value=f_GFS ($comment_str, "<$element_name>", "</$element_name>");			
				$buff [$element_name]=$element_value;
				$comment_str=str_replace("<$element_name>$element_value</$element_name>", '',$comment_str);
			}
			$buff['entry_id']=$entry_timetsamp;
			$comments_buff []=$buff;
			$j++;
		}
		$main_buffer ['comments']=$comments_buff;
		$entries_array []=$main_buffer;
		$i++;
	}
	return $entries_array;
}
function db_search($search_string,$pages_list,$language) 
{ 
	global $site_languages_array, $max_line_chars, $internal_fl_use, $alternative_db_folder;
		
	$result_pages=array();	$search_db_fname=array(); $search_in_all='true'; $fl=true;	

	foreach($site_languages_array as $k=>$v)	// check for auto reindex
	{	
		$ff='../documents/search_db_'.($k+1).'.ezg.php';
		if(file_exists($ff)) { $fsize=filesize($ff); if($fsize>0) {$fl=false; break;}  }
	}
	if($fl==true)	{reindex(true);}  
	
	if(isset($_POST['sa']))		$search_in_all=$_POST['sa'];
	elseif(isset($_GET['sa']))	$search_in_all=$_GET['sa'];		
	
	$q_pos=strpos($search_string,'"'); // opening " (if used)
	$qs_pos=strpos($search_string,'\"');
	$qcl_pos=strrpos($search_string,'"'); // closing " (if used)
	if( (($q_pos!==false && $q_pos==0) || ($qs_pos!==false && $qs_pos==0)) && $qcl_pos==(strlen($search_string)-1)) 
	{
		$search_string=substr($search_string,1,strlen($search_string)-2);
		if(strpos($search_string,'\\')!==false) $search_string=substr($search_string,1,strlen($search_string)-2);
		$key_words=array($search_string);		
	}
	else { $key_words=(strpos($search_string,' ')!==false? explode(' ',$search_string): array($search_string));	 }
	
	$key_words_trimmed=array();
	foreach($key_words as $k=>$v)  { if($v!='') $key_words_trimmed[]=trim($v); }
	$key_words_s=implode('|', $key_words_trimmed);

	//---------------
	if($search_in_all=='true' || $search_in_all=='TRUE')
	{
		foreach($site_languages_array as $k=>$v) {$search_db_fname[]='../documents/search_db_'.($k+1).'.ezg.php';}
	}
	else {$search_db_fname[]='../documents/search_db_'.$language.'.ezg.php';	}
    
	if($internal_fl_use==true)  {$search_db_fname[]='../../'.$alternative_db_folder.'/documents/search_db_1.ezg.php';} //fl only

	foreach($search_db_fname as $k=>$file)
	{	
		$content=f_read_file($file);
		if($content!='')
		{
			foreach($pages_list as $k=>$v) 
			{		
				$db_content='';
				$flag=false;
				$page_id=str_replace('<id>','',$v[10]);

				if(strpos($content, '<page_id_'.$page_id.'>')!==false) 
				{ 
					$page_info=f_GFS($content, '<page_id_'.$page_id.'>', '</page_id_'.$page_id.'>');
					$page_title=f_GFS($page_info, '<page_title>', '</page_title>');
					$page_url=f_GFS($page_info, '<page_url>', '</page_url>');
					$lm_date=f_GFS($page_info, '<page_date>', '</page_date>');

					if($internal_fl_use==true && (strpos('/flstudio7/', $file)!==false || strpos('/fl7order/', $file)!==false)) // fl only
					{
						if(strpos('/flstudio7/', $file)!==false)	$page_url=str_replace('../','../../flstudio7/',$page_url);
						elseif(strpos('/fl7order/', $file)!==false) $page_url=str_replace('../','../../fl7order/',$page_url);
					}

					$page_content=f_GFS($page_info, '<page_content>', '</page_content>');
					$page_content=f_un_esc($page_content);	
					$occurances_main=0;
					preg_pos($key_words_s, $page_content, $occurances_main);
						
					$db_content=f_GFS($page_info, '<db_content>', '</db_content>');
					if($db_content!='') 
					{	
						$occurances=0;
						$haystack=f_un_esc(urldecode($db_content));												
						$needle_pos=preg_pos($key_words_s, $haystack, $occurances);
						while($needle_pos!==false) 
						{	
							$page_url_fixed=$page_url;
							$entry_id=f_GFS(substr($haystack, $needle_pos),'</id_','_id>');
							$entry_data=f_GFS($haystack, '<id_'.$entry_id.'_id>','</id_'.$entry_id.'_id>');										$res_content=$entry_data;							

							if($v[4]=='136')		{ $page_url_fixed.='?event_id='.$entry_id; }
							elseif($v[4]=='137')	{ $page_url_fixed.='?entry_id='.$entry_id; }
							elseif($v[4]=='138')	{ $page_url_fixed.='?photo_id='.$entry_id; }
							elseif($v[4]=='143')	{ $page_url_fixed.='?entry_id='.$entry_id; }
							elseif($v[4]=='144')	{ $page_url_fixed.='?entry_id='.$entry_id; }
							elseif(in_array($v[4], array('21', '130', '140')))
							{
								$fixed_entry_id=$entry_id;
								if(strpos($entry_id,'_')!==false) { $temp=explode('_', $entry_id);  $cat_id=$temp[0]; $fixed_entry_id=$temp[1]; }
								if(isset($cat_id)) $append_url='?action=item&iid='.$fixed_entry_id.'&cat='.$cat_id.'&page=1';
								else $append_url='?cat='.$fixed_entry_id;

								if(strpos($page_url_fixed,'action=list')!==false) 
									$page_url_fixed=str_replace('?action=list', $append_url, $page_url_fixed);
								else $page_url_fixed=$page_url_fixed.$append_url;
							}										
							if(!array_key_exists("$page_url_fixed", $result_pages))
							{	
								$occurances=0;
								$fixed_pos=preg_pos($key_words_s,' '.$res_content,$occurances); 
								$occurances += $occurances_main;
								$lm_date=f_GFS($res_content, '%%lm_', '_date%%');
								$res_content=str_replace('%%lm_'.$lm_date.'_date%%', '', $res_content);
								$res_content=cut_result($res_content, $fixed_pos, $key_words_s);
								$result_pages ["$page_url_fixed"]=array($page_title,$page_url_fixed,$res_content,$page_id,$lm_date,$occurances);
								if($flag != true) $flag=true;
							}	
							$haystack=str_replace('<id_'.$entry_id.'_id>'.$entry_data.'</id_'.$entry_id.'_id>','', $haystack); 
							$needle_pos=preg_pos($key_words_s, $haystack, $occurances);
						}			 
					}				
					if($flag==false) 
					{
						$occurances=0;
						$needle_pos=preg_pos($key_words_s, $page_content, $occurances);
						if($needle_pos!==false)
						{ 								
							if(!array_key_exists("$page_url", $result_pages)) 
							{ //'../documents/search.php?page='.str_replace('../','',$page_url).'&amp;highlight='.urlencode($key_words_s)
								$result_pages ["$page_url"]=array($page_title, $page_url, cut_result($page_content, $needle_pos, $key_words_s), $page_id,$lm_date,$occurances);										
							}
						}
					}					
				}
			}
			if(strpos($content, '<ext_pages>')!==false)   
			{
				$ext_content=f_GFS($content,'<ext_pages>','</ext_pages>');			
				while(strpos($ext_content,'<page_id_')!==false)
				{
					$occurances=0;
					$page_info=f_GFS($ext_content,'<page_id','</page_id');
					$page_title=f_GFS($page_info, '<page_title>','</page_title>');
					$page_url=f_GFS($page_info,'<page_url>','</page_url>');
					$lm_date=f_GFS($page_info,'<page_date>','</page_date>');
					$page_content=f_GFS($page_info,'<page_content>','</page_content>');
					$page_content=f_un_esc($page_content);
						
					$needle_pos=preg_pos($key_words_s, $page_content, $occurances);
					if($needle_pos!==false)
					{ 								
						if(!array_key_exists("$page_url", $result_pages)) 
						{
							$result_pages ["$page_url"]=array($page_title, $page_url, cut_result($page_content, $needle_pos, $key_words_s), $page_url, $lm_date, $occurances);										
						}
					}
					$ext_content=substr($ext_content, strpos($ext_content, '</page_id')+9);
				}
			}								
		}
	}
	return $result_pages;
}
function reindex($auto=false)
{
	global $site_languages_array, $php_pages_ids, $max_line_chars, $sitemap_fname, $f_br, $gt_page;
	global $more_dirs_to_index, $ext_indexing_dir, $ext_indexing_fname, $internal_use, $query_st_time;
		$output='';		

	foreach($site_languages_array as $kkk=>$vvv)
	{
		$buffer='';
		clearstatcache();
		$search_db_fname='../documents/search_db_'.($kkk+1).'.ezg.php';

		if(file_exists($search_db_fname))
		{
			$page_reindex=(isset($_GET['pid']) && filesize($search_db_fname)>0? true: false);
			if($page_reindex)	$pages_list[]=f_get_page_params($_GET['pid'],$sitemap_fname);
			else				$pages_list=f_get_sitemap($sitemap_fname);

			if(!$page_reindex) $buffer.="<?php echo 'hi'; exit; /* ";
			foreach($pages_list as $k=>$v)
			{
				$p_lang=array_search ($v[16], $site_languages_array);
				$page_title=(strpos($v[0],'#')!==false && strpos($v[0],'#')==0? str_replace('#','',$v[0]): $v[0]);
				$id=str_replace('<id>', '', $v[10]);
																							
   				if(strpos($v[1],'http:')===false && strpos($v[1],'https:')===false && $p_lang==$kkk && $v[20]=='FALSE') // ignore 'HIDDEN in search'
				{					
					if(!in_array($v[4],$php_pages_ids))		// for NORMAL pages and PHP REQUEST pages
					{
						$main_fname=(strpos($v[1],'../')===false?'../'.$v[1]:$v[1]);
						$content=f_read_file($main_fname);	
						$lm_date=f_GFS($content,'<meta name="date" content="','">');
						$content=get_page_area($content);	
						$content=clear_html_tags($content);
						$buffer.='<page_id_'.$id.'><page_title>'.$page_title.'</page_title><page_date>'.$lm_date.'</page_date><page_url>'
							.$v[1].'</page_url><page_content>'.$content.'</page_content></page_id_'.$id.'>'; 
					}
					else									// for special PHP pages 
					{	
						$db_part='';
						if(in_array($v[4], array('20','21','130','140'))) // OEP and listers 
						{
							if(strpos($v[1],'../')===false) $dir='../'; 
							else	$dir='../'.f_GFS($v[1],'../','/').'/';	
							if($v[4]=='20')	$main_fname=($v[6]=='TRUE'? $dir.$id.'.php': $dir.$id.'.html');
							else $main_fname=$dir.$id.'.html';
							$content=get_page_content($main_fname);
							$content=clear_html_tags($content);
							
							if($v[4]=='20')		// online editable page
							{	
								$main_db_content='';
								$db_fname='../ezg_data/'.$id.'.ezg.php';
								$lm_date=array(filemtime($db_fname));
								$main_db_content=f_read_file($db_fname);											
								
								if(strpos($main_db_content,'<ea_main')!==false) $db_part.=f_GFS($main_db_content,'<ea_main>','</ea_main>');
								$area_id=1;
								while(strpos($main_db_content,'<ea_'.$area_id)!==false)
								{
									$db_part.=f_GFS($main_db_content,'<ea_'.$area_id.'>','</ea_'.$area_id.'>');
									$area_id++;											
								}				
								
								$content.=' '.clear_html_tags($db_part);
								$buffer.='<page_id_'.$id.'><page_title>'.$page_title.'</page_title><page_date>'.max($lm_date).'</page_date> <page_url>'.$v[1].'</page_url><page_content>'.$content.'</page_content></page_id_'.$id.'>';
							}
							else				// lister and shop pages
							{			
								$db_fname=$dir.$id.'_0.dat';	
								if(file_exists($db_fname)) 
								{
									$cnt=0;$fs=filesize($db_fname);               
									if($fs>0){$fp=fopen($db_fname,"r");$cats=fread($fp,$fs);fclose($fp);$cnt=count(split("\n",$cats));}
              									
									$fields=array();
									$field_types=array();
									$i=1;
									while($i<$cnt+1 && file_exists($dir.$id.'_'.$i.'.dat')) 
									{
									   $db_fname=$dir.$id.'_'.$i.'.dat';	
									   $cat_id=$i;								
									   if(filesize($db_fname)>0)
									   {
										    $md=filemtime($db_fname);
										    $fp=fopen($db_fname, "r");
										    $fields=fgetcsv($fp, $max_line_chars,'|');$field_types=fgetcsv($fp, $max_line_chars,'|');
										    $t=fgetcsv($fp, $max_line_chars,'|');

										    while($item_record=fgetcsv($fp, $max_line_chars,'|')) 
										    {  
											     $db_part.='<id_'.$cat_id.'_'.$item_record[0].'_id>';
											     foreach($item_record as $kk=>$vv) 
											     {  
												      if(isset($field_types[$kk]) && in_array($field_types[$kk],array('1','2','13','15')) && $vv!='') $db_part.=clear_html_tags(str_replace('<%23>','#',$vv)).' ';
											     }
											     $db_part.='%%lm_'.$md.'_date%%';
											     $db_part.='</id_'.$cat_id.'_'.$item_record[0].'_id>';
										    }	
										    fclose($fp); 						
										}  													
									   $i++;				
									}	
									$db_part=preg_replace("'%SLIDESHOWCAPTION_.*?%'si", "", $db_part);
								}
								$content=clear_macros($content,$v[4],$fields);

								$limit=($v[4]=='21')?4:2;

								for($i=1; $i<=$limit; $i++)
								{
									$add_content=get_page_content($dir.($id+$i).'.html');
									$add_content=clear_html_tags($add_content);
									$content.=' '.clear_macros($add_content,$v[4],$fields);
								}
								$add_content='';
								$buffer.='<page_id_'.$id.'><page_title>'.$page_title.'</page_title><page_url>'.$v[1].'</page_url><page_content>'
								.$content.'</page_content><db_content>'.$db_part.'</db_content></page_id_'.$id.'>';
							}   //end listers
						}	
						elseif(in_array($v[4],array('133')))			// subscribe page
						{
							if(strpos($v[1],'../')===false) { $dir='../'; } 
							else { $dir ='../'.f_GFS($v[1],'../','/').'/'; }

							if(empty($v[9]))						{ $fname=($v[6]=='TRUE'? $dir.$id.'.php': $dir.$id.'.html');  }
							elseif(strpos($v[9],'.')===false)		{ $fname=($v[6]=='TRUE'? $dir.$v[9].'.php': $dir.$v[9].'.html'); }	
							else									{ $fname=$dir.$v[9];  }							
							$content=f_read_file($fname);	
							$lm_date=f_GFS($content,'<meta name="date" content="','">');
							$content=get_page_area($content);		
							$content=clear_html_tags($content); 
							$buffer.='<page_id_'.$id.'><page_title>'.$page_title.'</page_title><page_date>'.$lm_date.'</page_date><page_url>'.$v[1]. '</page_url><page_content>'.$content.'</page_content></page_id_'.$id.'>';
						}
						elseif(in_array($v[4],array('136','137','138','143','144')))  //blog, pblog, cal, podcast, guestbook
						{
							if($v[4]=='136')	 $pat='../ezg_calendar';
							elseif($v[4]=='137') $pat='../blog';
							elseif($v[4]=='138') $pat='../photoblog';
							elseif($v[4]=='143') $pat='../podcast';
							elseif($v[4]=='144') $pat='../guestbook';

							if(strpos($v[1],'../')===false)		$dir='../'; 
							else $dir='../'.f_GFS($v[1],'../','/').'/';
							$main_fname=($v[6]=='TRUE'? $dir.$id.'.php': $dir.$id.'.html');  	
							$content=get_page_content($main_fname);	
							
							if($v[4]=='138')
							{
								$fname_arch=$dir.($id+1).'.html';
								$content.=get_page_content($fname_arch);				
							}
							$content=clear_html_tags($content); 	
							$content=clear_macros($content, $v[4]); 
							$dir='../'.f_GFS($v[1],'../','/').'/';

							if(in_array($v[4], array('137', '138', '143')))		// blog, photoblog, podcast
							{	
								$db_fname=$pat.'/'.$id.'_db_blog_entries.ezg.php';
								if(!$page_reindex)				{ $entries_records=extract_records($db_fname, $v[4]);}
								elseif(isset($_GET['entryid'])) { $entries_records=extract_records($db_fname, $v[4],$_GET['entryid']);}	
								
								if(!empty($entries_records)) 
								{
									foreach ($entries_records as $key=>$val) 
									{
										$db_part.='<id_'.$val['Id'].'_id>'.clear_html_tags(urldecode($val['Title']));
										if($v[4]=='143') 
										{
											if(!empty($val['Subtitle'])){ $db_part.=' '.clear_html_tags(urldecode($val['Subtitle'])); }
											if(!empty($val['Author']))	{ $db_part.=' '.clear_html_tags(urldecode($val['Author'])); }	
										}
										if(!empty($val['Content'])) { $db_part.=' '.clear_html_tags(urldecode($val['Content']));}
										$db_part.='%%lm_'.$val['Last_Modified'].'_date%%';
										$db_part.='</id_'.$val['Id'].'_id>';
									}
								}
								if(!empty($db_part))
								{
									$comments_records=extract_records($pat.'/'.$id.'_db_blog_comments.ezg.php', $v[4]);
									if (!empty($comments_records)) 
									{
										foreach ($comments_records as $key=>$val) 
										{
											$m='</id_'.($v[4]=='138'?$val['Photo_Id']:$val['Entry_Id']).'_id>';
											$db_part=str_replace($m,' '.clear_html_tags(urldecode($val['Visitor'])).$m, $db_part); 
											if(!empty($val['Comments']))	
											{	
												$db_part=str_replace($m,' '.clear_html_tags(urldecode($val['Comments'])).$m, $db_part); 
											}
										}
									}
								}
							}
							elseif(in_array($v[4], array('136')))   // calendar
							{
								$db_fname=$pat.'/'.$id.'_cal_events.ezg.php';
								if(!$page_reindex)				{ $entries_records=extract_records($db_fname,$v[4]);}
								elseif(isset($_GET['entryid'])) { $entries_records=extract_records($db_fname,$v[4],$_GET['entryid']);}
																
								if(!empty($entries_records)) 
								{
									foreach($entries_records as $key=>$val) 
									{			
										$db_part.='<id_'.$val['Id'].'_id>'.clear_html_tags(urldecode($val['Short_description']));
										if(!empty($val['Details'])) { $db_part.=' '.clear_html_tags(urldecode($val['Details'])); }
										if(!empty($val['Location'])) { $db_part.=' '.clear_html_tags(urldecode($val['Location'])); }
										$db_part.='%%lm_'.filemtime($db_fname).'_date%%';
										$db_part.='</id_'.$val['Id'].'_id>';														
									}
								}					
							}
							elseif(in_array($v[4], array('144')))   // guestbook
							{	
								$db_fname=$pat.'/'.$id.'_db_guestbook.ezg.php';
								if(!$page_reindex)				{ $entries_records=extract_records($db_fname,$v[4]);}
								elseif(isset($_GET['entryid'])) {$entries_records=extract_records($db_fname,$v[4],$_GET['entryid']);}
								
								if(!empty($entries_records)) 
								{
									foreach ($entries_records as $key=>$val) 
									{			
										$db_part.='<id_'.$val['timestamp'].'_id>'.clear_html_tags(urldecode($val['name']));		if(!empty($val['surname']))		{$db_part.=' '.clear_html_tags(urldecode($val['surname']));	}

										$db_part.=' '.clear_html_tags(urldecode($val['content'])); 
										if(!empty($val['country']))		{$db_part.=' '.clear_html_tags(urldecode($val['country']));	}										
										foreach($val['comments'] as $ka=>$va) 
										{
											if(!empty($va)) 
											{
												$db_part.=' '.clear_html_tags(urldecode($va['visitor']));		
												$db_part.=' '.clear_html_tags(urldecode($va['comments'])); 
											}
										}
										$db_part.='%%lm_'.$val['timestamp'].'_date%%';
										$db_part.='</id_'.$val['timestamp'] .'_id>';
									}
								}					
							}
							if(!$page_reindex)
							{	
								$buffer.='<page_id_'.$id.'><page_title>'.$page_title.'</page_title><page_url>'.$v[1].'</page_url><page_content>' .$content.'</page_content>'.'<db_content>'.$db_part.'</db_content>'.'</page_id_'.$id.'>';
							}
							else { $buffer.=$db_part; }
							//var_dump($page_reindex); var_dump($buffer); 
							$db_part='';
						}
					}
					$content='';
				}
			}
			if(!$page_reindex)
			{
				$buffer.='<ext_pages>';
				if(!empty($more_dirs_to_index))
				{
					$file_list=array();
					foreach($more_dirs_to_index as $d_k=>$d_v)
					{
						$dir_to_index='../'.$d_v;
						if(is_dir($dir_to_index))
						{
							$handle=opendir($dir_to_index);				
							while($file=readdir($handle)) //problem with php pages
							{ 
							  if( ($file!='.') && ($file!='..') && (strpos($file,".htm")!==false || strpos($file,".php")!==false || strpos($file,".HTM")!==false || strpos($file,".PHP")!==false) )  { $file_list[]=$dir_to_index.'/'.$file; }
							}
						}
					}
					foreach($file_list as $f_k=>$f_v)
					{
						if(file_exists($f_v) && filesize($f_v)>0)
						{
							$p_content=f_read_file($f_v);
							$p_title=f_GFS($p_content,'<title>','</title>');
							if(empty($p_title)) { $p_title=f_GFS($p_content,'<TITLE>','</TITLE>'); }
							if(empty($p_title)) { $p_title=f_GFS($p_content,'<p class="pagetitle">','<p>'); }
							if(empty($p_title)) { $p_title=$f_v; }	
							$p_title=clear_html_tags($p_title);
							$lm_date=f_GFS($p_content,'<meta name="date" content="','">');
							if(empty($lm_date)) { $lm_date=f_GFS($p_content,'<META name="date" content="','">'); }
							if(empty($lm_date)) { $lm_date=filemtime($f_v); }
							$p_content=get_page_area($p_content);
							$p_content=clear_html_tags($p_content);						
							$buffer.='<page_id_'.$f_v.'><page_title>'.$p_title.'</page_title><page_date>'.$lm_date.'</page_date><page_url>'.$f_v. '</page_url><page_content>'.$p_content.'</page_content></page_id_'.$f_v.'>';
							$p_content='';
						}
					}
				}
				if($internal_use==true && $kkk==0) //FL and EZG only
				{	
					$links = array();
					$f = $ext_indexing_dir.$ext_indexing_fname;
					if(file_exists($f)&& filesize($f)>0)
					{							
						$file_contents = f_read_file($f);				
						while(strpos($file_contents,'<A HREF="')!==false)
						{
							$link = f_GFS($file_contents,'<A HREF="','">');
							$title = f_GFS($file_contents,'<A HREF="'.$link.'">','</A>');
							$links [$title] = $ext_indexing_dir.str_replace('%20',' ',$link);
							$file_contents = substr($file_contents, strpos($file_contents,'<A HREF="')+9);
						}
						foreach($links as $m=>$url)
						{
							$p_content = f_read_file($url);
							$lm_date = f_GFS($p_content,'<meta name="date" content="','">');
							if(empty($lm_date)) { $lm_date = f_GFS($p_content,'<META name="date" content="','">'); }
							if(empty($lm_date)) { $lm_date = filemtime($f_v); }
							$p_content = get_page_area($p_content);
							$p_content = clear_html_tags($p_content);
							$buffer .= '<page_id_'.$url.'><page_title>'.$m.'</page_title><page_date>'.$lm_date.'</page_date><page_url>'.$url. '</page_url><page_content>'.$p_content.'</page_content></page_id_'.$url.'>';
							$p_content='';
						}				
					}
				}
				$buffer.='</ext_pages>';
				$buffer.=" */ ?>";
			}
			//
			if(!empty($buffer))
			{
				if(!$page_reindex) 
				{if(!$fp=@fopen($search_db_fname,"w")) {print f_fmt_in_template($gt_page,f_fmt_error_msg('DBFILE_NEEDCHMOD',$search_db_fname));exit;}}
				else				 
				{if(!$fp=@fopen($search_db_fname,"r+")) {print f_fmt_in_template($gt_page,f_fmt_error_msg('DBFILE_NEEDCHMOD',$search_db_fname));exit;} }
		
				flock($fp, LOCK_EX); 
				if($page_reindex) 
				{ 
					$db_existing_content=fread($fp,filesize($search_db_fname)); 	
					if(isset($_GET['entryid']) && strpos($db_existing_content,'<page_id_'.$_GET['pid'].'>')!==false) 
					{
						$page_for_repl=f_GFSAbi($db_existing_content,'<page_id_'.$_GET['pid'].'>','</page_id_'.$_GET['pid'].'>');
						if(strpos($page_for_repl,'<id_'.$_GET['entryid'].'_id>')!==false)
						{ 
							$for_repl=f_GFSAbi($page_for_repl,'<id_'.$_GET['entryid'].'_id>','</id_'.$_GET['entryid'].'_id>');
							$db_existing_content=str_replace($for_repl,$buffer,$db_existing_content);
						}
						else
						{ 
							$buffer=str_replace('</db_content>',$buffer.'</db_content>',$page_for_repl);
							$db_existing_content=str_replace($page_for_repl,$buffer,$db_existing_content);
						}
						
					}
					else  { break; }
					$buffer=$db_existing_content;
					ftruncate($fp,0);
					fseek($fp,0);
				} 	
				if(fwrite($fp, $buffer)===FALSE) { print "Cannot write to file ($search_db_fname)"; exit;} 
				flock($fp, LOCK_UN);
				fclose($fp);
				$output=$f_br.f_fmt_admin_title('Site Search successfully reindexed!').$f_br;
			}
		}
		elseif($auto==false && !isset($_GET['redirect'])) {$output=f_fmt_error_msg('MISSING_DBFILE',$search_db_fname);}	
	}	
	$output.=$f_br.'<div align="right"><span class="rvts8" style="font-size:9px;">Page created in '.round(microtime_float() - $query_st_time,4) .' seconds</span></div>'; 
	if($auto==false && !isset($_GET['redirect'])) 
	{ 
		$output=GT($gt_page,$output);
		if(strpos($output, '<'.'?php')===false && strpos($output, '<'.'?')===false) { print $output; }
		else { checkfor_php_code($output); }
	}
	if(isset($_GET['redirect']))  // auto reindex after online update
	{
		$redirect_url=urldecode($_GET['redirect']);
		f_url_redirect('../'.$redirect_url,false,' />'); 
	}
}
function microtime_float()
{
  list($usec,$sec)=explode(" ",microtime());
	return ((float)$usec+(float)$sec);
}

function process_search() 
{
	global $site_languages_array, $sitemap_fname, $version, $http_pref, $f_br, $gt_page, $query_st_time;
	
	$query_st_time=microtime_float();
	$action_id='';  //m
	if(isset($_REQUEST['action'])) $action_id=$_REQUEST['action']; //m
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') $http_pref='https://';

	if($action_id=="search")		
	{  
		$body_section=''; $search_string='';	
		$id=''; $page_info=''; $language=1;
		$pages_list=f_get_sitemap($sitemap_fname);
		if(isset($_REQUEST['id']))	
		{		
			$id=$_REQUEST['id'];
			foreach($pages_list as $k=>$v) { if(strpos($v[10],'<id>'.$id)!==false)  {$page_info=$v;break;} }
			if($page_info!='')	$language=array_search ($page_info[16],$site_languages_array)+1;	
		}	
	
		$show_results=10; $l_results='Results'; $l_page='Page'; $l_from='from'; 
		$l_search='Search'; $page=1; $search_in_all='true';
		if(isset($_REQUEST['mr']) && !empty($_REQUEST['mr']))	$show_results=$_REQUEST['mr'];
		if(isset($_REQUEST['lr']) && !empty($_REQUEST['lr']))	$l_results=$_REQUEST['lr'];  
		if(isset($_REQUEST['lp']) && !empty($_REQUEST['lp']))	$l_page=$_REQUEST['lp'];  
		if(isset($_REQUEST['lf']) && !empty($_REQUEST['lf']))	$l_from=$_REQUEST['lf'];  
		if(isset($_REQUEST['ls']) && !empty($_REQUEST['ls']))	$l_search=$_REQUEST['ls'];
		if(isset($_REQUEST['page'])) $page=$_REQUEST['page'];
		if(isset($_REQUEST['sa']))	$search_in_all=$_REQUEST['sa'];	
		settype($page,"integer"); settype($show_results,"integer");
		
		if(isset($_REQUEST['string']))
		{	
			$search_string=urldecode($_REQUEST['string']);
			$search_string=f_un_esc(trim($search_string));	
			if($search_string!='') 
			{				
				$results=db_search($search_string, $pages_list, $language);
				$body_section.=$f_br.'<table style="width:100%;"><tr><td></td><td><span class="rvts24"><b>'.$l_results.': '.$search_string.'</b></span>'.$f_br; 
				if(empty($results)) { $body_section.=$f_br.'<span class="rvts8"><i>- '.count($results)." -</i></span></td></tr>"; }
				else
				{
					$body_section.=$f_br.'<span class="rvts8"><b>'.(($page-1)*$show_results+1).' - '  
					.($show_results*$page>count($results)? count($results): $show_results*$page).'</b></span> <span class="rvts8"><i>'
					.$l_from.'</i></span> <span class="rvts8"><b>'.count($results)."</b></span>".$f_br."</td></tr>"; // (%%sr%% sec)
					if($show_results!=0)
					{
						$count_res=count($results);
						$n_pages=($count_res%$show_results==0? $count_res/$show_results: ceil($count_res/$show_results));			if($count_res>$show_results) 
						{
							$body_section.='<tr><td></td><td align="right">'. build_nav_bar($page,urlencode($search_string),$show_results,$n_pages,$l_page,$l_results,$l_from,$l_search,$id,$search_in_all,$gt_page).$f_br.'</td></tr>';	
						}
					}
					if(count($results)>$show_results && $show_results!=0) $results_cut=array_slice($results,($page-1)*$show_results,$show_results);
					else												$results_cut=$results; 
				
					$counter=($page-1)*$show_results;
					foreach($results_cut as $k=>$v) 
					{
						$counter++;
						$lm_date='';
						if(isset($v[4]) && !empty($v[4])) 
						{
							if(strpos($v[4],'-')!==false) 
							{
								list($year,$month,$day)=explode('-',$v[4]);
								$lm_date=date('j M Y', mktime(0,0,0,(integer)$month,(integer)$day,(integer)$year)).' - ';
							}
							else $lm_date=date('j M Y', $v[4]).' - ';
						}
						if(strpos($gt_page, '../')===false)		{ $url=str_replace('../','',$v[1]);  }
						else { if(strpos($v[1], '../')===false) { $url='../'. $v[1];} else {$url = $v[1];} }
						$body_section.="<tr><td valign='top'><span class='rvts0'><b>".$counter.". </b></span></td>"
						."<td><a class='rvts4' href='".$url."'>".$v[0]."</a>".$f_br."<span class='rvts8'>".$v[2]."</span>".$f_br 
						."<span class='rvts8'><i># ".$v[5].' - '.$lm_date."URL: ".$http_pref .str_replace('documents','',$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])) .str_replace('../','',$v[1])."</i></span>".$f_br.$f_br."</td></tr>"; 
					}
				}
				$body_section.='</table>';	

				$body_section.=$f_br.'<div align="right"><span class="rvts8" style="font-size:9px;">Page created in '.round(microtime_float() - $query_st_time,4) .' seconds</span></div>'; 
			}
		}
		if(isset($page_info[17])){$pi=$page_info[17];} else $pi=''; //miro 
		$body_section=GT($gt_page, $body_section, $search_string,$id,$pi);
		if(strpos($body_section, '<'.'?php')===false && strpos($body_section, '<'.'?')===false) { print $body_section; }
		else { checkfor_php_code($body_section); }
	}
	elseif($action_id=="reindex") reindex();  //m  
	elseif($action_id=="version")	echo $version; //m
	/*elseif(isset($_GET['page']) && isset($_GET['highlight']))
	{
		$result_page = '../'.$_GET['page'];
		$f_content = f_read_file($result_page);
		if(strpos($_GET['page'], '/')===false) { $f_content = str_replace('</title>', 
			'</title> <base href="http://'.$_SERVER['HTTP_HOST'].str_replace('documents','',dirname($_SERVER['PHP_SELF'])).'">',$f_content); }
		$p_content = get_page_area($f_content, true);
		
		$key_words_s = urldecode($_GET['highlight']);
		//$key_words_s = preg_quote($key_words_s);
		if(strpos($key_words_s,'*')!==false)	
			{ $wildcardPos = strpos($key_words_s,'*'); $wc='*'; $key_words_s = str_replace('*', '.w*?', $key_words_s); }
		elseif(strpos($key_words_s,'?')!==false) 
			{ $wildcardPos = strpos($key_words_s,'?'); $wc='?'; $key_words_s = str_replace('?', '.\w*?', $key_words_s); }
		else { $wildcardPos = false; }

		$pattern = '$1$2<span style="background: #FFFF40;">$3</span>$4$5';
		//$h_content=preg_replace('#(<[^/][^>]*>.*?\b|\W)('.$key_words_s.')(b|\W</[^>]*>)?#msi', $pattern, $p_content);
		$h_content=preg_replace('#(<[^/][^>]*>)(.*?)('.$key_words_s.')(.*?)(</[^>]*>)?#msi', $pattern, $p_content);
		if($wildcardPos!==false)	
		{ 
			$h_content = preg_replace('#(<[^/][^>]*>)(.*?)('.str_replace($wc,'',$key_words_s).')(.*?)(</[^>]*>)?#msi', $pattern, $h_content);	
		}
		$f_content = str_replace($p_content,$h_content,$f_content);
		print $f_content;
	}*/
}

process_search();
?>
