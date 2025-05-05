<?php
$version="ezgenerator centraladmin 3.47";
/*
  centraladmin.php
  http://www.ezgenerator.com
  Copyright (c) 2004-2008 Image-line
*/
error_reporting(E_ALL);
$pref=(file_exists('../sitemap.php'))?'../':'';
include($pref.'documents/htmlMimeMail.php'); 
include_once($pref.'ezg_data/functions.php'); 
$http_prefix='http://';
$admin_username="";  
$admin_pwd="d41d8cd98f00b204e9800998ecf8427e";  
$db_dir='../documents/';
$db_file=$db_dir.'centraladmin.ezg.php';
$db_settings_file=$db_dir.'centraladmin_conf.ezg.php'; // settings file --> counter,self-reg and other settings
$db_activity_log=$pref.'ezg_data/centraladmin_reglog.ezg.php'; // log file
$db_delay_file=$db_dir.'centraladmin_sec.ezg.php';
$old_counter_db_fname=$db_dir.'counter_db.ezg.php';
$counter_ts_db_fname='../ezg_data/counter_totals_db.ezg.php'; 
$counter_ds_db_fname='../ezg_data/counter_db.ezg.php'; 
$ca_lang_set_fname=$pref.'ezg_data/ca_lang_set.txt';
$ca_sitemap_file=$pref.'sitemap.php';
$sp_pages_ids=array('20','21','133','136','137','138','143','144');
$ima_array=array('15|20','15|18','15|19','9|13','15|13','12|14','6|7'); 
$access_type=array('0'=>'read','1'=>'read&write');
$access_type_ex=array('0'=>'read','1'=>'read&write','2'=>'access on page level');
$set_login_cookie=false; 
$rss_call_in_prot_page=false; // public rss when page is protected
if(isset($thispage_id) && isset($_GET['action']) && $_GET['action']=='rss') $rss_call_in_prot_page=true; // public rss when page is protected
if(!isset($thispage_id)) {$thispage_id=(isset($_GET['pageid'])? $_GET['pageid']: '');}

$ca_template_file='documents/template_source.html';
if(!file_exists($pref.$ca_template_file)) $ca_template_file='documents/home.html';
$template_in_root=false; 
$pref_dir='../documents/';  
if(strpos($ca_template_file,'.html')!==false && strpos($ca_template_file,'http://')===false)	
{
	$ca_template_file_f=$pref.$ca_template_file;
	if(strpos($ca_template_file,'/')===false) {$template_in_root=true; $pref_dir='documents/';}
}	
else																				
{
	$ca_template_file_f=f_define_source_page($pref); 
	if(strpos($ca_template_file_f,'/')===false) {$ca_template_file_f='../'.$ca_template_file_f; $template_in_root=true;$pref_dir='documents/';}
}
$ca_lang_l = array('site map'=>'site map','manage users'=>'manage users','add user'=>'add user','counter settings'=>'counter settings','logout'=>'logout','CENTRAL ADMIN'=>'CENTRAL ADMIN','page name'=>'page name','admin link'=>'admin link' ,'protected'=>'protected','ca controlled'=>'CA controlled','pageloads'=>'pageloads','edit'=>'edit','admin'=>'admin','na'=>'NA','tell a friend admin'=>'tell a friend admin','total pageloads'=>'total pageloads','unique visitors'=>'unique visitors','first time visitors'=>'first time visitors','returning visitors'=>'returning visitors','details'=>'details','detailed stat'=>'detailed statistics','prev'=>'prev','next'=>'next','first'=>'first','last'=>'last','date'=>'date','time'=>'time','browser'=>'browser','os'=>'os','resolution'=>'resolution','host'=>'host','ip'=>'ip','of'=>'of','referrer'=>'referrer','user'=>'user','none users'=>'none users defined','check range'=>'check range','section'=>'section','protected pages'=>'protected pages','non-protected pages'=>'non-protected pages controlled by Central Admin','back'=>'back','username'=>'username','name'=>'first name','surname'=>'last name','email'=>'email','password'=>'password','code'=>'code','edit access'=>'edit access','edit details'=>'edit details','edit password'=>'edit password','access to'=>'access to protected sections','fill in'=>'fill in','username exists'=>'such username already exists','can contain only'=>'username can contain only A-Z, a-z, _ and 0-9','repeat password'=>'repeat password','old password'=>'old password','new password'=>'new password','password and repeated password'=>'password and repeated password don\'t match','your password should be'=>'your password should be at least five symbols','select access'=>'select access section','nonvalid email'=>'use valid email address','incorrect username/password'=>'incorrect username/password','remove'=>'remove','remove MSG'=>'Are you sure you want to remove this user?','all'=>'all','selected'=>'selected','read'=>'read','read&write'=>'read&write','required fields'=>'required fields','background color'=>'background color','digit color'=>'digit color','select color'=>'select color','font size'=>'font size','small font'=>'small font','medium font'=>'medium font','bold font'=>'bold font','large font'=>'large font','stylish font'=>'stylish font','refresh'=>'refresh','display'=>'display','number of digits'=>'number of digits','maximum visit length'=>'maximum visit length','unique start offset'=>'unique start offset','pageloads start offset'=>'pageloads start offset','show unique visitors'=>'show unique visitors','show pageloads'=>'show pageloads','reset counter'=>'reset counter','submit'=>'submit','settings saved'=>'settings were saved successfully','reset done'=>'counter was reset succcessfully','confirm counter reset'=>'confirm counter reset','reset MSG1'=>'Reset Counter only in case you need to start counting from zero. '.$f_br.'Note that resetting counter will permanently remove all counter statistics!'.$f_br.'If you want to proceed, press the link below to confirm resetting.','reset MSG2'=>'Are you sure you want to reset counter? Note that this will remove all statistics data.','ca login'=>'Protected area login','login'=>'login','use correct username'=>'please, use correct username and password to login','adduser_msg1'=>"If you want to grant user access to certain protected pages only (not to ALL), you can add login page (section) in EZGenerator and associate this section with certain protected pages, using Login property in Page Settings panel.", 'adduser_msg2'=>'allows users to browse protected pages.','adduser_msg3'=>'allows users to browse protected pages and also access special pages admin screen (for example, Blog admin screen or Online Editable page edit screen).','field should match the text on the right'=>'field should match the text on the right','code'=>'code','agree with terms'=>'you should agree with Terms of Use in order to proceed','registration'=>'registration','log'=>'log file','settings'=>'settings','non-confirmed users'=>'non-confirmed users','clear log'=>'clear log','log file cleared'=>'log file cleared','clear log MSG'=>'Are you sure you want to clear this log file?','non-existing'=>'this is non-existing username','no email for user'=>'the email doesn\'t match this username','password changed'=>'password was changed successfully','wrong old'=>'OLD password is wrong','sr_agree_msg'=>'I agree with the %%Terms of Use%%.','sr_success_msg'=>'Your registration was successful. To complete it, check your email and follow the instructions.','sr_confirm_msg'=>'Your registration was successfully completed.' ,'sr_email_msg'=>'Dear %%username%%, ####thank you for registering at %%site%%. ##To complete your registration, please confirm it here: %CONFIRMLINK%.####The %%site%% Team','sr_email_subject'=>'Your registration at %%site%%','sr_forgotpass_note'=>'Enter your username and the email address associated with your account, and your login information will be sent to that email address.','sr_forgotpass_msg'=>'Dear %%username%%, ####this email comes as an answer to your request for new password to access %%site%%. ##Your new password is: %%newpassword%%.####The %%site%% Team','sr_forgotpass_subject'=>'New password for %%site%%','sr_forgotpass_msg2'=>'Check your email to find the new password.','sr_notif_subject'=>'New user has just registered at %%site%%','sr_already_confirmed'=>'You have already confirmed your registration!','admin email'=>'admin email','sendmail from'=>'sendmail from','return path'=>'return path','terms url'=>'Terms of Use page url','notes'=>'notes','confreg_msg1'=>'you can set here either absolute url (starting with http://), or relative url (following the example: ../documents/page.html)','confreg_msg2'=>'Type here the site administrator email address. Self-registration notifications will be delivered at this address.','confreg_msg3'=>'This option is used only in case your host provider has set trusted (registered) users to be used as "From" email address. Type here the administrator email address (or other) that will be used as "From" email address.','confreg_msg4'=>'This option is to be used only in case your host provider has not set Sendfrom_email configuration option in php.ini. Type here the administrator email address (or other).','confreg_msg5'=>'Type here some notes that you want to put at the bottom of your registration form.','forgotten password'=>'forgotten password','change password'=>'change password','forgot q'=>'Forgot your password?','member q'=>'Not a member yet? REGISTER','set language'=>'set Central Admin screen language','language'=>'language','confirmation email'=>'confirmation email','resend'=>'re-send','resend MSG'=>'Are you sure you want to resend confirmation email to','user removed'=>'user was removed successfully','email resent'=>'confirmation email was sent to user','date'=>'date','activity'=>'activity','result'=>'result','confirm'=>'confirm','creation date'=>'creation date','edit profile'=>'edit profile','want to get'=>'I want to receive newsletters for','back to page'=>'Back to current page','redirect page'=>'redirect page after logout','redirect page msg'=>'If you want to always redirect user to one specific page after logout, define here url of that page.','set tzone'=>'set time zone offset','cancel'=>'cancel','export'=>'export users as CSV','counter type'=>'counter type','access on page'=>'set access on page level','registration settings'=>'registration settings');
$ca_available_lang_sets=array('DA'=>'Danish','NL'=>'Dutch','EN'=>'English','FR'=>'French','DE'=>'German','IS'=>'Icelandic','NO'=>'Norwegian','PT'=>'Portuguese','RU'=>'Russian','SL'=>'Slovenian','ES'=>'Spanish','SV'=>'Swedish','CS'=>'Czech'); 
$ca_page_charset=''; 
$ca_lang_set='EN';  //m
if(isset($_REQUEST['lang'])) $ca_lang_set=$_REQUEST['lang'];
elseif(isset($_COOKIE['ca_lang'])) $ca_lang_set=$_COOKIE['ca_lang']; 
$ca_lang_set=strtoupper($ca_lang_set);  //m
$l=($ca_lang_set=='EN'?'':'lang='.$ca_lang_set);
if(file_exists($ca_lang_set_fname)&&(filesize($ca_lang_set_fname)>0))
{
	$fp=fopen($ca_lang_set_fname,'r');
	$read_f=false;
	while ($data=fgetcsv($fp,$f_max_chars,'|')) 
	{	
		if(isset($data[0]) && !empty($data[0]))
		{			
			if($data[0]=='[END]' && $read_f) break;
			elseif($read_f)	{$label=explode('=',$data[0]); $ca_lang_l["{$label[0]}"]=$label[1];}	
			if($data[0]=='['.strtoupper($ca_lang_set).']') $read_f=true;
		}
	}
	fclose($fp);						
}
$sr_enable=false;
$sr_notif_enabled=true; 
$ca_first_line="<?php echo 'hi'; exit; /*"; 
$ca_last_line="*/ ?>";
$ca_account_msg='<div align="left">'.$f_br.'<span class="rvts4"><em style="color:red;">Username & Password are not set for your Central Admin account.</em></span> '.$f_br.$f_br.'<span class="rvts8">To SOLVE the problem, go to <em style="color:red;">EZGenerator >> menu Extra >> Project Settings >> Central Admin</em> and set <em style="color:red;">Username & Password</em>.</div>';
$ca_user_msg='ADMIN & ADMIN is not secure combination and thus is not allowed. Please, type new one!';
$ca_mail_msg='<div align="left">'.$f_br.'<span class="rvts4"><em style="color:red;">Admin e-mail address not defined.</em></span> '.$f_br.$f_br.'<span class="rvts8">To SOLVE the problem, go to <em style="color:red;">Central Admin >> Registration Settings</em> and define <em style="color:red;">Admin Email!</em></span>';
$ca_td="<td align='left'>";
$ca_span8="<span class='rvts8'>";
$trtdsp='<tr><td align="left">'.$ca_span8;
$sptdtr='</span></td></tr>';	
$ca_tzone_offset=f_read_tagged_data($f_ca_settings_fname,'tzoneoffset'); 

function ca_tzone_date($timestamp)
{
	global $ca_tzone_offset;
	$fixed_date=f_tzone_date($timestamp, $ca_tzone_offset);
	return $fixed_date;
}

function un_esc($s) 
{
  $buff=htmlspecialchars(str_replace(array('\\\\','\\\'','%%%'),array('\\','\'','"'),$s),ENT_QUOTES);
	return $buff;
}

