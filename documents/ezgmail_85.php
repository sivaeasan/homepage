<?php
$version="ezgenerator form handler 1.52"; 
/*
 ezgemail.php
 http://www.ezgenerator.com
 Copyright (c) 2004-2008 Image-line
*/

$p_id = '85';
$use_captcha = false;
$send_to = 'poneasan@gmail.com';
$send_from_field ="Email";
$sender_in_from = false;
$sendmail_from = "";
$hidden_menus = false;
$return_path = '';
$forbid_urls=FALSE;
$page_encoding = 'iso-8859-1';
$re_upfields = array();
$re_fields = array();
$field_labels = array("Name"=>"Name","City"=>"Place","Email"=>"Email","Country"=>"Country","Notes"=>"Notes");
$AllowedTypes='gif|jpg|jpeg|png|mp3|swf|asf|avi|mpg|mpeg|wav|wma|mid|wmw|mov|ram|bmp|pdf|doc|';
$subject = "";
$submit_url = "../documents/86.html";
$error_url = "../documents/87.html";
$emailnotvalid_message = "It seems that your e-mail address is not valid. Please change it and try again...";
$fieldnotfound_message = "Field <b>%FIELDNAME%</b> is empty";
$emailnotsame_message = "Emails does not match.";
$fieldnotchecked_message = "Field %FIELDNAME% must be checked";
$valdontmatch_message = "Captcha does not match";
$reply_enabled = 0;
$reply_url = '';
$reply_images_path = '';
$main_message = <<<MSG
%FORM_DATA%
MSG;
$reply_message = <<<MSG
Thank you for submitting the form.

MSG;
$sendto_array = split("[;]",$send_to);
$reply_from = $sendto_array[0];
$reply_subject = "";

include_once('../documents/htmlMimeMail.php'); 
include_once('../ezg_data/functions.php');

function processform()
{
 global $reply_enabled,$send_to,$sendmail_from,$use_captcha,$version;

 if(isset($_GET['action']))//captcha
 {
  $action=$_GET['action'];
  if($action=="captcha"){generate_captcha();exit;}
  elseif($action=="drawcaptcha"){f_draw_captcha2();exit;}
  elseif($action=="version") {echo $version;exit;}
 }

 if($send_to=='your@email.com') echo 'please define request recipient e-mail on request page settings panel!';
 else
 {
  $formfields=get_fields();
  if(check_fields($formfields)) return;
  if($use_captcha) $_SESSION['CAPTCHA_CODE']=''; 
  if($sendmail_from !== '') ini_set('sendmail_from',$sendmail_from);
  $errors='';
  if(send_mail2($formfields,$errors))
  {
   if($reply_enabled) auto_reply($formfields);
   redirect($formfields);
  }
  else  {$er=(empty($errors))?'':implode(",", $errors);echo 'sending mail failed<br>'.$er;}
 }
}

function get_fields()
{
 $vars=array();
 foreach($_POST as $k=>$v)
 {
  $vars[$k]=''; 
  if(is_array($v)) {foreach($v as $sk=>$sv){$vars[$k].=$sv.';';}}
  else $vars[$k]=trim($v);
  $x=strtolower(urldecode($vars[$k]));
  if((strpos($x,'mime-version')!== false)||(strpos($x,'content-type:')!== false)) die("Why ?? :(");
 }
 return $vars;
}

function getExt($fname)
{
  $sTmp=$fname;
  while($sTmp!=""){$sTmp=strstr($sTmp,".");if($sTmp!=""){$sTmp=substr($sTmp,1);$sExt=$sTmp;} }
  return strtolower($sExt);
}

function isTypeAllowed($fname)
{
  global $AllowedTypes;
  if($AllowedTypes=="*") return true;
  if((strpos('|'.$AllowedTypes.'|','|'.getExt($fname).'|')!==false )&&(substr_count($fname,'.')==1)) return true;
  else return false;
}

