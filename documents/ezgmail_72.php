<?php
$version="ezgenerator form handler 1.47"; 
/*
 ezgemail.php
 http://www.ezgenerator.com
 Copyright (c) 2004-2008 Image-line
*/

$p_id = '72';
$use_captcha = false;
$send_to = 'poneasan@gmail.com';
$send_from_field ="Email";
$sender_in_from = false;
$sendmail_from = "";
$hidden_menus = false;
$return_path = '';
$page_encoding = 'iso-8859-1';
$re_upfields = array();
$re_fields = array();
$field_labels = array("Name"=>"Name","City"=>"City","Email"=>"Email","Country"=>"Country","Notes"=>"Notes");
if (isset($_SERVER['SERVER_SOFTWARE']))$use_linefeed = strpos($_SERVER['SERVER_SOFTWARE'] ,'Microsoft') !== false;
else $use_linefeed = false;
$mail_type = "mail";
$SMTP_HOST='%SMTP_HOST%';
$SMTP_PORT='%SMTP_PORT%';
$SMTP_HELLO='%SMTP_HELLO%';
$AUTH='%SMTP_AUTH%';
$SMTP_AUTH=(strtolower($AUTH)=='true');
$SMTP_AUTH_USR='%SMTP_AUTH_USR%';
$SMTP_AUTH_PWD='%SMTP_AUTH_PWD%';
$subject = "";
$submit_url = "../documents/73.html";
$error_url = "../documents/74.html";
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

include('htmlMimeMail.php');

function processform()
{
 global $reply_enabled,$send_to,$sendmail_from,$use_captcha,$version;

 if(isset($_GET['action']))//captcha
 {
  $action=$_GET['action'];
  if($action=="captcha"){generate_captcha();exit;}
  elseif($action=="drawcaptcha"){draw_captcha();exit;}
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
  else 
  {
    $er=(empty($errors))?'':implode(",", $errors);
    echo 'sending mail failed<br>'.$er;
  }
 }
}

function get_fields()
{
 $vars=$_POST;
 foreach($vars as $k=>$v)
 {
  $vars[$k]=trim($v);
  if(strpos(strtolower(urldecode($vars[$k])),'mime-version') !== false) {die("Why ?? :(");}
  if(strpos(strtolower(urldecode($vars[$k])),'content-type:') !== false) {die("Why ?? :(");}
 }
 return $vars;
}

function check_fields($vars)  //required fields starts with _re
{
 global $thispage_id,$use_captcha,$error_url,$send_from_field,$emailnotvalid_message,$fieldnotfound_message,$_FILES,
 $fieldnotchecked_message,$re_fields,$re_upfields,$field_labels,$emailnotsame_message,$valdontmatch_message;

 $issues=array();
 if(!isset($vars[$send_from_field])){echo 'Main e-mail field missing or not defined correctly!<br><img src="http://ezg.e-officedirect.com/news/email.gif">';exit;}
 if(!validate_email($vars[$send_from_field])) $issues[]=$emailnotvalid_message;
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
  if(!isset($_SESSION)) {int_start_session();} //capctha
  if(!isset($_SESSION['CAPTCHA_CODE'])||($_SESSION['CAPTCHA_CODE']=='')) {echo "This is illegal operation. You are not allowed to submit this form."; exit;}
  if(!isset($_POST['captchacode']) || (md5($_POST['captchacode'])!= $_SESSION['CAPTCHA_CODE'])) {$issues[]=$valdontmatch_message;} //capctha
 }

 if ($issues)
 {
  $issues='<br>'.join('<br>',$issues);
  $fp=fopen($error_url,"r");
  $contents=fread($fp,filesize($error_url));
  fclose($fp);
  $contents=str_replace("%ERRORS%",$issues,$contents);
  print $contents;
 }
 return $issues;
}