function esc($s)
{
	$buff=(get_magic_quotes_gpc()?str_replace('\"','%%%',$s):str_replace(array('\\','\'','"'),array('\\\\','\\\'','%%%'),$s));
	return $buff;
}
function get_page_info($page_id) // gets info for protected page
{
	global $ca_sitemap_file,$f_max_chars,$thispage_id,$f_br;
	
	$page=array();
	if(file_exists($ca_sitemap_file)&&(filesize($ca_sitemap_file)>0)) 
	{
			$fp=fopen($ca_sitemap_file,'r');
			while($data=fgetcsv($fp,$f_max_chars,'|')) 
			{
				$data_str=implode('|',$data); if(strpos($data_str,'<id>'.$page_id.'|')!==false) {$page=$data;break;}		
			}
			if(empty($page)) 
			{      
				if($thispage_id==$page_id) 
				{
					if(isset($_POST['loginid']))
					{             	
						rewind($fp);	
    				while($data=fgetcsv($fp,$f_max_chars,'|')) 
							{if(isset($data[10]) && $data[6]=='TRUE' && $data[7]==$_POST['loginid']) {$page=$data;break;}}
						if(empty($page))
						{
							rewind($fp);	
    					while($data=fgetcsv($fp,$f_max_chars,'|')) 
		      				{if(isset($data[10]) && $data[6]=='TRUE' && $data[4]=='136') {$page=$data;break;}}
						}
						if(empty($page))
						{
							print GT($f_br."<span class='rvts8'><b>This Login page is not associated with any protected page. The system doesn't know where to redirect you.".$f_br."You have to go to EZG and protect certain page with this Login page.</b></span>"); exit; 
						}
					}		   
				}		   
				else {echo "ERROR: the <b>Protected page</b> you are trying to access uses <b>Login</b> page that does not exist anymore! Please, go to protected page <b>Page Settings</b> panel and set existing page as <b>Login</b> page, or contact the site administrator.";fclose($fp);exit;}			   
			}        			
			fclose($fp);						
   }
   return $page;
}
function get_pages_list($type_id='') 
{
	global $sp_pages_ids,$ca_sitemap_file,$f_max_chars;
	$pages=array();
	if(file_exists($ca_sitemap_file)&&(filesize($ca_sitemap_file)>0)) 
	{
			$fp=fopen($ca_sitemap_file,'r');
			while ($data=fgetcsv($fp,$f_max_chars,'|')) 
			{
				$data_str=implode('|',$data);
				$buffer=array();
				if(strpos($data_str,'*/ ?>')===false && strpos($data_str,'<?php')===false) 
				{
					$p_name=strpos($data[0],'#')!==false && strpos($data[0],'#')==0? str_replace('#','',$data[0]): $data[0];
					if(strpos($data_str,'<id>')!==false) 
					{
						$buffer['name']= trim($p_name);
						$buffer['id']= trim($data[4]);
						$buffer['url']= $data[1];
						$buffer['protected']= $data[6];
						$buffer['section']=$data[7];
						$buffer['subpage']=$data[3];
						$buffer['frames']=$data[15];
						$buffer['subpage_url']=$data[18];
						$buffer['pageid']= str_replace('<id>','',$data[10]);
						if(in_array($data[4],$sp_pages_ids)) 
						{
							if($data[4]=='133') 
							{
								$buffer['adminurl']='../subscribe/subscribe_'.str_replace('<id>','',$data[10]).'.php?action=subscribers';		
							}
							elseif($data[4]=='143'&&strpos($data[1],'?flag=podcast')!==false) {$buffer['adminurl']=$data[1].'&action=index';}		
							elseif($data[4]=='21') 
							{
								if(strpos($data[1],'/')===false)	$data[1]='../'.$data[1];
								if(strpos($data[1],'action=list')!==false) $buffer ['adminurl']=str_replace('action=list','action=orders',$data[1]);
								else $buffer['adminurl']=$data[1].'?action=orders';
							}
							elseif($data[4]=='20')
							{
								if(strpos($data[1],'/')===false) $data[1]='../'.$data[1];
								if($data[7]!='' && $data[7]!='-1' || $data[6]=='TRUE') $new_action='action=doedit'; 
								else													$new_action='action=login'; 
								
								if(strpos($data[1],'action=show')!==false) $buffer['adminurl']=str_replace('action=show',$new_action,$data[1]);
								else $buffer['adminurl']=$data[1].'?'.$new_action;
							}
							else {$buffer ['adminurl']=$data[1].'?action=index';}
						}
					}
					else {$buffer=array('name' => trim($p_name));  }
					if($type_id=='' || isset($buffer['id']) && $buffer['id']==$type_id) { $pages[]=$buffer; }
				}
			}
			fclose($fp);   
   }
   return $pages;
}
function get_prot_pages_list($section_id='')
{
	global $ca_sitemap_file,$f_max_chars;

	$pages=array();
	if(file_exists($ca_sitemap_file)&&(filesize($ca_sitemap_file)>0)) 
	{
			$fp=fopen($ca_sitemap_file,'r');
			while($data=fgetcsv($fp,$f_max_chars,'|')) 
			{
				$data_str=implode('|',$data);
				if(strpos($data_str,'<id>')!==false) 
				{
					$p_name=strpos($data[0],'#')!==false && strpos($data[0],'#')==0? str_replace('#','',trim($data[0])): trim($data[0]);
					$ca_control = ($data[7]!='' && $data[7]!='-1' || $data[6]=='TRUE');
					if($ca_control && ($section_id=='' || $data[7]==$section_id)) 
					{	
						$temp=array('name'=>$p_name,'url'=>$data[1],'typeid'=>$data[4],'section'=>$data[7],'protected'=>$data[6],'id'=>str_replace('<id>','',$data[10]));
						$pages[]=$temp;
					}
				}
			}
			fclose($fp);
   }
   return $pages;
}
function get_sections_list() 
{
	global $ca_sitemap_file,$f_max_chars;
	$sections=array();
	if(file_exists($ca_sitemap_file)&&(filesize($ca_sitemap_file)>0))
	{	
		$fp=fopen($ca_sitemap_file,'r');
		while($data=fgetcsv($fp,$f_max_chars,'|'))
		{
			$data_str=implode('|',$data);
			if(strpos($data_str,'<id>')!==false) {if($data[4]=='22') $sections[]=$data;}
		}
		fclose($fp);
	}
	return $sections;
}
function get_section_name($section_id) 
{
	global $ca_sitemap_file,$f_max_chars;
	$sections_name='';
	if(file_exists($ca_sitemap_file)&&(filesize($ca_sitemap_file)>0)) 
	{
		$fp=fopen($ca_sitemap_file,'r');
		while($data=fgetcsv($fp,$f_max_chars,'|'))
		{
			$data_str=implode('|',$data);
			if(strpos($data_str,'<id>')!==false)
					{if($data[4]=='22' && strpos($data_str,'<id>'.$section_id.'|')!==false) $sections_name=$data[8];}
		}
		fclose($fp);
	}
	return $sections_name;
}
function duplicated_user($user) 
{
	global $admin_username;
	$existing_users_arr=array();
	$existing_users=db_get_users();
	$selfreg_users=db_get_users('selfreg_users');
	
	if($admin_username==$user) return true;
	if(strpos($existing_users,'username="'.$user.'"')!==false) return true;
	elseif(strpos($selfreg_users,'username="'.$user.'"')!==false) return true;
	else return false; 	
}
function error() 
{	
	global $ca_lang_l,$f_br;

	if(isset($_GET['ref_url']) && $_GET['ref_url']!='') $contents=build_login_form('',urldecode($_GET['ref_url'])); //event manager
	else $contents=build_login_form();

	if(strpos($contents,'<!--[error_message]')!==false)
	{
		$pattern=f_GFS($contents,'[error_message]','[/error_message]');
		if($pattern!='')
		{
			if(isset($_GET['extcall'])) $pattern="<div class='rvps1'><h5>".$pattern."</h5></div>";
			else 
			{
    		$contents=str_replace('<!--[error_message]',"<div class='rvps1'><h5>",$contents);
				$contents=str_replace(f_GFSAbi($contents,'[/error_message]','-->'),$f_br.$f_br."</h5></div>",$contents);
			}
		}
		else 
		{
			$pattern='<div class="rvps1"><h5>'.$ca_lang_l['use correct username'].$f_br.$f_br.'</h5></div>';
			$contents=str_replace(f_GFSAbi($contents,'<!--[error_message]','-->'),$pattern,$contents);
		}
	}
	else {$contents=str_replace('<!--page-->','<!--page-->'.'Error occured. '.$ca_lang_l['use correct username'],$contents);}
	if(isset($_GET['extcall'])) $contents=GT($pattern);
	echo $contents;
	exit;
}
function checkauth($user,$pawd,$non_pass_flag=false) 
{
	global $thispage_id;
	$auth=false; 
	$section_flag=false;
	$write_flag=false;
	$no_access=false;
	$user_account=db_get_specific_user($user);
	$prot_page_info=get_page_info($thispage_id);
	if(isset($prot_page_info[1])) $prot_page_name=$prot_page_info[1]; //path

	if(!empty($user_account) && isset($prot_page_info[1]))
	{
		$pass=$user_account['password'];
		if($user_account['access'][0]['section']!='ALL')
		{
			foreach($user_account['access'] as $k=>$v)
			{
				if($prot_page_info[7]==$v['section'])
				{
					$section_flag=true;
					if($v['type']=='1')	$write_flag=true;
					elseif($v['type']=='2' && isset($v['page_access']))
					{
						foreach($v['page_access'] as $key=>$val)
						{
							if($thispage_id==$val['page'] && $val['type']=='1') {$write_flag=true;break;}
							elseif($thispage_id==$val['page'] && $val['type']=='2') {$no_access=true;break;}
						}
					} 
					break;
				}
			}				
		}
		else 
		{
			$section_flag=true; 
			if($user_account['access'][0]['type']=='1')	$write_flag=true; 
		}
		if($user_account['username']==$user && ($pass==crypt($pawd,$pass) || $non_pass_flag) && $section_flag===true)   
		{
			if(!isset($_GET['indexflag']) && $no_access==false) $auth=true;
			elseif($write_flag==true) $auth=true;
		}
  }
  return $auth;
}
// ------------- admin
function index($action_id) // site map screen
{	
	global $sp_pages_ids,$counter_ts_db_fname,$counter_ds_db_fname,$pref_dir,$template_in_root,$ca_lang_l,$l,$f_br,
        $f_hr,$f_fmt_caption,$f_open_table_tag,$ca_td,$ca_span8,$f_max_chars;
	
	$body_section=''; 
	$os=array('Other','Win95','Win98','WinNT','W2000','WinXP','W2003','WinVista','Linux','Mac','Windows'); 
	$browsers=array('Other','IE','Opera','Firefox','Netscape','AOL','Safari','Konqueror','IE5','IE6','IE7','Opera7','Opera8','Firefox 1','Firefox 2','Netscape 6', 'Netscape 7','Firefox 3','Chrome','IE8'); 
	$counter_on=file_exists($counter_ts_db_fname)&&(filesize($counter_ts_db_fname)!==0);
	
	if(isset($_GET['stat']) && $_GET['stat']='detailed') // COUNTER detailed stat
	{
		$records=array();
    $all_records=array();
    $max_rec_on_page=20;
    $screen=(isset($_GET['page'])? $_GET['page']: 1);
    $p=(isset($_GET['pid']))?$_GET['pid']:''; 	  
	
		if(file_exists($counter_ds_db_fname)&&(filesize($counter_ds_db_fname)>0))
		{
			$fp=fopen($counter_ds_db_fname, 'r');
			$php_start_line=fgetcsv($fp, $f_max_chars);
			while($data=fgetcsv($fp, $f_max_chars,'|'))  if($data[0]==$p || $p=='') $all_records[]=$data;			
			fclose($fp);						
		}
			
		$records_count=count($all_records); 	
		$all_records=array_reverse($all_records); 

		$offset=($screen==1)?0:($screen-1)*$max_rec_on_page;
		$limit_rec_to=($screen*$max_rec_on_page>$records_count)?$max_rec_on_page-($screen*$max_rec_on_page-$records_count):$max_rec_on_page;
		
		$records=array_slice($all_records,$offset,$limit_rec_to); 
		$all_records=array();
		
		if(isset($_GET['pid']))
		{
			if($template_in_root) $purl=str_replace('../','',$_GET['purl']);
			else $purl=(strpos($_GET['purl'], '../')===false)?'../'.$_GET['purl']:$_GET['purl']; 
		} 

		$url_part=$pref_dir."centraladmin.php?process=index&amp;stat=detailed&amp;".$l."&amp;"
		.(isset($_GET['pid'])? "&amp;pid=".$_GET['pid']."&purl=".$purl."&pname=".$_GET['pname']: '');

		$body_section.=f_fmt_admin_title(ucfirst($ca_lang_l['site map']).' >> '.ucfirst($ca_lang_l['detailed stat']).' '.(isset($_GET['pid'])?' <a class="rvts12" href="'.$_GET['purl'].'" title="'.$purl.'">'.$_GET['pname'].'</a> page':'')).$f_br.$f_br;
		$labels=array('first'=>$ca_lang_l['first'], 'prev'=>$ca_lang_l['prev'], 'next'=>$ca_lang_l['next'], 'last'=>$ca_lang_l['last']);

		$body_section.=$f_open_table_tag."<tr><td colspan='6'>";
		$body_section.=f_page_navigation($records_count, $url_part, $max_rec_on_page, $screen, $ca_lang_l['of'], "class='rvts12'", $labels);
		$body_section.="</td></tr><tr>".$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['date'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption,ucfirst($ca_lang_l['time'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption,ucfirst($ca_lang_l['browser'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption,ucfirst($ca_lang_l['os'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption,ucfirst($ca_lang_l['resolution'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption,ucfirst($ca_lang_l['host'])."/".strtoupper($ca_lang_l['ip']) ."/".ucfirst($ca_lang_l['referrer'])).$f_hr."</td></tr>";
	
		foreach($records as $k=>$v) 
		{
			$fixed_date=ca_tzone_date($v[1]);
			$body_section.="<tr>".$ca_td.$ca_span8.date ('j F',$fixed_date)."</span></td>".$ca_td.$ca_span8.date ('H:i:s',$fixed_date)."</span></td>"
			.$ca_td.$ca_span8.$browsers[$v[4]]."</span></td>".$ca_td.$ca_span8.$os[$v[5]]."</span></td>".$ca_td.$ca_span8.$v[6]."</span></td>"
			.$ca_td.$ca_span8.$v[3].' ('.$v[2].') ';
			if(isset($v[7]) && $v[7]!='NA')  
				$body_section.='<a class="rvts12" href="'.$v[7].'" alt="'.$v[7].'" title="'.$v[7].'">'.ucfirst($ca_lang_l['referrer']).'</a>';
			else $body_section.=$ca_lang_l['na'];
			$body_section.='</span></td></tr>';
		}
		$body_section.='</table>';
	}	
	else
	{
		$pages_list=get_pages_list();	
		$counter_stat=f_read_tagged_data($counter_ts_db_fname,'totals'); // counter data

		$body_section.=f_fmt_admin_title($ca_lang_l['site map']).$f_br.$f_br;
		$body_section.=$f_open_table_tag."<tr>".$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['page name'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['admin link'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['protected'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['ca controlled'])).$f_hr."</td>"
		.($counter_on ? "<td colspan='2' align='left'>".sprintf($f_fmt_caption, ucfirst($ca_lang_l['pageloads'])).$f_hr."</td>":"")."</tr>"; 
		foreach($pages_list as $k=>$v) 
		{	
			if(isset($v['id']))  
			{
				if($template_in_root) $v_url=str_replace('../','',$v['url']);
				else $v_url=(strpos($v['url'],'../')===false)?'../'. $v['url']:$v['url']; 
				if($template_in_root) $supage_url=str_replace('../','',$v['subpage_url']);
				else $supage_url=(strpos($v['subpage_url'],'../')===false)?'../'. $v['subpage_url']:$v['subpage_url']; 
				
				$body_section.="<tr>".$ca_td.$ca_span8;
				if($v['subpage']=='1') 
				{
					$body_section.="&nbsp;&nbsp;&nbsp;&nbsp;- </span><a class='rvts12' href='";
					$body_section.=($v['frames']=='0' && $v['subpage']=='1')?$supage_url:$v_url;
				}			
				else 
				{
					$body_section.=":: </span><a class='rvts12' href='";
					$body_section.=($v['frames']=='0' && !empty($v['subpage_url']))?$supage_url:$v_url;
				}
				$body_section.="'>".$v['name']."</a></td>".$ca_td;

				if(in_array($v['id'],$sp_pages_ids)) 
				{
					if($template_in_root) $admin_url=str_replace('../','',$v['adminurl']);
					else $admin_url=(strpos($v['adminurl'],'../')===false)?'../'. $v['adminurl']:$v['adminurl'];
					$body_section.="<a class='rvts12' href='".$admin_url.'&'.$l."'>";
					$body_section.=($v['id']=='20')?'['.$ca_lang_l['edit'].']':'['.$ca_lang_l['admin'].']';
					$body_section.="</a>";
				}
				$body_section.="</td>".$ca_td.$ca_span8.($v['protected']=='TRUE'? '[X]': '') ."</span></td>";
				$body_section.=$ca_td.$ca_span8.(in_array($v['id'],$sp_pages_ids) || $v['protected']=='TRUE'? '[X]': '')."</span></td>";
				$body_section.=($counter_on?get_loads($counter_stat,$v['pageid'],$v_url,$v['name']):"")."</tr>"; // counter
			}
			else $body_section.="<tr>".$ca_td."<span class='rvts8'><b>".$v['name']."</b></span></td><td></td><td></td><td></td>".($counter_on?"<td> </td>":"")."</tr>";
		}
		$body_section.="<tr>".$ca_td.$f_hr.$ca_span8.":: </span><a class='rvts12' href='".$pref_dir."tell_friend.php?action=admin'>".ucfirst($ca_lang_l['tell a friend admin'])."</a></td><td>".$f_hr."&nbsp;</td><td>".$f_hr."&nbsp;</td><td align='left'>".$f_hr."[X]</td>".($counter_on?"<td colspan='2' align='left'>".$f_hr."&nbsp; </td>":"")."</tr>"; 
		if($counter_on) 
		{
			$body_section.="<tr>".$ca_td."<td></td><td></td><td colspan='3' align='left'>".$f_hr.$ca_span8.ucfirst($ca_lang_l['total pageloads']).": ".f_GFS($counter_stat,'<loads>','</loads>') ."</span>&nbsp;&nbsp;".(f_GFS($counter_stat,'<loads>','</loads>')!='0'?"<a class='rvts12' href='".$pref_dir."centraladmin.php?process=index&stat=detailed&".$l."'>[".$ca_lang_l['details']."]</a>":"")
			.$f_br.$ca_span8.ucfirst($ca_lang_l['unique visitors']).": ".f_GFS($counter_stat,'<unique>','</unique>')."</span>"
			.$f_br.$ca_span8.ucfirst($ca_lang_l['first time visitors']).": ".f_GFS($counter_stat,'<first>','</first>')."</span>"
			.$f_br.$ca_span8.ucfirst($ca_lang_l['returning visitors']).": ".f_GFS($counter_stat,'<returning>','</returning>')."</span></td></tr>";
		}
		$body_section.='</table>';
	}	
	$body_section=f_fmt_admin_screen($body_section, build_menu($action_id));
	$body_section=GT($body_section);
	print $body_section;
}
function get_loads($counter_stat,$page_id,$page_url,$page_title) // COUNTER get page loads
{	
	global $pref_dir,$ca_lang_l,$l;
	if(strpos($counter_stat, "<l_$page_id>")!==false)
		$page_total='<td align="left"><span class="rvts8">'.f_GFS($counter_stat, "<l_$page_id>","</l_$page_id>")."</span></td><td align='right'><a class='rvts12' href='".$pref_dir."centraladmin.php?process=index&stat=detailed&".$l."&pid=".$page_id."&purl=".$page_url."&pname=".$page_title. "'>[".$ca_lang_l['details']."]</a></td>";
	else $page_total='<td align="left"><span class="rvts8">'.$ca_lang_l['na'].'</span></td><td></td>';
	return $page_total;
}
function manage_users($action_id) 
{
	global $access_type, $pref_dir, $ca_lang_l, $l, $access_type_ex, $f_br, $f_hr, $f_fmt_caption, $f_open_table_tag, $f_ct, $ca_td, $ca_span8;
		
	$users=db_get_users();
	$users_array=($users!='')?f_format_users($users):array();
	if(count($users_array)>1)
	{
		foreach ($users_array as $key => $row) $name[$key]=$row['username'];
		$name_lower=array_map('strtolower',$name);
		array_multisort($name_lower,SORT_ASC,$users_array); 
	}
		
	$output=f_fmt_admin_title(ucfirst($ca_lang_l['manage users'])).$f_br.$f_br.$f_open_table_tag;
  $base=$_SERVER['PHP_SELF'];//m
	$output.="<tr><td colspan='3' style='text-align:center;'><input class='input1' type='button' value=' ".ucfirst($ca_lang_l['add user'])." ' onclick=\"document.location='".$base."?process=processuser&amp;".$l."'\"".$f_ct." <input class='input1' type='button' value=' ".ucfirst($ca_lang_l['non-confirmed users'])." ' onclick=\"document.location='".$base."?process=pendingreg&amp;".$l."'\"".$f_ct." <input class='input1' type='button' value=' ".ucfirst($ca_lang_l['export'])." ' onclick=\"document.location='".$base."?process=export&amp;".$l."'\"".$f_ct."</td></tr>";//m
	if(!empty($users_array))
	{
		$output.="<tr>".$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['user'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['edit'])."/".ucfirst($ca_lang_l['remove'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['access to'])).$f_hr."</td><td></td></tr>";
		foreach($users_array as $key=>$value)
		{
			if(!empty($value)) 
			{
				$output.="<tr>".$ca_td.$ca_span8.($key+1).". ".$value['username']."</span></td>";						
				$output.=$ca_td."<a class='rvts12' href='".$pref_dir."centraladmin.php?process=processuser&amp;editaccess=".$value['username']
				."&amp;".$l."'>[".$ca_lang_l['edit access']."]</a> <a class='rvts12' href='".$pref_dir ."centraladmin.php?process=processuser&amp;editdetails=".$value['username']."&amp;".$l."'>[".$ca_lang_l['details']."]</a>"
				." <a class='rvts12' href='".$pref_dir."centraladmin.php?process=processuser&amp;editpass=".$value['username'] ."&amp;".$l."'>[".$ca_lang_l['password']."]</a> <a class='rvts12' href='".$pref_dir ."centraladmin.php?process=processuser&amp;removeuser=".$value['username']."&amp;".$l."' onclick=\"javascript:return confirm('".ucfirst($ca_lang_l['remove MSG'])."')\"> ".$ca_lang_l['remove']."</a></td>";
				$output.='<td colspan="2"><table style="width:100%">';
				if(!isset($value['access'])) 
				{
					$output.="<tr><td>".$ca_span8.strtoupper($ca_lang_l['all']).' ('.$access_type[$v['type']].')'
					."</span></td><td></td></tr>";					
				}
				else 
				{
					foreach($value['access'] as $k=>$v) //ALL-write
					{
						if($v['section']=='ALL') {$output.="<tr>".$ca_td.$ca_span8.strtoupper($ca_lang_l['all']).' ('.$access_type_ex[$v['type']].')'
							."</span></td><td></td></tr>"; }
						else 
						{
							$section_name=get_section_name ($v['section']);
							if(empty($section_name)) $section_name=$v['section'];
							$output.="<tr>".$ca_td.$ca_span8.$section_name.' ('.$access_type_ex[$v['type']].')'."</span></td><td align='right'><a class='rvts12' href='".$pref_dir."centraladmin.php?process=processuser&amp;checksection=" .$v['section']."&amp;username=".$value['username']."&amp;".$l."'>[".$ca_lang_l['check range']."]</a></td></tr>";
						}
					}				
				}
				$output.='</table></td></tr>';
			}
		}
	}
	else $output.="<tr><td colspan='2' align='center'>".$ca_span8.ucfirst($ca_lang_l['none users'])."</span></td></tr>";
	$output.="</table>";
	$output=f_fmt_admin_screen($output, build_menu($action_id));
	$output=GT($output);
	print $output;
}
function process_users($action_id)  //process add/edit/remove user
{
	global $ca_lang_l,$ca_user_msg,$f_fmt_span8em;
	
	$output='';$sections='';$details='';$news='';
	
	if(isset($_POST["select_all"]) && $_POST["select_all"]=='no') 
	{					
		if(isset($_POST["selected_sections"])) 
		{
			foreach($_POST["selected_sections"] as $k=>$v) // to each section from section_list --> access_type assigned
			{
				$a_type=(isset($_POST["access_type".$v])? $_POST["access_type".$v]: "");
				$sections.='<access id="'.($k+1).'" section="'.$v.'" type="'.$a_type.'">';
				if($a_type=='2') 
				{
					$section_range=get_prot_pages_list($v);
					foreach($section_range as $key=>$val) 
					{
						$pid=$val['id'];
						if(isset($_POST["access_to_page".$pid])) 
							$sections.='<p id="'.($key+1).'" page="'.$pid.'" type="'.$_POST["access_to_page".$pid].'">';
					}
				}
				$sections.='</access>';
			}
		}
		else {$sections.='<access id="1" section="ALL" type="0"></access>';}
	}
	elseif(isset($_POST["select_all"]) && $_POST["select_all"]=='yesw') {$sections.='<access id="1" section="ALL" type="1"></access>';} //ALL-write
	else {$sections.='<access id="1" section="ALL" type="0"></access>';} //ALL-read
	
	if(isset($_POST["email"]) || isset($_POST["name"]) || isset($_POST["sirname"])) //details
	{
		$details.='<details email="'.$_POST["email"].'" name="'.$_POST["name"].'" sirname="'.$_POST["sirname"].'"';
	}
	else $details.='<details email="" name="" sirname=""';
	$details.=(isset($_POST["creation_date"]))?' date="'.$_POST["creation_date"].'"':' date="'.mktime().'"';
	$details.=(isset($_POST["sr"]))?' sr="'.$_POST["sr"].'"':' sr="0"';
	$details.='></details>';

	if(isset($_POST["news_for"])) //news - event manager
	{
		foreach($_POST["news_for"] as $k=>$v) 
		{ 
			if(strpos($v,'%')!==false) list($p,$c)=explode('%',$v);
			else {$p=$v;$c='';}
			$news.='<news id="'.($k+1).'" page="'.$p.'" cat="'.$c.'"></news>';
		}
	}
	
	if(isset($_POST['save'])) 
	{
		$username=(isset($_POST['username'])? $_POST['username']: "");
		$flag=(isset($_POST['flag'])? $_POST['flag']: "");			
		if($flag=='add' && !preg_match("/^[A-Za-z_0-9]+$/",$_POST['username'])) 
        $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['can contain only'])));
		elseif(($flag=='add'|| $flag=='editdetails') && empty($_POST['username'])) 
        $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['fill in']).' '.ucfirst($ca_lang_l['username'])),$username);
		elseif(($flag=='add'|| $flag=='editdetails' && $_POST['username']!=$_POST['old_username']) && duplicated_user($_POST['username'])) 
		    $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['username exists'])));
		elseif(($flag=='editpass'||$flag=='add') && empty($_POST['password'])) 
      	$output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['fill in']).' '.ucfirst($ca_lang_l['password'])),$username);
		elseif(($flag=='add'|| $flag=='editpass') && empty($_POST['repeatedpassword']))   
        $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['repeat password'])),$username);
		elseif(($flag=='add'|| $flag=='editpass') && $_POST['password']!=$_POST['repeatedpassword']) 
		    $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['password and repeated password'])),$username);
		elseif( ($flag=='add'|| $flag=='editpass') && strlen(trim($_POST['password']))<5) 
		    $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['your password should be'])),$username);
		elseif(($flag=='add'|| $flag=='editpass') && strtolower($_POST['username'])=='admin' && strtolower($_POST['password'])=='admin') 
        $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,$ca_user_msg),$username);
		elseif(($flag=='add'|| $flag=='editaccess') && $_POST["select_all"]=='no' && !isset($_POST["selected_sections"])) 
        $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['select access'])),$username);
		elseif(($flag=='add'|| $flag=='editdetails') && !empty($_POST["email"]) && !f_validate_email($_POST["email"])) 
        $output.=build_add_user_form($flag,sprintf($f_fmt_span8em,ucfirst($ca_lang_l['nonvalid email'])),$username);
		else 
		{	
			if($flag=='add')			db_write_user('add',$username,crypt($_POST['password']),$sections,$details,$news);	// ADD USER	
			elseif($flag=='editpass')	db_write_user('editpass',$username,crypt($_POST['password'])); // CHANGE PASS
			elseif($flag=='editaccess') db_write_user('editaccess',$username,'',$sections); // CHANGE ACCESS 
			elseif($flag=='editdetails')  db_write_user('editdetails',$_POST['old_username'],'','',$details,$news);	// CHANGE DETAILS 
			manage_users($action_id); 
      exit;
		}
	}
	elseif(isset($_GET['editaccess']))			// SHOW CHANGE ACCESS FORM
	{
		$username=$_GET['editaccess'];		
		$user_data=db_get_specific_user($username);				
		$output.=build_add_user_form('editaccess',f_fmt_admin_title($ca_lang_l['edit access']),$username,$user_data['access']);
	}
	elseif(isset($_GET['editdetails']))		// SHOW CHANGE DETAILS FORM
	{
		$username=$_GET['editdetails'];		
		$user_data=db_get_specific_user($username);
		$output.=build_add_user_form('editdetails',f_fmt_admin_title($ca_lang_l['edit details']),$username,$user_data);
	}
	elseif(isset($_GET['editpass']))		//SHOW CHANGE PASS FORM
	{
		$username=$_GET['editpass'];		
		$output.=build_add_user_form('editpass',f_fmt_admin_title($ca_lang_l['edit password']),$username);
	}
	elseif(isset($_GET['removeuser'])) 
	{
		$username=$_GET['removeuser'];
		db_remove_user($username);		// REMOVE USER
		manage_users($action_id);
		exit;
	}
	elseif(isset($_GET['checksection']))   //CHECK SECTION RANGE
	{
		$section_id=$_GET['checksection'];
		$username=(isset($_GET['username'])?$_GET['username']:'');
		$output.=check_section_range(1,$section_id,$username);
	}
	else $output.=build_add_user_form('add',f_fmt_admin_title($ca_lang_l['add user']));
	
	$output=f_fmt_admin_screen($output, build_menu($action_id));
	$output=GT($output);
	print $output;
}
function check_section_range($standalone,$section_id,$username='') // check section range screen
{
	global $template_in_root, $ca_lang_l, $access_type, $sp_pages_ids, $f_br;

	$section_range=get_prot_pages_list($section_id);
	$section_name=get_section_name($section_id);
	
	if($username!='')
	{
		$user_data=db_get_specific_user($username);	
		if(!empty($user_data))
		{
			foreach($user_data['access'] as $k=>$v) 
			{
				if($v['section']==$section_id && $v['type']=='2') { $page_access=$v['page_access']; break; }
				elseif($v['section']==$section_id)  { $a_type=$v['type']; break; }
			}
		}
		if(isset($page_access)) foreach($page_access as $k=>$v) { $access_by_page[$v['page']]=$v['type']; }
	}
	$legend="<span class='rvts8'>".ucfirst($ca_lang_l['access on page'])."</span>";
	if($standalone)
	{
		$body_section="<div align='center'><div style='width:350px' align='left'><p class='rvps1'>"
		.f_fmt_admin_title($ca_lang_l['check range'])."</p>".$f_br;
		$legend="<span class='rvts8'>".ucfirst($ca_lang_l['section']).": ".$section_name."</span>";
	}
	else $body_section="<div style='width:350px;'><div style='padding-left:25px;' align='left'>";
	$pro='';$unpro='';
	$line="<div style='position:relative;'><div style='padding-left:10px;'>:: <a class='rvts12' target='_blank' title='%s' href='%s'>%s</a></div><div style='position:absolute;right:10px;width:120px;top:0px' align='right'>%s</div>";
	foreach($section_range as $k=>$v)
	{	
		if($template_in_root)	$fixed_url=str_replace('../','',$v['url']);
		elseif(strpos($v['url'],'/')!==false)	$fixed_url=$v['url'];
		else	$fixed_url='../'.$v['url'];

		$url=str_replace('..','',$v['url']);
		if($v['protected']=='TRUE' && in_array($v['typeid'],$sp_pages_ids)) { $access_type_f=array('0'=>'read','1'=>'read&write','2'=>'no access'); }
		elseif($v['protected']=='TRUE' && !in_array($v['typeid'],$sp_pages_ids)) $access_type_f=array('0'=>'read','2'=>'no access');
		else $access_type_f=$access_type;
	
		if(!$standalone) 
		{ 
			$default=(isset($access_by_page)&&isset($access_by_page[$v['id']]))?$access_by_page[$v['id']]:'0';
			$combo=f_build_select('access_to_page'.$v['id'],$access_type_f,$default,'style="width: 90px"'); 
		}
		elseif(isset($access_by_page)) { $combo="<span class='rvts8'>[ ".(isset($access_by_page[$v['id']]) && isset($access_type_f[$access_by_page[$v['id']]])? $access_type_f[$access_by_page[$v['id']]]: $access_type["0"])." ]</span>"; }
		else $combo="<span class='rvts8'>[ ".(isset($a_type)? $access_type[$a_type]: "")." ]</span>";

		if($v['protected']=='TRUE')	$pro.=sprintf($line,$url,$fixed_url,$v['name'],$combo);
		elseif($v['protected']=='FALSE') $unpro.=sprintf($line,$url,$fixed_url,$v['name'],$combo);	
		
	}
	$line="<fieldset><legend>%s</legend><span class='rvts8'>%s</span>".$f_br."%s".$f_br."<span class='rvts8'>%s</span>".$f_br."%s</fieldset>";
	$body_section.=sprintf($line,$legend,$f_br.ucfirst($ca_lang_l['protected pages']),$pro,ucfirst($ca_lang_l['non-protected pages']),$unpro);

	if($standalone)$body_section.=$f_br.'<a class="rvts12" href="javascript:history.back();">['.$ca_lang_l['back']."]</a>";
	return $body_section.'</div></div>';
}
function check_pending_users($action_id,$msg='')
{
	global $pref_dir,$ca_lang_l,$l,$f_lf,$http_prefix,$f_br,$f_hr,$f_fmt_caption,$f_open_table_tag,$ca_td,$ca_span8;
	
	if(isset($_GET['removeuser']))   // REMOVE USER
	{
		$user_id=$_GET['removeuser'];
		db_remove_user($user_id,'selfreg_users');
		$msg=$f_br.ucfirst($ca_lang_l['user removed']);
	}
	$users=db_get_users('selfreg_users');
	$users_array=($users!='')?f_format_users($users):array();

	if(isset($_GET['resend']))   // RE_SEND CONFIRMATION EMAIL TO USER
	{
		$user_id=$_GET['resend'];
		foreach($users_array as $k=>$v) { if($v['id']==$user_id) { $user_info = $v; break; } } 
		
		$link=$http_prefix.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/centraladmin.php?id='.$user_id.'&process=register&'.$l;
		$content=str_replace(array("##","%CONFIRMLINK%"),array('<br>','<a href="'.$link.'">'.$link.'</a>'),$ca_lang_l['sr_email_msg']);
		$content=str_replace(array('%%username%%','%%USERNAME%%','%%site%%'),array($v['username'],$v['username'],$_SERVER['HTTP_HOST']),$content);
		$content_text=str_replace("##",$f_lf,$ca_lang_l['sr_email_msg']); 
		$content_text=str_replace("%%site%%", $_SERVER['HTTP_HOST'], $content_text); 
		$content_text=str_replace(array('%%username%%','%%USERNAME%%',"%CONFIRMLINK%"),array($v['username'],$v['username'],$link),$content_text);
		$subject=str_replace('%%site%%',$_SERVER['HTTP_HOST'],$ca_lang_l['sr_email_subject']);
		$send_to_email=$v["details"]["email"];
		$log_data='USER:'.$v['username'].' EMAIL:'.$v["details"]["email"];
		$log_msg='success';
	
		$result=send_mail_ca($content,$content_text,$subject,$send_to_email);
		if($result) 
    {
      $log_msg .= ", email SENT"; 
		  $msg = $f_br.ucfirst($ca_lang_l['email resent']).' '.strtoupper($v['username']);
    }
		else 
		{
			$log_msg.=", email FAILED"; 
			$msg='Email FAILED. Try again.'; 
		}
		write_log('resend',$log_data,$log_msg);			
	} 
	
	$output=f_fmt_admin_title($ca_lang_l['non-confirmed users']).($msg!=''? $f_br.'<span class="rvts8">'.$msg.'</span>': '').$f_br.$f_br; 
	if(!empty($users_array))
	{
		$output.=$f_open_table_tag."<tr>".$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['user'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['name'])."/".ucfirst($ca_lang_l['surname'])."/".ucfirst($ca_lang_l['email'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['remove'])).$f_hr."</td>"
		.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['confirmation email'])).$f_hr."</td></tr>";
		foreach($users_array as $key=>$value)
		{
			if(!empty($value)) 
			{						
				$output.="<tr>".$ca_td.$ca_span8.$value['username']."</span></td>".$ca_td.$ca_span8.strtoupper($value['details']['name'])." ".strtoupper($value['details']['sirname'])." ".$value['details']['email']."</span></td>";
				$output.=$ca_td."<a class='rvts12' href='".$pref_dir."centraladmin.php?process=pendingreg&amp;removeuser=" .$value['id']."&amp;".$l."' onclick=\"javascript:return confirm('".ucfirst($ca_lang_l['remove MSG'])."')\"> ".$ca_lang_l['remove']."</a></td>";		
				$output.=$ca_td."<a class='rvts12' href='".$pref_dir."centraladmin.php?process=pendingreg&amp;resend=".$value['id']."&amp;".$l."' onclick=\"javascript:return confirm('".ucfirst($ca_lang_l['resend MSG']).' '.strtoupper($value['username'])." - ".$value['details']['name']." ".$value['details']['sirname']."?')\"> ".$ca_lang_l['resend']."</a> <a class='rvts12' href='".$pref_dir."centraladmin.php?process=register&amp;id=".$value['id']."&amp;flag=admin&amp;".$l."'> ".$ca_lang_l['confirm']."</a></td></tr>";	
			}
		}
		$output.="</table>";
	}
	else $output .= $ca_span8.ucfirst($ca_lang_l['none users'])."</span>"; 
	$output=f_fmt_admin_screen($output, build_menu($action_id));
	$output=GT($output);
	print $output;
}
function conf_counter($action_id)
{	
	global $db_settings_file, $pref_dir, $ca_lang_l, $l, $ima_array, $template_in_root, $f_br, $f_hr, $f_ct, $ca_template_file_f;
	$C_UNIQUE_START_COUNT=0; $C_LOADS_START_COUNT=0; $C_GRAPHICAL=1;
	$C_MAX_VISIT_LENGHT=1800; $C_NUMBER_OF_DIGITS=8; $C_DISPLAY=0;   //1- page loads; 0- unique
	
	$visit_len_list=array('1800'=>'30 min','3600'=>'1 h','7200'=>'2 h','10800'=>'3 h','216000'=>'6 h','432000'=>'12 h','864000'=>'24 h');
	$number_digits_list=array(4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10);
	$show_list=array('show unique visitors','show pageloads');
	$counter_type=array('text','graphical');
	$div1b='<div style="text-align:left;position:relative;height:25px;padding-left:150px;">';
	$div1c='<div style="text-align:left;height:25px;padding-left:129px;">';
	$div2='<div style="text-align:left;position:absolute;left:0;height:20px;width:190px;top:0px"><span class="rvts8">';
	$css_ima='style="position:absolute;" ';

	if(!isset($_POST['save']))
	{
		$settings=f_read_tagged_data($db_settings_file,'counter');
		$max_visit_len=(strpos($settings,'<max_visit_len>')!==false)?f_GFS($settings,'<max_visit_len>','</max_visit_len>'):$C_MAX_VISIT_LENGHT;
		$number_of_digits=(strpos($settings,'<number_digits>')!==false)?f_GFS($settings,'<number_digits>','</number_digits>'):$C_NUMBER_OF_DIGITS;
		$size=(strpos($settings,'<size>')!==false)?f_GFS($settings,'<size>','</size>'):1;
		$display=(strpos($settings,'<display>')!==false)?f_GFS($settings,'<display>','</display>'):$C_DISPLAY;
		$loads_start_count=(strpos($settings,'<loads_start_value>')!==false)?f_GFS($settings,'<loads_start_value>','</loads_start_value>'):$C_LOADS_START_COUNT;
		$unique_start_count=(strpos($settings,'<unique_start_value>')!==false)?f_GFS($settings,'<unique_start_value>','</unique_start_value>'):$C_UNIQUE_START_COUNT;
		$graphical=(strpos($settings,'<graphical>')!==false)?f_GFS($settings,'<graphical>','</graphical>'):$C_GRAPHICAL;

		$output='<div align="center"><div style="width:350px;">';	
		$output.="<form name='frm' action='".$pref_dir."centraladmin.php?process=confcounter&amp;".$l."' method='post' enctype='multipart/form-data'>".f_fmt_admin_title($ca_lang_l['counter settings'])."".$f_br.$f_br;

		$s=(isset($_GET['size'])?$_GET['size']:$size);

		$output.=$f_br.$div1b.f_build_select('display',$show_list,(isset($_GET['display'])?$_GET['display']:$display)).$div2 .ucfirst($ca_lang_l['display']).'</span>'.'</div></div>';
		$output.=$div1b.f_build_select('number_digits',$number_digits_list,(isset($_GET['num_digits'])?$_GET['num_digits']:$number_of_digits-1)).$div2 .ucfirst($ca_lang_l['number of digits'])."</span>"."</div></div>";
		$output.=$div1b.f_build_select('max_visit_len',$visit_len_list,(isset($_GET['v_length'])?$_GET['v_length']:$max_visit_len)) .$div2 .ucfirst($ca_lang_l['maximum visit length'])."</span>"."</div></div>";	
		$output.=$div1b.f_build_input('u_st_count',(isset($_GET['u_offset'])?$_GET['u_offset']:$unique_start_count),'','','text','size="10"').$div2.ucfirst($ca_lang_l['unique start offset']).'</span>'.'</div></div>';
    $output.=$div1b.f_build_input('l_st_count',(isset($_GET['l_offset'])?$_GET['l_offset']:$loads_start_count),'','','text','size="10"').$div2.ucfirst($ca_lang_l['pageloads start offset']).'</span>'.'</div></div>';   		
		$output.=$div1b.f_build_select('graphical',$counter_type,(isset($_GET['graphical'])?$_GET['graphical']:$graphical)).$div2 .ucfirst($ca_lang_l['counter type']).'</span>'.'</div></div>';

		$inp=$div1c.'<input type="radio" name="size" value="%s" %s'.$f_ct.'<img '.$css_ima.'src="'.($template_in_root? '': '../').'ezg_data/c%s.gif" alt=""'.$f_ct.'</div>';    
		$cnt=count($ima_array)+1;for($i=1;$i<$cnt;$i++) $output.=sprintf($inp,$i,($s==$i)?'checked="checked"':'',$i);
  
		$output.=$f_br.'<input class="input1" name="save" type="submit" value="'.ucfirst($ca_lang_l['submit']).'"'.$f_ct." <input class='input1' type='button' value=' ".ucfirst($ca_lang_l['cancel'])
	." ' onclick=\"javascript:history.back();\"".$f_ct.$f_hr.'</form>';
		$output.="<span class='rvts8'>:: </span><a class='rvts12' href='".$pref_dir."centraladmin.php?process=resetcounter&".$l."'>".$ca_lang_l['reset counter']."</a><span class='rvts8'> ::</span></div></div>";
	}
	else 
	{
		$newsettings='<max_visit_len>'.$_POST['max_visit_len'].'</max_visit_len><graphical>'.$_POST['graphical'].'</graphical>'
		.'<number_digits>'.($_POST['number_digits']+1).'</number_digits><size>'.$_POST['size'].'</size><display>'.$_POST['display'].'</display>'
		.'<loads_start_value>'.$_POST['l_st_count'].'</loads_start_value><unique_start_value>'.$_POST['u_st_count'].'</unique_start_value>';
		$re=f_write_tagged_data('counter', $newsettings, $db_settings_file, $ca_template_file_f);
		$output="<span class='rvts8'>";
		$output.=($re==true)?ucfirst($ca_lang_l['settings saved']):"Settings not saved. ERROR.";
		$output.="</span>".$f_br.$f_br; 		
	}
	$output=f_fmt_admin_screen($output, build_menu($action_id));
	$output=GT($output);
	print $output;
}
function conf_registration($action_id)
{
	global $db_settings_file,$pref_dir,$ca_lang_l,$l,$access_type,$f_br,$f_ct,$trtdsp,$sptdtr,$ca_template_file_f;
	
	$admin_email=''; $sendmail_from=''; $return_path=''; $terms_url='';	
	$notes=''; $access_str=''; $access=array(); $output='';
	
	if(!isset($_POST['save']))
	{
		$settings=f_read_tagged_data($db_settings_file,'registration');
		if(strpos($settings,'<admin_email>')!==false)	$admin_email=f_GFS($settings,'<admin_email>','</admin_email>');
		if(strpos($settings,'<sendmail_from>')!==false)	$sendmail_from=f_GFS($settings,'<sendmail_from>','</sendmail_from>');
		if(strpos($settings,'<return_path>')!==false)	$return_path=f_GFS($settings,'<return_path>','</return_path>');
		if(strpos($settings,'<terms_url>')!==false)		$terms_url=f_GFS($settings,'<terms_url>','</terms_url>');
		if(strpos($settings,'<notes>')!==false)			$notes=f_GFS($settings,'<notes>','</notes>');
		if(strpos($settings,'<access>')!==false)		$access_str=f_GFS($settings,'<access>','</access>');
		if($access_str!='')	$temp_access=explode('|',$access_str);
		if(isset($temp_access)) { foreach($temp_access as $k=>$v) { $t=explode('%%',$v); $access[]=array('section'=>$t[0],'type'=>$t[1]); } }

		$output.="<form name='frm' action='".$pref_dir."centraladmin.php?process=confreg&amp;".$l."' method='post' enctype='multipart/form-data'>";
		$output.=f_fmt_admin_title(ucfirst($ca_lang_l['registration settings'])).$f_br.$f_br."<table width='70%' align='center'>"; 
		
		$admin_email_value=(isset($_GET['admin_email'])?$_GET['admin_email']:$admin_email);
		$output.=$trtdsp.ucfirst($ca_lang_l['admin email']).'</span></td><td align="left">'
		.'<input class="input1" type="text" name="admin_email" value="'.$admin_email_value.'" style="width:450px"'.$f_ct.'</td></tr><tr><td></td><td align="left"><span class="rvts8"><i>'.(empty($admin_email_value)? "<em style='color:red;'>":'').ucfirst($ca_lang_l['confreg_msg2']).(empty($admin_email_value)? "</em>":'').'</i>'.$sptdtr;

		$output.='<tr><td align="left" width="130px"><span class="rvts8">'.ucfirst($ca_lang_l['terms url']).'</span></td>'
		.'<td align="left"><input class="input1" type="text" name="terms_url" value="'.(isset($_GET['terms_url'])?$_GET['terms_url']:$terms_url).'" style="width:450px"'.$f_ct.'</td></tr><tr><td></td><td align="left"><span class="rvts8"><i>'.ucfirst($ca_lang_l['confreg_msg1']).'</i>'.$sptdtr;

		$output.=$trtdsp.ucfirst($ca_lang_l['notes']).'</span></td><td align="left">'
		.'<textarea class="input1" name="notes" style="width:450px" cols="20" rows="5">'.(isset($_GET['notes'])?$_GET['notes']:$notes). '</textarea></td></tr><tr><td></td><td align="left"><span class="rvts8"><i>'.ucfirst($ca_lang_l['confreg_msg5']).'</i>'.$sptdtr;  //m

		$output.=$trtdsp.ucfirst($ca_lang_l['sendmail from']).'</span></td><td align="left">'
		.'<input class="input1" type="text" name="sendmail_from" value="'.(isset($_GET['sendmail_from'])?$_GET['sendmail_from']:$sendmail_from).'" style="width:450px"'.$f_ct.'</td></tr><tr><td></td><td align="left"><span class="rvts8"><i>'.ucfirst($ca_lang_l['confreg_msg3']).'</i>'.$sptdtr;

		$output.=$trtdsp.ucfirst($ca_lang_l['return path']).'</span></td><td align="left">'
		.'<input class="input1" type="text" name="return_path" value="'.(isset($_GET['return_path'])?$_GET['return_path']:$return_path).'" style="width:450px"'.$f_ct.'</td></tr><tr><td></td><td align="left"><span class="rvts8"><i>'.ucfirst($ca_lang_l['confreg_msg4']).'</i>'.$sptdtr;

		$section_list=get_sections_list();
		$section_id=array();
		$section_access=array();
		$output.="<tr><td colspan='2' align='left'><fieldset><legend><span class='rvts8'>".ucfirst($ca_lang_l['access to'])."</span></legend>";  //m
		$output.="<input type='radio' name='select_all' value='yes' "
		.(empty($access) || $access[0]['section']=='ALL' && $access[0]['type']=='0'?"checked='checked'":"")
		.$f_ct." <span class='rvts8'>".strtoupper($ca_lang_l['all'])." (".$access_type['0'].") </span>".$f_br;
		$output.="<input type='radio' name='select_all' value='yesw' "
		.(!empty($access) && $access[0]['section']=='ALL' && $access[0]['type']=='1'?"checked='checked'":"")
		.$f_ct." <span class='rvts8'>".strtoupper($ca_lang_l['all'])." (".$access_type['1'].") </span>".$f_br;
		
		if(!empty($section_list)) 
		{
			$output.="<input type='radio' name='select_all' value='no' "
			.(!empty($access) && $access[0]['section']!='ALL'?"checked='checked'":"").$f_ct."<span class='rvts8'> ".ucfirst($ca_lang_l['selected'])." </span>".$f_br;
		}
		else {$output.=$f_br."<span class='rvts8'>".ucfirst($ca_lang_l['adduser_msg1'])."</span>";}
			
		if($access!='')
		{
			foreach($access as $k=>$v) { $section_id []=$v['section']; $section_access []= $v['type']; }
		}
		elseif(!empty($_POST["selected_sections"]))
		{
			foreach($_POST["selected_sections"] as $k=>$v) { $section_id []=$v; $section_access []= $_POST["access_type".$v]; }
		}
		foreach($section_list as $k=>$v)
		{
			$sec_id=str_replace('<id>','',$v[10]);
			$sec_name=$v[8];	
			$key_of_access=array_search($sec_id,$section_id);
			if($key_of_access!==false) {$t=$section_access[$key_of_access]; settype($t,'integer');}
			
			$output.="&nbsp;&nbsp;&nbsp;<input type='checkbox' name='selected_sections[]' value='".$sec_id."' ";

			if(in_array($sec_id,$section_id) ) {$output.=" checked='checked'";}

			$output.=$f_ct." <span class='rvts8'>".$sec_name."</span>&nbsp;&nbsp;<a class='rvts12' href='".$pref_dir."centraladmin.php?process=processuser&amp;checksection=".$sec_id."&amp;".$l."'>[".$ca_lang_l['check range']."]</a>&nbsp;&nbsp;"
			. f_build_select('access_type'.$sec_id,$access_type,(isset($key_of_access) && $key_of_access!==false && $key_of_access!==NULL?$t:"0")) ."&nbsp;".$f_br;
		}
		$output.=$f_br.$f_br."<span class='rvts8'><b>".ucfirst($ca_lang_l['read'])."</b></span><span class='rvts8'> - ".ucfirst($ca_lang_l['adduser_msg2']).'</span>'.$f_br."<span class='rvts8'><b>".ucfirst($ca_lang_l['read&write'])."</b></span><span class='rvts8'> - ".ucfirst($ca_lang_l['adduser_msg3'])."</span>".$f_br."</fieldset></td></tr>"; //m 
		
		$output.="<tr><td colspan='2' align='right'>&nbsp;".$f_br."<input class='input1' name='save' type='submit'  value='".ucfirst($ca_lang_l['submit'])."'".$f_ct." <input  class='input1' type='button' value=' ".ucfirst($ca_lang_l['cancel'])
	." ' onclick=\"javascript:history.back();\"".$f_ct."</td></tr>";
		$output.='</table></form>';
	}
	else 
	{
		$newsettings='<admin_email>'.$_POST['admin_email'].'</admin_email>'
		.'<terms_url>'.$_POST['terms_url'].'</terms_url>'.'<notes>'.$_POST['notes'].'</notes>'
		.'<sendmail_from>'.$_POST['sendmail_from'].'</sendmail_from>'.'<return_path>'.$_POST['return_path'].'</return_path>';
		$sections=array();
		if(isset($_POST["select_all"]) && $_POST["select_all"]=='no') 
		{					
			if(isset($_POST["selected_sections"])) 
			{
				foreach($_POST["selected_sections"] as $k=>$v) 
				{
					$sections[]=$v.'%%'.(isset($_POST["access_type".$v])?$_POST["access_type".$v]:"0");
				}
			}
			else $sections[]="ALL%%0";
		}
		elseif(isset($_POST["select_all"]) && $_POST["select_all"]=='yesw') {$sections []= "ALL%%1";} //ALL-write
		else {$sections[]= "ALL%%0";} //ALL-read
		$newsettings.='<access>'. implode('|',$sections).'</access>'; 
		$re=f_write_tagged_data('registration',$newsettings,$db_settings_file, $ca_template_file_f);
		$output.="<span class='rvts8'>"; 
		$output.=($re==true)?ucfirst($ca_lang_l['settings saved']):"Settings not saved. ERROR.";
		$output.="</span>".$f_br.$f_br; 
	}
	$output=f_fmt_admin_screen($output, build_menu($action_id));
	$output=GT($output);
	print $output;
}		
# ----------------- build HTML functions
function GT($html_output,$include_counter_flag=false) 
{
	global $ca_template_file_f, $ca_lang_l, $template_in_root, $http_prefix, $f_ct;		
	
	$contents=f_fmt_in_template($ca_template_file_f,$html_output, '', '', true,$include_counter_flag);
	$contents=str_replace(f_GFSAbi($contents,'<title>','</title>'), '<title>'.$ca_lang_l['CENTRAL ADMIN'].'</title>', $contents);
	if($template_in_root) 
	{
		$base_dir=(isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']: $_SERVER['PHP_SELF']);	
		$contents=str_replace('</title>',
		'</title> <base href="'.$http_prefix.$_SERVER['HTTP_HOST'].str_replace('documents','',dirname($base_dir)).'"'.$f_ct,$contents);
	}
	$rnd=f_GFSAbi($contents,'<!--rnd-->','<!--endrnd-->');//miro
  $contents=str_replace($rnd,'',$contents);
	return $contents;
}
function build_login_form($ms='',$ref_url='') 
{
	global $thispage_id, $ca_lang_l, $sp_pages_ids, $sr_enable, $l, $http_prefix, $f_br, $f_ct;
	
	$contents=''; $pattern='';	
	$direct_flag=(isset($_POST['loginid']) && isset($_GET['pageid']) && !isset($_GET['indexflag']));
	$prot_page_info=($direct_flag)?get_page_info(trim($_POST['loginid'])):get_page_info($thispage_id);
	$prot_page_name=$prot_page_info[1];
	$dir=(strpos($prot_page_info[1],'../')===false)?'documents/':'../documents/';

	if($direct_flag) { $contents=f_read_file($prot_page_name); } // when login page directly accessed
	elseif(!empty($prot_page_info[7]))					// when protected page (with login defined) is accessed
	{
		$login_page_info=get_page_info($prot_page_info[7]);
		if(in_array($prot_page_info[4],array('21','130','140'))) {$login_page_name=$login_page_info[1];}
		elseif(!in_array($prot_page_info[4],$sp_pages_ids) && (strpos($prot_page_info[1],'../')===false)) 	
			{$login_page_name=str_replace('../','',$login_page_info[1]);}
		elseif(in_array($prot_page_info[4],array('133','136','137','138','143','144','20')) &&($prot_page_info[6]=='TRUE')&&(strpos($prot_page_info[1],'../')===false)) 
			{$login_page_name=str_replace('../','',$login_page_info[1]);}
		else {$login_page_name=$login_page_info[1];}		
		
		$contents=f_read_file($login_page_name);
		if(strpos($prot_page_info[1],'../')===false) $contents=str_replace('../','',$contents);

		if($ref_url!='') //event manager
		{
			$contents=str_replace(f_GFSAbi($contents,'[/error_message]','-->'),'[/error_message]--><div align="center"><span class="rvts8"><b>'.$ms.$f_br.$f_br.'</b></span></div>',$contents);
			$contents=str_replace(f_GFSAbi($contents,'centraladmin.php?pageid=','"'), 
				'centraladmin.php?pageid='.$thispage_id.($ref_url!=''?'&amp;ref_url='.urlencode($ref_url):'').'"', $contents);
		}	
		elseif(isset($_GET['indexflag'])) 
			{ $contents=str_replace(f_GFSAbi($contents,'centraladmin.php?pageid=','"'), 
				'centraladmin.php?pageid='.$thispage_id.(isset($_GET['indexflag'])?'&amp;indexflag=index':'').'"', $contents);}
	}
	else							// when protected page (without login) is accessed
	{
		$contents='<!--page--><!--[error_message]'.$ca_lang_l['use correct username'].'[/error_message]-->'
		.'<form name="login" method="post" action="'.$dir.'centraladmin.php?pageid='.$thispage_id.'&amp;'.$l; 
		$contents .= ($ref_url!=''?'&amp;ref_url='.urlencode($ref_url):'').'">';    //event manager
		$contents .= $f_br."<table align='center'><tr><td></td><td><span class='rvts8'><b>".ucfirst($ca_lang_l['ca login'])."</b></span>".$f_br." </td></tr>"
		."<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['username'])."</span></td>"
		."<td><input class='input1' type='text' name='pv_username' style='width:180px'".$f_ct."</td></tr>"
		."<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['password'])."</span></td>"
		."<td><input class='input1' type='password' name='pv_password' style='width:180px'".$f_ct."</td></tr>"
		."<tr><td></td><td><input class='input1' type='submit' name='REQUEST_SEND' value='".ucfirst($ca_lang_l['login'])."'".$f_ct."</td></tr>";
		if($sr_enable)
		{
			$contents.='<tr><td></td><td><p> '.$f_br.'<a class="rvts12" href="'.$dir.'centraladmin.php?process=forgotpass&amp;'.$l.'">' 
			.$ca_lang_l['forgot q'].'</a></p><p class="rvps1"><span class="rvts8">&nbsp;</span></p><p><a class="rvts12" href="' .$dir.'centraladmin.php?process=register&amp;'.$l.'">'.$ca_lang_l['member q'].'</a></p></td></tr>';
		}
		$contents.="</table></form><!--/page-->";
	}

	if((!isset($_GET['pageid']) || isset($_GET['indexflag']) || in_array($prot_page_info[4],array('21','130','140')) || $ref_url!='') && !$direct_flag) 
	{
		$pattern=f_GFS($contents,'method="post" action="','">');     // login form action fixation  
		if($pattern=='') $pattern=f_GFS($contents,'method=post action=','>');  
			
		if(isset($_GET['indexflag'])) {$r_with=$dir."centraladmin.php?pageid=".$thispage_id."&amp;indexflag=index&amp;".$l;}
		elseif(isset($_GET['pageid']) && (in_array($prot_page_info[4],array('21','130','140')) || $ref_url!='') )  
		{
			$r_with=$dir."centraladmin.php?pageid=".$thispage_id."&amp;".$l.($ref_url!=''?'&amp;ref_url='.urlencode($ref_url):'');
		}
		else $r_with=$prot_page_name;
		$contents=str_replace($pattern,$r_with,$contents);
		
		if(in_array($prot_page_info[4],array('136','137','138','143','144','20')))    // Special PHP pages
		{			
			if(strpos($prot_page_info[1],'../')!==false) $f_dir='../'.f_GFS($prot_page_info[1],'../','/').'/';
			elseif($prot_page_info[6]!=='TRUE') $f_dir='../';
			else $f_dir='';
			$f_dir=str_replace('//','/',$f_dir);
						
			$prot_page_name_fixed=($prot_page_info[15]=='0' && $prot_page_info[3]=='1')?$f_dir.'SUB_':$f_dir;
			$prot_page_name_fixed.=$thispage_id.($prot_page_info[6]=='TRUE'?'.php':'.html');
		}			
		elseif(in_array($prot_page_info[4],array('21','130','140')))   // shop and lister pages
		{
			$f_dir='../'.f_GFS($prot_page_info[1],'../','/').'/';
			$prot_page_name_fixed=($prot_page_info[15]=='0' && $prot_page_info[3]=='1')?$f_dir.'SUB_':$f_dir;
			$prot_page_name_fixed.=$thispage_id.'.html';
		}
		elseif($prot_page_info[4]=='133') 
		{ 
			if(strpos($prot_page_info[1], '../')!==false) $prot_page_name_fixed=$prot_page_name;
			elseif($prot_page_info[6]!=='TRUE')	$prot_page_name_fixed='../'.$prot_page_name;
			else $prot_page_name_fixed=$prot_page_name;
			$prot_page_name_fixed=str_replace('//','/',$prot_page_name_fixed);
		}
		else $prot_page_name_fixed=$prot_page_name;
			
		if(file_exists($prot_page_name_fixed)) $protpage_content=f_read_file($prot_page_name_fixed);
		else $protpage_content='<html><head><link type="text/css" href="../documents/textstyles_nf.css" rel="stylesheet"'.$f_ct.'</head><BODY>missing</BODY></html>';
		
		$contents=str_replace(array('<BODY','</BODY>'),array('<body','</body>'),$contents);
    	
		if(strpos($contents,'<!--page-->')!==false) $replace_with=f_GFS($contents,'<!--page-->','<!--/page-->');
		else $replace_with=f_GFS($contents,f_GFSAbi($contents,'<body','>'),'</body>');

		$login_page_scripts=f_GFS($contents,'<!--scripts-->','<!--endscripts-->');				
		if(strpos($protpage_content,'<!--page-->')!==false) {$for_replace=f_GFS($protpage_content,'<!--page-->','<!--/page-->');}
		else $for_replace=f_GFS($protpage_content,f_GFSAbi($protpage_content,'<body','>'),'</body>');

		$contents=str_replace($for_replace,$replace_with,$protpage_content);
		$contents=str_replace(f_GFS($contents,'<!--counter-->','<!--/counter-->'),'',$contents);
		$contents=str_replace('<!--endscripts-->',$login_page_scripts.'<!--endscripts-->',$contents);
		$contents=preg_replace("'<\?php.*?\?>'si",'',$contents);
		if(strpos($prot_page_info[1],'../')===false)
		{
			$url=$http_prefix.$_SERVER['HTTP_HOST'].str_replace('//','/',str_replace('documents','',dirname($_SERVER['PHP_SELF'])).'/');
			$contents=str_replace('</title>','</title> <base href="'.$url.'"'.$f_ct,$contents);
		}
  }
  //for Miro
  if(isset($prot_page_info[7])) 
			$contents = preg_replace("'<!--".$prot_page_info[7].".*?".$prot_page_info[7]."-->'si",'',$contents);
  $contents=str_replace(array('GMload();','GUnload();'),array('',''),$contents);
  return $contents;
}
function build_menu($action_id)
{
	global $pref_dir, $ca_lang_l, $l, $f_br; 	

	$url_base=$pref_dir.'centraladmin.php?process=';
	$captions=array(); $urls=array(); $indexes=array();
	$captions[]=$ca_lang_l['site map']; $urls[]=$url_base."index&amp;".$l; $indexes[]="index";
	$captions[]=$ca_lang_l['manage users']; $urls[]=$url_base."manageusers&amp;".$l; $indexes[]="manageusers";
	$captions[]=$ca_lang_l['counter settings']; $urls[]=$url_base."confcounter&amp;".$l; $indexes[]="confcounter";
	$captions[]=$ca_lang_l['registration settings']; $urls[]=$url_base."confreg&amp;".$l;	$indexes[]="confreg";
	$captions[]=$ca_lang_l['settings']; $urls[]=$url_base."conflang&amp;".$l;	$indexes[]="conflang";	
	$captions[]=$ca_lang_l['log']; $urls[]=$url_base."log&amp;".$l;	$indexes[]="log";
	$captions[]=$ca_lang_l['logout'].'[ADMIN]'; $urls[]=$url_base."logoutadmin&amp;".$l;	$indexes[]="logoutadmin";	
	
	$action_key=array_search(trim($action_id),$indexes);
	if($action_key!==false) $selected=$action_key;
	elseif(in_array($action_id,array('processuser', 'pendingreg')))  $selected=array_search('manageusers',$indexes);
	elseif($action_id=='resetcounter')  $selected=array_search('confcounter',$indexes);
	elseif($action_id=='clearlog')  $selected=array_search('log',$indexes);
	else $selected='';	
	
	$output=f_admin_navigation($captions,$urls,$selected);
	return $output;
}
function build_login_form_ca($msg) 
{
	global $pref_dir,$ca_lang_l,$l,$f_ct; 
	$body_section="<div align='center'><form method='post' action='".$pref_dir."centraladmin.php?process=index&amp;".$l."' enctype='multipart/form-data'>";
	$body_section.="<table align='center'><tr><td colspan='2'><span class='rvts8'><b>".$msg."</b></span></td></tr><tr><td><span class='rvts8'>"
	.ucfirst($ca_lang_l['username'])."</span></td><td><input class='input1' type='text' name='username' style='width:180px'".$f_ct."</td></tr>"
	."<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['password'])."</span></td><td><input class='input1' type='password' name='password' style='width:180px'".$f_ct."</td></tr>";
	$body_section.="<tr><td></td><td><input class='input1' type='submit' name='login' value='".$ca_lang_l['login']."'".$f_ct. "&nbsp;</td></tr></table></form></div>";
	return $body_section;
}
function build_add_user_form ($flag,$msg,$username='',$data='')  //flags - add,editpass,editaccess,editdetails 
{	
	global $access_type,$access_type_ex,$pref_dir,$ca_lang_l,$l, $f_br,$f_ct, $trtdsp;
	
	$section_list=get_sections_list();
	$buffer_id=array(); $buffer_access=array();

	$body_section="<form action='".$pref_dir."centraladmin.php?process=processuser&amp;".$l."' method='post' enctype='multipart/form-data'>";
	$body_section.=$msg."<input type='hidden' name='flag' value='".$flag."'".$f_ct.$f_br.$f_br; 
	$body_section.="<table width='300px' align='center'>".$trtdsp.ucfirst($ca_lang_l['username']);
	if($flag=='editdetails') 
	{ 
		$creation_date=($data!=''?$data['details']['creation_date']:$_POST['creation_date']);
		$body_section.= "<input type='hidden' name='creation_date' value='".$creation_date."'".$f_ct; 
		$sr=($data!=''?$data['details']['sr']:$_POST['sr']);
		$body_section.= "<input type='hidden' name='sr' value='".$sr."'".$f_ct;
	}
	if($flag=='add' || $flag=='editdetails') 
  		{$body_section.="*</span></td><td><input type='hidden' name='old_username' value='".$username."'".$f_ct."<input class='input1' type='text' name='username' value='".$username."' style='width:220px' maxlength='50'".$f_ct;}
	elseif($flag=='editaccess')
  		{$body_section.="</span><input type='hidden' name='username' value='".$username."'".$f_ct."<span class='rvts8'><b> $username</b></span></td><td>";}
	else
  		{$body_section.="</span></td><td><input type='hidden' name='username' value='".$username."'".$f_ct."<span class='rvts8'><b> $username</b></span>";}
	$body_section.="</td></tr>";
	if($flag=='add' || $flag=='editdetails')
	{
		$body_section.=$trtdsp.ucfirst($ca_lang_l['name'])."</span></td><td><input class='input1' type='text' name='name' value='" .($data!=''?un_esc($data['details']['name']):(isset($_POST['save'])?un_esc($_POST['name']):''))."' style='width:220px'".$f_ct."</td></tr>";
		$body_section.=$trtdsp.ucfirst($ca_lang_l['surname'])."</span></td><td><input class='input1' type='text' name='sirname' value='".($data!=''?un_esc($data['details']['sirname']):(isset($_POST['save'])?un_esc($_POST['sirname']):''))."' style='width:220px'".$f_ct."</td></tr>";
		$body_section.=$trtdsp.ucfirst($ca_lang_l['email'])."</span></td><td><input class='input1' type='text' name='email' value='".($data!=''?$data['details']['email']:(isset($_POST['save'])?$_POST['email']:''))."' style='width:220px'".$f_ct."</td></tr>";
		if($flag=='editdetails')
		{
			$body_section.="<td colspan='2'><span class='rvts8'><i>".ucfirst($ca_lang_l['creation date']).': '.($creation_date!=''? date('r',ca_tzone_date($creation_date)): 'NA')."</i></span></td></tr>";
		}
	}
	if($flag=='add' || $flag=='editpass')
	{
		$body_section.=$trtdsp.ucfirst($ca_lang_l['password'])."*</span></td><td><input class='input1' type='password' name='password' value='' style='width:220px' maxlength='50'".$f_ct."</td></tr>";
		$body_section.=$trtdsp.ucfirst($ca_lang_l['repeat password'])."*</span></td><td><input class='input1' type='password' name='repeatedpassword' style='width:220px' maxlength='50'".$f_ct."</td></tr>";  //m
	}
	if($flag=='add' || $flag=='editaccess') 
	{
		$select_all_flag=(isset($_POST['select_all'])? true: false);
		$select_all_val=($select_all_flag)?$_POST["select_all"]:'undefined';
		$checked_all_read=($flag=='add' && (!$select_all_flag || $select_all_val=='yes') || ($flag=='editaccess' && $data!='' && $data[0]['section']=='ALL'));
		$checked_all_write=(($flag=='add' && $select_all_flag && $select_all_val=='yesw') || ($flag=='editaccess' && $data!='' && $data[0]['section']=='ALL' && $data[0]['type']=='1'));
		$checked_all_no=($select_all_flag && $_POST["select_all"]=='no' || $data!='' && $data[0]['section']!='ALL');
		$selected_sec_flag=(isset($_POST['selected_sections'])? true: false);

		$section_id=array();
		$section_access=array();
		$body_section.="<tr><td colspan='2' align='left' width='380px'><fieldset><legend><span class='rvts8'>"
			.ucfirst($ca_lang_l['access to'])."* </span></legend>";
		$body_section.="<input type='radio' name='select_all' value='yes' ".($checked_all_read? "checked='checked'": "") 
			.$f_ct." <span class='rvts8'>".strtoupper($ca_lang_l['all'])." (".$access_type['0'].") </span>".$f_br;
		$body_section.="<input type='radio' name='select_all' value='yesw' ".($checked_all_write? "checked='checked'": "")
			.$f_ct." <span class='rvts8'>".strtoupper($ca_lang_l['all'])." (".$access_type['1'].") </span>".$f_br;
		if(!empty($section_list)) 
		{
			$body_section.="<input type='radio' name='select_all' value='no' ".($checked_all_no? "checked='checked'": "")
			.$f_ct."<span class='rvts8'> ".ucfirst($ca_lang_l['selected'])." </span>".$f_br;
		}
		else {$body_section.=$f_br."<span class='rvts8'>".ucfirst($ca_lang_l['adduser_msg1'])."</span>";}
		
		$selected_sec_ids=array();	
		if($data!='')
		{
			foreach($data as $k=>$v)
			{
				$selected_sec_ids[]=$v['section'];
				$selected_sec_access[]=$v['type'];
			}
		}
		elseif($selected_sec_flag && !empty($_POST["selected_sections"]))
		{
			foreach($_POST["selected_sections"] as $k=>$v)
			{
				$selected_sec_ids[]=$v;
				$selected_sec_access[]=$_POST["access_type".$v];
			}
		}
		foreach($section_list as $k=>$v)
		{
			$cur_sec_id=str_replace('<id>','',$v[10]);
			$cur_sec_name=$v[8];
			$secaccess_type='0';
			if($flag=='add' && $selected_sec_flag || $flag=='editaccess')
			{ 
				$index=array_search($cur_sec_id,$selected_sec_ids);
				if($index!==false) $secaccess_type=$selected_sec_access[$index];
			}
			$body_section.="<div style='padding: 5px 22px;'><input type='checkbox' name='selected_sections[]' style='vertical-align:middle;'  value='".$cur_sec_id."' ";

			if($flag=='add' && $selected_sec_flag && in_array($cur_sec_id,$_POST["selected_sections"]) || $flag=='editaccess' && (in_array($cur_sec_id,$selected_sec_ids) || $selected_sec_flag && in_array($cur_sec_id,$_POST["selected_sections"])))
			  {$body_section.=" checked='checked'";}

			$body_section.=$f_ct." <span class='rvts8'>".$cur_sec_name."</span>&nbsp;&nbsp"
			. f_build_select('access_type'.$cur_sec_id,$access_type_ex,$secaccess_type,"onchange='javascript:tS(".$cur_sec_id.");'")."</div>";
			$body_section.="<div id='section".$cur_sec_id."' style='display:".(($secaccess_type=='2')?"block":"none")."'>";
			$body_section.=check_section_range(0,$cur_sec_id,$username)."</div>";
		}
		
		$body_section.=$f_br."<span class='rvts8'><b>".ucfirst($ca_lang_l['read'])."</b></span><span class='rvts8'> - ".ucfirst($ca_lang_l['adduser_msg2']).$f_br."</span><span class='rvts8'><b>".ucfirst($ca_lang_l['read&write'])."</b></span><span class='rvts8'> - ".ucfirst($ca_lang_l['adduser_msg3'])."</span>"; 
		$body_section.=$f_br.'</fieldset></td></tr>';
	}
	if($flag=='add' || $flag=='editdetails') // event manager
	{
		$calendar_categories=get_calendar_categories(); 
		if(!empty($calendar_categories) || isset($data['news']) && !empty($data['news'])) 
		{	
			$news_for=array();
			if(isset($data['news']) && !empty($data['news']))
			{
				foreach($data['news'] as $key=>$val) { $news_for [] = $val['page'].'%'.$val['cat']; }
			}
			$body_section.="<tr><td colspan='2' align='left' width='380px'><fieldset><legend><span class='rvts8'>".'I want to receive newsletters for'." </span></legend>".$f_br;
			foreach($calendar_categories as $k=>$v)
			{
				$ckbox_value=$v['pageid'].'%'.$v['catid'];
				$body_section.="<input type='checkbox' name='news_for[]' value='".$ckbox_value."' style='vertical-align: middle;' ".
				(in_array($ckbox_value,$news_for)? "checked='checked' ": "").$f_ct." <span class='rvts8'>".$v['pagename'].' - '.$v['catname']."</span>".$f_br;	
			}
			$body_section.=$f_br.'</fieldset></td></tr>';
		}
	}
	if($flag=='add') $body_section.="<tr><td colspan='2' align='right'><span class='rvts8'>(*) ".$ca_lang_l['required fields']."</span></td></tr>";
	$body_section.="<tr><td colspan='2' align='right'><input class='input1' name='save' type='submit' value=' ".ucfirst($ca_lang_l['submit'])." '".$f_ct." <input  class='input1' type='button' value=' ".ucfirst($ca_lang_l['cancel'])
	." ' onclick=\"javascript:history.back();\"".$f_ct."</td></tr>";
	$body_section.="</table></form>";  //m
	$body_section.="<script>function tS(id){if(document.getElementById('access_type'+id).selectedIndex==2) document.getElementById('section'+id).style.display='block'; else document.getElementById('section'+id).style.display='none'; } </script>";
	return $body_section;
}
function build_register_form($msg='',$data='')  
{	
	global $pref_dir,$ca_lang_l,$db_settings_file,$l, $f_br, $f_ct, $trtdsp;
	
	$sr_termsofuse_urls=''; 
	$settings=f_read_tagged_data($db_settings_file,'registration');
	if(strpos($settings,'<terms_url>')!==false)	$sr_termsofuse_urls=f_GFS($settings,'<terms_url>','</terms_url>');
	if(strpos($settings,'<notes>')!==false)	$sr_notes=f_GFS($settings,'<notes>','</notes>');
	
	if($sr_termsofuse_urls!='')
	{
		if(strpos($sr_termsofuse_urls,'../')!==false && strpos($pref_dir,'../')===false) 		
			{$sr_termsofuse_urls=str_replace('../','',$sr_termsofuse_urls);}
	}
	$body_section=$f_br."<form action='".$pref_dir."centraladmin.php?process=register&amp;".$l."' method='post' enctype='multipart/form-data'>";
	$body_section.="<div align='center'><table width='50%'><tr><td colspan='2' align='center'><span class='rvts8'><b>".ucfirst($ca_lang_l['registration']). $msg."</b></span><span class='rvts8'>".$f_br.$f_br."</span></td></tr>"; 
	$body_section.=$trtdsp.ucfirst($ca_lang_l['username'])."*</span></td><td align='left'><input class='input1' type='text' name='username' value='".($data!=''?un_esc($data['username']):(isset($_POST['save'])?un_esc($_POST['username']):''))."' style='width:240px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['name'])."*</span></td><td align='left'><input class='input1' type='text' name='name' value='" .($data!=''?un_esc($data['name']):(isset($_POST['save'])?un_esc($_POST['name']):''))."' style='width:240px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['surname'])."*</span></td><td align='left'><input class='input1' type='text' name='sirname' value='".($data!=''?un_esc($data['sirname']):(isset($_POST['save'])?un_esc($_POST['sirname']):''))."' style='width:240px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['email'])."*</span></td><td align='left'><input class='input1' type='text' name='email' value='".($data!=''?$data['email']:(isset($_POST['save'])?$_POST['email']:''))."' style='width:240px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['password'])."*</span></td><td align='left'><input class='input1' type='password' name='password' value='' style='width:240px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['repeat password'])."*</span></td><td align='left'><input class='input1' type='password' name='repeatedpassword' style='width:240px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['code'])."*</span></td><td align='left'><input class='input1' type='text' name='code' value='' size='4' maxlength='4'".$f_ct." ";	
	if(f_is_able_build_img())
	{
		$body_section.='<img src="'.$pref_dir.'centraladmin.php?process=captcha&amp;'.$l.'" border="0" alt="" style="vertical-align: middle;"'.$f_ct;
	}
	else $body_section.="<span class='rvts0'><b>".f_generate_captcha_code2()."</b></span>";
	
	$sr_agree_msg_fixed = ucfirst($ca_lang_l['sr_agree_msg']);
	if($sr_termsofuse_urls!='')
	{
		$pattern = f_GFS($sr_agree_msg_fixed,'%%','%%');
		$sr_agree_msg_fixed = str_replace('%%'.$pattern.'%%','<a class="rvts12" href="'.$sr_termsofuse_urls.'">'.$pattern.'</a>',$sr_agree_msg_fixed);
	}
	else $sr_agree_msg_fixed=str_replace('%%','',$sr_agree_msg_fixed);
	$body_section.="</td></tr><tr><td></td>"; 
	$body_section.="<td align='left'><input type='checkbox' name='agree' value='agree' style='vertical-align: middle;'".$f_ct." <span class='rvts8'> *"; 
	$body_section.=$sr_agree_msg_fixed."</span></td></tr><tr><td></td><td><span class='rvts8'> </span></td></tr>";
	if(isset($sr_notes) && !empty($sr_notes))
		$body_section.="<tr><td></td><td align='left'><span class='rvts8'>".$sr_notes."</span></td></tr>";
	
	$calendar_categories=get_calendar_categories(); 
	if(!empty($calendar_categories)) //event manager
	{	
		$body_section.="<tr><td></td><td align='left'><span class='rvts8'><b>I want to receive newsletters for:".$f_br." </b></span></td></tr>";
		foreach($calendar_categories as $k=>$v)
		{
			$body_section.="<tr><td></td><td align='left'><input type='checkbox' name='news_for[]' value='".$v['pageid'].'%'.$v['catid']."' style='vertical-align: middle;'".$f_ct." <span class='rvts8'>".$v['pagename'].' - '.$v['catname']."</span></td></tr>"; 	
		}
		$body_section.=" <tr><td></td><td><span class='rvts8'> </span></td></tr>";
	}
	$body_section.="<tr><td></td><td align='left'><span class='rvts8'>(*) ".$ca_lang_l['required fields']."</span></td></tr>";
	$body_section.="<tr><td></td><td align='left'><input class='input1' name='save' type='submit' value=' ".ucfirst($ca_lang_l['submit'])." '".$f_ct."</td></tr>";
	$body_section.="</table></div></form>";
	return $body_section;
}
function build_forgotpass_form($msg='')  
{	
	global $pref_dir, $ca_lang_l, $l, $f_br, $f_ct;	

	$body_section=$f_br."<form action='".$pref_dir."centraladmin.php?process=forgotpass&amp;".$l."' method='post' enctype='multipart/form-data'>";
	$body_section.="<div align='center'><table width='40%'><tr><td colspan='2' align='center'><span class='rvts8'><b>".ucfirst($ca_lang_l['forgotten password']).' '.$msg."</b></span>".$f_br.$f_br."<span class='rvts8'>" .ucfirst($ca_lang_l['sr_forgotpass_note']).$f_br.$f_br."</span></td></tr>"; 
	$body_section.="<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['username'])."*</span></td><td><input class='input1' type='text' name='username' value='".(isset($_POST['submit'])?un_esc($_POST['username']):'')."' style='width:220px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.="<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['email'])."*</span></td><td><input class='input1' type='text' name='email' value='".(isset($_POST['submit'])?$_POST['email']:'')."' style='width:220px' maxlength='50'".$f_ct."</td></tr>";	 
	$body_section.="<tr><td colspan='2' align='right'><span class='rvts8'>(*) ".$ca_lang_l['required fields']."</span></td></tr>";
	$body_section.="<tr><td colspan='2' align='right'><input class='input1' name='submit' type='submit' value=' ".ucfirst($ca_lang_l['submit'])." '".$f_ct."</td></tr>";
	$body_section.="</table></div></form>";
	return $body_section;
}
function build_changepass_form($username,$msg='')  
{	
	global $pref_dir, $ca_lang_l, $l, $f_br, $f_ct;	

	$body_section=$f_br."<form action='".$pref_dir."centraladmin.php?process=changepass&amp;".$l."&amp;pageid=".$_GET['pageid'] ."&amp;ref_url=".$_GET['ref_url']."' method='post' enctype='multipart/form-data'>";
	$body_section.="<div align='center'><table width='340px'><tr><td colspan='2' align='center'><span class='rvts8'><b>".ucfirst($ca_lang_l['change password']).' '.$msg."</b></span><input type='hidden' name='username' value='".$username."'".$f_ct."</td></tr>"; 
	$body_section.="<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['old password'])."*</span></td><td align='right'><input class='input1' type='password' name='oldpassword' value='' style='width:220px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.="<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['new password'])."*</span></td><td align='right'><input class='input1' type='password' name='newpassword' value='' style='width:220px' maxlength='50'".$f_ct."</td></tr>";
	$body_section.="<tr><td><span class='rvts8'>".ucfirst($ca_lang_l['repeat password'])."*</span></td><td align='right'><input class='input1' type='password' name='repeatedpassword' style='width:220px' maxlength='50'".$f_ct."</td></tr>";	
	$body_section.="<tr><td colspan='2' align='right'><span class='rvts8'>(*) ".$ca_lang_l['required fields']."</span></td></tr>";
	$body_section.="<tr><td colspan='2' align='right'><input class='input1' name='submit' type='submit' value=' ".ucfirst($ca_lang_l['submit'])." '".$f_ct."</td></tr>";
	$body_section.="</table></div></form>";
	return $body_section;
}
function build_editprofile_form($username,$data='',$msg='')  
{	
	global $pref_dir,$ca_lang_l,$l, $f_br, $f_ct, $trtdsp;	

	$body_section=$f_br."<form action='".$pref_dir."centraladmin.php?process=editprofile&amp;pageid=".$_GET['pageid'] ."&amp;ref_url=".$_GET['ref_url'].'&amp;'.$l."' method='post' enctype='multipart/form-data'>";
	
	$creation_date=($data!=''?$data['details']['creation_date']:$_POST['creation_date']);
	$body_section.="<input type='hidden' name='creation_date' value='".$creation_date."'".$f_ct;
	
	$sr=($data!=''?$data['details']['sr']:$_POST['sr']);
	$body_section.="<input type='hidden' name='sr' value='".$sr."'".$f_ct;

	$body_section.="<div align='center'><table width='340px'><tr><td colspan='2' align='center'><span class='rvts8'><b>".ucfirst($ca_lang_l['edit profile']).' '.$msg."</b></span><input type='hidden' name='username' value='".$username."'".$f_ct."</td></tr>"; 
	$body_section.=$trtdsp.ucfirst($ca_lang_l['name'])."*</span></td><td align='right'><input class='input1' type='text' name='name' value='" .($data!=''?un_esc($data['details']['name']):(isset($_POST['save'])?un_esc($_POST['name']):''))."' style='width:220px'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['surname'])."*</span></td><td align='right'><input class='input1' type='text' name='sirname' value='".($data!=''?un_esc($data['details']['sirname']):(isset($_POST['save'])?un_esc($_POST['sirname']):''))."' style='width:220px'".$f_ct."</td></tr>";
	$body_section.=$trtdsp.ucfirst($ca_lang_l['email'])."*</span></td><td align='right'><input class='input1' type='text' name='email' value='".($data!=''?$data['details']['email']:(isset($_POST['save'])?$_POST['email']:''))."' style='width:220px'".$f_ct."</td></tr>";

	$calendar_categories=get_calendar_categories(); 
	if(!empty($calendar_categories)) // || isset($data['news']) && !empty($data['news'])
	{
		
		$news_for=array();
		if(isset($data['news']) && !empty($data['news']))
		{
			foreach($data['news'] as $key=>$val) $news_for[]=$val['page'].'%'.$val['cat'];
		}
		$body_section.="<tr><td colspan='2' align='left' width='380px'><fieldset><legend><span class='rvts8'>".ucfirst($ca_lang_l['want to get'])." </span></legend>".$f_br;
		foreach($calendar_categories as $k=>$v)
		{
			$ckbox_value=$v['pageid'].'%'.$v['catid'];
			$body_section.="<input type='checkbox' name='news_for[]' value='".$ckbox_value."' style='vertical-align: middle;' ".
			(in_array($ckbox_value,$news_for)? "checked='checked' ": "").$f_ct." <span class='rvts8'>".$v['pagename'].' - '.$v['catname']."</span>".$f_br;	
		}
		$body_section.=$f_br.'</fieldset></td></tr>';
	}
	$body_section.="<tr><td colspan='2' align='right'><span class='rvts8'>(*) ".$ca_lang_l['required fields']."</span></td></tr>";
	$body_section.="<tr><td colspan='2' align='right'><input class='input1' name='submit' type='submit' value=' ".ucfirst($ca_lang_l['submit'])." '".$f_ct."</td></tr>";
	$body_section.="</table></div></form>";
	return $body_section;
}
# ------------ self-registration
function process_register($action_id,$ms='')  
{	
  global $pref_dir, $db_file, $ca_lang_l, $l,$db_settings_file, $http_prefix, $f_lf, $ca_template_file_f;
  global $sr_notif_enabled, $ca_page_charset, $ca_user_msg, $f_br,$f_fmt_span8em;
	
 $msg='';
 if(isset($_POST['save'])) // send registration email 
 {
	if(!isset($_SESSION)) {f_int_start_session();}
	if(!isset($_SESSION['CAPTCHA_CODE'])) {echo "This is illegal operation. You are not allowed to register.";exit;}
	else 
	{			
		if(empty($_POST['username'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['username']);
		elseif(!preg_match("/^[A-Za-z_0-9]+$/",$_POST['username'])) $msg.=$f_br.ucfirst($ca_lang_l['can contain only']);
		elseif(duplicated_user($_POST['username'])) $msg.=$f_br.ucfirst($ca_lang_l['username exists']);
		
		if(empty($_POST['name']))	$msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['name']);	
		if(empty($_POST['sirname'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['surname']);
		if(empty($_POST['email'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['email']);
		elseif(!empty($_POST["email"]) && !f_validate_email($_POST["email"])) $msg.=$f_br.ucfirst($ca_lang_l['nonvalid email']);
		
		if(empty($_POST['password'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['password']);
		elseif(strlen(trim($_POST['password']))<5) $msg.=$f_br.ucfirst($ca_lang_l['your password should be']);		
		elseif(empty($_POST['repeatedpassword'])) $msg.=$f_br.ucfirst($ca_lang_l['repeat password']);
		elseif($_POST['password']!=$_POST['repeatedpassword']) $msg.=$f_br.ucfirst($ca_lang_l['password and repeated password']);
		elseif(strtolower($_POST['username'])=='admin' && strtolower($_POST['password'])=='admin') $msg.=$f_br.$ca_user_msg;
		
		if(empty($_POST['code']) || md5(strtoupper($_POST['code']))!= $_SESSION['CAPTCHA_CODE']) 
			$msg.=$f_br.strtoupper($ca_lang_l['code']).' '.$ca_lang_l['field should match the text on the right'];			
		if(!isset($_POST['agree'])) $msg.=$f_br.ucfirst($ca_lang_l['agree with terms']);
		
    if($msg!='') $body_section=build_register_form($f_br.sprintf($f_fmt_span8em,$msg));
		else 
		{
			$settings=f_read_tagged_data($db_settings_file,'registration');
			$access=array();
			if(strpos($settings,'<access>')!==false) $access_str=f_GFS($settings,'<access>','</access>');
			else $access_str='';
			if($access_str!='')	$temp_access=explode('|',$access_str);
			if(isset($temp_access)) { foreach($temp_access as $k=>$v) { $t = explode('%%',$v); $access[]=array('section'=>$t[0],'type'=>$t[1]); } }

			$uniqueid=md5(uniqid(mt_rand(),true));
			$link=$http_prefix.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/centraladmin.php?id='.$uniqueid.'&process=register&'.$l;
			$content=str_replace("##",'<br>',$ca_lang_l['sr_email_msg']);
			$content=str_replace(array("%CONFIRMLINK%",'%%site%%'), array('<a href="'.$link.'">'.$link.'</a>',$_SERVER['HTTP_HOST']), $content);
			$content=str_replace(array("%CONFIRMLINK%",'%%site%%'), array('<a href="'.$link.'">'.$link.'</a>',$_SERVER['HTTP_HOST']), $content);
			$content=str_replace(array('%%username%%','%%USERNAME%%'), array($_POST['username'],$_POST['username']), $content);
			$content_text=str_replace(array("##","%CONFIRMLINK%"), array($f_lf,$link), $ca_lang_l['sr_email_msg']); 
			$content_text=str_replace("%%site%%", $_SERVER['HTTP_HOST'], $content_text); 
			$content_text=str_replace(array('%%username%%','%%USERNAME%%'), array($_POST['username'],$_POST['username']), $content_text);
			$subject=str_replace('%%site%%',$_SERVER['HTTP_HOST'],$ca_lang_l['sr_email_subject']);

			if((strpos(strtolower($content),'mime-version')!==false) || (strpos(strtolower($content),'content-type')!==false)) 
			{
				$log_msg=" Registration email CAN NOT be sent - possible dangerous content";
				$body_section=$log_msg;							
			}	
			$send_to_email=$_POST["email"];
			$sections='';
			$news='';
			if(empty($access)) { $sections.='<access id="1" section="ALL" type="0"></access>'; }
			else 
			{
				foreach($access as $k=>$v) $sections.='<access id="'.($k+1).'" section="'.$v['section'].'" type="'.$v['type'].'"></access>';
			}
			if(isset($_POST["news_for"])) //event manager
			{
				foreach($_POST["news_for"] as $k=>$v) 
				{ 
					if(strpos($v,'%')!==false) { list($p,$c)=explode('%',$v); }
					else { $p=$v; $c=''; }
					$news.='<news id="'.($k+1).'" page="'.$p.'" cat="'.$c.'"></news>';
				}
			}
			$details='<details email="'.$_POST["email"].'" name="'.esc($_POST["name"]).'" sirname="'.esc($_POST["sirname"]).'" sr="1"></details>';	
			$log_msg='success';
	
			$result=send_mail_ca($content,$content_text,$subject,$send_to_email);
			if($result) 
			{
				db_write_user('selfreg',$_POST['username'],crypt($_POST['password']),$sections,$details,$news,$uniqueid); //event manager
				$log_msg.=", email SENT"; $body_section = $f_br.'<div align="center"><h5>'.$ca_lang_l['sr_success_msg'].'</h5></div>'; 
			}
			else 
			{
				$log_msg.=", email FAILED"; 
				$body_section=$f_br.'Email FAILED. Try again.'; 
			}
			write_log('reg','USER:'.$_POST['username'],$log_msg);
			if(isset($_SESSION['CAPTCHA_CODE'])) $_SESSION['CAPTCHA_CODE']='';			 		
		}
	 }
 }
 elseif(isset($_GET['id'])) // confirm registration  
 {
		$file_contents='<?php echo "hi"; exit; /*<users> </users>*/ ?>';
		if(!$fp=fopen($db_file,'r+')) {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
		flock($fp,LOCK_EX);
		$fsize=filesize($db_file);
		if($fsize>0) $file_contents=fread( $fp,$fsize);
		$users=f_GFS($file_contents,'<users>','</users>');
		if(strpos($file_contents,'<user id="'.$_GET['id'])!==false)
		{
			if($users!='') {$new_id=substr_count($users,'user id="')+1;}
			else			{$new_id=1; }		
			$_user=f_GFSAbi($file_contents,'<user id="'.$_GET['id'].'"','</user>');
			$username=f_GFS($_user,'username="','"');
			$new_user=str_replace($_GET['id'],$new_id,$_user);
			$new_user=str_replace('<details','<details date="'.mktime().'"',$new_user);  // creation date
			$file_contents=str_replace('</users>',$new_user.'</users>',$file_contents); 
			$file_contents=str_replace($_user,'',$file_contents); 

			ftruncate($fp,0);fseek($fp,0);
			if(fwrite($fp,$file_contents) === FALSE) {print "Cannot write to file";  exit;}
			flock($fp,LOCK_UN);fclose($fp);	
			$body_section=$f_br."<span class='rvts8'>".$ca_lang_l['sr_confirm_msg'].'</span>';
			$log_msg='success';
			if($sr_notif_enabled)  
			{
				$users=f_GFS($file_contents,'<users>','</users>');
				$users_arr=f_format_users($users);
				if(!empty($users_arr)) 
				{
					foreach($users_arr as $k=>$v) if(in_array($username,$v)) {$user_data=$v; break;}
				}
				$content='register_id= '.$_GET['id'].'<br>'.'username= '.$user_data['username'].'<br>';
				$content.='name= '.$user_data['details']['name'].'<br>'.'surname= '.$user_data['details']['sirname'].'<br>';
				$content.='email= '.$user_data['details']['email'].'<br>'.'date= '.date('Y-m-d G:i', ca_tzone_date(mktime())).'<br>';		
				$content.='IP= '.(isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"").'<br>';
				$content.='HOST= '.(isset($_SERVER['REMOTE_HOST'])?$_SERVER['REMOTE_HOST']:"").'<br>';
				$content.='AGENT= '.(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"").'<br>';
				$subject=str_replace('%%site%%',$_SERVER['HTTP_HOST'],$ca_lang_l['sr_notif_subject']);
				
				$result=send_mail_ca($content,str_replace('<br>',$f_lf,$content),$subject);
				if($result) $log_msg.=", notification SENT";
				else $log_msg.=", notification FAILED";			
			}	
			if(!isset($_GET['flag'])) write_log('conf','USER:'.$username,$log_msg);
			else {write_log('confadmin','USER:'.$username,$log_msg); check_pending_users($action_id,$body_section); exit; }
		}
		else $body_section=$f_br."<h5>".$ca_lang_l['sr_already_confirmed']."</h5>";
 }
 else $body_section=build_register_form($ms);
 $body_section=GT($body_section);
 print $body_section;
}

function process_forgotpass()  
{	
	global $pref_dir,$ca_lang_l,$f_lf,$db_file,$ca_page_charset,$f_br,$f_fmt_span8em,$ca_template_file_f;
	$msg='';

	if(isset($_POST['submit'])) 
	{	
		if(empty($_POST['username'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['username']);
		elseif(!preg_match("/^[A-Za-z_0-9]+$/",$_POST['username'])) $msg.=$f_br.ucfirst($ca_lang_l['can contain only']);
		elseif(!duplicated_user($_POST['username'])) $msg.=$f_br.ucfirst($ca_lang_l['non-existing']);
		
		if(empty($_POST['email'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['email']);
		elseif(!empty($_POST["email"]) && !f_validate_email($_POST["email"])) $msg.=$f_br.ucfirst($ca_lang_l['nonvalid email']);
    		
		if($msg!='') $body_section=build_forgotpass_form($f_br.sprintf($f_fmt_span8em,$msg));
		else 
		{	
			$user_data = db_get_specific_user($_POST['username']);
			if(isset($user_data['details']['email']) && $user_data['details']['email']==$_POST['email'])
			{
				$new_pass=mt_rand();
				$send_to_email=$_POST["email"];	
				$content=str_replace("##",'<br>',$ca_lang_l['sr_forgotpass_msg']);
				$content=str_replace(array("%%newpassword%%",'%%site%%'),array($new_pass,$_SERVER['HTTP_HOST']),$content);
				$content=str_replace(array('%%username%%','%%USERNAME%%'),array($_POST['username'],$_POST['username']),$content);	
				$content_text=str_replace("##",$f_lf,$content); 
				$subject=str_replace('%%site%%',$_SERVER['HTTP_HOST'],$ca_lang_l['sr_forgotpass_subject']);
		
				$result=send_mail_ca($content,$content_text,$subject,$send_to_email);
				if($result) 
				{	
					if(!$fp=fopen($db_file,'r+'))  {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
					flock($fp, LOCK_EX);
					$file_contents=fread($fp,filesize($db_file));

					$users=f_GFS($file_contents,'<users>','</users>');
					$old_data=f_GFSAbi($users,'<user id="'.$user_data['id'].'"','</user>');
					$new_data=str_replace(f_GFSAbi($old_data,'password="','">'),'password="'.crypt($new_pass).'">',$old_data); 
					$file_contents=str_replace($old_data,$new_data,$file_contents); 		

					ftruncate($fp,0);fseek($fp,0);
					if(fwrite($fp,$file_contents) === FALSE) {print "Cannot write to file";  exit;  }
					flock($fp,LOCK_UN);fclose($fp);	

					$log_msg="success, email SENT"; 
					$body_section=$f_br.'<h5>'.$ca_lang_l['sr_forgotpass_msg2'].'</h5>'; 
				}
				else 
				{
					$log_msg='success, email FAILED';
					$body_section='Email FAILED. Try again.'; 
				}
				write_log('forgotpass','USER:'.$_POST['username'],$log_msg);			
			}
			else $body_section=build_forgotpass_form($f_br.sprintf($f_fmt_span8em,ucfirst($ca_lang_l['no email for user'])));
	  }
 }
 else $body_section=build_forgotpass_form();
 $body_section=GT($body_section);
 print $body_section; 
}
function process_changepass()  //m
{	
	global $pref_dir,$ca_lang_l,$db_file,$ca_page_charset,$template_in_root,$f_br,$f_fmt_span8em,$ca_template_file_f;
	$msg='';
	if(isset($_SESSION['SID_ADMIN'])) $user=$_REQUEST['username'];  //m
	else $user=$_SESSION['cur_user'];
	if(isset($_POST['submit'])) 
	{	
		$user_data=db_get_specific_user($user);  //m

		if(empty($_POST['oldpassword'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['old password']);
		elseif($user_data['password']!=crypt($_POST['oldpassword'],$user_data['password'])) $msg.=$f_br.ucfirst($ca_lang_l['wrong old']);

		if(empty($_POST['newpassword'])) $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['new password']);
		elseif(strlen(trim($_POST['newpassword']))<5) $msg.=$f_br.ucfirst($ca_lang_l['your password should be']);		
		elseif(empty($_POST['repeatedpassword'])) $msg.=$f_br.ucfirst($ca_lang_l['repeat password']);
		elseif($_POST['newpassword']!=$_POST['repeatedpassword']) $msg.=$f_br.ucfirst($ca_lang_l['password and repeated password']);
		
		if($msg!='') $body_section=build_changepass_form($user,$f_br.sprintf($f_fmt_span8em,$msg));  //m
		else 
		{			
			if(isset($user_data['username']) && $user_data['username']==$user)   //m
			{
				if(!$fp=fopen($db_file,'r+')) {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
				flock($fp,LOCK_EX);
				$file_contents=fread($fp,filesize($db_file));

				$users=f_GFS($file_contents,'<users>','</users>');
				$old_data=f_GFSAbi($users,'<user id="'.$user_data['id'].'"','</user>');
				$new_data=str_replace(f_GFSAbi($old_data,'password="','">'),'password="'.crypt($_POST['newpassword']).'">',$old_data); 
				$file_contents=str_replace($old_data,$new_data,$file_contents); 		
				ftruncate($fp,0);fseek($fp,0);
				if(fwrite($fp,$file_contents)==FALSE) {print "Cannot write to file";exit;}
				flock($fp,LOCK_UN);fclose($fp);				
				
				$body_section=$f_br.'<h5>'.ucfirst($ca_lang_l['password changed']).'.</h5>'.$f_br;
				if(isset($_GET['ref_url']))	
				{
					$u=$_GET['ref_url'];
					if(strpos($_GET['ref_url'],'/')===false && $template_in_root==false) $u='../'.$u;
					$body_section.='<a class="rvts12" href="'.urldecode($u).'">'.ucfirst($ca_lang_l['back to page']).'</a>';
				}				
				write_log('changepass','USER:'.$user,'success');		//m	
			}			
	  }
	}
	else $body_section=build_changepass_form($user);
	$body_section=GT($body_section);
	print $body_section;  exit;
}
function process_editprofile()  //m
{	
	global $pref_dir,$ca_lang_l,$db_file,$ca_page_charset,$f_br,$f_fmt_span8em,$ca_template_file_f;
	$msg='';
	if (isset($_SESSION['SID_ADMIN'])) $user=$_REQUEST['username'];  //m
	else $user=$_SESSION['cur_user'];
	if(isset($_POST['submit'])) 
	{	
		$user_data=db_get_specific_user($user);  //m
		if(empty($_POST['name']))	  $msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['name']);
		if(empty($_POST['sirname']))$msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['surname']);
		if(empty($_POST['email']))	$msg.=$f_br.ucfirst($ca_lang_l['fill in']).' '.strtoupper($ca_lang_l['email']);
		
		if($msg!='') $body_section=build_editprofile_form($user,'',$f_br.sprintf($f_fmt_span8em,$msg));   //m
		else 
		{			
			if(isset($user_data['username']) && $user_data['username']==$user)  //m
			{
				if(!$fp=fopen($db_file,'r+')) {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
				flock($fp,LOCK_EX);
				$file_contents=fread($fp,filesize($db_file));

				$users=f_GFS($file_contents,'<users>','</users>');
				$old_data=f_GFSAbi($users,'<user id="'.$user_data['id'].'"','</user>');
				$new_details='<details email="'.$_POST["email"].'" name="'.$_POST["name"].'" sirname="'.$_POST["sirname"]  
				.'" date="'.$_POST["creation_date"].'" sr="'.$_POST["sr"].'"></details>';
				$new_data=str_replace(f_GFSAbi($old_data,'<details','</details>'),$new_details,$old_data); 

				$news='';
				if(isset($_POST["news_for"])) //event manager
				{
					foreach($_POST["news_for"] as $k=>$v) 
					{ 
						if(strpos($v,'%')!==false) list($p,$c)=explode('%',$v);
						else {$p=$v;$c='';}
						$news.='<news id="'.($k+1).'" page="'.$p.'" cat="'.$c.'"></news>';
					}
				}
				if(!empty($news))
				{
					if(strpos($new_data,'</news_data>')===false)  //event manager
						$new_data=str_replace('</details>','</details><news_data>'.$news.'</news_data>',$new_data);
					else
						$new_data=str_replace(f_GFSAbi($old_data,'<news_data>','</news_data>'),'<news_data>'.$news.'</news_data>',$new_data);
				}
				$file_contents=str_replace($old_data,$new_data,$file_contents); 		
				ftruncate($fp,0);fseek($fp,0);
				if(fwrite($fp,$file_contents)==FALSE) {print "Cannot write to file";  exit;  }
				flock($fp,LOCK_UN); fclose($fp);				
				
				$body_section=$f_br.'<h5>'.'Profile edited'.'.</h5>'.$f_br;
				if(isset($_GET['ref_url']))	
				{
					$u=$_GET['ref_url'];
					$u=str_replace('../','',$u);  //m
					//if(strpos($_GET['ref_url'],'/')==false) $u='../'.$u;
					$body_section.='<a class="rvts12" href="'.urldecode($u).'">'.ucfirst($ca_lang_l['back to page']).'</a>';
				}									
				write_log('editprofile','USER:'.$user,'success');   //m
			}			
	  }
 }
 else {$user_data=db_get_specific_user($user); $body_section=build_editprofile_form($user,$user_data);}
 $body_section=GT($body_section);
 print $body_section;  exit;
}
function send_mail_ca($content,$content_text,$subject,$send_to_email='')
{
	global $db_settings_file,$f_use_linefeed,$ca_lang_l, $ca_mail_msg;
	global $f_mail_type,$f_SMTP_HOST,$f_SMTP_PORT,$f_SMTP_HELLO,$f_SMTP_AUTH,$f_SMTP_AUTH_USR,$f_SMTP_AUTH_PWD;
		$sr_admin_email='your@email.here'; 
		$sr_sendfrom_email='';  
		$sr_return_path=''; 
		$res=false;
	$settings=f_read_tagged_data($db_settings_file,'registration');
	if(strpos($settings,'<admin_email>')!==false)	$sr_admin_email=f_GFS($settings,'<admin_email>','</admin_email>');
	if(strpos($settings,'<sendmail_from>')!==false)	$sr_sendfrom_email=f_GFS($settings,'<sendmail_from>','</sendmail_from>');
	if(strpos($settings,'<return_path>')!==false)	$sr_return_path=f_GFS($settings,'<return_path>','</return_path>');
	if($sr_sendfrom_email!='') {ini_set('sendmail_from', $sr_sendfrom_email);}	

	if(strpos($sr_admin_email,'your@email.here')!==false || $sr_admin_email=='') { print GT($ca_mail_msg); exit; }
	else 
	{				
		$mail = new htmlMimeMail();
		if ($f_use_linefeed) $mail->setCrlf("\r\n"); 
		$mail->setHtml($content, $content_text);
		$mail->setSubject($subject);
		if($sr_sendfrom_email=='') $mail->setFrom($sr_admin_email);
		else $mail->setFrom($sr_sendfrom_email);
		if ($sr_return_path!='') {$mail->setReturnPath($sr_return_path);}
		if(($f_mail_type=='smtp')&&($f_SMTP_HOST!=='')) $mail->setSMTPParams($f_SMTP_HOST,$f_SMTP_PORT,$f_SMTP_HELLO,$f_SMTP_AUTH,$f_SMTP_AUTH_USR,$f_SMTP_AUTH_PWD);
		if($send_to_email!='') $res = $mail->send(array($send_to_email),$f_mail_type);
		else $res = $mail->send(array($sr_admin_email),$f_mail_type);
	}
	return $res;
}
function get_calendar_categories()
{
	$categories=array();
	$calendar_pages=get_pages_list ('136'); 	
	foreach($calendar_pages as $k=>$v)
	{
		$cat=array();
		$fp=@fopen($v['url'],'r');
		if($fp) { $file_contents=fread($fp,4096); fclose($fp); }
		if(isset($file_contents) && !empty($file_contents)) 
		{
			if(strpos($file_contents,'$em_enabled=TRUE;')!==false || strpos($file_contents,'$em_enabled=true;')!==false)
			{
				$cat_names=f_GFS($file_contents,'$category_name=array(',');');
				$cat_names_arr=explode(',',$cat_names);
				$cat_ids=f_GFS($file_contents,'$category_id=array(',');');
				$cat_ids_arr=explode(',',$cat_ids);
				$cat_visib=f_GFS($file_contents,'$category_vis=array(',');');
				$cat_visib_arr=explode(',',$cat_visib);
				foreach($cat_names_arr as $kk=>$vv) 
				{ 
					if($kk>0 && isset($cat_visib_arr[$kk]) && $cat_visib_arr[$kk]=='true') //miro
					$categories[]= array('pageid'=>$v['pageid'],'pagename'=>$v['name'],'catid'=>$cat_ids_arr[$kk],'catname'=>str_replace('"','',$vv));
				}
			}
		}
	}
	return $categories;
}
# ---------- DB
function write_log($change,$data,$message="")  
{
	global $db_activity_log, $ca_first_line, $ca_last_line, $f_lf;
		
	$time=mktime(); 
	$typechange=array("reg"=>"Register", "conf"=>"Confirmation", "confadmin"=>"Confirmation (Admin)", "forgotpass"=>"Forgotten pass", "changepass"=>"Change pass", "editprofile"=>"Edit profile", "resend"=>"Confirmation email resend", "login"=>"Login", "logout"=>"Logout");
	$currchange=$typechange[$change];
	$record_line="$time => $currchange -> $data => Result: $message";  
		
	clearstatcache();
	if(!file_exists($db_activity_log)) $handle=@fopen($db_activity_log,'w');
	else $handle=@fopen($db_activity_log,'a');

	if(!$handle) { return; } 
	else
	{
		flock($handle,LOCK_EX);
		if(filesize($db_activity_log)==0) {$buf=$ca_first_line.$f_lf.$record_line.$f_lf;}
		else {$buf=$record_line.$f_lf;}
		fwrite($handle,$buf); flock($handle,LOCK_UN); fclose($handle);
	}
}
function db_get_users($tag='users') 
{
	global $db_file;
		
	$filename=$db_file;
	if(!file_exists($filename)) $filename=str_replace('../','',$filename);
	$src=f_read_file($filename);
	$users=f_GFS($src,'<'.$tag.'>','</'.$tag.'>');
	return $users;
}
function db_get_specific_user($username)
{
	$users_arr=array();
	$specific_user=array(); 
	$users=db_get_users();
	if($users!='') {$users_arr=f_format_users($users);}

	if(!empty($users_arr)) 
	{
		foreach($users_arr as $k=>$v) {if(in_array($username,$v)) {$specific_user=$v; break;}}
	}
	return $specific_user;	
}
function db_remove_user($username,$flag='users')
{
	global $db_file, $ca_template_file_f;
	$result=false;
	$updated_users='';
	$users=db_get_users($flag);
	if($flag=='users') {if($users!='') $users_arr=f_format_users($users);}
	else {if($users!='') $users_arr=$users;}

	if(isset($users_arr) && !empty($users_arr)) 
	{
		$counter=0;
		if(!$fp=fopen($db_file,'r+')) {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
		flock($fp, LOCK_EX);
		$fsize=filesize($db_file);
		if($fsize>0) $file_contents=fread($fp,$fsize);
		if($flag=='users')
		{
			foreach($users_arr as $k=>$v) 
			{
				if(!in_array($username,$v)) 
				{
					$counter++;
					$updated_users.=' <user id="'.$counter.'" username="'.$v['username'].'" password="'.$v['password'].'"> <access_data>';
					foreach($v['access'] as $key=>$val)
					{
						$updated_users.='<access id="'.($key+1).'" section="'.$val['section'].'" type="'.$val['type'].'"></access>';
					}
					$updated_users.='<access_data><news_data>';
					foreach($v['news'] as $key=>$val)
					{ 						
						$updated_users.='<news id="'.($key+1).'" page="'.$val['page'].'" cat="'.$val['cat'].'"></news>';
					}
					$updated_users.='</news_data> <details email="'.$v['details']['email'].'" name="'.$v['details']['name'].'" sirname="'.$v['details']['sirname'].'" date="'.$v['details']['creation_date'].'" sr="'.$v['details']['sr'].'"></details> </user>';			
				}
			}
		}
		else {$updated_users=str_replace(f_GFSAbi($users_arr,'<user id="'.$username.'"','</user>'),'',$users_arr);}
			
		$file_contents=str_replace($users, $updated_users,$file_contents);
		ftruncate($fp, 0);
		fseek($fp, 0);
		if(fwrite($fp,$file_contents) === FALSE) {print "Cannot write to file";  exit;  }
		flock($fp, LOCK_UN);
		fclose( $fp );
		$result=true;
	}
    return  $result;
}
function db_write_user($flag,$username,$pwd='',$sections='',$details='',$news='',$uniqueid='')  //write user
{
	$users_arr=array(); 
	$specific_user=array(); 
	if($flag=='selfreg') { db_add_user($uniqueid,$username,$pwd,$sections,$details,$news,true);   }
	else 
	{  
		$users=db_get_users();
		if($users!='') $users_arr=f_format_users($users);
		if(!empty($users_arr)) 
		{
			foreach($users_arr as $k=>$v) {if(in_array($username,$v))  {$id=$k+1; break;}	}
		}
		if(isset($id))	db_edit_user($flag,$id,$username,$pwd,$sections,$details,$news);
		else	db_add_user(count($users_arr)+1,$username,$pwd,$sections,$details,$news);
	}
}
function db_add_user($id,$username,$pwd,$sections,$details,$news,$self_reg=false)  //add user
{
	global $db_file, $ca_template_file_f;
	$result=false;
	$file_contents='<?php echo "hi"; exit; /*<users> </users>*/ ?>';
    
	$new_user='<user id="'.$id.'" username="'.$username.'" password="'.$pwd.'"><access_data>'.$sections.'</access_data>'. ($news!=''?'<news_data>'.$news.'</news_data>':'').$details.'</user>'; //event manager

	if(!file_exists($db_file)) { print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('MISSING_DBFILE',$db_file)); exit; }
	else if(!$fp=fopen($db_file,'r+')) {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
	flock($fp, LOCK_EX);
	$fsize=filesize($db_file);
	if($fsize>0) $file_contents=fread($fp,$fsize);

	if($self_reg==false) {$file_contents=str_replace('</users>',$new_user.'</users>',$file_contents);}
	else
	{
		if(strpos($file_contents,'<selfreg_users>')===false) 
		  {$file_contents=str_replace('</users>','</users><selfreg_users>'.$new_user.'</selfreg_users>',$file_contents);}
		else {$file_contents=str_replace('</selfreg_users>',$new_user.'</selfreg_users>',$file_contents);}
	}
	if(strpos($file_contents,'/*<users>')===FALSE) 
	{
		$file_contents=str_replace('<users>','/*<users>',$file_contents);
		$file_contents=str_replace('</users>','</users>*/',$file_contents);
	}

	ftruncate($fp,0);fseek($fp,0);
	if(fwrite($fp,$file_contents) === FALSE) {print "Cannot write to file";  exit;  }
	flock($fp,LOCK_UN);fclose($fp);
	$result=true;
}
function db_edit_user($flag,$id,$username,$pwd='',$sections='',$details='',$news='')  //edit user's password or access
{
	global $db_file, $ca_template_file_f;
	
	$users=''; $file_contents=''; $fixed='';	
	
	$users=db_get_users();
	if(!$fp=fopen($db_file,'r+'))  {print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_file)); exit;}
	flock($fp,LOCK_EX);
	$fsize=filesize($db_file);
	if($fsize>0) $file_contents=fread($fp,$fsize);
			
	$user_to_update='<user id="'.$id.'" '.f_GFS($users,'<user id="'.$id.'" ','</user>').'</user>';
		
	if(strpos($user_to_update,'</access_data>')===false || strpos($user_to_update,'<user id="'.($id+1).'"')!==false) 
	{
		$fixed=$user_to_update;
		if(strpos($user_to_update,'</access><access_data>')!==false) 
		{
			$fixed=str_replace('</access><access_data>','</access></access_data>',$user_to_update);
		}
		else 
		{
			if(strpos($user_to_update,'<user id="'.($id+1).'"')!==false) 
			{
				$fixed=str_replace('<user id="'.($id+1).'"','</access_data> <details email="" name="" sirname="" date=""></details> </user> <user id="'.($id+1).'"',$user_to_update);
			}					
		}
		$file_contents=str_replace($user_to_update,$fixed,$file_contents);
		ftruncate($fp,0);fseek($fp,0);
		if(fwrite($fp,$file_contents)===FALSE) {print "Cannot write to file";  exit;  }
		flock($fp,LOCK_UN);fclose( $fp );

		$users=db_get_users();

		if(!$fp=fopen($db_file,'r+')) {print "Cannot open file"; exit;}
		flock($fp,LOCK_EX);
		$fsize=filesize($db_file);
		if($fsize>0) $file_contents=fread($fp,$fsize);
	}		
	if($flag=='editpass') $updated_user=str_replace(f_GFS($user_to_update,'password="','"'),$pwd,$user_to_update);
	elseif($flag=='editaccess') $updated_user=str_replace(f_GFS($user_to_update,'<access_data>','</access_data>'),$sections,$user_to_update);
	elseif($flag=='editdetails') 
	{
		$updated_user=str_replace(f_GFSAbi($user_to_update,'<details ','></details>'),$details,$user_to_update);
		
		if(strpos($user_to_update,'</news_data>')===false)  //event manager
			$updated_user=str_replace('</details>','</details><news_data>'.$news.'</news_data>',$updated_user);
		else
			$updated_user=str_replace(f_GFSAbi($user_to_update,'<news_data>','</news_data>'),'<news_data>'.$news.'</news_data>',$updated_user);
		if(isset($_POST['old_username']))  
		{
			$old_user_name=f_GFSAbi($updated_user,'username="','"');
			$updated_user=str_replace($old_user_name,'username="'.$_POST['username'].'"',$updated_user);
		}
	}
	else { $updated_user=$user_to_update; }
		
	$file_contents=str_replace($user_to_update,$updated_user,$file_contents);  
	ftruncate($fp,0);fseek($fp,0);
	if(fwrite($fp,$file_contents)===FALSE) {print "Cannot write to file";exit;}
	flock($fp,LOCK_UN);fclose($fp);
	
	return true;
}
# ----------- login/logout
function login_admin($action_id)  // process login  admin
{
	global $db_settings_file,$admin_username,$admin_pwd,$ca_lang_l,$ca_account_msg;
		
	$body_section="";
	$user=$admin_username; $pass=$admin_pwd;	
	if(isset($_POST['login'])) 
	{		
		if(isset($_POST['password'])) $pass_filled=md5($_POST['password']);
				
		if(empty($_POST['username']) || empty($_POST['password'])) 
		{
			$body_section.=build_login_form_ca("<em style='color:red;'>".ucfirst($ca_lang_l['fill in']).' '.ucfirst($ca_lang_l['username']).' & '.ucfirst($ca_lang_l['password'])."</em>");
		}
		elseif($_POST['username']!=$user || $pass_filled!=$pass) 
		{
			set_delay();	
			$body_section.=build_login_form_ca("<em style='color:red;'>".ucfirst($ca_lang_l['incorrect username/password'])."</em>");
		}
		else 
		{
			f_set_session_var('SID_ADMIN',$user);	//ADMIN
			if(isset($_SERVER['HTTP_USER_AGENT'])) f_set_session_var( 'HTTP_USER_AGENT',md5($_SERVER['HTTP_USER_AGENT']));
			set_admin_cookie(); // for counter - to ignore hits from site admin
			index($action_id); exit;
		}
	}
	else 
	{
		if(strtolower($user)=='admin' && ($pass==md5('admin') || $pass==md5('Admin') || $pass==md5('ADMIN'))) { print GT($ca_account_msg); exit; }
		$body_section.=build_login_form_ca($ca_lang_l['CENTRAL ADMIN']);
	}
	$body_section=GT($body_section);
	print $body_section;
}
function set_admin_cookie()
{
	if(!isset($_COOKIE['visit_from_admin']))  // counter needed to ignore hits from site admin
	{
		$ts=mktime();
		$expire_ts=mktime(23, 59, 59, date ('n',$ts), date ('j',$ts), 2037);				
		setcookie('visit_from_admin',md5(uniqid(mt_rand(),true)),$expire_ts);		
	}
}
function set_delay()
{
	global $db_delay_file;

	$max_exec=ini_get('max_execution_time'); settype($max_exec,'integer');
	$delay=($max_exec>=12 || $max_exec<3)?10:$max_exec-2;
	$ts=mktime(); $last_wrong_ts=$ts;

	if(file_exists($db_delay_file) && is_writable($db_delay_file))
	{	
		$fsize=filesize($db_delay_file);
		if($fsize>0) 
		{
			$fp=fopen($db_delay_file,'r');
			$last_wrong_ts=fread($fp,$fsize); 
			settype($last_wrong_ts,'integer');
			fclose($fp);
		}
		if($ts-$last_wrong_ts<=30) sleep($delay);
		$fp=fopen($db_delay_file,'w');
		flock($fp, LOCK_EX); fwrite($fp,$ts);			
		flock($fp, LOCK_UN); fclose($fp);		
	}
	elseif($ts-$last_wrong_ts<=30) sleep($delay);
	//{while($ts-$last_wrong_ts<=$delay) {$ts=mktime(); continue;} }
}
function logout_user($action_id) 
{
	global $ca_template_file, $db_settings_file;
	
	if($action_id=='logoutadmin') { write_log('logout', 'USER:Administrator', 'success'); }
	if($action_id=='logout' && isset($_SESSION['SID_ADMIN'])) { write_log('logout', 'USER:Administrator', 'success'); }
	elseif(isset($_SESSION['cur_user'])) { $user=$_SESSION['cur_user']; write_log('logout', 'USER:'.$user, 'success'); }

	f_unset_session();
	$logout_redirect_url=f_read_tagged_data($db_settings_file,'logout_redirect_url');

	if(!empty($logout_redirect_url)) { $redirect_page_name=(strpos($logout_redirect_url,'http')===false? 'http://': '').$logout_redirect_url; }
	elseif(isset($_GET['ref_url'])) { $redirect_page_name=urldecode($_GET['ref_url']); }
	elseif(isset($_GET['pageid'])) 
	{
		$prot_page_info=get_page_info($_GET['pageid']); $prot_page_name=$prot_page_info[1];
		if(strpos($prot_page_name,'../')===false) { $redirect_page_name='../'.$prot_page_name; }
		else $redirect_page_name=$prot_page_name;
	}
	else 
	{
		$pos=strpos($ca_template_file,'http://');
		if($pos!==false) {$redirect_page_name=substr($ca_template_file,$pos);}	
		else {$redirect_page_name='../'.$ca_template_file;}
	}
	f_url_redirect($redirect_page_name,false); 
}

//function user_navigation($logged_as_label='logged as',$ca_label='central admin',$logout_label='logout',$change_label='change pass')
function user_navigation($logged_as_label='',$ca_label='',$logout_label='',$change_label='',$profile_label='')
{
	global $thispage_id,$l;
	
	$thispage_dir='';
	if(empty($_SESSION)) { f_int_start_session(); header("Cache-control: private"); }
    $logged_as_caadmin=isset($_SESSION['SID_ADMIN']);
    $logged_as_causer=isset($_SESSION['cur_user']);

	$prot_page_info=get_page_info($thispage_id);
	if(strpos($prot_page_info[1],'../')===false) {$thispage_dir='documents/';}
	else {$thispage_dir='../documents/';}

    $heading='';
	if(strtolower($logged_as_label)=='username' && $ca_label=='' && $logout_label=='' && $change_label=='')
	{
		if($logged_as_caadmin)	$heading=$_SESSION['SID_ADMIN'];
		elseif($logged_as_causer) $heading=$_SESSION['cur_user'];
	}
	else
	{
		$ca_url=$thispage_dir.'centraladmin.php?process=';
		$ref_url=$prot_page_info[1];
		if($logged_as_caadmin)
		{
			$heading.='<span class="rvts8">'.$logged_as_label.' ['.$_SESSION['SID_ADMIN'].'] </span> ';
			$heading.=':: <a class="rvts12" href="'.$ca_url.'index&amp;'.$l.'">'.$ca_label.'</a> ';
			$heading.=':: <a class="rvts12" href="'.$ca_url.'logoutadmin&amp;pageid='.$thispage_id.'&amp;'.$l.'">'.$logout_label.'</a>';
		}
		elseif($logged_as_causer || $logged_in_oep)
		{
			$heading.='<span class="rvts8">'.$logged_as_label.' ['.$_SESSION['cur_user'].'] </span> ';
			$heading.=':: <a class="rvts12" href="'.$ca_url.'logout&amp;pageid='.$thispage_id.'&amp;'.$l.'">'.$logout_label.'</a>';
		}					
		if($logged_as_causer) 
		{	
			$ca_detailed_url=$thispage_dir.'centraladmin.php?pageid='.$thispage_id.'&amp;ref_url='.urlencode($ref_url)
				.'&amp;username='.$_SESSION['cur_user'].'&amp;'.$l.'process=';	
			$heading.=' :: <a class="rvts12" href="'.$ca_detailed_url.'changepass">'.$change_label.'</a>';
			$heading.=' :: <a class="rvts12" href="'.$ca_detailed_url.'editprofile">'.$profile_label.'</a>';
		}
	}
    print $heading;
}
function scramble_string($string)
{
	$result='';
	$str_len=strlen($string);
	for($i=0; $i<$str_len; $i++) 
		{ $result.=Chr(Ord($string[$i])+ (($i && 1) + 1));}
	return $result;
}
function descramble_string($string)
{
	$result='';
	$str_len=strlen($string);
	for($i=0; $i<$str_len; $i++)
		{ $result.=Chr(Ord($string[$i]) - (($i && 1) + 1)); }
	return $result;
}
function users_import() 
{
	global $db_file;
	$result=false;
	$flag=false;
	$sections=array();		
	$sections_info=get_sections_list();
	foreach($sections_info as $k=>$v) $sections[]=$v[1];  //sections path list
	foreach($sections as $k=>$v) 
	{
		if(!empty($v)) 
		{
			$newdb_file=str_replace('.html','',$v).'users.ezg.php';
			$olddb_file=str_replace('.html','',$v).'users.php';
			if(file_exists($db_file) && filesize($db_file)==0) 
			{
				if(file_exists($newdb_file) && filesize($newdb_file)>0 || file_exists($olddb_file) && filesize($olddb_file)>0) {$flag=true;break;}
			}
		}
	}
	if($flag) 
	{	
		$existing_users_arr=array();
		$existing_users=db_get_users();
		if($existing_users!='') {$existing_users_arr=f_format_users($existing_users);}
		foreach($sections as $k=>$v) 
		{
			$new_users='';
			$newdb_file=str_replace('.html','',$v).'users.ezg.php';
			$olddb_file=str_replace('.html','',$v).'users.php';
			if(file_exists($newdb_file) && filesize($newdb_file)>0) $import_from_file=$newdb_file;
			elseif(file_exists($olddb_file) && filesize($olddb_file)>0) $import_from_file=$olddb_file;
				
			$fp=fopen($import_from_file,'r');$fsize=filesize($import_from_file);$buffer=fread($fp,$fsize);fclose($fp);

			$users=f_GFS($buffer,'<users>','</users>');				
			$users_arr=explode('|',$users);		
			foreach($users_arr as $k=>$v) 
			{
				if(!empty($v)) 
				{
					$t=explode(':',$v);
					if(!empty($existing_users_arr)) 
					{
						foreach($existing_users_arr as $k=>$v)
						{
							if(!in_array($t[0],$v)) 
							{db_write_user('add',$t[0],$t[1],'<access id="1" section="ALL" type="0"></access>','<details email="" name="" sirname="" date=""></details>');}
						}
					}
					else {db_write_user('add',$t[0],$t[1],'<access id="1" section="ALL" type="0"></access>','<details email="" name="" sirname="" date=""></details>');}
				}
			}			
		}		
		$result=true;
	}
	return $result;
}

function process_admin() 
{
	global $admin_username, $admin_pwd, $thispage_id, $version, $sp_pages_ids, $ca_account_msg, $http_prefix, $ca_template_file_f;
	global $ca_sitemap_file, $db_settings_file, $old_counter_db_fname, $counter_ds_db_fname, $sr_enable, $db_activity_log, $ca_template_file_f;
	global $l, $ca_available_lang_sets, $ca_lang_set, $pref_dir, $ca_lang_l, $set_login_cookie, $f_br, $f_ct, $rss_call_in_prot_page, $pref;
	global $counter_ts_db_fname, $pref_dir, $ca_lang_l, $l, $ca_first_line,$f_lf, $f_hr, $f_open_table_tag, $f_fmt_caption, $ca_td, $ca_span8;

	$action_id=''; $old_action_id=''; $access_flag=false; 	
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') $http_prefix='https://';
  	
	$admin_actions= array("index","manageusers","processuser","loginadmin","confcounter","resetcounter","log","clearlog","confreg", "pendingreg","conflang","export");
	if(empty($_GET) && empty($thispage_id)) $action_id='index';

	users_import();
	if(empty($_SESSION)) {f_int_start_session(); header("Cache-control: private");}

	if(isset($_GET['process']))			$action_id=$_GET['process'];
	elseif(isset($_POST['process']))	$action_id=$_POST['process'];
	if(isset($_GET['action']))			$old_action_id=$_GET['action']; // for old 'user logoff' 

	if($action_id=='logout' || $old_action_id=='logoff' || $action_id=="logoutadmin")	logout_user($action_id);
	elseif($action_id=="version")							echo $version;
	elseif($action_id=="register" && $sr_enable)			process_register($action_id);
	elseif($action_id=="register" && !$sr_enable)   
	{
		$output=GT($f_br."<span class='rvts8'><b>Sorry, self-registration is not enabled for this site.</b></span>"); 
		print $output; exit;  
	}
	elseif ($action_id=="captcha")		f_draw_captcha2();
	elseif($action_id=="forgotpass")	process_forgotpass();
	elseif($action_id=='sitemap')
	{
		$file_contents='';
		if(isset($_GET['pwd']) && crypt('admin',$_GET['pwd'])=='llRanR22sJYds')  $file_contents=f_read_file($ca_sitemap_file);
		$file_contents=str_replace(array('<?php echo "hi"; exit; /*','*/ ?>'),array('',''),$file_contents);
		print $file_contents; exit;	
	}	
	elseif(in_array($action_id,$admin_actions))
	{	
		if(!f_is_logged('SID_ADMIN') || f_is_logged('HTTP_USER_AGENT') && $_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['HTTP_USER_AGENT']) ) 
		{	 
			if(function_exists('session_regenerate_id') && version_compare(phpversion(),"4.3.3",">=") )  {session_regenerate_id();} 
			login_admin($action_id); exit;
		}	
		if($action_id=="index")				{index($action_id);}	
		elseif($action_id=="loginadmin")    {login_admin($action_id);}
		elseif($action_id=="manageusers")	{manage_users($action_id);}	
		elseif($action_id=="processuser")	{process_users($action_id);}
		elseif($action_id=="pendingreg")    {check_pending_users($action_id);} 
		elseif($action_id=="confcounter")   {conf_counter($action_id);} 	
		elseif($action_id=="resetcounter")  
		{
			if(isset($_GET['confirmreset']) && file_exists($counter_ts_db_fname) && (filesize($counter_ts_db_fname)!==0))
			{
				$files=array($counter_ts_db_fname,$counter_ds_db_fname);
				foreach($files as $k=>$v) {$fp=fopen($v,'r+');flock($fp,LOCK_EX);ftruncate($fp,0);fseek($fp,0);flock($fp,LOCK_UN);fclose($fp);}
				f_write_tagged_data("counter_cookie_suffix", mktime(), $db_settings_file, $ca_template_file_f); 
				clearstatcache();	
				$output="<span class='rvts8'>".ucfirst($ca_lang_l['reset done'])."</span>".$f_br.$f_br; 
				$flag=true;
			}	
			else 
			{
				$output=f_fmt_admin_title(ucfirst($ca_lang_l['reset counter'])).$f_br.$f_br."<span class='rvts8'>".ucfirst($ca_lang_l['reset MSG1'])."</span>".$f_br.$f_br; 
				$output.="<a class='rvts12' href='".$pref_dir."centraladmin.php?process=resetcounter&amp;confirmreset=confirm&amp;".$l."' onclick=\"javascript:return confirm('".ucfirst($ca_lang_l['reset MSG2'])."')\">".$ca_lang_l['confirm counter reset']."</a>".$f_br.$f_br; 
				$flag=false;
			}
			$output=f_fmt_admin_screen($output, build_menu($action_id));
			$output=GT($output,$flag);
			print $output;
		}
		elseif($action_id=="confreg")		{conf_registration($action_id);} 
		elseif($action_id=="conflang")    
		{
			$logout_redirect_url=f_read_tagged_data($db_settings_file,'logout_redirect_url');	
			$tzone_offset=f_read_tagged_data($db_settings_file,'tzoneoffset'); 
			$output="";
			if(isset($_POST['submit'])) 
			{  
				$ts=mktime(); setcookie('ca_lang',$_POST['lang'], mktime(23,59,59,date('n',$ts),date('j',$ts),2037));	
				f_write_tagged_data(array('logout_redirect_url','tzoneoffset'), array($_POST['logout_redirect_url'],$_POST['tzone_offset']), $db_settings_file, $ca_template_file_f);
				$output.="<span class='rvts8'>".ucfirst($ca_lang_l['settings saved'])."</span>";
			}
			else 
			{
				$output.="<form action='".$pref_dir."centraladmin.php?process=conflang' method='post' enctype='multipart/form-data'>";
				$output.=f_fmt_admin_title(ucfirst($ca_lang_l['settings'])).$f_br.$f_br;
				$output.="<table cellspacing='2' align='center' width='30%'><tr><td><span class='rvts8'>".ucfirst($ca_lang_l['language']).'&nbsp;&nbsp;'.f_build_select('lang',$ca_available_lang_sets,$ca_lang_set).$f_br."</span></td><td style='text-align:right'><span class='rvts8'>".ucfirst($ca_lang_l['set tzone']).'</span>&nbsp;&nbsp;'."<input class='input1' name='tzone_offset' type='text' value='".$tzone_offset."' size='3'".$f_ct.$f_br."</td></tr>";
				$output.="<tr><td colspan='2'><span class='rvts8'>".ucfirst($ca_lang_l['redirect page'])."</span>".$f_br."<input class='input1' type='text' name='logout_redirect_url' style='width:350px' value='".$logout_redirect_url."'".$f_ct."</td></tr>";
				$output.="<tr><td colspan='2' align='center'><span class='rvts8'><i>".ucfirst($ca_lang_l['redirect page msg']).$f_br.$f_br."</i></span><input class='input1' name='submit' type='submit' value=' ".ucfirst($ca_lang_l['submit'])." '".$f_ct." <input  class='input1' type='button' value=' ".ucfirst($ca_lang_l['cancel'])." ' onclick=\"javascript:history.back();\"".$f_ct. "</td></tr></table></form>";
			}
			$output=f_fmt_admin_screen($output, build_menu($action_id));
			$output=GT($output);
			print $output;	
		}  	
		elseif($action_id=="log")			
		{	
			$logcontent=array();
			clearstatcache();
			if(file_exists($db_activity_log))
			{
				$handle=fopen($db_activity_log,'r');
				while($data=fgetcsv($handle, 8192,'%')) 
				{
					if($data[0]!=$ca_first_line) 
					{   
						list($dt,$temp,$result)=explode('=>',$data[0]);
						list($activity,$user)=explode('->',$temp);
						if(strpos($user,'EMAIL:')!==false) $user=f_GFS($user,'USER:','EMAIL:');
						elseif(strpos($user,'ID:')!==false) $user=f_GFS($user,'USER:','ID:');
						else $user=str_replace('USER:','',$user);
						$logcontent[]=array('date'=>trim($dt),'activity'=>trim($activity),'user'=>$user, 'result'=>str_replace('Result:','',$result));
					}
				}
				fclose($handle);
			}
			$output=f_fmt_admin_title(ucfirst($ca_lang_l['log'])).$f_br.$f_br
			.'<form method="POST" action="'.$pref_dir.'centraladmin.php?process=clearlog&amp;'.$l.'" enctype="multipart/form-data">'; 		

			if(!empty($logcontent))
			{
				$logcontent=array_reverse($logcontent);
				$output.=$f_open_table_tag."<tr>".$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['date'])).$f_hr."</td>"
				.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['activity'])).$f_hr."</td>"
				.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['user'])).$f_hr."</td>"
				.$ca_td.sprintf($f_fmt_caption, ucfirst($ca_lang_l['result'])).$f_hr."</td></tr>";
				foreach($logcontent as $key=>$value)
				{
					if(!empty($value)) 
					{
						if(strpos($value['date'],':')) $date_value=$value['date'];
						else $date_value=date('d M Y h:i:s',ca_tzone_date($value['date']));
						$output.="<tr>".$ca_td.$ca_span8.$date_value."</span></td>".$ca_td.$ca_span8." :: ".$value['activity']."</span></td>";
						$output.=$ca_td.$ca_span8.$value['user']."</span></td>".$ca_td.$ca_span8." :: ".$value['result']."</span></td></tr>";
					}
				}
				$output.="</table>".$f_br.$f_br."<input class='input1' type='submit' value=' ".ucfirst($ca_lang_l['clear log'])." ' onclick=\"javascript:return confirm('".ucfirst($ca_lang_l['clear log MSG'])."')\"".$f_ct."</form>";
			}
			$output=f_fmt_admin_screen($output, build_menu($action_id));
			$output=GT($output);
			print $output;
		}
		elseif($action_id=="clearlog")		
		{
			if(!$handle=fopen($db_activity_log,'r+')){print f_fmt_in_template($ca_template_file_f,f_fmt_error_msg('DBFILE_NEEDCHMOD',$db_activity_log)); exit;}
			ftruncate($handle,0); fseek($handle,0); fclose($handle);	
			$output="<span class='rvts8'>".ucfirst($ca_lang_l['log file cleared'])."</span>".$f_br.$f_br;
			$output=f_fmt_admin_screen($output, build_menu($action_id));
			$output=GT($output);
			print $output;
		}
		elseif($action_id=="export")
		{
			$output='';
			$users=db_get_users();
			if($users!='') {$users_array=f_format_users($users);}
			else {$users_array=array();}
			if(count($users_array)>1)
			{
				foreach ($users_array as $key => $row) { $name[$key]=$row['username'];  }
				$name_lower=array_map('strtolower',$name);
				array_multisort($name_lower,SORT_ASC,$users_array); 
			}
			if(!empty($users_array)) 
			{   
				$field_names=array('username','name','sirname','email','creation_date','self-registered');		
				foreach($field_names as $k=>$v) { $output.=($k==0?'':',').'"'.f_sth(urldecode($v)).'"'; }
				$output.=$f_lf;
				
				foreach($users_array as $key=>$value) 
				{					
					$rec=array_keys($value);
					$output.='"'.f_sth(urldecode($value['username'])).'"';
					$output.=',"'.f_sth(urldecode($value['details']['name'])).'"';
					$output.=',"'.f_sth(urldecode($value['details']['sirname'])).'"';
					$output.=',"'.f_sth(urldecode($value['details']['email'])).'"';
					$output.=',"'.$value['details']['creation_date'].'"';
					$output.=',"'.(isset($value['details']['sr']) && $value['details']['sr']=='1'? 'Yes': 'No').'"';
					$output.=$f_lf;
				}
			}		
			header("Pragma: public"); header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public"); header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"users_export.csv\";");
			header("Content-Transfer-Encoding: binary");
			print $output; exit;
		}	
	}
	else 
	{	
		if(empty($_POST) && empty($thispage_id) && !isset($_GET['pageid'])) 
			{ f_url_redirect($pref_dir."centraladmin.php?process=index",false); exit; }

		$user=$admin_username;
		$pass=$admin_pwd;
		if(isset($_POST['pv_username'])) $pv_username=trim($_POST['pv_username']);
		if(isset($_POST['pv_password'])) $pv_password=trim($_POST['pv_password']);
		if(isset($_POST['pv_username']) && isset($_POST['pv_password'])) $pass_filled=md5($pv_password);
			
		if(isset($_GET['pageid']) && isset($_POST['loginid'])) // when login page is directly accessed
		{
			$cur_section=trim($_POST['loginid']);
			if($_GET['pageid']=="0" && $thispage_id=="0")   
			{
				$controlled_pages=get_prot_pages_list($cur_section);  $protected_pages=array();
				foreach($controlled_pages as $k=>$v) { if($v['protected']=='TRUE') $protected_pages[]=$v['id'];  }
				
				if(!empty($protected_pages))
				{
					$redirect_to_page='';
					$user_account=db_get_specific_user($pv_username);
					
					if($user==$pv_username && $pass==$pass_filled) $redirect_to_page=$protected_pages[0];
					elseif(!empty($user_account))
					{
						$user_password=$user_account['password'];
						if($user_account['username']==$pv_username && $user_password==crypt($pv_password,$user_password))   
						{
							if($user_account['access'][0]['section']!='ALL')
							{
								foreach($user_account['access'] as $k=>$v)
								{
									if($cur_section==$v['section'])
									{
										if($v['type']!='2')	{$redirect_to_page=$protected_pages[0]; break; }
										elseif(isset($v['page_access']))
										{
											foreach($v['page_access'] as $key=>$val)
											{
												if($val['type']=='0' && in_array($val['page'],$protected_pages)) 
													{$redirect_to_page=$val['page']; break; }
											}
										} 
									}
								}				
							} 
							else { $redirect_to_page=$protected_pages[0];  }						
						}
						else { set_delay(); error();}				
					}
					else { set_delay(); error();}	
				}
				if(empty($redirect_to_page))
				{
					$output=GT($f_br."<span class='rvts8'><b>This Login page is not associated with any protected page. The system doesn't know where to redirect you.".$f_br."You have to go to EZG and protect certain page with this Login page.</b></span>"); 
					print $output; exit; 
				}
				else
				{
					$prot_page_info=get_page_info($redirect_to_page);
					$thispage_id=str_replace('<id>','', trim($prot_page_info[10])); 
				}
			}
			if(!isset($pv_username) || !isset($pv_password) ) { set_delay(); error();}
			elseif(strtolower($user)=='admin' && strtolower($user)==strtolower($pv_username) && ($pass==md5('admin') || $pass==md5('Admin') || $pass==md5('ADMIN'))  &&  ($pass==md5(strtolower($pv_password)) || $pass==md5(ucfirst($pv_password)) || $pass==md5(strtoupper($pv_password)))) { print GT($ca_account_msg); exit; }
			elseif(checkauth($pv_username,$pv_password)==false) 
			{ 
				if($user!=$pv_username || $pass!=$pass_filled) {set_delay(); error();}
			}
		}
		$prot_page_info=get_page_info($thispage_id); 
		$prot_page_name=$prot_page_info[1];
		 
		if($rss_call_in_prot_page && in_array($prot_page_info[4],array('136','137','138','143','144'))) // public rss when page is protected
		{
			if($prot_page_info[4]=='137') $rss_set_dir='blog/';
			elseif($prot_page_info[4]=='138') $rss_set_dir='photoblog/';
			elseif($prot_page_info[4]=='136') $rss_set_dir='ezg_data/';
			elseif($prot_page_info[4]=='143') $rss_set_dir='podcast/';
			elseif($prot_page_info[4]=='144') $rss_set_dir='guestbook/';

			if($prot_page_info[4]=='144') $rss_public_on=f_read_file($pref.$rss_set_dir.$thispage_id."_db_guestbook.ezg.php");
			elseif($prot_page_info[4]=='136') $rss_public_on=f_read_file($pref.$rss_set_dir.$thispage_id."_settings.ezg.php"); 
			else $rss_public_on=f_read_file($pref.$rss_set_dir.$thispage_id."_blocked_ips.ezg.php"); 
			$rss_public_on=f_GFS($rss_public_on,'<public_rss>','</public_rss>');			
		}
														//start of actual pwd protection check
		if(isset($rss_public_on) && $rss_public_on=='1') {$access_flag=true;} 
		elseif(!f_is_logged('SID_ADMIN') || f_is_logged('HTTP_USER_AGENT') && $_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['HTTP_USER_AGENT']) || isset($_GET['ref_url']))   
		{
			if(!isset($_SESSION['cur_user']) || checkauth($_SESSION['cur_user'],'none', true)==false) 
			{
				if(!isset($pv_username) && !isset($pv_password)) 
				{
					if(isset($_GET['ref_url']) && strpos($_GET['ref_url'],'action=register')!==false)
						{ $ms='Identify yourself with username and password before registering for event.'; }
					elseif(isset($_GET['ref_url']) 
						&& (strpos($_GET['ref_url'],'action=chregister')!==false||strpos($_GET['ref_url'],'action=clregister')!==false))
						{ $ms='Identify yourself with username and password before changing or canceling your registration.'; }
					elseif(isset($_GET['ref_url']) && strpos($_GET['ref_url'],'event_id=')!==false)
						{ $ms='Identify yourself with username and password before checking attendees list.'; }
					else { $ms=''; }
						
					$ref_url=(isset($_GET['ref_url'])? urldecode($_GET['ref_url']): ''); //event manager
					
					if(strtolower($user)=='admin' && ($pass==md5('admin') || $pass==md5('Admin') || $pass==md5('ADMIN')))
						{print GT($ca_account_msg); exit;}
					
					$contents=build_login_form($ms, $ref_url);
					$error_pattern=f_GFSAbi($contents,'<!--[error_message]','-->');		
					if($error_pattern!='') { $contents=str_replace($error_pattern,'',$contents); }		
					print $contents; exit;
				}
				else 
				{				
					if(!isset($pv_username) || !isset($pv_password) ) {error();}				
					if(checkauth($pv_username,$pv_password)==true) 
					{
						if(function_exists('session_regenerate_id') && version_compare(phpversion(),"4.3.3",">=") )  {session_regenerate_id();}
						f_set_session_var('cur_user',$pv_username);
						write_log('login', 'USER:'.$pv_username, 'success');
						if($set_login_cookie==true)	{setcookie("logged",$pv_username, time()+60*60*24);}
						//if(isset($_POST['remember']))	{setcookie("vid", md5($pv_username), time()+14*24*60*60);}
						$access_flag=true;
						//if(isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'],'stockwatcher.be')!==false) //JPC
						//{
						//	$user_ac=db_get_specific_user($pv_username);
						//	header("Location: http://www.stockwatcher.be/documents/logged.html?admin=stockwatcher&referrer_url=" .str_replace('../','',$prot_page_name) ."&u=".scramble_string($pv_username)."&n=".scramble_string($user_ac['details']['name']) ."&s=".scramble_string($user_ac['details']['sirname'])."&e=".scramble_string($user_ac['details']['email']) ."&t=".scramble_string(mktime()) 
						//	."&ip=".(isset($_SERVER['REMOTE_ADDR'])?scramble_string($_SERVER['REMOTE_ADDR']):"") );
						//	exit;
						//}
					}
					else 
					{					
						if($user!=$pv_username || $pass!=$pass_filled) {set_delay(); error();  }  //wrong username or password
						if($user==$pv_username && $pass==$pass_filled) 
						{	
							if(function_exists('session_regenerate_id') && version_compare(phpversion(),"4.3.3",">=") )  {session_regenerate_id();}
							f_set_session_var('SID_ADMIN',$pv_username);
							write_log('login', 'USER:Administrator', 'success');
							if($set_login_cookie==true)	{ setcookie("logged","admin",time()+60*60*24); } 							
							if(isset($_SERVER['HTTP_USER_AGENT'])) { f_set_session_var( 'HTTP_USER_AGENT',md5($_SERVER['HTTP_USER_AGENT'])); }
							set_admin_cookie(); // for counter - to ignore hits from site admin
							$access_flag=true;
						}
					}
				}
			}
			else {$access_flag=true;}
		}
		else {$access_flag=true;}  //end of actual pwd protection check

		if($access_flag==true)
		{
			if($action_id=="changepass")      process_changepass();  //m
			elseif($action_id=="editprofile") process_editprofile();  //m
		}			
		if(isset($_GET['pageid']))  
		{
			if($access_flag==true) 
			{
				$load_page=$prot_page_name; 
				if(isset($_GET['indexflag']))
				{
					if($prot_page_info[4]=='143' && strpos($prot_page_info[1],'?flag=podcast')!==false) 
					{$load_page=$prot_page_name.'&action=index&'.$l;}
					elseif($prot_page_info[4]=='133')
					{$load_page=(strpos($prot_page_info[1],'../')!==false? '../':''). 'subscribe/subscribe_'.str_replace('<id>','',$prot_page_info[10]).'.php?action=subscribers&'.$l;}
					elseif($prot_page_info[4]=='20') 
					{
						if(isset($_SESSION['cur_pwd'.$_GET['pageid']])) $r_with='action=remcookie';
						else											$r_with='action=doedit';
						if(strpos($prot_page_name,'action=show')!==false)
							$load_page=str_replace('action=show',$r_with,$prot_page_name);
						else $load_page=$prot_page_name.'?'.$r_with;
					}
					elseif($prot_page_info[4]=='21') 
					{
						if(strpos($prot_page_name,'action=list')!==false) 
							$load_page=str_replace('action=list','action=orders',$prot_page_name);					
						else $load_page=$prot_page_name.'?action=orders';
					}
					else  {$load_page=$prot_page_name.'?action=index&'.$l;}
				}		
				elseif($prot_page_info[15]=='0' && ($prot_page_info[3]=='1' || $prot_page_info[3]=='0' && strpos($prot_page_info[1],'/SUB_')!==false) ) // FRAMES and SUBPAGE
				{
					if($prot_page_info[7]>0)
					{   
						$login_page_info=get_page_info($prot_page_info[7]);
						if(strpos($prot_page_info[1],'/SUB_')!==false)
						{
						  if(isset($login_page_info[3]) && $login_page_info[3]=='0') $load_page=str_replace('SUB_','',$load_page);
						}                   
						elseif(in_array($prot_page_info[4],$sp_pages_ids))
						{  
						  if(isset($login_page_info[3]) && $login_page_info[3]=='0') $load_page=str_replace('<id>','',$prot_page_info[10]).'.php';
						}					 
					}
				}
				if(isset($_GET['ref_url']))			{ $load_page=urldecode($_GET['ref_url']);  } //event manager
				if(strpos($prot_page_name,'../')===false) {$load_page='../'.$load_page;}
				f_url_redirect($load_page,false); exit;
			}
		}
	}
}
process_admin();
?>