function check_fields($vars)  //required fields starts with _re
{
 global $thispage_id,$use_captcha,$error_url,$send_from_field,$emailnotvalid_message,$fieldnotfound_message,$_FILES,$forbid_urls,
 $fieldnotchecked_message,$re_fields,$re_upfields,$field_labels,$emailnotsame_message,$valdontmatch_message;

 $issues=array();
 if(!isset($vars[$send_from_field])){echo 'Main e-mail field missing or not defined correctly!<br><img src="http://ezg.e-officedirect.com/news/email.gif">';exit;}
 if(!f_validate_email($vars[$send_from_field])) $issues[]=$emailnotvalid_message;
 if(isset($vars[$send_from_field.'_confirm']))
 {if($vars[$send_from_field] != $vars[$send_from_field.'_confirm']) $issues[]=$emailnotsame_message;}

 foreach($re_fields as $k=>$v)
 {
  $vx=str_replace(' ','_',$v);
  $label=$v;
  if(isset($field_labels[$v])) $label=$field_labels[$v];
  if(in_array($vx,$re_upfields)){if((!isset($_FILES[$vx]['name']))||($_FILES[$vx]['name']=='')) $issues[]=str_replace("%FIELDNAME%",ucfirst($label),$fieldnotfound_message);}
  else if(!isset($vars[$vx])) $issues[]=str_replace("%FIELDNAME%",ucfirst($label),$fieldnotchecked_message);
  else if(!strlen($vars[$vx])) $issues[]=str_replace("%FIELDNAME%",ucfirst($label),$fieldnotfound_message);
 }

 if($use_captcha)
 {
  if(!isset($_SESSION)) f_int_start_session();
  if(!isset($_SESSION['CAPTCHA_CODE'])||($_SESSION['CAPTCHA_CODE']=='')) {echo "This is illegal operation. You are not allowed to submit this form."; exit;}
  if(!isset($_POST['captchacode'])||(md5(strtoupper($_POST['captchacode']))!= $_SESSION['CAPTCHA_CODE'])) $issues[]=$valdontmatch_message;
 } 
  if(count($_FILES))
  {
    $files=array_keys($_FILES);
    foreach($files as $file)
    {
      $fname=$_FILES[$file]['name'];
      if(($fname!='')&&(!isTypeAllowed($fname)))$issues[]=$fname." is not allowed!";            
    }
  }
 
 if($forbid_urls) 
 {
   $tmp=strtolower(implode(",",$vars));
   if((strpos($tmp,'http')!==false || strpos($tmp,'href')!==false || strpos($tmp,'www.')!==false)) $issues[]="Url's are not allowed!";
 }

 if ($issues)
 {
  $issues='<br>'.join('<br>',$issues);
  $fp=fopen($error_url,"r");$src=fread($fp,filesize($error_url));fclose($fp);
  $src=str_replace("%ERRORS%",$issues,$src);
  print $src;
 }
 return $issues;
}

function redirect($vars)
{
 global $submit_url;
 if(strpos(strtolower($submit_url),'http://') !== false) {header("Location: $submit_url");header("Status: 303");}
 else
 {
  $fp=fopen($submit_url,"r");$src=fread($fp,filesize($submit_url));fclose($fp);
  foreach($vars as $rs=>$v) $src=str_replace('%'.$rs.'%',$v,$src);
  print $src;
 }
 exit();
}

function apply_smtp($m)
{
 global $f_mail_type,$f_SMTP_HOST,$f_SMTP_PORT,$f_SMTP_HELLO,$f_SMTP_AUTH,$f_SMTP_AUTH_USR,$f_SMTP_AUTH_PWD;
 if(($f_mail_type=='smtp')&&($f_SMTP_HOST!=='')) $m->setSMTPParams($f_SMTP_HOST,$f_SMTP_PORT,$f_SMTP_HELLO,$f_SMTP_AUTH,$f_SMTP_AUTH_USR,$f_SMTP_AUTH_PWD);
}

function auto_reply($vars)
{
 global $reply_from,$reply_subject,$reply_message,$send_from_field,$reply_url,$reply_images_path,$f_use_linefeed,$f_mail_type,$f_lf,
        $return_path,$page_encoding;

 $mail=new htmlMimeMail();
 $r_message=$reply_message;
 $r_subject=$reply_subject;

 if($page_encoding !== ''){$mail->setTextCharset($page_encoding);$mail->setHtmlCharset($page_encoding);$mail->setHeadCharset($page_encoding);}

 foreach($vars as $key => $value) {$r_message=str_replace('%'.$key.'%',$value,$r_message);}
 foreach($vars as $key => $value) {$r_subject=str_replace('%'.$key.'%',$value,$r_subject);}

 if($reply_url != '')
 {
  $html=$mail->getFile($reply_url);
  foreach($vars as $key => $value) $html=str_replace('%'.$key.'%',$value,$html);

  if (strpos($html,'textstyles_nf.css')!==false)
  {
     if(file_exists('../documents/textstyles_nf.css')) $css=$mail->getFile('../documents/textstyles_nf.css');
     elseif(file_exists('documents/textstyles_nf.css')) $css=$mail->getFile('documents/textstyles_nf.css');
     if($css!=='')
     {
      $css_meta=f_GFSAbi($html,'<link type="text/css" href="','>');
      $url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';
      $css='<style type="text/css"><!--'.$css.'--></style>';
      $html=str_replace($css_meta,$css,$html);
     }
  }
  $mail->setHtml($html,$r_message,$reply_images_path);
 }
 else $mail->setText($r_message);

 $mail->setSubject($r_subject);
 $mail->setFrom($reply_from);
 if($return_path != '') $mail->setReturnPath($return_path);
 if($f_use_linefeed) $mail->setCrlf($f_lf);
 apply_smtp($mail);
 $result = $mail->send(array($vars[$send_from_field]),$f_mail_type);
 return $result;
}

