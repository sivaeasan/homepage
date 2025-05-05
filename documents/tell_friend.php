<?php
$version = "tell a friend 1.41";
/*
	tell_friend.php
	http://www.ezgenerator.com
    Copyright (c) 2004-2008 Image-line  
*/
error_reporting(E_ALL);
if(get_magic_quotes_runtime()==1) set_magic_quotes_runtime(0);	
include ('../documents/htmlMimeMail.php');
include_once ('../ezg_data/functions.php');

$bg_tag='background: #FFFFFF;';
$sa_mode='1';  // 0 - embedded on page with JS; 1 - standalone (normal);  2 - as hidden div; 3 - inside page
$log_fname="tell_friend_log.ezg.php";
$doc_dir='documents';
$frames_on=false;
$proj_charsets='iso-8859-1|'; 
$proj_charsets_array=explode('|', $proj_charsets); array_pop($proj_charsets_array);
$proj_languages='English|';  $proj_languages=urldecode($proj_languages);    
$proj_languages_array=explode('|', $proj_languages); array_pop($proj_languages_array);

$current_lang=(isset($_GET['language'])? $_GET['language']: (isset($_POST['language'])?$_POST['language']:$proj_languages_array[0]));
$page_charset=$proj_charsets_array[array_search($current_lang, $proj_languages_array)] ; 
if($page_charset=='0')		$page_charset='utf-8';
elseif($page_charset=='')	$page_charset='iso-8859-1';
$http_pref='http://'; if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') $http_pref='https://';
$full_path_to_script=$http_pref.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']);

$default_language_strings=array('tell_friend'=>'tell a friend', 'your_name'=>'your name', 'your_email'=>'your email', 'recipient_email'=>'recipient email', 'message'=>'message', 'send'=>'send', 'clear_fields'=>'clear fields', 'close'=>'close', 'administrator'=>'administrator', 'required_msg'=>'Fields marked with * are required', 'email_msg'=>'Invalid email address', 'on_success_msg'=>'The Message Was Sent', 'on_fail_msg'=> 'The Message Could Not Be Sent','code'=>'code'); 
$settings_keys=array('tell_friend', 'your_name', 'your_email', 'recipient_email', 'message', 'send', 'clear_fields', 'close', 'administrator', 'required_msg', 'email_msg', 'on_success_msg', 'on_fail_msg', 'code', 'from_address', 'subject', 'default_message', 'hidden_message', 'include_url', 'allow_msg_change','include_captcha'); 
$first_line="<?php echo 'hi'; exit; /* ";
$last_line=" */ ?>";
$default_msg="Dear Friend, I am happy to share with you this interesting site:";
$max_line_chars=25000;
$source_page=f_define_source_page();
$rel_path=(strpos($source_page,'../')===false? '': '../'); 
$source_page=(strpos($source_page,'../')!==false? '': '../').$source_page;