function redirect($vars)
{
 global $submit_url;
 if(strpos(strtolower($submit_url),'http://') !== false) {header("Location: $submit_url");header("Status: 303");}
 else
 {
  $fp=fopen($submit_url,"r");$contents=fread($fp,filesize($submit_url));fclose($fp);
  foreach($vars as $rs=>$v) $contents=str_replace('%'.$rs.'%',$v,$contents);
  print $contents;
 }
 exit();
}

function auto_reply($vars)
{
 global $reply_from,$reply_subject,$reply_message,$send_from_field,$reply_url,$reply_images_path,$use_linefeed,$mail_type,
        $return_path,$page_encoding,$SMTP_HOST,$SMTP_PORT,$SMTP_HELLO,$SMTP_AUTH,$SMTP_AUTH_USR,$SMTP_AUTH_PWD;

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
      $css_meta=GetFromStringAbi($html,'<link type="text/css" href="','>');
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
 if($use_linefeed) $mail->setCrlf("\r\n");
 if(($mail_type=='smtp')&&($SMTP_HOST!=='')) $mail->setSMTPParams($SMTP_HOST,$SMTP_PORT,$SMTP_HELLO,$SMTP_AUTH,$SMTP_AUTH_USR,$SMTP_AUTH_PWD);
 $result = $mail->send(array($vars[$send_from_field]),$mail_type);
 return $result;
}

function _build_fields($vars)
{
 global $use_linefeed;

 $pa_fields="";
 foreach($vars as $rs=>$v)
 {
  if(strlen($rs)> 2)$suff=strtolower(substr($rs,strlen($rs)-2)); else $suff='';
  if((strpos($suff,'_x')===false)&&(strpos($suff,'_y')===false)&&(strpos($suff,'.x')===false)&&(strpos($suff,'.y')===false))
  {
   if($use_linefeed) $pa_fields.=$rs."= $v\r\n";
   else $pa_fields .=$rs."= $v\n";
  }
 }
 return $pa_fields;
}

function GetFromString($src,$start,$stop)
{
 if($start=='') $res=$src;
 else if(strpos($src,$start)===false) {$res='';return $res;}
 else $res=substr($src,strpos($src,$start)+strlen($start));
 if(($stop != '')&&(strpos($res,$stop) !== false)) $res=substr($res,0,strpos($res,$stop));
 return $res;
}

function GetFromStringAbi($src,$start,$stop) {$res2=GetFromString($src,$start,$stop);return $start.$res2.$stop;}

function send_mail2($vars,&$errors)
{
 global $sendto_array, $subject,$_SERVER,$_FILES,$send_from_field,$use_linefeed,$main_message,
        $reply_from,$mail_type,$sender_in_from,$return_path,$page_encoding,
        $SMTP_HOST,$SMTP_PORT,$SMTP_HELLO,$SMTP_AUTH,$SMTP_AUTH_USR,$SMTP_AUTH_PWD;

 $files=array();
 $mail=new htmlMimeMail();
 if($page_encoding !== ''){$mail->setTextCharset($page_encoding);$mail->setHtmlCharset($page_encoding);$mail->setHeadCharset($page_encoding);}

 if(count($_FILES)){$files=array_keys($_FILES);}
 $date_time=date('d-m-Y');
 $fields=_build_fields($vars);
 $fields=str_replace("\'","'",$fields);
 $fields=str_replace('\"','"',$fields);
 if($use_linefeed) $fields.="\r\n"; else $fields.="\n";
 $fields.='IP= '.$_SERVER['REMOTE_ADDR'];
 if($use_linefeed) $fields.="\r\n"; else $fields.="\n";
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

 if($use_linefeed) $mail->setCrlf("\r\n");

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
 foreach($vars as $key => $value) $body=str_replace('%'.$key.'%',$value,$body);

 $mail->setText($body);
 $mail->setSubject($r_subject);

 foreach($sendto_array as $value) {if(strpos($value,'<')===false) $value=GetFromString($value,'<','>');}
 if($return_path != '') $mail->setReturnPath($return_path);
 if(($mail_type=='smtp')&&($SMTP_HOST!=='')) $mail->setSMTPParams($SMTP_HOST,$SMTP_PORT,$SMTP_HELLO,$SMTP_AUTH,$SMTP_AUTH_USR,$SMTP_AUTH_PWD);
 $result=$mail->send($sendto_array,$mail_type);
 if(isset($mail->errors))$errors=$mail->errors;
 return $result;
}