function _build_fields($vars)
{
 global $f_lf;

 $pa_fields="";
 foreach($vars as $rs=>$v)
 {
  if(strlen($rs)> 2)$suff=strtolower(substr($rs,strlen($rs)-2)); else $suff='';
  if((strpos($suff,'_x')===false)&&(strpos($suff,'_y')===false)&&(strpos($suff,'.x')===false)&&(strpos($suff,'.y')===false)) $pa_fields.=$rs."= $v".$f_lf;
 }
 return $pa_fields;
}

function send_mail2($vars,&$errors)
{
 global $sendto_array, $subject,$_SERVER,$_FILES,$send_from_field,$f_use_linefeed,$f_lf,$main_message,$reply_from,$f_mail_type,$sender_in_from,$return_path,$page_encoding;

 $files=array();
 $mail=new htmlMimeMail();
 if($page_encoding !== ''){$mail->setTextCharset($page_encoding);$mail->setHtmlCharset($page_encoding);$mail->setHeadCharset($page_encoding);}

 if(count($_FILES)){$files=array_keys($_FILES);}
 $date_time=date('d-m-Y');
 $fields=_build_fields($vars);
 $fields=str_replace("\'","'",$fields);
 $fields=str_replace('\"','"',$fields);
 $fields.=$f_lf.'IP= '.$_SERVER['REMOTE_ADDR'].$f_lf;
 $fields.='date= '.$date_time;

 if(count($files))
 {
  foreach($files as $file)
  {
   $file_name=$_FILES[$file]['name'];
   $file_type=$_FILES[$file]['type'];
   $file_tmp_name=$_FILES[$file]['tmp_name'];
   $file_cnt="";
   $f=@fopen($file_tmp_name,"rb");
   if(!$f) continue;
   while($f && !feof($f)) $file_cnt .=fread($f,4096);
   fclose($f);
   if(!strlen($file_type)) $file_type="application/octet-stream";
   if($file_type == 'application/x-msdownload') $file_type="application/octet-stream";
   $mail->addAttachment($file_cnt,$file_name,$file_type);
  }
 }

 if($f_use_linefeed) $mail->setCrlf($f_lf);

 $r_subject=$subject;
 foreach($vars as $key => $value) $r_subject=str_replace('%'.$key.'%',$value,$r_subject);

 if($sender_in_from)
 {
  if(isset($vars['Name'])) $mail->setFrom('"'.$vars['Name'].'" <'.$vars[$send_from_field].'>');
  else $mail->setFrom($vars[$send_from_field]);
 }
 else if(strpos($reply_from,'<')===false) $mail->setFrom('"'.$r_subject.'_request" <'.$reply_from.'>');
 else $mail->setFrom($reply_from);

 if($main_message=='')$main_message='%FORM_DATA%';
 $body=str_replace('%FORM_DATA%',$fields,$main_message);
 $body=str_replace('%IP%',$_SERVER['REMOTE_ADDR'],$body);
 $body=str_replace('%date%',$date_time,$body);
 
 foreach($vars as $key => $value) $body=str_replace('%'.$key.'%',$value,$body);

 $mail->setText($body);
 $mail->setSubject($r_subject);

 foreach($sendto_array as $value) {if(strpos($value,'<')===false) $value=f_GFS($value,'<','>');}
 if($return_path != '') $mail->setReturnPath($return_path);
 apply_smtp($mail);
 $result=$mail->send($sendto_array,$f_mail_type);
 if(isset($mail->errors))$errors=$mail->errors;
 return $result;
}

function generate_captcha()
{
 global $p_id ;
 if(function_exists('imagecreate')&&(function_exists('imagegif')||function_exists('imagejpeg')||function_exists('imagepng')))
  {echo 'document.write(" <input class=\'input1\' type=\'text\' name=\'captchacode\' id=\'captchacode\' size=\'6\' maxlength=\'4\'> <img align=\'top\' src=\'ezgmail_'.$p_id .'.php?action=drawcaptcha\' border=\'0\'>")';}
 else
  {
  $captcha=f_generate_captcha_code2();
  echo 'document.write(" <input class=\'input1\' type=\'text\' name=\'captchacode\' id=\'captchacode\' size=\'6\' maxlength=\'4\'> '.$captcha.'")';
  }
}

processform();
?>