// ---------------------------------------------------------------------
function prepare_for_write($data)
{
	foreach ($data as $k=>$v) {$temp=trim($v); $data[$k]=f_esc($v);}
	$line=implode('|',$data);
	return $line;
}
function build_ass_array_record($value, $key)  // format data  from db  as a record ( associative array)
{
	$output=array();
	foreach($key as $k=>$v) 
	{
		$output[$v]=(current($value)?current($value):'NULL'); 
		next($value);
	}
	return $output;
}
function define_lang_label($labels,$name)
{
	global $default_language_strings;
	return (isset($labels[$name]) && $labels[$name]!='NULL'? f_sth($labels[$name]): $default_language_strings[$name]);
}
function get_language_labels($lang)
{
	global $log_fname, $default_language_strings, $settings_keys;
		
	$logcontent=f_read_file($log_fname);
	if(strpos($logcontent,'<LANGUAGE_'.$lang)!==false)
	{
		$lang_params=f_GFS($logcontent,'<LANGUAGE_'.$lang.'>','</LANGUAGE_'.$lang.'>');
		$lang_params=str_replace(array('<LANGUAGE_'.$lang.'>','</LANGUAGE_'.$lang.'>'),array("",""), $lang_params);
		$data_t=explode('|', $lang_params);
		$data=build_ass_array_record($data_t, $settings_keys);
	}
	else { $data=$default_language_strings; }
	return $data;
}
function GT($html_output, $msg, $include_menu=true) 
{
	global $doc_dir, $sa_mode, $bg_tag, $http_pref, $source_page, $rel_path; //(strpos($source_page,'../')!==false? '': '../')
    
	$contents=f_fmt_in_template($source_page, $html_output, '', $bg_tag, $include_menu); 	
	
	if($sa_mode!='3' && (!$include_menu)) 
	{ 
		$contents=str_replace(f_GFS($contents,'<!--menu_java-->','<!--/menu_java-->'),'',$contents);
		$contents=str_replace('onload="preloadimages();"','',$contents);
	}	
	if($rel_path=='') 
	{ 
		$contents=str_replace('</title>','</title> <base href="'.$http_pref.$_SERVER['HTTP_HOST'] .str_replace($doc_dir,'',dirname($_SERVER['PHP_SELF'])).'">',$contents); 
		$contents=str_replace('action="../','action="',$contents); 
	}
	$contents=str_replace('<title>'.f_GFS($contents,'<title>','</title>').'</title>','<title>'.$msg.'</title>',$contents); 
	return $contents;
}
function db_write_data($record_line,$open_tag,$close_tag,$flag='log')  //  writing data in log file
{ 
	global $log_fname, $first_line, $last_line, $f_lf, $f_use_linefeed, $source_page;
	$buf="";
	$old_data="";
		
	clearstatcache(); 
	if(file_exists($log_fname))
	{
		if(!$handle=@fopen($log_fname,"r+")) {print f_fmt_in_template($source_page,f_fmt_error_msg('DBFILE_NEEDCHMOD',$log_fname)); exit;}
		flock($handle,LOCK_EX);
		if(filesize($log_fname)==0) $buf.=$first_line.$open_tag.$record_line.$close_tag.$last_line;
		else 
		{
			$old_data=fread($handle,filesize($log_fname));
			if($flag=="log") 
			{
				if(strpos($old_data,$close_tag)!==false) $buf.=str_replace($close_tag,$record_line.$close_tag." ",$old_data);
				else $buf.=str_replace($last_line,$open_tag.$record_line.$close_tag.$last_line,$old_data);
			}
			else 
			{
				if(strpos($old_data,$close_tag)!==false)
				{
					$for_replace=substr($old_data,strpos($old_data, $open_tag),strpos($old_data, $close_tag)-strpos($old_data,$open_tag)+ strlen($close_tag));
					$buf.=str_replace(trim($for_replace),$open_tag.$record_line.$close_tag." ",$old_data);
				}
				else $buf.=str_replace($last_line,$open_tag.$record_line.$close_tag.$last_line,$old_data);
			}
			if(ftruncate($handle,0)===false) {echo "Failed to truncate file --> last update failed"; exit;}
			fseek($handle,0);
		}
		if(fwrite($handle,$buf)===FALSE) {echo "Failed to edit file --> last update failed";exit;}
		flock($handle,LOCK_UN);
		fclose($handle);
	}
}
function build_tell_friend_form($labels,$suggested_url,$msg='',$sender_name="",$sender_from="",$send_to="",$message="")
{
	global $full_path_to_script, $current_lang, $default_language_strings, $default_msg, $f_br, $f_ct;
	global $frames_on, $page_charset, $sa_mode, $f_lf, $doc_dir, $rel_path, $f_ct;

	$span8='<span class="rvts8">%s<em style="color:red;">*</em></span>'.$f_br;
	$inp='<input class="input1" type="text" name="%s" value = "%s" style="width:270px" maxlength="50"'.$f_ct.$f_br;
	
	if(strpos($suggested_url, "../")!==false || strpos($suggested_url, "/")==0) 
	{
		$full_path_fixed=str_replace('/'.$doc_dir, '', $full_path_to_script).str_replace('..', '', $suggested_url);
	}
	else { $full_path_fixed=$full_path_to_script."/".str_replace($doc_dir.'/', '', $suggested_url); }

	if(isset($labels['include_url']) && $labels['include_url']=='no') { $full_path_fixed = ""; }		
	if($frames_on) 
	{
		$full_path_fixed=$full_path_to_script."/". "tell_friend.php?action=load&amp;language=$current_lang&amp;charset=$page_charset&amp;url=$full_path_fixed";
	}
	if($message!='') {  $default_message=f_un_esc($message); }
	else { $default_message=((isset($labels['default_message']) && $labels['default_message']!='NULL')?
	f_sth($labels['default_message']): $default_msg).' '.$full_path_fixed; }

	if($sa_mode=='0') $default_message=str_replace(array("\r\n","\r","\n"),array("","",""),$default_message);

	$addform_html='<div style="padding:10px">'.$f_br.'<form name="tell_friend_frm" action="../'.$doc_dir.'/tell_friend.php?action=send&amp;charset=' .$page_charset.'&amp;sa='.$sa_mode.(isset($_GET['divid'])?'&amp;divid='.$_GET['divid']:'').($sa_mode!='1'?'&amp;url='.$suggested_url:'').'" method="post" name="tell_friend" enctype="multipart/form-data">';
	$addform_html.='<span class="rvts8"><b>'.define_lang_label($labels,'tell_friend').$f_br.$msg.'</b></span>'.$f_br;
	$addform_html.=sprintf($span8,define_lang_label($labels,'your_name')).sprintf($inp,'Sender',f_un_esc($sender_name)).'<input class="input1" type="hidden" name="language" value="'.$current_lang.'"'.$f_ct;
	$addform_html.=sprintf($span8,define_lang_label($labels,'your_email')).sprintf($inp,'Sender_email',$sender_from);
	$addform_html.=sprintf($span8,define_lang_label($labels,'recipient_email')).sprintf($inp,'Recipient_email',$send_to);
	$addform_html.=sprintf($span8,define_lang_label($labels,'message'));
	$addform_html.='<textarea class="input1" style="width:270px" name="Message" cols="50" '.(isset($labels['allow_msg_change']) && ($labels['allow_msg_change']=='no')?'readonly="readonly"':'').' rows="15">'.$default_message.'</textarea>'.$f_br;
	
	if(isset($labels['include_captcha']) && $labels['include_captcha']=='yes') 
	{
		$addform_html.=sprintf($span8,define_lang_label($labels,'code')).'<input class="input1" type="text" name="Validator" id="validator" size="6" maxlength="4"'.$f_ct; 
		if(f_is_able_build_img())
		{	  
			$addform_html.=' <img src="'.($sa_mode!=0? $rel_path: (substr_count($suggested_url, '/')>1? '../': '')).
			$doc_dir.'/tell_friend.php?action=captcha" border="0" alt="" style="vertical-align: middle;"'.$f_ct;
		}
		else 
		{
			$captcha=f_generate_captcha_code(); 
			f_set_session_var('CAPTCHA_CODE',md5($captcha));
			$addform_html.=' <span class="rvts0"><b>'.$captcha.'</b></span>';
		}
		$addform_html.=$f_br.$f_br; 	
	}
	$addform_html.='<input class="input1" name="Send" type="submit" value=" '.define_lang_label($labels,'send').' "'.$f_ct.' <input class="input1" type="button" value=" '.define_lang_label($labels,'clear_fields').' "  onclick="javascript:document.tell_friend_frm.reset();"'.$f_ct; 
	if($sa_mode=='1') $addform_html.= ' <input class="input1" type="button" value="  '.define_lang_label($labels,'close'). ' " onclick="javascript:window.close();"'.$f_ct;
	$addform_html.='</form></div>';
 
	return $addform_html;
}
function build_settings_form($dir, $language, $labels_data) 
{
	global $proj_languages_array, $current_lang, $settings_keys, $default_language_strings, $page_charset, $f_br, $f_ct;
	$inp="<div style='position:relative;height:23px;'>%s<div style='position:absolute;left:150px;top:0px;'><input class='input1' type='text' name='%s' value='%s' style='width:250px' maxlength='%s'".$f_ct."</div></div>";
	$inpc="<input type='checkbox' name='%s' value='yes' %s".$f_ct."%s";
	$area="<div style='position:absolute;top:0px;left:%spx;'>%s".$f_br."<textarea class='input1' name='%s' cols='35' rows='8' style='width:190px'>%s</textarea></div>";
	$jstring="onchange=\"document.location='".$dir."tell_friend.php?action=admin&amp;charset=".$page_charset."&amp;language=' + this.options[this.selectedIndex].value;\"";

	$body_section="<div class='rvts8' style='width:400px;text-transform:capitalize'><div align='left'><form method='post' action='".$dir."tell_friend.php?action=admin&amp;charset="."' enctype='multipart/form-data'>";
	$body_section.=$f_br."<div style='position:relative;height:35px;'><b>LANGUAGE</b>";
	$body_section.="<div style='position:absolute;left:150px;top:0px;'>".f_build_select("language", $proj_languages_array, $language, '', 'value', $jstring) .'</div></div>';  
	foreach($labels_data as $k=>$v) 
	{
		if(array_key_exists($k,$default_language_strings)) 
		{
			if($k=='required_msg')			$ms='required fields msg';
			elseif($k=='email_msg')			$ms='email not valid msg'; 
			elseif($k=='on_fail_msg')		$ms='fail message';
			elseif($k=='on_success_msg')$ms='success message';
			else $ms=$default_language_strings[$k];
			$body_section.=sprintf($inp,$ms,$k,($v!='NULL'?f_sth($v):""),'50');			
		}
	}
	$body_section.=$f_br.sprintf($inp,'from address','from_address',($labels_data['from_address']=='NULL'?"" :f_sth($labels_data['from_address'])),'250');
	$body_section.=sprintf($inp,'subject','subject',($labels_data['subject']=='NULL'?"" :f_sth($labels_data['subject'])),'250');
	$body_section.="<div style='position:relative;height:130px;'>".sprintf($area,'0','default message','default_message',($labels_data['default_message']=='NULL'?"" :f_sth($labels_data['default_message'])));
	$body_section.=sprintf($area,'210','hidden message','hidden_message',($labels_data['hidden_message']=='NULL'?"" :f_sth($labels_data['hidden_message'])))."</div>";
	$body_section.="<div style='height:45px;'>".sprintf($inpc,'include_url',($labels_data['include_url']=='NULL'||$labels_data['include_url']=='yes'? " checked='checked'" :''),'include page URL in msg&nbsp;&nbsp;');
	$body_section.=sprintf($inpc,'allow_msg_change',($labels_data['allow_msg_change']=='NULL'||$labels_data['allow_msg_change']=='yes'? " checked='checked'" :''),'allow users to edit msg'.$f_br);
	$body_section.=sprintf($inpc,'include_captcha',($labels_data['include_captcha']=='yes'? " checked='checked'":''),'include captcha')."</div>";
	$body_section.="<input class='input1' name='save' type='submit' value=' ".'save changes'." '".$f_ct;
	$body_section.="</form></div></div>";
	return $body_section;	
}
function send($suggested_url) 
{
	global $f_mail_type,$f_use_linefeed,$current_lang,$f_lf,$default_language_strings,$sa_mode,$page_charset,$doc_dir, $f_return_path;
	global $f_SMTP_HOST,$f_SMTP_PORT,$f_SMTP_HELLO,$f_SMTP_AUTH,$f_SMTP_AUTH_USR,$f_SMTP_AUTH_PWD, $f_br, $f_ct, $f_sendmail_from;
	
	$body_section="";
	$sender_name=f_un_esc($_POST['Sender']);
	$sender_email=$_POST['Sender_email'];
	$send_to=$_POST['Recipient_email'];
	$message=f_un_esc($_POST['Message']);
	$send_to_array=array($_POST['Recipient_email']);
	$sender_ip=(isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"unknown");
	$labels=get_language_labels($current_lang);
	$time=date("d M Y H:i:s"); 
	$spem="<span class='rvts8'><em style='color: red;'>";	
	$dir=$doc_dir.'/';
	if($f_sendmail_from!='') ini_set('sendmail_from',$f_sendmail_from);

	if(empty($_SESSION)) f_int_start_session();
	if(!f_is_logged('SID_ALLOW_TELLFRIEND')) {echo "This is illegal operation. You are not allowed to use this Tell a friend.";exit;}

	if(isset($_POST['Send']))
	{
		if(empty($_POST['Sender']) || empty($_POST['Sender_email']) || empty($_POST['Recipient_email']) || empty($_POST['Message'])) 
		{
			$msg=$spem.define_lang_label($labels,'required_msg')."</em></span>";	
			$body_section.=build_tell_friend_form($labels,$suggested_url,$msg,$sender_name,$sender_email,$send_to,$message);
		}
		elseif(!f_validate_email($sender_email) || !f_validate_email($send_to)) 
		{
			$msg=$spem.define_lang_label($labels,'email_msg')."</em></span>";		
			$body_section.=build_tell_friend_form($labels,$suggested_url,$msg,$sender_name,$sender_email,$send_to,$message);						
		}
		elseif(isset($labels['include_captcha']) && $labels['include_captcha']=='yes' && (!isset($_POST['Validator']) || $_POST['Validator']=='' || md5(strtolower($_POST['Validator']))!=$_SESSION['CAPTCHA_CODE'])) 
		{
			$msg=$spem.define_lang_label($labels,'required_msg')."</em></span>";	
			$body_section.=build_tell_friend_form($labels, $suggested_url, $msg, $sender_name, $sender_email, $send_to, $message);
		}
		else 
		{
			$message.=$f_lf.$f_lf.(isset($labels['hidden_message'])&&($labels['hidden_message']!='NULL')? str_replace(array('\\\\','\\\'','\"'), array('\\','\'','"'), $labels['hidden_message']): "");
			$send_from=(isset($labels['from_address']) && ($labels['from_address']!='NULL')? $labels['from_address']: $sender_email);
			$message=str_replace(array("%%SENDERNAME%%","%%SENDEREMAIL%%"),array($sender_name,$sender_email), $message);
			$message=str_replace(array("%%SENDERIP%%","%%RECIPIENTEMAIL%%"),array($sender_ip,$send_to), $message);

			$mail=new htmlMimeMail();
			if($f_use_linefeed) $mail->setCrlf("\r\n");
			$mail->setHeadCharset($page_charset); 
			$mail->setTextCharset($page_charset); 
			$mail->setSubject(isset($labels['subject']) && ($labels['subject']!='NULL')?str_replace(array('\\\\','\\\'','\"'),array('\\','\'','"' ),$labels['subject']): 'I want to share with you');
			$mail->setText(str_replace(array('\\\\','\\\'','\"'),array('\\','\'','"'),$message));				
			$mail->setFrom(str_replace(array('\\\\','\\\'','\"'),array('\\','\'','"'),$send_from));
			if ($f_return_path!= '')  $mail->setReturnPath($f_return_path);
			if(($f_mail_type=='smtp')&&($f_SMTP_HOST!=='')) $mail->setSMTPParams($f_SMTP_HOST,$f_SMTP_PORT,$f_SMTP_HELLO,$f_SMTP_AUTH,$f_SMTP_AUTH_USR,$f_SMTP_AUTH_PWD);

			$body_section.="<div align='center'>".$f_br."<span class='rvts8'>";
			if((strpos(strtolower($message),'mime-version')!==false) || (strpos(strtolower($message),'content-type')!==false))
			{
				$msg="FAILED - possible dangerous content";
				$body_section.=define_lang_label($labels,'on_fail_msg')."</span>";
			}
			else 
			{
				$result=$mail->send($send_to_array,$f_mail_type);
				if($result) { $msg="SENT"; $body_section.=define_lang_label($labels,'on_success_msg')."</span>";}
				else { $msg="FAILED"; $body_section.=define_lang_label($labels,'on_fail_msg')."</span>"; }
			}
			if($sa_mode=='1')
			{ 
				$body_section.=$f_br.$f_br."<input class='input1' type='button' value=' ".define_lang_label($labels,'close')." ' onclick=\"javascript:window.close();\"".$f_ct;
			}
			$body_section.="</div>";
			$record_line="$time ==> Sender: $sender_email, Sender IP: $sender_ip, Recipient: $send_to, Message: $message  ==> Result: $msg".$f_lf;
			$record_line=str_replace(array('\\\\', '\\\'', '\"'),array( '\\', '\'', '"' ),$record_line) ; 
			db_write_data($record_line, '<LOG>', '</LOG>');
			f_unset_session();
		}
	}	
	if ($sa_mode=='1' || $sa_mode=='3') print GT($body_section,$labels['tell_friend'],$sa_mode=='3');
	elseif ($sa_mode=='2')
	{
		$fixed_url=(file_exists($suggested_url)?$suggested_url :'../'.$suggested_url );	
		$contents=f_read_file($fixed_url);
			
		$old_form=f_GFS($contents,'<!--tellfriend-->','<!--/tellfriend-->');
		$contents=str_replace($old_form,$body_section,$contents);

		$show_div='showHdiv('.$_GET['divid'].',1000)';	
		$contents=str_replace(array('<BODY','</BODY'),array('<body','</body'),$contents);	
		$contents=str_replace(array('ONLOAD=','onLoad='),array('onload=','onload='),$contents);
		$old_body='<body'.f_GFS($contents,'<body','</body>').'</body>';
		if(strpos($old_body, 'onload="')!==false) $new_body=str_replace('onload="','onload="'.$show_div.';',$old_body);	
		else $new_body=str_replace('<body','<body onload="'.$show_div.';"',$old_body); 
		$contents=str_replace($old_body,$new_body,$contents);
		print $contents;
	}
	else
	{
		$fixed_url=(file_exists($suggested_url)?$suggested_url :'../'.$suggested_url );
		$contents=f_read_file($fixed_url);

		$j_scr='<script language="javascript" type="text/javascript" src="%stell_friend.php';
		$pat=sprintf($j_scr,$dir);
		if(strpos($contents,$pat)===false)$pat=sprintf($j_scr,'../'.$dir);
		$pattern=f_GFSAbi($contents,$pat,'</script>');
		$contents=str_replace($pattern, $body_section, $contents);
		print $contents;
	}
}
function build_admin_screen()
{
  global $log_fname, $first_line, $last_line, $proj_languages_array, $current_lang, $doc_dir, $default_language_strings;
  global $settings_keys, $page_charset, $f_use_linefeed, $source_page, $rel_path, $f_br, $f_ct;
	$logcontent="";
	$body_section="";$output="";
	$record_array=array();
	$dir=$rel_path.$doc_dir.'/';

	$f=$dir.'tell_friend.php';
	$labels=get_language_labels($current_lang);
	$body_section.=$f_br."<div align='center'><a class='rvts12' href='".$f."?action=admin&amp;language=$current_lang'>settings</a> :: <a class='rvts12' href='".$f."?action=admin&amp;checklog=checklog&amp;language=$current_lang'>check log</a> :: <a class='rvts12' href='".$dir."centraladmin.php?process=logoutadmin'>logout</a> :: <a class='rvts12' href='".$dir."centraladmin.php?process=index'>back to CENTRAL ADMIN</a>";

	if(!file_exists($log_fname)) {print f_fmt_in_template($source_page,f_fmt_error_msg('MISSING_DBFILE',$log_fname)); exit;}

	if(isset($_GET['checklog'])) 
	{
		$logcontent=f_read_file($log_fname);

		$body_section.="<form method='post' action='".$dir."tell_friend.php?action=admin&amp;language=$current_lang&amp;charset=$page_charset' enctype='multipart/form-data'>";
		$body_section.=$f_br.$f_br."<textarea class='input1' id='htmlarea' name='htmlarea' readonly='readonly' style='width:100%' rows='23' cols='80'>";
		if(strpos($logcontent, '<LOG>')!==false)
		{
			$temp=f_GFS($logcontent,'<LOG>','</LOG>');
			$body_section.=str_replace(array('<LOG>','</LOG>'), array("",""), $temp);
		}
		$body_section.="</textarea>";
		$body_section.=$f_br."<input class='input1' type='submit' name='clear_log' value=' Clear Log ' onclick=\"javascript:return confirm('Are you sure you want to clear this log file?')\"".$f_ct."</form>"; 
	}
	elseif(isset($_POST['clear_log'])) 
	{
		if(filesize($log_fname)>0) 
		{
			if(!$handle=@fopen($log_fname,'r+')) {print f_fmt_in_template($source_page,f_fmt_error_msg('DBFILE_NEEDCHMOD',$log_fname)); exit;}
			flock($handle,LOCK_EX);
			$logcontent=fread($handle,filesize($log_fname));
			if(strpos($logcontent,'<LOG>')!==false) 
			{
				$buf=f_GFS($logcontent,'<LOG>','</LOG>');
				$logcontent=str_replace($buf," ",$logcontent);
				if(ftruncate($handle,0)===false) {echo "Failed to truncate file --> last update failed";exit;}
				fseek($handle,0);
				if(fwrite($handle,$logcontent)===FALSE) {echo "Failed to edit file --> last update failed";exit;}
				$body_section.=$f_br."<span class='rvts8'>Log file was cleared.</span>";
			}
			else $body_section.=$f_br."<span class='rvts8'>Log file is empty. No need to clear it.</span>";
			flock($handle,LOCK_UN);
			fclose($handle);
		}
		else $body_section.=$f_br."<span class='rvts8'>Log file is empty. No need to clear it. </span>";		
	}
	else 
	{	
		if(isset($_POST['save']))
		{
			$lang=$_POST['language'];
			foreach($_POST as $k=>$v)
			{
				if($k!='language' && $k!='save' && $k!='allow_msg_change' && $k!='include_url' && $k!='include_captcha')
				{
					if($v!="") $record_array[$k]=$v;
					else $record_array[$k]='NULL';
				}
			}
			if(isset($_POST['include_url'])) $record_array['include_url']='yes'; 
			else $record_array['include_url']='no';
			if(isset($_POST['allow_msg_change']))	$record_array['allow_msg_change']='yes';
			else $record_array['allow_msg_change']='no';			
			if(isset($_POST['include_captcha'])) $record_array['include_captcha']='yes';
			else $record_array['include_captcha']='no';

			$record_line=prepare_for_write($record_array);
			db_write_data($record_line,"<LANGUAGE_$lang>","</LANGUAGE_$lang>",'lang');
		}
		else $lang=$current_lang;
		$data=get_language_labels($lang);
		$ass_data=build_ass_array_record($data,$settings_keys);
		$body_section.=build_settings_form($dir,$lang,$ass_data);
	}
	$body_section.="</div>"; 
	$output=GT($body_section,'Tell A Friend Admin'); 
	$patt='charset='.f_GFS($output,'charset=','"'); 
	$output=str_replace($patt,'charset='.$page_charset,$output);
	print $output;
}
function process_tell()
{		
	global $sa_mode, $version, $doc_dir, $current_lang;

	$suggested_url='';
	if(isset($_GET['url'])) $suggested_url=$_GET['url'];
	if(isset($_REQUEST['sa'])) $sa_mode=$_REQUEST['sa'];
	$action_id=(isset($_REQUEST['action']))?$_REQUEST['action']:'index';

	if($action_id=="send")			send($suggested_url);
	elseif($action_id=="version")	echo $version;
	elseif($action_id=="captcha")	{$captcha=f_generate_captcha_code(); f_set_session_var('CAPTCHA_CODE',md5($captcha)); f_draw_captcha(strtoupper($captcha));}
	elseif($action_id=="index")		
	{
		if(empty($_SESSION)) f_int_start_session();
		f_set_session_var('SID_ALLOW_TELLFRIEND',session_id());
		$labels=get_language_labels($current_lang);
		$body_section=build_tell_friend_form($labels,$suggested_url);

		if($sa_mode=='1' || $sa_mode=='3') print GT($body_section,$labels['tell_friend'],$sa_mode=='3');
		else print "document.write('".$body_section."');";
	}
	elseif($action_id=="admin")
	{
 		if(empty($_SESSION)) f_int_start_session();
		if(function_exists('session_regenerate_id') && version_compare(phpversion(),"4.3.3",">=")) session_regenerate_id();  
		if(!f_is_logged('SID_ADMIN') || f_is_logged('HTTP_USER_AGENT') && ($_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['HTTP_USER_AGENT'])))
			{f_url_redirect("../$doc_dir/centraladmin.php?process=index",false);exit;}
		build_admin_screen();
	}
}

process_tell();

?>