function validate_email($email)
{
 if(!strlen($email)) return false;
 if(!preg_match('/^[0-9a-zA-Z\.\-\_]+\@[0-9a-zA-Z\.\-]+$/',$email)) return false;
 if(preg_match('/^[^0-9a-zA-Z]|[^0-9a-zA-Z]$/',$email)) return false;
 if(!preg_match('/([0-9a-zA-Z_]{1})\@./',$email)) return false;
 if(!preg_match('/.\@([0-9a-zA-Z_]{1})/',$email)) return false;
 if(preg_match('/.\.\-.|.\-\..|.\.\..|.\-\-./',$email)) return false;
 if(preg_match('/.\.\_.|.\-\_.|.\_\..|.\_\-.|.\_\_./',$email)) return false;
 if(!preg_match('/\.([a-zA-Z]{2,5})$/',$email)) return false;
 return true;
}

function int_start_session()
{
 if('' != '') session_save_path('');
 session_start();
}

function generate_captcha_code()
{
 if(!isset($_SESSION)) int_start_session();
 $str='';
 for($i=0;$i<4;$i++) $str.=chr(rand(97,122));
 $str=strtoupper($str);
 $_SESSION['CAPTCHA_CODE']=md5($str);
 return $str;
}

function draw_captcha()
{
  $captcha=generate_captcha_code();
  $im=imagecreate(105,18);
  $bg=imagecolorallocate($im,255,255,255);

  for($i=0;$i<100;$i++){$clr2=imagecolorallocate($im,rand(110,255),rand(110,255),rand(110,255));$x=rand(0,105);$y=rand(0,18);imageline($im,$x,$y,$x+rand(0,3),$y+2,$clr2);}
  for($i=0;$i<10;$i++){$x=rand(0,120);$y=rand(0,18);$xs=rand(180,255);$clr2=imagecolorallocate($im,$xs,$xs,$xs);imagearc($im,$x,$y,rand(15,30),rand(15,30),rand(0,360),rand(180,360),$clr2);}
  $clr1=imagecolorallocate($im,120,120,120);
  imagerectangle($im,0,0,104,17,$clr1);
  $result='';
  for($i=0;$i<strlen($captcha);$i++){$char=substr($captcha,$i,1);$result .= $char." ";}
  $tekst2=explode(" ",$result);
  $aantal=count($tekst2);
  $xas2=10;$xaz=25;
  for($i=0;$i<$aantal;$i++){$xas2=rand(5,14);$yas2=rand(0,4);$clr=imagecolorallocate($im,rand(0,110),rand(0,110),rand(0,110));imagestring($im,5,$i*$xaz+$xas2,$yas2,$tekst2[$i],$clr);}
  if(function_exists("imagegif")){header("Content-type: image/gif");imagegif($im);}
  elseif(function_exists("imagejpeg")){header("Content-type: image/jpeg");imagejpeg($im);}
  elseif(function_exists("imagepng")) {header("Content-type: image/png");imagepng($im);}
  imagedestroy($im);
}

function generate_captcha()
{
 global $p_id ;
 if(function_exists('imagecreate')&&(function_exists('imagegif')||function_exists('imagejpeg')||function_exists('imagepng')))
  {echo 'document.write(" <input class=\'input1\' type=\'text\' name=\'captchacode\' id=\'captchacode\' size=\'6\' maxlength=\'4\'> <img align=\'top\' src=\'ezgmail_'.$p_id .'.php?action=drawcaptcha\' border=\'0\'>")';}
 else
  {
  $captcha=generate_captcha_code();
  echo 'document.write(" <input class=\'input1\' type=\'text\' name=\'captchacode\' id=\'captchacode\' size=\'6\' maxlength=\'4\'> '.$captcha.'")';
  }
}

processform();
?>
