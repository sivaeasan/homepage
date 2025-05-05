<?php
$version="ezgenerator shop 3.34"; 
/*
http://www.ezgenerator.com
Copyright (c) 2004-2008 Image-line
*/
error_reporting(E_ALL);
if(get_magic_quotes_runtime()==1) {set_magic_quotes_runtime(0);}
include('htmlMimeMail.php');

 $script_url='';
 $g_id=64;
 $g_data_ext = '.ezg.php';
 $g_use_abs_path = false;
 $g_abs_path = '';
 $g_encoding = 'iso-8859-1';
 $page_dir = 'documents/';

 $g_shop_on = false;
 $g_id_field = 'P_ID';
 $g_pwd = "Administrator";
 $g_currency = 'USD';
 $g_pricefield = '';
 $g_vatfield = '';
 $g_namefield = '';
 $g_shoptarget = '';
 $g_subfield = '';
 $g_subfield1 = '';
 $g_subfield2 = '';
 $g_searchnomatch = 'We were unable to find exact matches for your search for %SEARCHSTRING%';
 $g_useimgplaceholder = TRUE;
 $g_subcat = 'none';

 $g_pagename = '64.html';
 $g_realname = 'catalog.php';
 $g_listcols = 1;
 $g_catcols = 1;
 $g_catpgmax = 0;
 $g_checkout_str = array(""=>"");
 $g_price_decimals = '2';
 $g_check_name = 'last_name';
 $g_payment_method_field = 'ec_PaymentMethod';
//shipping
 $g_ship_vat = 0.00;
 $g_ship_type = 0;
 $g_ship_amount = 0;
 $g_ship_settings = '';
 $g_ship_above_limit = 0;
 $g_ship_above_on = 'FALSE';
 $g_ship_cost_perc = 0;
 $g_shipping_field = '';
//mail settings
 $g_send_to = 'your@email.here';
 if(isset($_SERVER['SERVER_SOFTWARE']))$use_linefeed = strpos($_SERVER['SERVER_SOFTWARE'],'Microsoft') !== false;
 else $use_linefeed = false;
 $return_path = '';
 $sendmail_from = '';
 $g_check_email = 'Email';
 $set_bankwire_email = FALSE;
 $hotmail_text = TRUE;
 $re_fields = array();
 $re_upfields = array();
 $field_labels = array("P_Name"=>"Name");
 $mail_type = "mail";
 $SMTP_HOST='%SMTP_HOST%';
 $SMTP_PORT='%SMTP_PORT%';
 $SMTP_HELLO='%SMTP_HELLO%';
 $AUTH='%SMTP_AUTH%';
 $SMTP_AUTH=(strtolower($AUTH)=='true');
 $SMTP_AUTH_USR='%SMTP_AUTH_USR%';
 $SMTP_AUTH_PWD='%SMTP_AUTH_PWD%';
//callback settings
 $g_checkout_callback_on = array(""=>"");
 $g_callback2 = false;
 $g_callback_mail = false;
 $g_callback_mail_template = "";
 $g_audiofield = '';
//return vars
 $g_callback_str = array(""=>"");
 foreach($g_callback_str as $ind=>$val)
 {
  $pairs=explode('&',$val);$tmp=array();
  foreach($pairs as $k=>$v){if(strpos($v,'=')!==false) {$kv=explode("=",$v);$tmp[$kv[0]]=$kv[1];}}
  $g_callback_str[$ind]=$tmp;
 }

 $g_decimal_sign = '.';
 $g_thousands_sep = ",";
 $g_shop_name = '';
 $g_shopnitofication="this is notification from  you can find more info about orders here:\n%ABSURL%\n\n info received from payment provider: \n";
 $g_return_subject = 'your order';
 $g_return_subject_admin = '%PAYMENT_TYPE% order';
//messages
 $g_mailnotvalid_msg = "It seems that your e-mail address is not valid. Please change it and try again...";
 $g_mailnotsame_msg = "Emails does not match.";
 $g_fieldntfnd_msg = "Field <b>%FIELDNAME%</b> is empty";
 $g_fieldntchck_msg = "Field %FIELDNAME% must be checked";
 $cart_empty_msg = "Your cart is empty!";
//internal params
 $page_header = '<html><head><link type="text/css" href="../documents/textstyles.css" rel="STYLESHEET"></head>';

$table_cell_style = <<<MSG
 <style> 
 .tbl{border: 1px solid silver;width:100%}
 .tbl td{text-align:left;}
 .td_f{border-right: 1px solid silver;background:red;} 
 .td_c{border-right: 1px solid silver;}   
 .td_h{border-bottom: 1px solid silver;font-variant:small-caps;}   
 </style>
MSG;
 $defbwmess="this is confirmation of order: %SHOP_ORDER_ID% made on %SHOP_ORDER_DATE% in our shop. Copy of order below.";   
 $session_on = false;
 $session_transaction_id = 0;
 $global_pagescr = '';
$admin_message = <<<MSG
This is %PAYMENT_TYPE% notification from  
you can find more info about orders here: %ORDERS_LINK%
Order Id=%ORDER_ID%
%FORM_DATA%
%SHOP_CART%
MSG;

function m_header($url,$td)
{
  if(false) echo '<meta http-equiv="refresh" content="0;url='.$url. ' " />';
  else
  {
  if($td) header("HTTP/1.0 307 Temporary redirect");
  header("Location: $url");
  }
}

function getTarget($g_targetpop)
{
 global $g_shoptarget;
 $result=$g_shoptarget;
 if($g_targetpop && $result=='') $result='shopmain';
 return $result;
}

function get_input($name,$value){ return '<input type="hidden" name="'.$name.'" value="'.$value.'"/>';}

function int_start_session()
{
 if('' != '') session_save_path('');
 session_start();
}

function send_mail($message,$text,$subject,$sfrom,$sto)
{
 global $use_linefeed,$hotmail_text,$return_path,$mail_type,$g_encoding,$HTTP_POST_FILES,$sendmail_from;
 global $SMTP_HOST,$SMTP_PORT,$SMTP_HELLO,$SMTP_AUTH,$SMTP_AUTH_USR,$SMTP_AUTH_PWD;
 if($sendmail_from !== '') ini_set('sendmail_from',$sendmail_from);
 $mail=new htmlMimeMail();
 if($g_encoding !==''){$mail->setTextCharset($g_encoding);$mail->setHtmlCharset($g_encoding);$mail->setHeadCharset($g_encoding);}

 if(count($HTTP_POST_FILES))
 {
  $files=array();
  $files=array_keys($HTTP_POST_FILES);
  if(count($files))
  {
   foreach($files as $file)
   {
    $file_name=$HTTP_POST_FILES[$file]['name'];
    $file_type=$HTTP_POST_FILES[$file]['type'];
    $file_tmp_name=$HTTP_POST_FILES[$file]['tmp_name'];
    $file_cnt="";
    $f=@fopen($file_tmp_name,"rb");
    if(!$f) continue;
    while($f && !feof($f)) $file_cnt .=fread($f,4096);
    fclose($f);
    if(!strlen($file_type)) $file_type="application/octet-stream";
    if($file_type=='application/x-msdownload') $file_type="application/octet-stream";
    $mail->addAttachment($file_cnt,$file_name,$file_type);
   }
  }
 }
 $text=urldecode($text);
 if($message !=='')
 {
  if($hotmail_text && (strpos($sto,'@hotmail.com') !==false)) $mail->setText($text);
  else $mail->setHtml($message,$text,'');
 }
 else $mail->setText($text);

 $mail->setSubject($subject);
 $mail->setFrom($sfrom);
 if($use_linefeed) $mail->setCrlf("\r\n");
 if($return_path !='') $mail->setReturnPath($return_path);
 if(($mail_type=='smtp')&&($SMTP_HOST!=='')) $mail->setSMTPParams($SMTP_HOST,$SMTP_PORT,$SMTP_HELLO,$SMTP_AUTH,$SMTP_AUTH_USR,$SMTP_AUTH_PWD);
 $result=$mail->send(array($sto),$mail_type);
 return $result;
}

function evalAndPrint($src)
{
 if(strpos(strtolower($src),'<?php') !== false)
 {
  $src='?'.'>'.trim($src).'<'.'?';
  eval($src);
 }
 else print $src;
}

function build_logged_info(&$data)
{
	global $g_id,$g_realname,$page_dir;
	$link=':: <a class="rvts12" href="%s">%s</a>';
	$ca_url='../documents/centraladmin.php?';
	$lang_='&amp;lang=EN';
	if(strpos($data,"<?php if(function_exists('user_navigation'))")!==false)
	{
		$un_param_raw=getfromstringabi($data,"<?php if(function_exists('user_navigation'))","?>");
		if($un_param_raw!='')
		{
			$heading='';
			if(!isset($_SESSION)) int_start_session();
			$un_strings=explode(',',str_replace("'",'',getfromstring($un_param_raw,'user_navigation(',')')));
			$logged_as_caadmin=isset($_SESSION['SID_ADMIN']);
			$logged_as_causer=isset($_SESSION['cur_user']);
			if(strtolower(implode('',$un_strings))=="username") 
			{
				if($logged_as_caadmin)		$heading=$_SESSION['SID_ADMIN'];
				elseif($logged_as_causer)	$heading= $_SESSION['cur_user'];
			}
			else
			{				
				$p_name='../'.$page_dir.$g_realname;
				if($logged_as_caadmin)
				{
					$heading=sprintf($link,$ca_url.'process=index'.$lang_,$un_strings[1]);
					$heading.=sprintf($link,$ca_url.'process=logoutadmin'.$lang_,$un_strings[2]);
					$heading='<span class="rvts8">'.$un_strings[0].' ['.$_SESSION['SID_ADMIN'].'] </span>'.$heading;
				}
				if($logged_as_causer) 
				{
					$ca_detailed_url=$ca_url.'pageid='.$g_id.'&amp;username='.$_SESSION['cur_user'] .'&amp;ref_url='.urlencode($p_name).$lang_.'&amp;process=';	
					$heading.=sprintf($link,$ca_url.'process=logout&amp;pageid='.$g_id.$lang_,$un_strings[2]);	
					$heading.=sprintf($link,$ca_detailed_url.'changepass',$un_strings[3]);
					$heading.=sprintf($link,$ca_detailed_url.'editprofile',(isset($un_strings[4])?$un_strings[4]:'edit profile'));
					$heading='<span class="rvts8">'.$un_strings[0].' ['.$_SESSION['cur_user'].'] </span>'.$heading;
				}	
			}
			$data=str_replace($un_param_raw,$heading,$data);
		}
	}
  return $data;
}

function getHtmlTemplate($html_output,$scripts)
{
 global $g_pagename;

 $fp=fopen($g_pagename,"r");$contents=fread($fp,filesize($g_pagename));fclose($fp);
 if(strpos($contents,'<!--page-->')!==false)
  {$pattern=substr($contents,strpos($contents,'<!--page-->'),strpos($contents,'<!--/page-->')-strpos($contents,'<!--page-->')+12);}
 else
 {
   $body_pos=strpos(strtoupper($contents),'<BODY');$body_endpos=strpos(strtoupper($contents),'</BODY');
   $pattern=substr($contents,$body_pos,$body_endpos-$body_pos+7);
   $body_part=substr($pattern,0,strpos($pattern,'>')+1);
   $html_output=$body_part.$html_output.'</body>';
 }
 $contents=str_replace($pattern,$html_output,$contents);
 if(strpos($contents,'</HEAD>')!==false){$end_head='</HEAD>';} else $end_head='</head>';
 if($scripts !== '') $contents=str_replace($end_head,$scripts.$end_head,$contents);
 $contents=str_replace(GetFromString($contents,'<!--counter-->','<!--/counter-->'),'',$contents);
 return $contents;
}

function parse_page_id($src)
{
 global $g_id,$g_realname;
 $result=str_replace('%SHOP_PAGE_ID%_lister.php',$g_realname,$src);
 $result=str_replace('%SHOP_PAGE_ID%',$g_id,$result);
 return $result;
}

function check_fields($vars)  //required fields with _re
{
 global $g_id,$g_check_email,$g_mailnotvalid_msg,$g_fieldntfnd_msg,$re_fields,$g_fieldntchck_msg,
 $g_mailnotsame_msg,$field_labels,$_FILES,$re_upfields;
 $issues=array();
 $error_url=($g_id+1).".html";

 if(!validate_email($vars[$g_check_email])) $issues[]=$g_mailnotvalid_msg;
 if(isset($vars[$g_check_email.'_confirm']))
    {if($vars[$g_check_email] != $vars[$g_check_email.'_confirm']) $issues[]=$g_mailnotsame_msg;}

 foreach($re_fields as $k=>$v)
 {
  $vx=str_replace(' ','_',$v);$label=$v;
  if(isset($field_labels[$v])) $label=$field_labels[$v];
  if(in_array($vx,$re_upfields)){if((!isset($_FILES[$vx]['name']))||($_FILES[$vx]['name']=='')) $issues[]=str_replace("%FIELDNAME%",ucfirst($label),$g_fieldntfnd_msg);}
  else if(!isset($vars[$vx])) $issues[]=str_replace("%FIELDNAME%",ucfirst($label),$g_fieldntchck_msg);
  else if(!strlen($vars[$vx])) $issues[]=str_replace("%FIELDNAME%",ucfirst($label),$g_fieldntfnd_msg);
 }

 if($issues)
 {
  $issues='<br>'.join('<br>',$issues);
  $fp=fopen($error_url,"r");$contents=fread($fp,filesize($error_url));fclose($fp);
  $contents=str_replace("%ERRORS%",$issues,$contents);
  $contents=parse_page_id($contents);
  evalAndPrint($contents);
  $_SESSION['frmfields']=$vars;
 }
 else if(isset($_SESSION['frmfields'])) unset($_SESSION['frmfields']);
 return $issues;
}

function validate_email($email)
{
 if(!strlen($email)) return false;
 if(!preg_match('/^[0-9a-zA-Z\.\-\_]+\@[0-9a-zA-Z\.\-]+$/',$email)) return false;
 if( preg_match('/^[^0-9a-zA-Z]|[^0-9a-zA-Z]$/',$email)) return false;
 if(!preg_match('/([0-9a-zA-Z_]{1})\@./',$email) ) return false;
 if(!preg_match('/.\@([0-9a-zA-Z_]{1})/',$email) ) return false;
 if( preg_match('/.\.\-.|.\-\..|.\.\..|.\-\-./',$email)) return false;
 if( preg_match('/.\.\_.|.\-\_.|.\_\..|.\_\-.|.\_\_./',$email)) return false;
 if(!preg_match('/\.([a-zA-Z]{2,5})$/',$email)) return false;
 return true;
}

function format_number($val)
{
 global $g_decimal_sign,$g_thousands_sep;
 $result=str_replace($g_thousands_sep,'',$val);
 $result=str_replace($g_decimal_sign,'.',$val);
 return $result;
}

class basket
{
 var $bas_catid;
 var $bas_itemid;
 var $bas_amount;
 var $bas_subtype;
 var $bas_subtype1;
 var $bas_subtype2;

 function basket() {}

 function fill_from_session($cat_id,$item_id,$amount,$sub_type,$sub_type1,$sub_type2)
 {
  $this->bas_catid=$cat_id;
  $this->bas_itemid=$item_id;
  $this->bas_amount=$amount;
  $this->bas_subtype=$sub_type;
  $this->bas_subtype1=$sub_type1;
  $this->bas_subtype2=$sub_type2;
 }

 function add_item($cat_id,$item_id,$it_amount,$sub_type,$sub_type1,$sub_type2)
 {
  $bcounter=0;$updateitem=0;$updateflag=0;
  if(count($this->bas_itemid))
  {
   foreach($this->bas_itemid as $basketid)
   {
    if(($item_id==$basketid)&&($sub_type==$this->bas_subtype[$bcounter])&&($sub_type1==$this->bas_subtype1[$bcounter])&&($sub_type2==$this->bas_subtype2[$bcounter]))
      {$updateflag=1;$updateitem=$bcounter;break;}
    $bcounter++;
   }
   if($updateflag){$this->bas_amount[$updateitem]=$this->bas_amount[$updateitem]+$it_amount;}
   else
   {
    $this->bas_catid[]=$cat_id;
    $this->bas_itemid[]=$item_id;
    $this->bas_amount[]=$it_amount;
    $this->bas_subtype[]=$sub_type;
    $this->bas_subtype1[]=$sub_type1;
    $this->bas_subtype2[]=$sub_type2;
   }
  }
  else // add first item to basket
  {
   $this->bas_catid[]=$cat_id;
   $this->bas_itemid[]=$item_id;
   $this->bas_amount[]=$it_amount;
   $this->bas_subtype[]=$sub_type;
   $this->bas_subtype1[]=$sub_type1;
   $this->bas_subtype2[]=$sub_type2;
  }
 }

 function delete_item($item_id,$sub_type,$sub_type1,$sub_type2)
 {
  $itemid=0;
  $count=count($this->bas_itemid);

  for ($i=1;$i<$count;$i++)
   {if(($this->bas_itemid[$i]==$item_id)&&($sub_type==$this->bas_subtype[$i])&&($sub_type1==$this->bas_subtype1[$i])&&($sub_type2==$this->bas_subtype2[$i]))  {$itemid=$i;break;}}
  for ($i=$itemid;$i<($count-1);$i++)
   {
    $bcounter=$i+1;
    $this->bas_itemid[$i]=$this->bas_itemid[$bcounter];
    $this->bas_catid[$i]=$this->bas_catid[$bcounter];
    $this->bas_amount[$i]=$this->bas_amount[$bcounter];
    $this->bas_subtype[$i]=$this->bas_subtype[$bcounter];
    $this->bas_subtype1[$i]=$this->bas_subtype1[$bcounter];
    $this->bas_subtype2[$i]=$this->bas_subtype2[$bcounter];
   }
  array_pop($this->bas_itemid);
  array_pop($this->bas_catid);
  array_pop($this->bas_amount);
  array_pop($this->bas_subtype);
  array_pop($this->bas_subtype1);
  array_pop($this->bas_subtype2);
  }

  function update_item_count($item_id,$sub_type,$sub_type1,$sub_type2,$it_count)
  {
   $bcounter=0;$updateitem=0;$updateflag=0;
   if($it_count==0) $this->delete_item($item_id,$sub_type,$sub_type1,$sub_type2);
   else
   {
    foreach($this->bas_itemid as $basketid)
    {
     if(($item_id==$basketid)&&($sub_type==$this->bas_subtype[$bcounter])&&($sub_type1==$this->bas_subtype1[$bcounter])&&($sub_type2==$this->bas_subtype2[$bcounter]))
        {$updateflag=1;$updateitem=$bcounter;break;}
     $bcounter++;
    }
    if($updateflag){$this->bas_amount[$updateitem]=$it_count;}
   }
 }

 function CountHash($src)
 {
  $src=str_replace(" ","",$src);
  $src=str_replace("\t","",$src);
  $src=str_replace("\n","",$src);
  $src=str_replace("&amp;","&",$src);
  $src=str_replace("&lt;","<",$src);
  $src=str_replace("&gt;",">",$src);
  $src=str_replace("&quot;","\"",$src);
  return sha1($src);
 }

 function hmac($key,$data)
 {
 $b=64;
 if(strlen($key)>$b) {$key=pack("H*",md5($key));}
 $key=str_pad($key,$b,chr(0x00));
 $ipad=str_pad('',$b,chr(0x36));
 $opad=str_pad('',$b,chr(0x5c));
 $k_ipad=$key^$ipad;
 $k_opad=$key^$opad;
 return md5($k_opad.pack("H*",md5($k_ipad.$data)));
 }

 function hmac2($key, $data){return (bin2hex(mhash(MHASH_MD5, $data, $key)));}

 function parse_itemline($il,$itemid,$bcounter,$itemname,$itemprice,$itemvat,$itemshipping,$catdata_a,$itemcode,&$sub_type,&$sub_type1,&$sub_type2,&$line_category)
 {
  global $g_price_decimals;

  $il=str_replace('<ITEM_INDEX>',$itemid,$il);
  $il=str_replace('<ITEM_ID>',$this->bas_itemid[$bcounter],$il);
  $il=str_replace('<ITEM_QUANTITY>',$this->bas_amount[$bcounter],$il);
  $il=str_replace('<ITEM_AMOUNT>',number_format($itemprice,$g_price_decimals),$il);
  $il=str_replace('<ITEM_AMOUNT_IDEAL>',number_format($itemprice*100,0,'',''),$il);
  $il=str_replace('<ITEM_VAT>',number_format($itemvat,$g_price_decimals),$il);
  $il=str_replace('<ITEM_SHIPPING>',number_format($itemshipping,$g_price_decimals),$il);
  $il=str_replace('<ITEM_CODE>',$itemcode,$il);
  if(strpos($il,'<ITEM_SUBNAME>')) {$il=str_replace('<ITEM_SUBNAME>',$this->bas_subtype[$bcounter],$il);}
  else if($this->bas_subtype[$bcounter] != '') $sub_type=' '.$this->bas_subtype[$bcounter];
  if(strpos($il,'<ITEM_SUBNAME1>')) {$il=str_replace('<ITEM_SUBNAME1>',$this->bas_subtype1[$bcounter],$il);}
  else if($this->bas_subtype1[$bcounter] != '') $sub_type1=' '.$this->bas_subtype1[$bcounter];
  if(strpos($il,'<ITEM_SUBNAME2>')) {$il=str_replace('<ITEM_SUBNAME2>',$this->bas_subtype2[$bcounter],$il);}
  else if($this->bas_subtype2[$bcounter] != '') $sub_type2=' '.$this->bas_subtype2[$bcounter];

  $il=str_replace('<ITEM_NAME>',$itemname.$sub_type.$sub_type1.$sub_type2,$il);
  $line_category=GetFromString($catdata_a[$this->bas_catid[$bcounter]-1],'','#');
  $il=str_replace('<ITEM_CATEGORY>',$line_category,$il);
  return $il;
 }

 function parse_itemlineship($il,$itemid,$shop_shipping)
 {
  global $g_price_decimals,$g_ship_vat;
  $il=str_replace('<ITEM_INDEX>',$itemid,$il);
  $il=str_replace('<ITEM_ID>','1000000',$il);
  $il=str_replace('<ITEM_QUANTITY>','1',$il);
  $il=str_replace('<ITEM_AMOUNT>',number_format($shop_shipping,$g_price_decimals),$il);
  $il=str_replace('<ITEM_AMOUNT_IDEAL>',number_format($shop_shipping*100,0,'',''),$il);
  $il=str_replace('<ITEM_NAME>','shipping',$il);
  $il=str_replace('<ITEM_VAT>',number_format($g_ship_vat,$g_price_decimals),$il);
  $il=str_replace('<ITEM_CATEGORY>','',$il);
  $il=str_replace('<ITEM_CODE>','',$il);
  return $il;
 }

 function parse_shop_cart($src,$transId,&$pending_string,&$items_lines_bw)
 {
  global $script_url,$g_id,$g_pricefield,$g_namefield,$g_price_decimals,$g_vatfield,$g_ship_vat,$_SERVER;
  global $g_shipping_field,$g_currency,$g_checkout_str;

  $item_vars_string=GetFromString($src,'<ITEM_VARS>','</ITEM_VARS>');
  if($item_vars_string=='') $item_vars_string=GetFromString($src,'<ITEM_VARS_LINE>','</ITEM_VARS_LINE>');
  $src=str_replace($item_vars_string,'',$src);
  $item_hashvars_string=GetFromString($src,'<ITEM_HASHVARS>','</ITEM_HASHVARS>');
  $src=str_replace($item_hashvars_string,'',$src);

  $bcounter=0;$price_total=0;$vat_total=0;$description_total='';$products_count=0;$items_lines='';
  $shop_shipping=0.00;$itemcounter=0;$cart_string='';$pending_string='';$items_lines_bw='';$items_hashlines='';

  $cartstring=cart('show_final',0,'','',0,'','','','',0,'','');
  $src=str_replace('%SHOP_CART%',$cartstring,$src);
  $src=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$src);

  if(count($this->bas_itemid))
  {
   $catdata='';
   $page_name=$g_id.'_0.dat';
   $fs=filesize($page_name);
   if($fs>0){$fp=fopen($page_name,"r");$catdata=fread($fp,$fs);fclose($fp);}
   $catdata_a=split("\n",$catdata);

   foreach($this->bas_itemid as $basketid)  //getting record values
   {
    $data='';
    $page_name=$g_id.'_'.$this->bas_catid[$bcounter].".dat";
    $fs=filesize($page_name);
    if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
    $fnames_a=split("[|]",GetLine($data,0));
    $ftypes_a=split("[|]",GetLine($data,1));

    $record_line=GetRecordLine($data,$this->bas_itemid[$bcounter]);
    $itemid=$bcounter+1;

    $itemname=GetFieldValueFast($g_namefield,$fnames_a,$ftypes_a,$record_line);
    $itemname=urlencode($itemname);
    $itemprice=0.00 + format_number(GetFieldValueFast($g_pricefield,$fnames_a,$ftypes_a,$record_line));
    $price_offset=$this->get_price_offset($bcounter);
    $itemprice=$itemprice+$price_offset;

    $itemcode=GetFieldValueFast('P_Code',$fnames_a,$ftypes_a,$record_line);
    if($g_vatfield != '') $itemvat=0.00 + format_number(GetFieldValueFast($g_vatfield,$fnames_a,$ftypes_a,$record_line));
    else $itemvat=0.00;
    if($g_shipping_field != '') $itemshipping=0.00 + format_number(GetFieldValueFast($g_shipping_field,$fnames_a,$ftypes_a,$record_line));
    else $itemshipping=0.00;
    $sub_type='';$sub_type1='';$sub_type2='';$line_category='';
    $items_lines=$items_lines.$this->parse_itemline($item_vars_string,$itemid,$bcounter,$itemname,$itemprice,$itemvat,$itemshipping,$catdata_a,$itemcode,$sub_type,$sub_type1,$sub_type2,$line_category);
    if($item_hashvars_string !== '')
     $items_hashlines=$items_hashlines.$this->parse_itemline($item_hashvars_string,$itemid,$bcounter,$itemname,$itemprice,$itemvat,$itemshipping,$catdata_a,$itemcode,$sub_type,$sub_type1,$sub_type2,$line_category);
    //bankwire
    $items_lines_bw=$items_lines_bw.$itemid.". ".$this->bas_amount[$bcounter]."x ".$itemname.$sub_type.$sub_type1.$sub_type2." ID [".$this->bas_itemid[$bcounter]."] ".number_format($itemprice,$g_price_decimals)." ".$g_currency." (".$line_category.")\n";
    //pending strings
    $pending_string=$pending_string.'<'.$itemid.'>'.$this->bas_itemid[$bcounter].'|'.$this->bas_catid[$bcounter].'|'.$this->bas_amount[$bcounter].'|'.number_format($itemprice,$g_price_decimals).'|'.$this->bas_subtype[$bcounter].'|'.$itemvat.'|'.$itemshipping.'|'.$this->bas_subtype1[$bcounter].'|'.$this->bas_subtype2[$bcounter].'|</'.$itemid.'>';

    if($description_total=='') $description_total=$itemname;
    else $description_total=$description_total.','.$itemname;

    $price_total=$price_total + ($itemprice*$this->bas_amount[$bcounter]);
    //vat
    if($itemvat>0) $vat_total=$vat_total + (($itemprice*$this->bas_amount[$bcounter])*($itemvat)) / ($itemvat+100);
    if($itemshipping>0) $shop_shipping=$shop_shipping + ($this->bas_amount[$bcounter]*$itemshipping);
    $itemcounter=$itemcounter + $this->bas_amount[$bcounter];
    $products_count=$products_count+1;
    $cart_string=$cart_string.$this->bas_catid[$bcounter].','.$this->bas_itemid[$bcounter].','.$this->bas_amount[$bcounter].'|';
    $bcounter++;
   }
  }

  $src=str_replace('%SHOP_SUB_TOTAL%',number_format($price_total,$g_price_decimals),$src);
  $src=str_replace('%SHOP_SUB_TOTAL_EX%',number_format($price_total-$vat_total,$g_price_decimals),$src);

  if($price_total==0) $items_lines='';
  else $shop_shipping=count_shipping($price_total,$itemcounter,$shop_shipping);

  if($shop_shipping > 0)
  {
   $price_total=$price_total + $shop_shipping;
   $itemid++;
   if(($g_shipping_field=='')||(strpos($item_vars_string,'<ITEM_SHIPPING>')===false))
    $items_lines=$items_lines.$this->parse_itemlineship($item_vars_string,$itemid,$shop_shipping);
   if($item_hashvars_string !== '')
    $items_hashlines=$items_hashlines.$this->parse_itemlineship($item_hashvars_string,$itemid,$shop_shipping);

   $vat_total=$vat_total+($shop_shipping*$g_ship_vat)/($g_ship_vat+100);
   $products_count++;
   //bankwire
   $items_lines_bw=$items_lines_bw."\nShipping=".number_format($shop_shipping,$g_price_decimals)." ".$g_currency."\n\n";
   //pe
   $pending_string=$pending_string.'<'.$itemid.'>1000000|0|1|'.number_format($shop_shipping,$g_price_decimals).'|'.$g_ship_vat.'||||</'.$itemid.'>';
  }
  $return_url=str_replace(':','%3A',$script_url).'?';
  $src=str_replace('%SHOP_RETURN_URL%',$return_url.'action=return',$src);
  $src=str_replace('%SHOP_CALLBACK_URL%',$return_url.'action=callback',$src);
  $src=str_replace('%SHOP_CURRENCY%',$g_currency,$src);
  $src=str_replace('%SHOP_CANCELRETURN_URL%',$return_url.'action=cancel',$src);
  $src=str_replace('%SHOP_VAT_TOTAL%',number_format($vat_total,$g_price_decimals),$src);
  $price_f=number_format($price_total,$g_price_decimals);
  $src=str_replace('%SHOP_TOTAL%',$price_f,$src);
  $src=str_replace('%SHOP_TOTAL_CENTS%',$price_f*100,$src);
  if(strpos($src,'%ANET_FINGERPRINT%')!==false) //authorize.net
  {
    srand(time());$sequence=rand(1,1000);$tstamp=time();
    $ctrl_str=$g_checkout_str['authorize.net'];
    $tran_key=GetFromString($ctrl_str,'x_tran_key=','&');
    $x_login=GetFromString($ctrl_str,'x_login=','&');
    $fingerprint=$this->hmac($tran_key,$x_login."^".$sequence."^".$tstamp."^".$price_f."^".$g_currency);
    $src=str_replace('%ANET_FINGERPRINT%',$fingerprint,$src);
    $src=str_replace('%ANET_TIMESTAMP%',$tstamp,$src);
    $src=str_replace('%ANET_SEQUENCE%',$sequence,$src);
    $xx=GetFromStringAbi($src,'x_tran_key=','&');
    if($xx!='') $src=str_replace($xx,'',$src);
  }
  $src=str_replace('%SHOP_DATETIME%',date("Y-m-d_H:i:s"),$src);
  $price_f=number_format($price_total,$g_price_decimals);
  $src=str_replace('%SHOP_TOTAL_IDEAL%',number_format($price_total*100,0,'',''),$src);
  $price_f=preg_replace('/[^0-9]/','_',$price_f);
  $src=str_replace('%SHOP_TRANS_NR%',$transId,$src);
  $src=str_replace('%SHOP_TRANS_ID%',$transId.'_'.sha1($price_f.'_'.$transId),$src);
  $src=str_replace('%SHOP_TOTAL_EX%',number_format($price_total-$vat_total,$g_price_decimals),$src);
  $src=str_replace('%SHOP_DESCRIPTION_TOTAL%',$description_total,$src);
  $src=str_replace('%SHOP_ITEMS_COUNT%',$products_count,$src);
  $src=str_replace('%SHOP_ITEMS_COUNT2%',$itemcounter,$src);
  $src=str_replace('%SHOP_CART_STRING%',$cart_string,$src);
  $src=str_replace('%IDEAL_VALID%',date('Y-m-d\TH:m:s:000\Z',mktime(date('h')+1,date('m'),date('s'),date('m'),date('d'),date('Y'))),$src);
  $src=str_replace('<ITEM_VARS></ITEM_VARS>',$items_lines,$src);
  $src=str_replace('<ITEM_VARS_LINE></ITEM_VARS_LINE>',$items_lines,$src);
  $src=str_replace('<ITEM_HASHVARS></ITEM_HASHVARS>',$items_hashlines,$src);
  $hasstring=GetFromStringAbi($src,'%HASH(',')%');
  if($hasstring !== '') {$src=str_replace($hasstring,$this->CountHash(GetFromString($src,'%HASH(',')%')),$src);}

  $src=str_replace('%SHOP_IPUSER%',$_SERVER['REMOTE_ADDR'],$src);

  $items_lines_bw=$items_lines_bw."Order Total=".number_format($price_total,$g_price_decimals)." ".$g_currency."\n\n\n";
  return $src;
 }

 function checkout()
 {
  global $g_id,$_SERVER,$session_transaction_id,$global_pagescr;

  $global_pagescr='';
  $page_name=($g_id+3).".html";
  $fp=fopen($page_name,"r");$page=fread($fp,filesize($page_name));fclose($fp);

  if (isset($_SESSION['frmfields']))
  {
   $vars=$_SESSION['frmfields'];
   $old_frm=GetFromStringAbi($page,'<form name="frm"','</form>');
   $new_frm=$old_frm;
   foreach($vars as $k=>$v)
   {
     if(strpos($new_frm,'name="'.$k.'">')!==false)
     {
      $select=GetFromStringAbi($new_frm,'name="'.$k.'">','</select>');
      $nselect=str_replace(' selected>','>',$select);
      $nselect=str_replace('value="'.$v.'">','value="'.$v.'" selected>',$nselect);
      $new_frm=str_replace($select,$nselect,$new_frm);
     }
     else if(strpos($new_frm,'name="'.$k.'" value="')!==false)
     {
      $input=GetFromStringAbi($new_frm,'name="'.$k.'" value="','>');
      $old_val=GetFromStringAbi($input,'value="','"');
      $ninput=str_replace($old_val,'value="'.$v.'"',$input);
      $new_frm=str_replace($input,$ninput,$new_frm);
     }
     else $new_frm=str_replace('name="'.$k.'"','name="'.$k.'" value="'.$v.'"',$new_frm);
   }
   $page=str_replace($old_frm,$new_frm,$page);
  }

  $dummy1='';$dummy2='';
  $page=$this->parse_shop_cart($page,$session_transaction_id,$dummy1,$dummy2);
  $page=str_replace('<SHOP>','',$page);$page=str_replace('</SHOP>','',$page);
  $page=parse_page_id($page);
  if($global_pagescr !== '') $page=str_replace('<!--scripts-->','<!--scripts-->'.$global_pagescr,$page);
  build_logged_info($page);
  return $page;
 }

 //mini cart
 function show_minicart($current_cat_id,$current_page_id,$current_sub_id,$src,$searchstring,$afteraction)
 {
  global $script_url,$g_realname,$g_id,$g_pricefield,$g_namefield,$g_price_decimals;
  global $cart_empty_msg,$g_currency,$g_shipping_field,$g_abs_path,$g_subcat;

  $bcounter=0;
  $itemcounter=0;
  $price_total=0.00;
  $shop_shipping=0;
  $result=$src;
  $items_result='<ul style="font-size:10px;line-height:1.3em;list-style-type:none;margin:0;padding:0;">';

  $items_string_full=GetFromStringAbi($result,'%ITEMS(',')%');
  $items_string=$items_string_full;
  $items_string=str_replace('%ITEMS(</span></p>','%ITEMS(',$items_string);
  $items_string=str_replace('<p><span class="rvts8">)%',')%',$items_string);
  $items_string=str_replace('%ITEMS(</p>','%ITEMS(',$items_string);
  $items_string=str_replace('<p>)%',')%',$items_string);
  $items_string=GetFromString($items_string,'%ITEMS(',')%');

  $read_data=(strpos($result,'%SHOP_TOTAL%') || ($items_string !== '') || strpos($result,'%SHOP_SHIPPING%'));

  if(count($this->bas_itemid))
  {
   foreach($this->bas_itemid as $basketid)
   {
    $itemid=$bcounter+1;
    if($read_data)
    {
     $data='';
     $page_name=$g_id.'_'.$this->bas_catid[$bcounter].".dat";
     $fs=filesize($page_name);
     if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
     $data=str_replace('<%23>','#',$data);
     $fnames_a=split("[|]",GetLine($data,0));$ftypes_a=split("[|]",GetLine($data,1));
     $price_offset=$this->get_price_offset($bcounter);

     $record_line=GetRecordLine($data,$this->bas_itemid[$bcounter]);

     $itemprice=0.00 + format_number(GetFieldValueFast($g_pricefield,$fnames_a,$ftypes_a,$record_line));
     $itemprice=$itemprice+$price_offset;
     $itemprice=format_number($itemprice);

     if($g_shipping_field!='') $itemshipping=0.00 + format_number(GetFieldValueFast($g_shipping_field,$fnames_a,$ftypes_a,$record_line));
     else $itemshipping=0.00;

     if($itemshipping > 0) $shop_shipping=$shop_shipping + ($this->bas_amount[$bcounter] * $itemshipping);

     $price_total=$price_total + ($itemprice*$this->bas_amount[$bcounter]);

     if($items_string !== '')
     {
      $itemline=ReplaceFields($items_string,false,$data,$this->bas_itemid[$bcounter],$this->bas_subtype[$bcounter],$this->bas_subtype1[$bcounter],$this->bas_subtype2[$bcounter]);
      $itemline=str_replace('%QUANTITY%',$this->bas_amount[$bcounter],$itemline);
      $itemline=str_replace('%LINETOTAL%',number_format($this->bas_amount[$bcounter]*$itemprice,$g_price_decimals),$itemline);

      $btn_url=$g_abs_path.$g_realname.'?action=remove&amp;iid='.$this->bas_itemid[$bcounter].'&amp;cat='.$current_cat_id.'&amp;page='.$current_page_id;
      if($g_subcat !== 'none')$btn_url.='&amp;subcat='.$current_sub_id;
      if($searchstring != '') $btn_url=$btn_url.'&amp;search='.$searchstring;

      if($this->bas_subtype[$bcounter] != '')  $btn_url.='&amp;subtype='.$this->bas_subtype[$bcounter];
      if($this->bas_subtype1[$bcounter] != '') $btn_url.='&amp;subtype1='.$this->bas_subtype1[$bcounter];
      if($this->bas_subtype2[$bcounter] != '') $btn_url.='&amp;subtype2='.$this->bas_subtype2[$bcounter];

      $btn_string=GetFromString($itemline,'<SHOP_DELETE_BUTTON>','</SHOP_DELETE_BUTTON>');
      $img_src=GetFromString($btn_string,'src="','"');
      $btn_parsed='<a href="'.$btn_url.'"><img src="'.$img_src.'" border="0" align="bottom" alt=""></a>';
      $itemline=str_replace('<SHOP_DELETE_BUTTON>'.$btn_string.'</SHOP_DELETE_BUTTON>',$btn_parsed,$itemline);
      $itemline=str_replace('%SHIPPING%',$itemshipping*$this->bas_amount[$bcounter],$itemline);
      $itemline=str_replace('%SHOP_CARTPRICE%',number_format(format_number($itemprice),$g_price_decimals),$itemline);
      $items_result=$items_result.'<li>'.$itemline.'</li>';
     }
    }
    $itemcounter=$itemcounter+$this->bas_amount[$bcounter];
    $bcounter++;
   }
  }
  else {$result=$cart_empty_msg;}

  if(strpos($result,'%SHOP_SHIPPING'))
  {
   if($price_total !== 0){$shop_shipping=count_shipping($price_total,$itemcounter,$shop_shipping);}
   if($shop_shipping > 0){$price_total=$price_total+$shop_shipping;}
   $result=str_replace('%SHOP_SHIPPING%',number_format($shop_shipping,$g_price_decimals),$result);
  }

  if($items_string !== '') {$result=str_replace($items_string_full,$items_result.'</ul>',$result);}
  $result=str_replace('%SHOP_TOTAL%',number_format($price_total,$g_price_decimals),$result);
  $result=str_replace('%SHOP_ITEMS_COUNT%',count($this->bas_itemid),$result);
  $result=str_replace('%SHOP_ITEMS_COUNT2%',$itemcounter,$result);

  $cleanuplink=$script_url.'?cleanup=';
  if(($current_cat_id==0)&&($current_page_id==0)) {$cleanuplink=$cleanuplink.'&amp;action='.$afteraction;}
  else {
  $cleanuplink=$cleanuplink.'&amp;cat='.$current_cat_id.'&amp;page='.$current_page_id;
  if($g_subcat !== 'none')$cleanuplink.='&amp;subcat='.$current_sub_id;
  }
  if($searchstring != '') $cleanuplink=$cleanuplink.'&amp;search='.$searchstring;
  $cleanup_string=GetFromStringAbi($result,'"%SHOP_CLEANUP_BUTTON%','"');
  if($cleanup_string !== '') $result=str_replace($cleanup_string,'"'.$cleanuplink.'"',$result);

  $result=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$result);
  return $result;
 }

 function get_price_offset($id)
 {
  $offset=0;
  if($this->bas_subtype[$id] != '')  {$off=GetFromString($this->bas_subtype[$id],'[',']');if(strpos($off,' ')!==false)$off=GetFromString($off,'',' ');if($off!=='') $offset=$offset+(float)$off;}
  if($this->bas_subtype1[$id] != '') {$off=GetFromString($this->bas_subtype1[$id],'[',']');if(strpos($off,' ')!==false)$off=GetFromString($off,'',' ');if($off!=='') $offset=$offset+(float)$off;}
  if($this->bas_subtype2[$id] != '') {$off=GetFromString($this->bas_subtype2[$id],'[',']');if(strpos($off,' ')!==false)$off=GetFromString($off,'',' ');if($off!=='') $offset=$offset+(float)$off;}
  return $offset;
 }

 function show_cart($cart_only,$current_cat_id,$current_sub_id,$current_page_id,$action,$searchstring)
 {
   global $script_url,$g_realname,$g_id,$g_pricefield,$g_namefield,$g_price_decimals,$g_vatfield;
   global $g_ship_vat,$cart_empty_msg,$g_currency,$g_shipping_field,$g_abs_path,$global_pagescr,$g_subcat;

   $page_name=($g_id+4).".html";
   $fp=fopen($page_name,"r");$page=fread($fp,filesize($page_name));fclose($fp);

   $google_on=(strpos($page,'%GOOGLE_CHECKOUT(')!==false);
   if($google_on)
   {
     $google_str=GetFromString($page,'%GOOGLE_CHECKOUT(',')%');
     $google_id=GetFromString($google_str,'<ID>','</ID>');
     $google_cid=GetFromString($google_str,'<CID>','</CID>');
     if($google_cid=='US') $google_cid='en_US';
     else $google_cid='en_GB';
     $google_tax=GetFromString($google_str,'<TAX>','</TAX>');
     $google_shipping=GetFromString($google_str,'<SHIPPING>','</SHIPPING>');
     $google_sandbox=(GetFromString($google_str,'<SANDBOX>','</SANDBOX>')=='TRUE');
     if($google_sandbox) $google_url='https://sandbox.google.com/checkout/';
     else $google_url='https://checkout.google.com/';
     $google_form='<form method="POST" target="_blank" action="'.$google_url.'cws/v2/Merchant/'.$google_id.'/checkoutForm" accept-charset="utf-8">';
    }

   if($cart_only) {$page_head=GetFromString($page,'<SHOP>','<LISTER_BODY>');$page_foot=GetFromString($page,'</LISTER_BODY>','</SHOP>');}
   else {$page_head=GetFromString($page,'','<LISTER_BODY>');$page_foot=GetFromString($page,'</LISTER_BODY>','');}
   $page_body=GetFromString($page,'<LISTER_BODY>','</LISTER_BODY>');

   $bcounter=0;$itemcounter=0;$price_total=0.00;$vat_total=0.00;$shop_shipping=0;$result='';$cart_string='';

   if(count($this->bas_itemid))
   {
    foreach($this->bas_itemid as $basketid)
    {
     $data='';
     $page_name=$g_id.'_'.$this->bas_catid[$bcounter].".dat";
     $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
     $data=str_replace('<%23>','#',$data);
     $fnames_a=split("[|]",GetLine($data,0));$ftypes_a=split("[|]",GetLine($data,1));

     $record_line=GetRecordLine($data,$this->bas_itemid[$bcounter]);

     $itemid=$bcounter+1;
     $itemline=str_replace('%QUANTITY%',$this->bas_amount[$bcounter],$page_body);
     $itemline=ReplaceFields($itemline,false,$data,$this->bas_itemid[$bcounter],$this->bas_subtype[$bcounter],$this->bas_subtype1[$bcounter],$this->bas_subtype2[$bcounter]);
     $btn_url=$g_abs_path.$g_realname.'?action=remove&amp;iid='.$this->bas_itemid[$bcounter].'&amp;cat='.$current_cat_id.'&amp;page='.$current_page_id;
     if($g_subcat !== 'none')$btn_url.='&amp;subcat='.$current_sub_id;
     if($searchstring != '') $btn_url=$btn_url.'&amp;search='.$searchstring;
     if($this->bas_subtype[$bcounter] != '')  $btn_url.='&amp;subtype='.$this->bas_subtype[$bcounter];
     if($this->bas_subtype1[$bcounter] != '') $btn_url.='&amp;subtype1='.$this->bas_subtype1[$bcounter];
     if($this->bas_subtype2[$bcounter] != '') $btn_url.='&amp;subtype2='.$this->bas_subtype2[$bcounter];

     // delete button
     $btn_string=GetFromString($itemline,'<SHOP_DELETE_BUTTON>','</SHOP_DELETE_BUTTON>');
     $img_src=GetFromString($btn_string,'src="','"');
     $btn_parsed='<a href="'.$btn_url.'"><img src="'.$img_src.'" border="0" align="bottom" alt=""></a>';
     $itemline=str_replace('<SHOP_DELETE_BUTTON>'.$btn_string.'</SHOP_DELETE_BUTTON>',$btn_parsed,$itemline);
     // quantity form
     $btn_url=$g_abs_path.$g_realname;
     $newtarget=getTarget(false);

     $btn_string=GetFromString($itemline,'<QUANTITY>','</QUANTITY>');
     $img_src=GetFromString($btn_string,'src="','"');
     $btn_parsed='<form method="GET" action="'.$btn_url.'" onsubmit="" target="'.$newtarget.'">'.$btn_string;
     $btn_parsed.='<input type="hidden" name="action" value="update"><input type="hidden" name="iid" value="'.$this->bas_itemid[$bcounter].'"><input type="hidden" name="subtype" value="'.$this->bas_subtype[$bcounter].'"><input type="hidden" name="subtype1" value="'.$this->bas_subtype1[$bcounter].'"><input type="hidden" name="subtype2" value="'.$this->bas_subtype2[$bcounter].'"><input type="hidden" name="cat" value="'.$current_cat_id.'"><input type="hidden" name="page" value="'.$current_page_id.'">';
     if($g_subcat !== 'none')$btn_parsed.='<input type="hidden" name="subcat" value="'.$current_sub_id.'">';
     $btn_parsed.='</FORM>';
     $itemline=str_replace($btn_string,$btn_parsed,$itemline);

     $itemname=GetFieldValueFast($g_namefield,$fnames_a,$ftypes_a,$record_line);
     $itemprice=0.00 + format_number(GetFieldValueFast($g_pricefield,$fnames_a,$ftypes_a,$record_line));
     $price_offset=$this->get_price_offset($bcounter);
     $itemprice=$itemprice+$price_offset;
     $itemline=str_replace('%SHOP_CARTPRICE%',number_format(format_number($itemprice),$g_price_decimals),$itemline);

     if($google_on)
     {
      $google_form.=get_input('item_name_'.$itemid,$itemname);
      $google_form.=get_input('item_description_'.$itemid,$this->bas_subtype[$bcounter].'_'.$this->bas_subtype1[$bcounter].' '.$this->bas_subtype2[$bcounter]);
      $google_form.=get_input('item_quantity_'.$itemid,$this->bas_amount[$bcounter]);
      $google_form.=get_input('item_price_'.$itemid,number_format($itemprice,$g_price_decimals,'.',''));
      $google_form.=get_input('item_currency_'.$itemid,$g_currency);
     }

     $rep=$g_abs_path.$g_realname.'?action=item&amp;iid='.$this->bas_itemid[$bcounter].'&amp;cat='.$this->bas_catid[$bcounter].'&amp;page='.$current_page_id;
     if($g_subcat !== 'none')$rep.='&amp;subcat='.$current_sub_id;
     $itemline=str_replace('%SHOP_DETAIL%',$rep,$itemline);

     $itemline=str_replace('%LINETOTAL%',number_format($this->bas_amount[$bcounter]*$itemprice,$g_price_decimals),$itemline);

     if($g_shipping_field !='') $itemshipping=0.00 + format_number(GetFieldValueFast($g_shipping_field,$fnames_a,$ftypes_a,$record_line));
     else $itemshipping=0.00;

     $itemline=str_replace('%SHIPPING%',$itemshipping*$this->bas_amount[$bcounter],$itemline);
     $result.=$itemline;

     if($g_vatfield != '') $itemvat=0.00 + format_number(GetFieldValueFast($g_vatfield,$fnames_a,$ftypes_a,$record_line));
     else $itemvat=0.00;

     $itemcounter=$itemcounter+$this->bas_amount[$bcounter];
     $price_total=$price_total+($itemprice*$this->bas_amount[$bcounter]);
     //vat
     if($itemvat>0) $vat_total=$vat_total+(($itemprice*$this->bas_amount[$bcounter])*($itemvat)) / ($itemvat+100);
     if($itemshipping>0) $shop_shipping=$shop_shipping+($this->bas_amount[$bcounter]*$itemshipping);

     $cart_string=$cart_string.$this->bas_catid[$bcounter].','.$this->bas_itemid[$bcounter].','.$this->bas_amount[$bcounter].'|';
     $bcounter++;
    }
   }
   else {$result=$cart_empty_msg;}

   $page_foot=str_replace('%SHOP_SUB_TOTAL%',number_format($price_total,$g_price_decimals),$page_foot);
   $page_foot=str_replace('%SHOP_SUB_TOTAL_EX%',number_format($price_total-$vat_total,$g_price_decimals),$page_foot);

   $checkout_link=$g_abs_path.$g_realname.'?action=checkout"';

   if($itemcounter==0) {$checkout_link='';}
   else $shop_shipping=count_shipping($price_total,$itemcounter,$shop_shipping);

   if($shop_shipping>0)
   {
    if($google_on)
    {
      $google_form.=get_input('ship_method_name_1','Shipping');
      $google_form.=get_input('ship_method_price_1',number_format($shop_shipping,$g_price_decimals,'.',''));
      $google_form.=get_input('ship_method_currency_1',$g_currency);
    }
    $price_total=$price_total+$shop_shipping;
    $vat_total=$vat_total+($shop_shipping*$g_ship_vat)/($g_ship_vat+100);
    $itemid++;
   }
   else if($google_on && ($google_shipping!==''))
   {
     $shi=explode('|',$google_shipping);
     foreach($shi as $k=>$v)
     {
      if($v!=='')
      {$google_form.=get_input('ship_method_name_'.$k,GetFromString($v,'','='));$google_form.=get_input('ship_method_price_'.$k,GetFromString($v,'=',''));}
     }
   }

   $page_foot=str_replace('%SHOP_SHIPPING%',number_format($shop_shipping,$g_price_decimals),$page_foot);
   $page_foot=str_replace('%SHOP_VAT_TOTAL%',number_format($vat_total,$g_price_decimals),$page_foot);
   $page_foot=str_replace('%SHOP_TOTAL%',number_format($price_total,$g_price_decimals),$page_foot);
   $page_foot=str_replace('%SHOP_ITEMS_COUNT%',count($this->bas_itemid),$page_foot);
   $page_foot=str_replace('%SHOP_ITEMS_COUNT2%',$itemcounter,$page_foot);
   $page_foot=str_replace('%SHOP_TOTAL_EX%',number_format($price_total-$vat_total,$g_price_decimals),$page_foot);

   if(($action=='show_final') || ($itemcounter==0))
   {
    $temp=GetFromStringAbi($page_foot,'<a href="<SHOP_URL>','</a>');$page_footer=str_replace($temp,'',$page_foot);
    $temp=GetFromStringAbi($page_foot,'<a href="%SHOP_CHECKOUT%"','</a>');$page_foot=str_replace($temp,'',$page_foot);
    $temp=GetFromStringAbi($page_foot,'<a href="'.$g_abs_path.$g_realname.'?action=pay','</a>');$page_foot=str_replace($temp,'',$page_foot);
    $temp=GetFromStringAbi($page_foot,'<a href="'.$g_abs_path.$g_realname.'?action=checkout','</a>');$page_foot=str_replace($temp,'',$page_foot);
   }
   else {$page_foot=str_replace('%SHOP_CHECKOUT%',$checkout_link,$page_foot);}

   $page_foot=str_replace('%SHOP_CART_STRING%',$cart_string,$page_foot);

   $result=$page_head.$result.$page_foot;
   $result=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$result);
   $result=parse_page_id($result);

   $cleanuplink=$script_url.'?cleanup=';

   if($google_on)
   {
    if($google_tax!=='')
    {
      $tax=explode('|',$google_tax);
      foreach($tax as $k=>$v)
      {
       if($v!=='')
       {
        $google_form.=get_input('tax_us_state',GetFromString($v,'','='));
        $google_form.=get_input('tax_rate',GetFromString($v,'=',''));
       }
      }
    }
    $google_form.='<input type="image" name="Google Checkout" alt="Fast checkout through Google" ';
    $google_form.='src="'.$google_url.'buttons/checkout.gif?merchant_id='.$google_id.'&w=180&h=46&style=white&variant=text&loc='.$google_cid.'" height="46" width="180"/></form>';
    $gstr=GetFromStringAbi($result,'<a href="&type=GOOGLE&%GOOGLE_CHECKOUT(','</a>');
    $result=str_replace($gstr,$google_form,$result);
   }

   if(($current_cat_id==0)&&($current_page_id==0)) {$cleanuplink=$cleanuplink.'&amp;action=checkout';}
   else {
   $cleanuplink=$cleanuplink.'&amp;cat='.$current_cat_id.'&amp;page='.$current_page_id.'&amp;action=basket';
   if($g_subcat !== 'none')$cleanuplink.='&amp;subcat='.$current_sub_id;
   }
   if($searchstring != '') $cleanuplink=$cleanuplink.'&amp;search='.$searchstring;
   $cleanup_string=GetFromStringAbi($result,'"%SHOP_CLEANUP_BUTTON%','"');
   if($cleanup_string !== '')  $result=str_replace($cleanup_string,'"'.$cleanuplink.'"',$result);
   if($global_pagescr !== '') $result=str_replace('<!--scripts-->','<!--scripts-->'.$global_pagescr,$result);
   $result=str_replace('<SHOP>','',$result);
   $result=str_replace('</SHOP>','',$result);
   if(!$cart_only)build_logged_info($result);
   return $result;
  }

  function delete_cart ()
  {unset($this->bas_itemid);unset($this->bas_catid);unset($this->bas_amount);unset($this->bas_subtype);unset($this->bas_subtype1);unset($this->bas_subtype2);}

  function process_order()
  {
   global $script_url,$g_id,$g_checkout_str,$session_transaction_id,$set_bankwire_email,$g_data_ext;
   global $g_send_to,$g_shop_name,$g_check_email,$g_payment_method_field,$g_return_subject,$admin_message,$g_return_subject_admin;

   $formfields=get_fields();
   $direct=false;
   if(isset($formfields[$g_payment_method_field])) {$payment_method=$formfields[$g_payment_method_field];}
   else {$payment_method='paypal';$direct=true;}

   if(eregi("\r",$payment_method) || eregi("\n",$payment_method)){die("Why ?? :(");}

   if(isset($_POST[$g_check_email])){if(check_fields($formfields)) return;}
   else if(!$direct)
   {
    echo 'default email field is not correctly set!! go to shop page settings and change email field property to match (case-sensitive) email field name on page';
    exit;
   }

   $safe_transaction_id=writeto_file('<id>',$g_id.'_orderid'.$g_data_ext,false);
   $date_time=date('Y-m-d H:i:s');

   $pending_string='';$items_lines_bw='';
   if(isset($g_checkout_str[$payment_method])) $ctrl_str=$g_checkout_str[$payment_method];
   else $ctrl_str='';
   $postdata=$this->parse_shop_cart($ctrl_str,$safe_transaction_id,$pending_string,$items_lines_bw);
   //writing order data to pending orders
   $session_transaction_id=$safe_transaction_id;
   writeto_file('<order_'.$session_transaction_id.'><date>'.$date_time.'</date><items>'.$pending_string.'</items><form_fields>'._build_fields($formfields,'|').'</form_fields></order_'.$session_transaction_id.'>',$g_id.'_pending_orders'.$g_data_ext,true);
   $pm=strtolower($payment_method);

   if(($pm=='authorize.net')||($pm=='paypal')||($pm=='worldpay')||($pm=='default')||($pm=='nochex')||($pm=='eway')||($pm=='ideal'))
   {
    $form_data='';
    while(list($key,$val)=each($_POST))
    {
     if($key!='x_tran_key')
     {
      if(($key=='night_phone_c')&&($val==''))$val='0';
      if(($key=='night_phone_a')&&($val==''))$val='0';
      if($key==$g_check_email){$postdata=str_replace('%SHOP_USER_EMAIL%',$val,$postdata);}
      $val=stripslashes($val);
      $val=urlencode($val);   
      $form_data.='&' .$key.'='.$val;
     }
    }
    if($pm=='paypal')
    {
     if(isset($_POST['night_phone_b']))
     {
      if(!isset($_POST['night_phone_c'])){$form_data.='&night_phone_c=0';}
      if(!isset($_POST['night_phone_a'])){$form_data.='&night_phone_a=0';}
     }
    }
    
    $postdata=str_replace(' ','%20',$postdata);
    $postdata=$postdata.$form_data;
    m_header($postdata,false);
   }
   else
   {
    $parsed_fields=_build_fields($formfields,"\n");
    $abs_url=$script_url.'?action=orders&pending';$abs_url=str_replace(" ","%20",$abs_url);
    //send e-mail to us
    $ip=(isset($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'';
    $mail_mess=str_replace(array('%PAYMENT_TYPE%','%ORDERS_LINK%','%ORDER_ID%','%FORM_DATA%','%SHOP_CART%','%SHOP_IPUSER%','%ORDER_DATE%'),array($payment_method,$abs_url,$session_transaction_id,$parsed_fields,$items_lines_bw,$ip,date("Y-m-d_H:i:s")),$admin_message);  
    $_send_from=get_shop_from();
    $mail_subject=str_replace(array('%PAYMENT_TYPE%','%ORDERS_LINK%','%ORDER_ID%'),array($payment_method,$abs_url,$session_transaction_id),$g_return_subject_admin);
    
    $result=send_mail('',$mail_mess,$mail_subject,$_send_from,$g_send_to);

    if($set_bankwire_email)  //send e-mail to customer
    {
     $abs_url=$script_url.'?action=order&id='.$session_transaction_id.'_'.crypt($session_transaction_id,'jhjshdjhj98');
     $abs_url=str_replace(" ","%20",$abs_url);
     $text_msg='';
     $parsed_return=parse_returnpage($payment_method,$session_transaction_id,true,'',$text_msg,false);
     $result=send_mail($parsed_return,$text_msg,$g_return_subject,$_send_from,$formfields[$g_check_email]);
    }
    $abs_url=$script_url.'?action=return_ok&payment='.$payment_method;
    m_header($abs_url,false);
   }
  }

  function get_pending_order_line($id)
  {
    global $g_id,$g_data_ext;
    $orders='';
    $page_name=$g_id."_pending_orders".$g_data_ext;
    $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$orders=fread($fp,$fs);fclose($fp);}
    return GetFromString($orders,'<order_'.$id.'>','</order_'.$id.'>');
  }

  function return_file()
  {
  global $g_id,$g_audiofield,$g_namefield,$session_transaction_id,$g_data_ext,$g_callback_mail;

  $id=0;$pa_id='';$trid='';
  if(isset($_REQUEST['id'])) $id=intval($_REQUEST['id']);
  if(isset($_REQUEST['trid'])) $trid=intval($_REQUEST['trid']);
  if(isset($_REQUEST['pa_id'])) $pa_id=$_REQUEST['pa_id'];

  if(($pa_id=='')||(!$g_callback_mail)) $order_string=return_order($session_transaction_id,'',false);
  else
  {
    $order_string=return_order($trid,'',false);
    if(strpos($order_string,'|payer_id='.$pa_id.'|') !== false) $session_transaction_id=$trid;
    else if(strpos($order_string,'payment_status=moved+email'.$pa_id.'|') !== false) $session_transaction_id=$trid;
  }

  if($session_transaction_id >0)
  {
    if(($id>0)&&($order_string !== ''))
    {

      $order_line=$this->get_pending_order_line($session_transaction_id);
      $items=GetFromString($order_line,'<items>','</items>');
      $count=count(explode('><',$items));
      $item=GetFromString($order_line,'<'.$id.'>','</'.$id.'>');

      if($item !='')
      {
      $items=explode('|',$item);$item_id=$items[0];$cat_id=$items[1];
      $data='';
      $page_name=$g_id.'_'.$cat_id.".dat";
      $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
      $data=str_replace('<%23>','#',$data);
      $fnames_a=split("[|]",GetLine($data,0));$ftypes_a=split("[|]",GetLine($data,1));

      $record_line=GetRecordLine($data,$item_id);
      $itemname=GetFieldValueFast($g_namefield,$fnames_a,$ftypes_a,$record_line);
      $fname=GetFieldValueFast($g_audiofield,$fnames_a,$ftypes_a,$record_line);
      if(strpos($fname,'/')!==false) {$audioname_encoded=$fname;$fname=basename($fname);}
      else $audioname_encoded=$g_id.'_'.$item_id.'.php';
      if(is_readable($audioname_encoded))
      {
       $fp=fopen($audioname_encoded,'rb');
       if($fp)
       {
        $safe_mode=ini_get('safe_mode');
        if(!$safe_mode) set_time_limit(86400);
        $ext=end(explode(".",strtolower($fname)));
        if(strpos($ext,'tif')!==false) $mime='image/tiff';
        elseif(strpos($ext,'png')!==false) $mime='image/png';
        elseif(strpos($ext,'gif')!==false) $mime='image/gif';
        elseif(strpos($ext,'jp')!==false) $mime='image/jpeg';
        elseif(strpos($ext,'pdf')!==false) $mime='application/pdf';
        elseif(strpos($ext,'swf')!==false) $mime='application/x-shockwave-flash';
        elseif(strpos($ext,'doc')!==false) $mime='application/msword';
        elseif(strpos($ext,'wav')!==false) $mime='audio/wav';
        elseif(strpos($ext,'avi')!==false) $mime='video/avi';
        else $mime='audio/mpeg3';
        header("Cache-Control: ");
        header("Pragma: ");
        header("Content-type: ".$mime);
        header("Content-Length: " .(string)(filesize($audioname_encoded)) );
        header("Content-disposition: attachment;filename=".$fname);
        header("Content-Transfer-Encoding: binary\n");
        fpassthru($fp);
       }
      }
     }
    }
    else echo 'error ['.$session_transaction_id.']';
   }
  }

  function order_ok($payment,$tr_id)
  {
   global $g_id,$g_data_ext,$g_callback_str;

   $page_name=$g_id."_orders".$g_data_ext;
   $fp=fopen($page_name,"r");
   while($fp===false) {$fp=fopen($page_name,"r");}
   $orders=fread($fp,filesize($page_name));
   fclose($fp);
   $fx_id=(isset($g_callback_str[$payment]['SHOP_RET_ORDERID']))?'custom':$g_callback_str[$payment]['SHOP_RET_ORDERID'];
   $cb_param=$fx_id.'='.$tr_id.'_';
   if(strpos($orders,$cb_param) !==false) $result=1;
   else $result=0;
   if(($result==0)&&($payment='paypal'))  //check for pending orders
   {
    $page_name=$g_id."_paypal".$g_data_ext;
    $fp=fopen($page_name,"r");$callbacks=fread($fp,filesize($page_name));fclose($fp);
    $callbacks_a=explode('VERIFIED**',$callbacks);
    foreach($callbacks_a as $k=>$v) {if((strpos($v,$cb_param) !==false)&&(strpos($v,'&payment_status=Pending') !==false)) $result=2;}
   }
   return $result;
  }

  function return_ok($pt)
  {
   global $session_transaction_id,$g_send_to,$script_url,$g_checkout_callback_on,$g_return_subject,$_send_from,$g_check_email,$g_shop_name;

   $result='';$text_msg='';
   if(isset($_REQUEST['payment']))$payment_type=$_REQUEST['payment'];
   else if($pt!=='') $payment_type=$pt;
   else
   {
     $order_line=$this->get_pending_order_line($session_transaction_id);
     $payment_type=GetFromString($order_line,'ec_PaymentMethod=','|');
     if($payment_type=='')$payment_type='paypal';
   }

   if((($payment_type=='eway')||($payment_type=='paypal')||($payment_type=='worldpay')||($payment_type=='authorize.net'))&&($g_checkout_callback_on[$payment_type]=='TRUE'))
   {
    if($session_transaction_id > 0)
    {
     if($pt==$payment_type) $order_valid=1;
     else {sleep(10);$order_valid=$this->order_ok($payment_type,$session_transaction_id);}
     if($order_valid==0) {sleep(10);$order_valid=$this->order_ok($payment_type,$session_transaction_id);}
     if($order_valid==1) $result=parse_returnpage($payment_type,$session_transaction_id,false,'',$text_msg,false);
     else
     {
       $abs_url=$script_url.'?action=return&payment='.$payment_type;
       if($order_valid==0) $result='error processing order ['.$session_transaction_id.'], try to <a href="'.$abs_url.'">reload</a> page or contact shop <a href="mailto:'.$g_send_to.'">administrator</a>';
       else if($order_valid==2) $result='Order ['.$session_transaction_id.'] is pending, Finish your order and <a href="'.$abs_url.'">reload</a> page or contact shop <a href="mailto:'.$g_send_to.'">administrator</a>';
     }
    }
   }
   else
   {
     if(($payment_type=='paypal')&&($g_checkout_callback_on[$payment_type]!=='TRUE'))
     {
      if(!isset($order_line)) $order_line=$this->get_pending_order_line($session_transaction_id);
      $parsed_fields=str_replace('|',"\n",GetFromString($order_line,'<form_fields>','</form_fields>'));
      $abs_url=$script_url.'?action=orders&pending';$abs_url=str_replace(" ","%20",$abs_url);
    //send e-mail to us
      $mail_mess="this is ".$payment_type." notification from ".$g_shop_name."\n"
       ."you can find more info about orders here:\n".$abs_url." \n\n"
       ."Order Id: ".$session_transaction_id."\n"
       .$parsed_fields;
      $_send_from=get_shop_from();
      $result=send_mail('',$mail_mess,$payment_type.' order',$_send_from,$g_send_to);
     }
     $result=parse_returnpage($payment_type,$session_transaction_id,false,'',$text_msg,false);
   }
   evalAndPrint($result);
  }
}
//end basket class**

function replace_rel_paths($src)
{
 global $script_url;
 $src=str_replace('src="..','src="'.GetFromString($script_url,'','/documents'),$src);
 $src=str_replace('href="..','href="'.GetFromString($script_url,'','/documents'),$src);
 $src=str_replace('url(..','url('.GetFromString($script_url,'','/documents'),$src);
 $src=str_replace('url (..','url('.GetFromString($script_url,'','/documents'),$src);
 return $src;
}

function parse_returnpage($payment_type,$order_id,$foremail,$payer_id,&$text_msg,$bwconfirmed)
{
 global $script_url,$g_id,$g_audiofield,$g_namefield,$g_currency,$g_data_ext,$g_callback_mail_template;
 global $g_price_decimals,$g_ship_vat,$session_transaction_id,$_SERVER,$g_checkout_callback_on;

 $result='';
 $itemcounter=0;$bcounter=0;
 $page_name=($g_id+2).".html";
 $fp=fopen($page_name,"r");
 $callback_on=(isset($g_checkout_callback_on[$payment_type]))&&($g_checkout_callback_on[$payment_type]=='TRUE');
 if($bwconfirmed)$callback_on=true;
 $is_download=($callback_on && ($g_audiofield !== 'none')&&($g_audiofield !== ''));
 $return_page=fread($fp,filesize($page_name));

 if($foremail)  //needed for worldpay callback
 {
  if(($g_callback_mail_template !== '')&&($callback_on))
    {$fr=fopen($g_callback_mail_template,"r");$return_page=fread($fr,filesize($g_callback_mail_template));fclose($fr);}
  else
  {
   if(strpos($return_page,'<style type="text/css">') !== false)
   {
    $return_page='<head>'.GetFromStringAbi($return_page,'<style type="text/css">','</style>').'</head><body>'.GetFromStringAbi($return_page,'<SHOP>','</SHOP>').'</body>';
    $return_page=replace_rel_paths($return_page);
   }
   else
   {
     if(strpos($return_page,'</HEAD>')!==false){$head='HEAD';} else $head='head';
     $return_page=GetFromStringAbi($return_page,'<'.$head,'</'.$head.'>').'<body>'.GetFromStringAbi($return_page,'<SHOP>','</SHOP>').'</body>';
   }
   $return_page=str_replace(GetFromStringAbi($return_page,'<!--menu_java-->','<!--/menu_java-->'),'',$return_page);
  }
 }

 if(($payment_type=='worldpay')||($payment_type=='authorize.net')) {$return_page=replace_rel_paths($return_page);}
 $payment_section='';
 if($is_download) {$payment_section=GetFromString($return_page,'<download>','</download>');}

 if($payment_section=='') $payment_section=GetFromString($return_page,'<'.$payment_type.'>','</'.$payment_type.'>');
 if(($payment_section=='')&&(strtolower($payment_type)=='bankwire')) $payment_section=GetFromString($return_page,'<BANKWIRE>','</BANKWIRE>');
 if($payment_section=='') $payment_section=GetFromString($return_page,'<default>','</default>');
 if($payment_section != '')
 {
  if($foremail)
  {
   $payment_section=str_replace(GetFromStringAbi($payment_section,'<header>','</header>'),'',$payment_section);
   $payment_section=str_replace(GetFromStringAbi($payment_section,'<footer>','</footer>'),'',$payment_section);
  }
  $return_page=str_replace(GetFromString($return_page,'<SHOP>','</SHOP>'),$payment_section,$return_page);
 }
 else if(strtolower($payment_type)!='bankwire') $return_page=str_replace(GetFromString($return_page,'<BANKWIRE>','</BANKWIRE>'),'',$return_page);

 $product_body=GetFromString($return_page,'<LISTER_BODY>','</LISTER_BODY>');

 $page_name=$g_id."_pending_orders".$g_data_ext;
 $fp=fopen($page_name,"r");$orders=fread($fp,filesize($page_name));fclose($fp);

 $order_line=GetFromString($orders,'<order_'.$order_id.'>','</order_'.$order_id.'>');

 $items=GetFromString($order_line,'<items>','</items>');
 $count=count(explode('><',$items));

 $shipping_amount=0;$price_total=0;$vat_total=0;

 $catdata='';
 $page_name=$g_id.'_0.dat';
 $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$catdata=fread($fp,$fs);fclose($fp);}
 $catdata_a=split("\n",$catdata);

 $id=1;
 for($i=1;$i<($count+1);$i++)
 {
  $item=GetFromString($order_line,'<'.$i.'>','</'.$i.'>');
  if($item != '')
  {
   $items=explode('|',$item);
   $item_id=$items[0];$cat_id=$items[1];
   $item_count=$items[2];
   $item_price=str_replace(',','',$items[3]);
   $item_subname=$items[4].' '.$items[7].' '.$items[8];
   $itemvat=$items[5];

   if($item_id != '1000000') //getting record values
   {
    $data='';
    $page_name=$g_id.'_'.$cat_id.".dat";
    $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
    $fnames_a=split("[|]",GetLine($data,0));$ftypes_a=split("[|]",GetLine($data,1));

    $record_line=GetRecordLine($data,$item_id);
    $itemname=GetFieldValueFast($g_namefield,$fnames_a,$ftypes_a,$record_line);
    $itemcode=GetFieldValueFast('P_Code',$fnames_a,$ftypes_a,$record_line);
    $itemcounter=$itemcounter + $item_count;
    $bcounter++;

    $product_line=$product_body;
    if($is_download)
    {
     $audioname=GetFieldValueFast($g_audiofield,$fnames_a,$ftypes_a,$record_line);
     $dlink_caption=GetFromString($product_line,'%SHOP_ITEM_DOWNLOAD_LINK(',')%');
     $dlink_string='%SHOP_ITEM_DOWNLOAD_LINK('.$dlink_caption.')%';

     $pa_id='';
     if($foremail) $pa_id='&amp;pa_id='.$payer_id;

     if(trim($audioname)=='') {$item_string='';}
     else $item_string='<a class="rvts4" href="'.$script_url.'?action=download&id='.$id.'&trid='.$order_id.$pa_id.'">'.$dlink_caption.'</a>';

     $product_line=str_replace($dlink_string,$item_string,$product_line);
    }
    $product_line=str_replace('%SHOP_ORDER_ITEM_NAME%',$itemname,$product_line);
    $product_line=str_replace('%SHOP_ORDER_ITEM(P_Code)%',$itemcode,$product_line);
    $product_line=str_replace('%SHOP_ORDER_ITEM_CATEGORY%',GetFromString($catdata_a[$cat_id-1],'','#'),$product_line);
    $product_line=str_replace('%SHOP_ORDER_ITEM_SUBNAME%',$item_subname,$product_line);
    $product_line=str_replace('%SHOP_ORDER_ITEM_COUNT%',$item_count,$product_line);
    $product_line=str_replace('%SHOP_ORDER_ITEM_AMOUNT%',number_format($item_price,$g_price_decimals),$product_line);
    $product_line=str_replace('%LISTER_COUNTER%',$i,$product_line);
    $price_total=$price_total + ($item_price*$item_count);
    if($itemvat > 0) $vat_total=$vat_total+(($item_price*$item_count)*($itemvat)) / ($itemvat+100);

    $result.=$product_line;
    $id++;
   }
   else {$shipping_amount=$item_price;}
  }
 }
 $result=str_replace($product_body,$result,$return_page);
 //parsing fields
 $order_fields=GetFromString($order_line,'<form_fields>','</form_fields>');
 $order_fields=explode('|',$order_fields);
 $count=count($order_fields);
 for($i=0;$i<($count);$i++)
 {
  $fname=GetFromString($order_fields[$i],'','=');$fvalue=GetFromString($order_fields[$i],'=','');
  $result=str_replace('%'.$fname.'%',$fvalue,$result);
 }

 $result=str_replace('%SHOP_SUB_TOTAL_EX%',number_format($price_total-$vat_total,$g_price_decimals),$result);
 $result=str_replace('%SHOP_SUB_TOTAL%',number_format($price_total,$g_price_decimals),$result);
 //adding shipping vat
 $vat_total=$vat_total+($shipping_amount*$g_ship_vat)/($g_ship_vat+100);
 $result=str_replace('%SHOP_SHIPPING%',number_format($shipping_amount,$g_price_decimals),$result);
 //totals
 $result=str_replace('%SHOP_TOTAL%',number_format($price_total+$shipping_amount,$g_price_decimals),$result);
 $result=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$result);
 $result=str_replace('%SHOP_TRANS_ID%',$session_transaction_id,$result);
 $result=str_replace('%SHOP_TOTAL_EX%',number_format($price_total+$shipping_amount-$vat_total,$g_price_decimals),$result);
 $result=str_replace('%SHOP_VAT_TOTAL%',number_format($vat_total,$g_price_decimals),$result);
 $result=str_replace('%SHOP_ORDER_ID%',$order_id,$result);
 $order_date=GetFromString($order_line,'<date>','</date>');
 $result=str_replace('%SHOP_ORDER_DATE%',$order_date,$result);
 $result=str_replace('%SHOP_ITEMS_COUNT%',$bcounter,$result);
 $result=str_replace('%SHOP_ITEMS_COUNT2%',$itemcounter,$result);
 $result=parse_page_id($result);

 $cleanup_string=GetFromStringAbi($result,'"%SHOP_CLEANUP_BUTTON%','"');
 $cleanuplink=$script_url.'?cleanup=&amp;action=list';
 if($cleanup_string !== '') $result=str_replace($cleanup_string,'"'.$cleanuplink.'"',$result);

 $search_h=array("'<script[^>]*?>.*?</script>'si","'<[/!]*?[^<>]*?>'si","'([rn])[s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(d+);'e");
 $replace_h=array("","","\1","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\1)");
 $text_msg=preg_replace($search_h,$replace_h,GetFromString($result,'<SHOP>','</SHOP>'));

 return $result;
}

function count_shipping($price_total,$itemcounter,$itembased_shipping)
{
 global $g_ship_type,$g_ship_settings,$g_ship_amount,$g_ship_above_limit,$g_ship_above_on,$g_ship_cost_perc;

 $result=0;
 $shop_shipping_settings=split("[|]",$g_ship_settings);
 $ship_amount=(float)$g_ship_amount;
 $ship_above_limit=(float)$g_ship_above_limit;
 if($itembased_shipping > 0)
 {
  if(($g_ship_above_on=='TRUE')&&($price_total >= $ship_above_limit)) $result=0;
  else if($g_ship_type==6)
  {
   $count=count($shop_shipping_settings);
   for($i=0;$i<$count-1;$i++)
   {
    $limits=split("[-]",GetFromString($shop_shipping_settings[$i],'','='));
    if(($itembased_shipping >= (float)$limits[0])&&($itembased_shipping <= (float)$limits[1])) {$result=(float)str_replace(',','.',GetFromString($shop_shipping_settings[$i],'=',''));}
   }
  }
  else $result=$itembased_shipping;
 }
 else
 {
  $result=0;
  if($g_ship_type==4) {$result=0;}
  else if(($g_ship_above_on=='TRUE')&&($price_total >= $ship_above_limit)){$result=0;}
  else if($g_ship_type==3){$result=$ship_amount;}
  else if($g_ship_type==2){$result=($ship_amount*$itemcounter);}
  else if($g_ship_type==1)
  {
   $count=count($shop_shipping_settings);
   for ($i=0;$i<$count-1;$i++)
   {
    $limits=split("[-]",GetFromString($shop_shipping_settings[$i],'','='));
    if(($itemcounter >= (float)$limits[0])&&($itemcounter <= (float)$limits[1])) {$result=(float)str_replace(',','.',GetFromString($shop_shipping_settings[$i],'=',''));}
   }
  }
  else if($g_ship_type==0)
  {
   if($g_ship_cost_perc > 0) {$result=(float)($price_total*$g_ship_cost_perc)/100;}
   else
   {
    $count=count($shop_shipping_settings);
    for ($i=0;$i<$count-1;$i++)
    {
     $limits=split("[-]",GetFromString($shop_shipping_settings[$i],'','='));
     if(($price_total >= (float)$limits[0])&&($price_total <= (float)$limits[1])){$result=(float)str_replace(',','.',GetFromString($shop_shipping_settings[$i],'=',''));}
    }
   }
  }
 }
 return $result;
}

function GetFromString($src,$start,$stop)
{
 if($start=='') $res=$src;
 else if(strpos($src,$start)===false){$res='';return $res;}
 else $res=substr($src,strpos($src,$start) + strlen($start));
 if(($stop != '')&&(strpos($res,$stop) !== false))$res=substr($res,0,strpos($res,$stop));
 return $res;
}

function GetFromStringAbi($src,$start,$stop){$res2=GetFromString($src,$start,$stop);return $start.$res2.$stop;}

function GetLine($da,$line){$lines=split("\n",$da);return $lines[$line];}

function GetRecordLine($da,$id)
{$lines2=split("\n",$da);$count=count($lines2);for ($i=2;$i<$count;$i++) {if(strpos($lines2[$i],$id.'|')===0) return $lines2[$i];}}

function GetFieldValueFast($fieldname,$fnames_a,$ftypes_a,$fvalues)
{
 $fvalues_a=split("[|]",$fvalues);
 $val='';
 $key=array_search($fieldname,$fnames_a);
 if($key===false) return '';
 else
 {
   if(isset($fvalues_a[$key]))$val=$fvalues_a[$key];
   $val=str_replace('%1310','<br>',$val);
   if(isset($ftypes_a[$key])&&($ftypes_a[$key]=='10')) {$val='<img src="'.$val.'">';}
 }
 return $val;
}

function create_listbox($id,$src,$default,$recordid)
{
 if($default=='')
 {
  $items=split("[;]",$src);$count=count($items);
  if($count > 1)
  {
   $result='<select name="'.$id.'" class=input1>';
   for ($i=0;$i<$count;$i++) {$result.='<option value="'.$items[$i].'">'.$items[$i];}
   $result.='</select>';
  }
  else if($count==1) {$result=$src.'<input type="hidden" name="'.$id.'" value="'.$src.'">';}
  else $result='';
 }
 else $result=$default;
 return $result;
}

function ReplaceFieldsII($src,$srcIsFull,$fnames,$ftypes,$fvalues,$recordid,$record_subvalue,$record_subvalue1,$record_subvalue2)
{
 global $g_price_decimals,$g_decimal_sign,$g_subfield,$g_subfield1,$g_subfield2,$global_pagescr,$g_useimgplaceholder;

 $fvalues_a=split("[|]",$fvalues);$fnames_a=split("[|]",$fnames);$ftypes_a=split("[|]",$ftypes);

 $count=count($fnames_a);
 $psrc=$src;

 for($i=0;$i<$count;$i++)
 {
  if(trim($fvalues)==''){$val='';$realval='';}
  else
  {
   $val=$fvalues_a[$i];$realval=$val;$fname=$fnames_a[$i];
   $val=str_replace('%1310','<br>',$val);
   if($ftypes_a[$i]=='10')
   {
    if((!$g_useimgplaceholder)&&(strpos($val,GetFromString($val,'%','%').'_empty')!==false)) $val='';
    else if(strpos($fname,'SCALE(')!==false)
    {
      $params_a=explode(',',GetFromString($fname,'SCALE(',')'));
      if((count($params_a)==3)||($params_a[3])=='')$val='<img border="0" src="'.$val.'" alt="">';
      else
      {
      $pop=str_replace('/'.$params_a[1].$params_a[2].'_','/'.$params_a[3].$params_a[4].'_',$val);
      if(file_exists($pop))
      {
       $img_dimensions=getimagesize($pop);
       if($img_dimensions!==false) {$params_a[3]=$img_dimensions[0];$params_a[4]=$img_dimensions[1];}
      }
      if(isset($params_a[6])) {$borderc=$params_a[6];} else $borderc='#808080';
      $val='<a href="javascript:void(0);" onmouseover="return overlib(\'\',BGCOLOR,\''.$borderc.'\',FGCOLOR,\'#FFFFFF\',FGBACKGROUND,\''.$pop.'\',WIDTH,\''.$params_a[3].'\',HEIGHT,\''.$params_a[4].'\',RIGHT);" onMouseOut="nd();"><img border="0" src="'.$val.'" alt=""></a>';
      }
    }
    else $val='<img border="0" src="'.$val.'" alt="">';
   }
   else if($ftypes_a[$i]=='110') {$val=number_format(format_number($fvalues_a[$i]),$g_price_decimals);}
   else if($fnames_a[$i]==$g_subfield) {$val=create_listbox('subtype',$fvalues_a[$i],$record_subvalue,$recordid);}
   else if($fnames_a[$i]==$g_subfield1) {$val=create_listbox('subtype1',$fvalues_a[$i],$record_subvalue1,$recordid);}
   else if($fnames_a[$i]==$g_subfield2) {$val=create_listbox('subtype2',$fvalues_a[$i],$record_subvalue2,$recordid);}
  }

  if(strpos($val,'<!--scripts2-->') !== false)
  {
   $scripts=GetFromStringAbi($val,'<!--scripts2-->','<!--endscripts-->');
   $val=str_replace($scripts,'',$val);
   $scripts=str_replace('<!--//','',$scripts);$scripts=str_replace('//-->','',$scripts);$scripts=str_replace('// -->','',$scripts);
  }
  else $scripts='';

  if(strpos($psrc,'%'.$fnames_a[$i]) !== false)    //     %vat&amp;decimals=2%
  {
   if(($ftypes_a[$i]=='110')&&(strpos($psrc,'%'.$fnames_a[$i].'&amp;decimals=') !== false))
   {
    $dec=GetFromString($psrc,'%'.$fnames_a[$i].'&amp;decimals=','%');
    if($dec=='') $dec=0;
    $val_dec=number_format(format_number($fvalues_a[$i]),$dec);
    $psrc=str_replace('%'.$fnames_a[$i].'&amp;decimals='.$dec.'%',$val_dec,$psrc);
   }
   if(strpos($val,'<p') !== false) {$psrc=str_replace('<p>%'.$fnames_a[$i].'%</p>',$val,$psrc);}
   $psrc=str_replace('#%'.$fnames_a[$i].'%#',$realval,$psrc);
   $psrc=str_replace('%'.$fnames_a[$i].'%',$val,$psrc);

   if($scripts !== '')
   {
    if($srcIsFull) $psrc=str_replace('<!--scripts-->','<!--scripts-->'.$scripts,$psrc);
    else if(strpos($global_pagescr,$scripts)===false) $global_pagescr .=$scripts;
   }
  }
 }
 return $psrc;
}

function DivMod($num,$tel,&$Res,&$Rem){$Res=floor($num / $tel);$Rem=$num-($Res*$tel);}

function DecDate($days,&$Year,&$Month,&$Day)
{
  $D1=365;$D4=($D1*4)+1;$D100=($D4*25)-1;$D400=($D100*4)+1;
  $MonthDays=array(array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31),array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31));  
  $days+=693594;$days--;$Y=1;
  while($days >= $D400){$days-=$D400;$Y+=400;}
  DivMod($days,$D100,$I,$D);
  if($I==4){$I++;$D+=$D100;}
  $Y+=$I*100;DivMod($D,$D4,$I,$D);$Y+=$I*4;DivMod($D,$D1,$I,$D);
  if($I==4){$I--;$D+=$D1;}
  $Y+=$I;
    
  $leap=($Y % 4 == 0)&&(($Y % 100 <> 0)||($Y % 400 == 0));
  $DayTable=$MonthDays[$leap];$M=1;
  while(true)
  {
    $I=$DayTable[$M-1];
    if($D<$I) break;
    $D-=$I;$M++;
  }
  $Year=$Y;$Month=$M;$Day=$D+1;
}

function ReplaceMacros($src)
{
 while (strpos($src,'%age(') !== false) :
  $temp=GetFromStringAbi($src,'%age(',')%');
  $cond=GetFromString($temp,'%age(',')%');
  $year=0;$month=0;$day=0;
  if($cond!='')
  {
    $tday=intval(date("d"));$tmonth=intval(date("m"));$tyear=intval(date("Y"));  
    DecDate($cond,$year,$month,$day);
    if($tmonth>$month)$res=$tyear-$year;
    else if($tmonth<$month) $res=$tyear-$year-1;
    else $res=($tday<$day)?$tyear-$year-1:$tyear-$year;    
  }
  else $res='';
  $src=str_replace($temp,$res,$src);
 endwhile;
 while (strpos($src,'%IF<condition>') !== false) :
  $temp=GetFromStringAbi($src,'%IF<condition>','</falsevalue>%');
  $cond=GetFromString($src,'<condition>','</condition>');
  $trueval=GetFromString($src,'<truevalue>','</truevalue>');
  $falseval=GetFromString($src,'<falsevalue>','</falsevalue>');
  //evaluate condition
  $lcond=GetFromString($cond,'',' ');
  if(strpos($lcond,'php:') !== false)
  {
   $lcond=GetFromString($lcond,'php:','');
   $lcond=eval('return '.$lcond.';');
  }
  $zcond=GetFromString($cond,' ',' ');$rcond=GetFromString($cond,$zcond.' ','');
  $res=$falseval;
  if($zcond=='=') {if($lcond==$rcond) $res=$trueval;}
  else if($zcond=='>'){if($lcond > $rcond) $res=$trueval;}
  else if($zcond=='<'){if($lcond < $rcond) $res=$trueval;}
  else if($zcond=='<='){if($lcond <= $rcond) $res=$trueval;}
  else if($zcond=='=>'){if($lcond >= $rcond) $res=$trueval;}
  else if($zcond=='<>'){if($lcond != $rcond) $res=$trueval;}
  $src=str_replace($temp,$res,$src);
 endwhile;
 return $src;
}

function ReplaceFields($src,$srcIsFull,$d,$recordid,$record_subvalue,$record_subvalue1,$record_subvalue2)
{
 $fnames=GetLine($d,0);$ftypes=GetLine($d,1);
 $fvalues=GetRecordLine($d,$recordid);
 $result=ReplaceFieldsII($src,$srcIsFull,$fnames,$ftypes,$fvalues,$recordid,$record_subvalue,$record_subvalue1,$record_subvalue2);
 $result=ReplaceMacros($result);
 return $result;
}

function get_product_hidden_fields($action,$item_id,$cat_id,$subcat_id,$page_id,$searchstr)
{
global $g_subcat;
$ret='<input type="hidden" name="action" value="'.$action.'"><input type="hidden" name="iid" value="'.$item_id.'"><input type="hidden" name="cat" value="'.$cat_id.'"><input type="hidden" name="page" value="'.$page_id.'"><input type="hidden" name="search" value="'.$searchstr.'">';
if($g_subcat !== 'none')$ret.='<input type="hidden" name="subcat" value="'.$subcat_id.'">';
return $ret;}

function get_product_category($p_id)
{
 global $g_id,$g_id_field;

 $categories='';
 $page_name=$g_id."_0.dat";
 $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$categories=fread($fp,$fs);fclose($fp);}
 $categories_a=split("\n",$categories);
 $count=count($categories_a);
 $result='0';

 for($i=0;$i<$count;$i++)
 {
  if($categories_a[$i] !== '')
  {
   $catdata='';$cat_id=$i+1;
   $page_name=$g_id."_".$cat_id.".dat";
   $fs=filesize($page_name);
   if($fs>0){$fp=fopen($page_name,"r");$catdata=trim(fread($fp,$fs));fclose($fp);}
   $fnames_a=split("[|]",GetLine($catdata,0));$ftypes_a=split("[|]",GetLine($catdata,1));

   $lines_a=split("\n",$catdata);$count2=count($lines_a);
   for($x=2;$x<$count2;$x++)
   {
    if($lines_a[$x] != '')
    {
     $line_p_id=trim(GetFieldValueFast($g_id_field,$fnames_a,$ftypes_a,$lines_a[$x]));
     if($line_p_id==$p_id) {$result=$cat_id;break;}
    }
   }
  }
 }
 return $result;
}

function show_item($item_id)    //shows product detail page
{
 global $g_realname,$g_id,$g_currency,$g_abs_path,$g_namefield,$g_id_field;

 if(isset($_GET['cat'])) {$cat_id=$_GET['cat'];} else $cat_id=get_product_category($item_id);
 if(isset($_GET['page'])) {$page_id=$_GET['page'];} else $page_id=0;
 if(isset($_GET['subcat'])) {$subcat_id=$_GET['subcat'];} else $subcat_id='';

 $page_name=($g_id+1).".html";
 $fp=fopen($page_name,"r");$page=fread($fp,filesize($page_name));fclose($fp);

 //getting record values
 $data='';
 $page_name=$g_id.'_'.$cat_id.".dat";
 $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
 $data=str_replace('<%23>','#',$data);

 if(strpos($page,'<MINI_CART>') !== false)
 {
  $minicart=GetFromStringAbi($page,'<MINI_CART>','</MINI_CART>');
  $page=str_replace($minicart,cart('minicart',0,'','',0,'',$minicart,'','',0,'item&iid='.$item_id.'&cat='.$cat_id.'&page='.$page_id,''),$page);
 }
 $title=GetFromStringAbi($page,'<title>','</title>');
 if(strpos($title,'%')===false)
 {
 if($g_namefield=='')
 {
  if($g_id_field=='Nummer')$fparam='%Name%'; 
  else $fparam='%P_Name%';
 }
 else $fparam='%'.$g_namefield.'%'; 
 $page=str_replace(GetFromStringAbi($page,'<title>','</title>'),'<title>'.$fparam.'</title>',$page);
 }
 $page=ReplaceFields($page,true,$data,$item_id,'','','');
 $page=str_replace('%SELF_URL%',$g_abs_path.$g_realname.'?action=item&iid='.$item_id.'&cat='.$cat_id.'&page='.$page_id,$page);
 $xaction='add';

 if(strpos($page,'<SHOP_BUY_BUTTON>') !== false)        // buy button without quantity
 {
  $btn_string=GetFromString($page,'<SHOP_BUY_BUTTON>','</SHOP_BUY_BUTTON>');
  $img_src=GetFromString($btn_string,'src="','"');

  $hidden_fields=get_product_hidden_fields($xaction,$item_id,$cat_id,$subcat_id,$page_id,'');
  $btn_parsed=$hidden_fields.'<input type="image" style="text-align:middle" src="'.$img_src.'" border="0">';
  $newtarget=getTarget(true);
  $page=str_replace('<LISTER_BODY>','<form method="GET" action="'.$g_abs_path.$g_realname.'" onSubmit="" target="'.$newtarget.'"><input name=quantity type=hidden value=1>',$page);
  $page=str_replace('</LISTER_BODY>','</form>',$page);
  $page=str_replace('<SHOP_BUY_BUTTON>'.$btn_string.'</SHOP_BUY_BUTTON>',$btn_parsed,$page);
 }

 if(strpos($page,'<QUANTITY>') !== false)        //buy button with quantity
 {
  $btn_url=$g_abs_path.$g_realname;
  $newtarget=getTarget(true);
  $btn_string=GetFromString($page,'<QUANTITY>','</QUANTITY>');
  $btn_string_parsed=str_replace('%QUANTITY%','1',$btn_string);
  $hidden_fields=get_product_hidden_fields($xaction,$item_id,$cat_id,$subcat_id,$page_id,'');
  $btn_parsed=$hidden_fields.$btn_string_parsed;
  $page=str_replace('<LISTER_BODY>','<form method="GET" action="'.$g_abs_path.$g_realname.'" onsubmit="" target="'.$newtarget.'">',$page);
  $page=str_replace('</LISTER_BODY>','</form>',$page);
  $page=str_replace($btn_string,$btn_parsed,$page);
 }

 $page=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$page);
  
 $page=parse_page_id($page);
 $page=replace_category_combo(0,$page);
 build_logged_info($page);
 return $page;
}

function str_replace_once($needle,$replace,$haystack)
{
 $pos=strpos($haystack,$needle);if($pos===false) {return $haystack;}
 return substr_replace($haystack,$replace,$pos,strlen($needle));
}

function db_error()
{
echo "Missing Data files on server --> go to Project Settings --> Upload Settings, press 'Re-upload data' button and make Upload.";
exit;
}

//show categories list (shop main page)
function show_list()
{
 global $g_realname,$g_pagename,$g_id,$g_listcols,$g_currency,$g_abs_path;

 $items_a=array();
 $fp=fopen($g_pagename,"r");$page=fread($fp,filesize($g_pagename));fclose($fp);

 $fs=0;
 $categories='';
 $page_name=$g_id."_0.dat";
 if(file_exists($page_name)) {$fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$categories=fread($fp,$fs);fclose($fp);}}
 if($fs>0)
 {
  $categories_a=split("\n",$categories);

  $page_head=GetFromString($page,'','<LISTER_BODY>');$page_foot=GetFromString($page,'</LISTER_BODY>','');
  $page_body=GetFromString($page,'<LISTER_BODY>','</LISTER_BODY>');

  while($categories_a[count($categories_a)-1]=='') array_pop($categories_a);

  $counter=1;
  while(count($categories_a)<$g_listcols)  array_push($categories_a,'*null*');
  $count=count($categories_a);

  $page=$page_head.'<table style="width:100%;border:0px;" cellspacing="0" cellpadding="0">';

  if(strpos($page,'<MINI_CART>') !== false)
  {
   $minicart=GetFromStringAbi($page,'<MINI_CART>','</MINI_CART>');
   $page=str_replace($minicart,cart('minicart',0,'','',0,'',$minicart,'','',0,'list',''),$page);
  }

//columns
  $incolcount=0;
  $colmax=round($count/$g_listcols);$colwidth=round(100/$g_listcols);$colmod=$count/$g_listcols;
  if($g_listcols >1) $page=$page.'<tr>';

  if($count>0)
  {
   $data='';
   $page_name=$g_id."_1.dat";
   if(file_exists($page_name)){
   $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
   $fnames=GetLine($data,0);$ftypes=GetLine($data,1);
   } else db_error();
  }

  $pc=0;
  for($i=0;$i<$count;$i++)
  {
   if($categories_a[$i] !== '')
   {
    if($categories_a[$i] !== '*null*')
    {
     if(($g_listcols>1)&&($incolcount==0)) $page=$page.'<td width="'.$colwidth.'%" valign="top"><table width="100%" cellspacing="0" cellpadding="0" border="0">';
     $cid=$i + 1;
     $temp=str_replace('%URL=Detailpage%',$g_abs_path.$g_realname.'?cat='.$cid,$page_body);
     $cat_fields=split("[#]",$categories_a[$i]);
     $cat_fields=str_replace('<%23>','#',$cat_fields);
     if($cat_fields[0]=='')$cat_fields[0]='&nbsp;&nbsp;&nbsp;&nbsp;';
     $temp=str_replace('%LISTER_CATEGORY%',$cat_fields[0],$temp);
     $cnt=trim($cat_fields[2]);
     for ($x=1;$x<=$cnt;$x++) {$items_a[$pc]['ct']=$cid;$items_a[$pc]['id']=$x;$items_a[$pc]['used']=0;$pc++;}
     $temp=str_replace('%CATEGORY_COUNT%',trim($cat_fields[2]),$temp);
     $temp=str_replace('%LISTER_COUNTER%',$counter,$temp);
     //replace fields on category page
     $temp=ReplaceFieldsII($temp,true,$fnames,$ftypes,$cat_fields[1],$cid,'','','','','');
    }
    else
    {
     if(($g_listcols>1)&&($incolcount==0)) $page=$page.'<td width="'.$colwidth.'%" valign="top"><table width="100%" cellspacing="0" cellpadding="0" border="0">';
     $temp='&nbsp';
     $cid=$i+1;
    }
    $counter++;
    $temp='<tr><td>'.$temp.'</td></tr>';
    $incolcount++;
    if(strpos($temp,'ToggleBody') > 0) $temp=parse_dropdown($temp,$i);
    $page=$page.$temp;
    if(($g_listcols > 1)&&($incolcount==$colmax)){$page=$page.'</table></td>';$incolcount=0;}
   }
  }

  if(($g_listcols>1)&&($incolcount>0)&&($incolcount !== $colmax)) $page=$page.'</table></td>';
  if($g_listcols>1) $page=$page.'</tr>';
  $page=$page.'</table>'.$page_foot;
  $page=replace_category_combo(0,$page);

  if(strpos($page,'%SHOP_CART%') !== false)
  {
   $cartstring=cart('show',0,'','',0,'','','','',0,'','');
   $page=str_replace('%SHOP_CART%',$cartstring,$page);
  }
  $page=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$page);
  $page=parse_page_id($page);

  if(strpos($page,'%LISTER_CATEGORIES%')>0)
  {$cat_one_string=show_category(1,'');
  $page=str_replace('%LISTER_CATEGORIES%',$cat_one_string,$page);}

  if(strpos($page,'<RANDOM>') !== false) $page=replace_random($page,$items_a);

  $page=str_replace('<SHOP>','',$page);$page=str_replace('</SHOP>','',$page);
  $page=str_replace('<LISTER>','',$page); $page=str_replace('</LISTER>','',$page);
 }
 else {$page=str_replace(GetFromStringAbi($page,'<LISTER_BODY>','</LISTER_BODY>'),'no categories in this shop, shop is empty!',$page);}
 build_logged_info($page);
 return $page;
}

function replace_random($page,$items_a)
{
 global $g_realname,$g_id,$g_abs_path;

 $count=count($items_a);
 if($count > 3)
 {
  $zc=1;
  while (($zc <= ($count /2))&&(strpos($page,'<RANDOM>') !== false))
  {
   $rnd_string=GetFromStringAbi($page,'<RANDOM>','</RANDOM>');
   if($rnd_string=='') break;
   $rnd_key=array_rand($items_a);
   while ($items_a[$rnd_key]['used']==1) $rnd_key=array_rand($items_a);

   $data='';
   $page_name=$g_id.'_'.$items_a[$rnd_key]['ct'].".dat";
   if(file_exists($page_name)){
   $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}}
   else db_error();

   $tmp_a=split("[|]",GetLine($data,$items_a[$rnd_key]['id']+1));
   $p_id=$tmp_a[0];
   $rnd_string_par=ReplaceFields($rnd_string,false,$data,$p_id,'','','');

   $plink=$g_abs_path.$g_realname.'?action=item&amp;iid='.$p_id.'&amp;cat='.$items_a[$rnd_key]['ct'].'&amp;page=1';
   $rnd_string_par=str_replace('%SHOP_DETAIL%',$plink,$rnd_string_par);
   $rnd_string_par=str_replace('<RANDOM>','',$rnd_string_par);
   $rnd_string_par=str_replace('</RANDOM>','',$rnd_string_par);
   $page=str_replace_once($rnd_string,$rnd_string_par,$page);
   $items_a[$rnd_key]['used']=1;
   $zc++;
  }
 }
 else
 {
  if(strpos($page,'<RANDOM>') !== false)
  {
   $rnd_string=GetFromStringAbi($page,'<RANDOM>','</RANDOM>');
   if($rnd_string=='') break;
   $data='';
   $page_name=$g_id.'_'.$items_a[0]['ct'].".dat";
   $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}

   $tmp_a=split("[|]",GetLine($data,$items_a[0]['id']+1));
   $p_id=$tmp_a[0];
   $rnd_string_par=ReplaceFields($rnd_string,false,$data,$p_id,'','','');

   $plink=$g_abs_path.$g_realname.'?action=item&amp;iid='.$p_id.'&amp;cat='.$items_a[0]['ct'].'&amp;page=1';
   $rnd_string_par=str_replace('%SHOP_DETAIL%',$plink,$rnd_string_par);
   $rnd_string_par=str_replace('<RANDOM>','',$rnd_string_par);$rnd_string_par=str_replace('</RANDOM>','',$rnd_string_par);
   $page=str_replace_once($rnd_string,$rnd_string_par,$page);
  }
 }
 while((strpos($page,'<RANDOM>') !== false)) $page=str_replace(GetFromStringAbi($page,'<RANDOM>','</RANDOM>'),'',$page);
 return $page;
}

function replaceNewlines($src)
{
 $result=str_replace("\r\n", " ", $src);$result=str_replace("\n", " ", $result);$result=str_replace("\r", " ", $result);
 $result=str_replace("'", "\'", $result);
 $result=str_replace("&amp;#", "&#", $result);$result=str_replace("&amp;", "&", $result);
 $result=str_replace("&#60;", "<", $result);$result=str_replace("&lt;", "<", $result);$result=str_replace("&gt;", ">", $result);
 return $result;
}

function return_random()
{
 global $g_pagename,$page_dir,$g_id,$g_listcols,$g_currency,$g_abs_path;
 $items_a=array();

 if(isset($_GET['file'])) {$fp=fopen($_GET['file'],"r");$rnd_tag=fread($fp,filesize($_GET['file']));fclose($fp);}
 else if(isset($_GET['tag'])) {$rnd_tag=$_GET['tag'];}
 else $rnd_tag='undefined<br>';
 if(isset($_GET['count'])) {$rnd_count=$_GET['count'];} else $rnd_count=1;
 if(isset($_GET['iid'])) {$iid=$_GET['iid'];} else $iid=0;
 if(isset($_GET['root'])) {$root=$_GET['root'];} else $root='-1';
 if(isset($_GET['dir'])) {$dir=$_GET['dir'];} else $dir='v';

 $categories='';
 $page_name=$g_id."_0.dat";
 $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$categories=fread($fp,$fs);fclose($fp);}

 $categories_a=split("\n",$categories);
 while($categories_a[count($categories_a)-1]=='') array_pop($categories_a);
 $count=count($categories_a);

 $pc=0;
 for($i=0;$i<$count;$i++)
 {
  if($categories_a[$i] !== '')
  {
   $cid=$i+1;
   $cat_fields=split("[#]",$categories_a[$i]);
   $cnt=trim($cat_fields[2]);
   if($iid>0)
   {
    $page_name=$g_id."_".($i+1).".dat";
    $data='';
    $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=fread($fp,$fs);fclose($fp);}
    $lines_a=split("\n",$data);
    for($x=1;$x<=$cnt;$x++) if(strpos($lines_a[$x+1],$iid.'|')===0) {$items_a[$pc]['ct']=$cid;$items_a[$pc]['id']=$x;$items_a[$pc]['used']=0;break;};
   }
   else for($x=1;$x<=$cnt;$x++) {$items_a[$pc]['ct']=$cid;$items_a[$pc]['id']=$x;$items_a[$pc]['used']=0;$pc++;}
  }
 }
 $rnd_src='';
 if($dir=='h')$rnd_tag='<td>'.$rnd_tag.'</td>';
 for($i=0;$i<$rnd_count;$i++) $rnd_src.='<RANDOM>'.$rnd_tag.'</RANDOM>';
 $result=replace_random($rnd_src,$items_a);
 $result=replaceNewlines($result);
 if($dir=='h')$result='<table><tr>'.$result.'</tr></table>';
 if($root=='1'){
 $result=str_replace('src="','src="'.$page_dir,$result);
 $result=str_replace('href="','href="'.$page_dir,$result);
 }
 else if($root=='0') $result=str_replace('href="','href="../'.$page_dir,$result);
 print "document.write('".$result."');";
}

function replace_category_combo($cat_id,$srcpage)
{
 global $g_realname,$g_id,$g_abs_path;

 $param=GetFromString($srcpage,'%LISTER_CATEGORYCOMBO(',')%');

 $categories='';
 $page_name=$g_id."_0.dat";
 $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$categories=fread($fp,$fs);fclose($fp);}
 $categories_a=split("\n",$categories);
 $count=count($categories_a);
 $result='<select class=input1 name="catlist" ONCHANGE="javascript: location.href=this.options[this.selectedIndex].value;return true;">';

 if($param != '')
 {
  if($cat_id==0) $result.='<option SELECTED>'.$param.'</option>';
  else $result.='<option>'.$param.'</option>';
 }
 else if($cat_id==0) $cat_id=1;

 for ($i=0;$i<$count;$i++)
 {
  if($categories_a[$i] !== '')
  {
   $cid=$i + 1;
   $cat_fields=split("[#]",$categories_a[$i]);
   if($cat_id==$cid) $result.='<option SELECTED value="'.$g_abs_path.$g_realname.'?cat='.$cid.'">'.$cat_fields[0].'</option>';
   else $result.='<option value="'.$g_abs_path.$g_realname.'?cat='.$cid.'">'.$cat_fields[0].'</option>';
  }
 }

 $result.='</select>';
 $srcpage=str_replace('%LISTER_CATEGORYCOMBO('.$param.')%',$result,$srcpage);
 $srcpage=str_replace('%LISTER_CATEGORYCOMBO%',$result,$srcpage);//backward compatibility
 return $srcpage;
}

function HtmlToText($src)
{
 $isText=true;
 $lastIsNewLine=false;
 $result='';
 for ($i=1;$i<strlen($src);$i++)
 {
  if($src[$i]=='<') {$isText=false;}
  else if($src[$i]=='>') {$isText=true;}
  else if($isText==true) {$result.=$src[$i];}
 }
 $result=str_replace('&nbsp','',$result);
 return str_replace('P_ImageFull','',$result);
}

function parse_dropdown($temp,$i)
{
  for($ii=1;$ii<5;$ii++)
  {
    $drop_down_id='a'.$ii;
    $temp=str_replace("ToggleBody('".$drop_down_id."'","ToggleBody('".$drop_down_id."_".$i."'",$temp);
    $temp=str_replace($drop_down_id.'Body',$drop_down_id.'_'.$i.'Body',$temp);
    $temp=str_replace($drop_down_id.'Up',$drop_down_id.'_'.$i.'Up',$temp);
  }
  return $temp;
}

function search()
{
 global $g_realname,$g_id,$g_catpgmax,$g_subfield,$g_searchnomatch,$g_currency,$g_id_field,$g_abs_path,$global_pagescr;

 $searchstring=strtolower($_GET['search']);
 $searchstring=str_replace("\\",'',$searchstring);
 $global_pagescr='';
 if(isset($_GET['subcat'])) $subcat_id=$_GET['subcat']; else $subcat_id='';
 if(isset($_GET['page']))  $page_id=intval($_GET['page']);
 else $page_id=1;
 $search_type='';
 if(isset($_GET['search_type'])) $search_type=$_GET['search_type'];

 if(($searchstring != '')||($search_type=='conditions'))
 {
  $page_name=($g_id+2).".html";
  $fp=fopen($page_name,"r");$page=fread($fp,filesize($page_name));fclose($fp);

  $categories='';
  $page_name=$g_id."_0.dat";
  $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$categories=fread($fp,$fs);fclose($fp);}

  if(($search_type=='')&&(strpos($searchstring,'"') !== false))
  {
   $search_type='exact';
   $searchstring=str_replace('"','',$searchstring);
   $searchstring_words=split("             ",$searchstring);
  }
  else $searchstring_words=split(" ",$searchstring);

  $categories_a=split("\n",$categories);
  $cat_count=count($categories_a);

  for($i=0;$i<$cat_count;$i++)
  {
   if($categories_a[$i] !== '')
   {
    //getting data
    $catdata='';
    $cat_id=$i + 1;
    $page_name=$g_id."_".$cat_id.".dat";
    $fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$catdata=trim(fread($fp,$fs));fclose($fp);}
    $catdata=str_replace('<%23>','#',$catdata);
    $lines_a=split("\n",$catdata);
    $count=count($lines_a);

    if($i==0)
    {
     $temp=str_replace("\r","X_CAT|\r",$lines_a[0]);$data=$temp."\n";
     $temp=str_replace("\r","3|\r",$lines_a[1]);$data.=$temp."\n";
    }

    if($search_type !== '') //limit search to field specified by hidden param 'search_type'
    {
     $field_names_a=split("[|]",$lines_a[0]);
     $field_id=-1;
     $ct=count($field_names_a);
     for($x=0;$x<$ct;$x++) {if($field_names_a[$x]==$search_type) $field_id=$x;}
    }

    for($x=2;$x<$count;$x++)
    {
     if($lines_a[$x] !== '')
     {
      if($search_type=='exact')
      {
       if(strpos(strtolower(HtmlToText($lines_a[$x])),$searchstring) !== false)
          {$temp=str_replace("\r",'',$lines_a[$x]);$data=$data.$temp.$cat_id."|\r"."\n";}
      }
      else if($search_type=='conditions')
      {
        $cond_ok=true;
        $field_names_a=split("[|]",$lines_a[0]);$fvalues_a=split("[|]",$lines_a[$x]);
        $ct=count($field_names_a);
        for($zz=0;$zz<$ct;$zz++)
        {
         if(isset($_REQUEST[$field_names_a[$zz]]))
         {
          $rqval=$_REQUEST[$field_names_a[$zz]];
          $cond_ok=$cond_ok && (strtolower($fvalues_a[$zz])==strtolower($rqval));
          $page=str_replace('<option value="'.$rqval.'">'.$rqval.'</option>','<option selected value="'.$rqval.'">'.$rqval.'</option>',$page);
         }
        }
        if($cond_ok)
        {
         if($searchstring !== '')
         {
          $lt=strtolower(HtmlToText($lines_a[$x]));$ct=0;
          foreach($searchstring_words as $k=>$v) {if(strpos($lt,$v) !== false) $ct++;}
          $meet=($ct==count($searchstring_words));
         }
         else $meet=true;
         if($meet){$temp=str_replace("\r",'',$lines_a[$x]);$data=$data.$temp.$cat_id."|\r"."\n";}
        }
       }
       else if(($search_type !== '')&&($field_id > -1))
       {
        $fvalues_a=split("[|]",$lines_a[$x]);
        if(strpos(strtolower(HtmlToText(' '.$fvalues_a[$field_id])),$searchstring) !== false)
            {$temp=str_replace("\r",'',$lines_a[$x]);$data=$data.$temp.$cat_id."|\r"."\n";}
       }
       else  // word search
       {
        $lt=strtolower(HtmlToText($lines_a[$x]));$ct=0;
        foreach($searchstring_words as $k=>$v) {if(strpos($lt,$v) !== false) $ct++;}
        if($ct==count($searchstring_words)) {$temp=str_replace("\r",'',$lines_a[$x]);$data=$data.$temp.$cat_id."|\r"."\n";}
       }
      }
     }
    }
   }

   $page_head=GetFromString($page,'','<LISTER_BODY>');$page_foot=GetFromString($page,'</LISTER_BODY>','');
   $page_body=GetFromString($page,'<LISTER_BODY>','</LISTER_BODY>');
   $page=$page_head;
   $category_section=GetFromStringAbi($page,'<CATEGORY_HEADER>','</CATEGORY_HEADER>');
   $page=str_replace($category_section,'',$page);

   $lines_b=split("\n",$data);$count=count($lines_b);
   if($g_catpgmax==0) $g_catpgmax=$count;

   if($page_id > 1) $counter=$g_catpgmax*($page_id-1)+1;
   else $counter=1;
   $startitem=$counter+1;
   $stopitem=(($startitem + $g_catpgmax)>$count)?$count:($startitem+$g_catpgmax);

   $fnames_a=split("[|]",GetLine($data,0));$ftypes_a=split("[|]",GetLine($data,1));
   $page=str_replace('%LISTER_PRODUCTS','%SHOP_PRODUCTS',$page);
   $ps_key=GetFromString($page,'%SHOP_PRODUCTS(%','%)%');
   $name_key=array_search($ps_key,$fnames_a);
   $product_select='';

   for($i=$startitem;$i<$stopitem;$i++)
   {
    if($lines_b[$i] !== '')
    {
     $fvalues_a=split("[|]",$lines_b[$i]);
     $p_id=trim(GetFieldValueFast($g_id_field,$fnames_a,$ftypes_a,$lines_b[$i]));
     $xcat_id=trim(GetFieldValueFast('X_CAT',$fnames_a,$ftypes_a,$lines_b[$i]));
     $item_id=GetFromString($lines_b[$i],'','|');
     $cat_body=ReplaceFields($page_body,false,$data,$item_id,'','','');
     $cat_string=$g_abs_path.$g_realname.'?action=item&amp;iid='.$p_id.'&amp;cat='.$xcat_id.'&amp;page='.$page_id.'&amp;search='.$searchstring;
     if($ps_key!=''){$product_select.='<option value="'.$cat_string.'">'.$fvalues_a[$name_key].'</option>';};

     $temp=str_replace('%SHOP_DETAIL%',$cat_string,$cat_body);
     $temp=str_replace('%LISTER_COUNTER%',$counter,$temp);

     $btn_string=GetFromString($temp,'<SHOP_BUY_BUTTON>','</SHOP_BUY_BUTTON>');
     $img_src=GetFromString($btn_string,'src="','"');
     if(strpos($btn_string,'<FROMCART>')>0) $xaction='addandbasket'; else $xaction='add';

     if($g_subfield != '')
     {
      $hidden_fields=get_product_hidden_fields($xaction,$p_id,$xcat_id,'',$page_id,$searchstring);
      $btn_parsed=$hidden_fields.'<input name=quantity type=hidden value=1><input type="image" style="text-align:middle" src="'.$img_src.'" border="0">';
      $temp='<form method="GET" action="'.$g_abs_path.$g_realname.'" onsubmit="">'.$temp.'</form>';
     }
     else {$btn_parsed='<a href="'.$g_abs_path.$g_realname.'?action='.$xaction.'&amp;iid='.$p_id.'&amp;cat='.$xcat_id.'&amp;quantity=1&amp;page='.$page_id.'&amp;search='.$searchstring.'"><img src="'.$img_src.'" border="0" align="bottom" alt="">';}
     $temp=str_replace('<SHOP_BUY_BUTTON>'.$btn_string.'</SHOP_BUY_BUTTON>',$btn_parsed,$temp);

     if(strpos($temp,'ToggleBody')>0) $temp=parse_dropdown($temp,$i); //support for drop-down tables

     if(strpos($temp,'<QUANTITY>') !== false)        //buy button with quantity
     {
      $btn_url=$g_abs_path.$g_realname;
      $newtarget=getTarget(false);
      $btn_string=GetFromString($temp,'<QUANTITY>','</QUANTITY>');
      $btn_string_parsed=str_replace('%QUANTITY%','1',$btn_string);
      $hidden_fields=get_product_hidden_fields($xaction,$p_id,$xcat_id,'',$page_id,'');
      $btn_parsed=$hidden_fields.$btn_string_parsed;
      $temp='<form method="GET" action="'.$g_abs_path.$g_realname.'" onSubmit="">'.$temp.'</form>';
      $temp=str_replace($btn_string,$btn_parsed,$temp);
     }
     $counter++;$page=$page.$temp;
    }
   }
   //parsing previous page param
   $prevstring=GetFromString($page_foot,'%LISTER_PREVIOUS(',')%');
   if($page_id > 1) $prev='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?search='.$searchstring.'&amp;page='.($page_id-1).'">'.$prevstring.'</a>';
   else $prev='';
   $page_foot=str_replace('%LISTER_PREVIOUS('.$prevstring.')%',$prev,$page_foot);

   $prevstring=GetFromString($page,'%LISTER_PREVIOUS(',')%');
   if($page_id > 1) $prev='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?search='.$searchstring.'&amp;page='.($page_id-1).'">'.$prevstring.'</a>';
   else $prev='';
   $page=str_replace('%LISTER_PREVIOUS('.$prevstring.')%',$prev,$page);

   $nextstring=GetFromString($page_foot,'%LISTER_NEXT(',')%');
   if($stopitem < $count) $next='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?search='.$searchstring.'&amp;page='.($page_id+1).'">'.$nextstring.'</a>';
   else $next='';

   $page_foot=str_replace('%LISTER_NEXT('.$nextstring.')%',$next,$page_foot);

   $nextstring=GetFromString($page,'%LISTER_NEXT(',')%');
   if($stopitem < $count) $next='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?search='.$searchstring.'&amp;page='.($page_id+1).'">'.$nextstring.'</a>';
   else $next='';

   $page=str_replace('%LISTER_NEXT('.$nextstring.')%',$next,$page);

   $pages='';
   $stopitem=intval((($count-2) / $g_catpgmax));
   if(($stopitem * $g_catpgmax)< ($count-2)) $stopitem++;
   for($i=1;$i<=$stopitem;$i++)
   {
    if($i==$page_id) $pages=$pages.$i.' ';
    else $pages=$pages.'<a class="rvts4" href="'.$g_abs_path.$g_realname.'?search='.$searchstring.'&amp;page='.($i).'">'.$i.'</a> ';
   }
   $page_foot=str_replace('%LISTER_PAGES%',$pages,$page_foot);
   $page=str_replace('%LISTER_PAGES%',$pages,$page);

   if($counter==1)
   {
    $searchnomatch=str_replace('%SEARCHSTRING%','<span class="rvts3">'.$searchstring.'</span>',$g_searchnomatch);
    $page=$page.'<span class="rvts0">'.$searchnomatch.$search_type.'</span>';
   }
   $page=$page.$page_foot;

   $page=replace_category_combo(0,$page);

   if(strpos($page,'<MINI_CART>') !== false)
   {
    $minicart=GetFromStringAbi($page,'<MINI_CART>','</MINI_CART>');
    $page=str_replace($minicart,cart('minicart',$cat_id,'','',$page_id,'',$minicart,'','',0,'list',''),$page);
   }
  if($ps_key!=''){
   $product_select='<select class=input1 name="prolist" onchange="javascript: location.href=this.options[this.selectedIndex].value;return true;">'.$product_select.'</select>';
   $page=str_replace('%SHOP_PRODUCTS(%'.$ps_key.'%)%',$product_select,$page);}
 

   if(strpos($page,'%SHOP_CART%') !== false)
   {
    $cartstring=cart('show',0,'','',$page_id,$searchstring,'','','',0,'','');
    $page=str_replace('%SHOP_CART%',$cartstring,$page);
   }
   $page=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$page);
   $page=parse_page_id($page);

   $cat_string=GetFromString($page,'%LISTER_CATEGORIES(',')%');
   $page=str_replace('%LISTER_CATEGORIES('.$cat_string.')%','<a class="rvts4" href="'.$g_abs_path.$g_realname.'">'.$cat_string.'</a>',$page);
   if($global_pagescr !== '') $page=str_replace('<!--scripts-->','<!--scripts-->'.$global_pagescr,$page);
   $page=$page.'<!--<pagelink>/'.$g_abs_path.$g_realname.'?search='.$searchstring.'&amp;page='.$page_id.'</pagelink>-->';
   $page=str_replace('%SUBCATEGORIES%','',$page);
   return $page;
 }
 else {$url=$g_abs_path.$g_realname.'?cat=1';m_header($url,false);}
}

function show_category($cat_id,$current_sub_id)
{
 global $g_realname,$g_id,$g_catpgmax,$g_currency,$g_id_field,$g_abs_path,$global_pagescr,$g_subcat,$g_catcols,$g_namefield;

 if(isset($_GET['page'])) $page_id=intval($_GET['page']);
 else $page_id=1;

 $page_name=($g_id+2).".html";
 $fp=fopen($page_name,"r");$page=fread($fp,filesize($page_name));fclose($fp);
 $page=str_replace('id="sa"','',$page);
 $page=str_replace('id="mix'.$cat_id,'id="sa"',$page);
 $page=str_replace(";cm('mi_".$g_id."'",";cm('mic_".$g_id."_".$cat_id."'",$page);
 $page=str_replace("psmi='mi_".$g_id,"psmi='mic_".$g_id."_".$cat_id,$page);

 $data='';
 $page_name=$g_id."_".$cat_id.".dat";
 if(file_exists($page_name)){$fs=filesize($page_name);if($fs>0){$fp=fopen($page_name,"r");$data=trim(fread($fp,$fs));fclose($fp);}}
 else db_error();
 $data=str_replace('<%23>','#',$data);


 $page_head=GetFromString($page,'','<LISTER_BODY>');$page_foot=GetFromString($page,'</LISTER_BODY>','');
 $page_body=GetFromString($page,'<LISTER_BODY>','</LISTER_BODY>');
 $category_section=GetFromStringAbi($page,'<CATEGORY_HEADER>','</CATEGORY_HEADER>');

 $page=$page_head;

 $lines_a=split("\n",$data);
 $count=count($lines_a);
 $subcats_a=array('');
 $fnames_a=split("[|]",GetLine($data,0));
 $fcnt=count($fnames_a);

 if($g_subcat !== 'none')
 {
   $temp=array();
   $namekey=array_search($g_subcat,$fnames_a);
   for($x=0;$x<$count;$x++)
   {
     if($x<2)array_push($temp,$lines_a[$x]);
     else
     {
      $fvalues_a=split("[|]",$lines_a[$x]);
      $sc_name=$fvalues_a[$namekey];
      if(array_search($sc_name,$subcats_a)===false){if($current_sub_id==''){$current_sub_id=$sc_name;};array_push($subcats_a,$sc_name);}
      if($sc_name==$current_sub_id) array_push($temp,$lines_a[$x]);
     }
   }
   $lines_a=$temp;
   $count=count($lines_a);
 }

 if($g_catpgmax==0) $g_catpgmax=$count;

 if($page_id>1) $counter=$g_catpgmax*($page_id-1) +1;else $counter=1;
 $startitem=$counter + 1;
 $stopitem=(($startitem+$g_catpgmax)>$count)?$count:($startitem+$g_catpgmax);

 $global_pagescr='';$first_url='';
 $namekey=array_search($g_id_field,$fnames_a);
 $page=str_replace('%LISTER_PRODUCTS','%SHOP_PRODUCTS',$page);
 $ps_key=GetFromString($page,'%SHOP_PRODUCTS(%','%)%');
 $name_key=array_search($ps_key,$fnames_a);
 
 if($g_catcols>1){$page.='<table cellpadding="0">';$incol=0;}
 $product_select='';
 for($i=$startitem;$i<$stopitem;$i++)
 {
  if($lines_a[$i]!=='')
  {
   if($g_catcols>1){if($incol==0)$page.='<tr valign="top">';$page.='<td>';}
   $fvalues_a=split("[|]",$lines_a[$i]);
   $p_id=$fvalues_a[$namekey];
   $item_id=GetFromString($lines_a[$i],'','|');
   $cat_body=ReplaceFields($page_body,false,$data,$item_id,'','','');

   if(strpos($cat_body,'ToggleBody')>0) $cat_body=parse_dropdown($cat_body,$i);     //support for drop-down tables

   $cat_string=$g_abs_path.$g_realname.'?action=item&amp;iid='.$p_id.'&amp;cat='.$cat_id.'&amp;page='.$page_id;
   if($ps_key!=''){$product_select.='<option value="'.$cat_string.'">'.$fvalues_a[$name_key].'</option>';}

   if($g_subcat!=='none')$cat_string.='&amp;subcat='.$current_sub_id;
   $temp=str_replace('%SHOP_DETAIL%',$cat_string,$cat_body);
   if($i==$startitem) $first_url=$cat_string;

   $xaction='add';
   if(strpos($temp,'<FROMCART>')>0) $xaction='addandbasket';

   if(strpos($temp,'<SHOP_BUY_BUTTON>')!==false) //buy button
   {
    $btn_string=GetFromString($temp,'<SHOP_BUY_BUTTON>','</SHOP_BUY_BUTTON>');
    $img_src=GetFromString($btn_string,'src="','"');
    $hidden_fields=get_product_hidden_fields($xaction,$p_id,$cat_id,$current_sub_id,$page_id,'');
    $btn_parsed=$hidden_fields.'<input type="image" style="text-align:middle" src="'.$img_src.'" border="0">';
    $temp='<form method="GET" action="'.$g_abs_path.$g_realname.'" onsubmit=""><input name=quantity type=hidden value=1>'.$temp.'</form>';
    $temp=str_replace('<SHOP_BUY_BUTTON>'.$btn_string.'</SHOP_BUY_BUTTON>',$btn_parsed,$temp);
   }

   if(strpos($temp,'<QUANTITY>') !== false)        //buy button with quantity
   {
    $btn_url=$g_abs_path.$g_realname;
    $newtarget=getTarget(false);
    $btn_string=GetFromString($temp,'<QUANTITY>','</QUANTITY>');
    $btn_string_parsed=str_replace('%QUANTITY%','1',$btn_string);
    $hidden_fields=get_product_hidden_fields($xaction,$p_id,$cat_id,$current_sub_id,$page_id,'');
    $btn_parsed=$hidden_fields.$btn_string_parsed;

    $temp='<form method="GET" action="'.$g_abs_path.$g_realname.'" onSubmit="">'.$temp.'</form>';
    $temp=str_replace($btn_string,$btn_parsed,$temp);
   }

   $temp=str_replace('%LISTER_COUNTER%',$counter,$temp);
   $counter++;
   $page=$page.$temp;
   if($g_catcols>1){
    $page.='</td>';$incol++;
    if($incol==$g_catcols){$page.='</tr>';$incol=0;}
    }
  }
 }
 if($g_catcols>1){
    if($incol>0){
        for($i=$incol;$i<$g_catcols;$i++)$page.='<td></td>';
        $page.='</tr>';
    }
    $page.='</table>';
 }

 if($category_section !== '')
 {
  $categories='';
  $page_name=$g_id."_0.dat";
  $fs=filesize($page_name);
  $category_section_replaced='';
  if($fs>0)
  {
    $fp=fopen($page_name,"r");$categories=fread($fp,$fs);fclose($fp);
    $categories_a=split("\n",$categories);
    $cat_fields=split("[#]",$categories_a[$cat_id-1]);
    $cat_fields=str_replace('<%23>','#',$cat_fields);
    $fnames=GetLine($data,0);$ftypes=GetLine($data,1);
    $category_section_replaced=GetFromString($page,'<CATEGORY_HEADER>','</CATEGORY_HEADER>');
    $category_section_replaced=ReplaceFieldsII($category_section_replaced,false,$fnames,$ftypes,$cat_fields[1],$cat_id-1,'','','','','');
  }
  $page=str_replace($category_section,$category_section_replaced,$page);
 }

 if($g_subcat !== 'none')$sbcat_str='&amp;subcat='.$current_sub_id;
 else $sbcat_str='';

 $prevstring=GetFromString($page_foot,'%LISTER_PREVIOUS(',')%');
 if($page_id > 1) $prev='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?cat='.$cat_id.$sbcat_str.'&amp;page='.($page_id-1).'">'.$prevstring.'</a>';
 else $prev='';
 $page_foot=str_replace('%LISTER_PREVIOUS('.$prevstring.')%',$prev,$page_foot);

 $prevstring=GetFromString($page,'%LISTER_PREVIOUS(',')%');
 if($page_id > 1)$prev='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?cat='.$cat_id.$sbcat_str.'&amp;page='.($page_id-1).'">'.$prevstring.'</a>';
 else $prev='';
 $page=str_replace('%LISTER_PREVIOUS('.$prevstring.')%',$prev,$page);
 
 if($ps_key!=''){
 $product_select='<select class=input1 name="prolist" onchange="javascript: location.href=this.options[this.selectedIndex].value;return true;">'.$product_select.'</select>';
 $page=str_replace('%SHOP_PRODUCTS(%'.$ps_key.'%)%',$product_select,$page);}
 
 $nextstring=GetFromString($page_foot,'%LISTER_NEXT(',')%');
 if($stopitem < $count)$next='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?cat='.$cat_id.$sbcat_str.'&amp;page='.($page_id+1).'">'.$nextstring.'</a>';
 else $next='';

 $page_foot=str_replace('%LISTER_NEXT('.$nextstring.')%',$next,$page_foot);

 $nextstring=GetFromString($page,'%LISTER_NEXT(',')%');
 if($stopitem < $count)$next='<a class="rvts4" href="'.$g_abs_path.$g_realname.'?cat='.$cat_id.$sbcat_str.'&amp;page='.($page_id+1).'">'.$nextstring.'</a>';
 else $next='';

 $page=str_replace('%LISTER_NEXT('.$nextstring.')%',$next,$page);
 $pages='';
 $stopitem=intval((($count-2)/$g_catpgmax));
 if(($stopitem*$g_catpgmax)<($count-2))$stopitem++;
 for($i=1;$i<=$stopitem;$i++)
 {
  if($i==$page_id)$pages=$pages.$i.' ';
  else $pages=$pages.'<a class="rvts4" href="'.$g_abs_path.$g_realname.'?cat='.$cat_id.$sbcat_str.'&amp;page='.($i).'">'.$i.'</a> ';
 }
 $page_foot=str_replace('%LISTER_PAGES%',$pages,$page_foot);
 $page=str_replace('%LISTER_PAGES%',$pages,$page);

 $page=$page.$page_foot;
 $page=replace_category_combo($cat_id,$page);

 if(strpos($page,'%SHOP_CART%')!==false)
 {
  $cartstring=cart('show',$cat_id,$current_sub_id,'',$page_id,'','','','',0,'','');
  $page=str_replace('%SHOP_CART%',$cartstring,$page);
 }
 if(strpos($page,'<MINI_CART>')!==false)
 {
  $minicart=GetFromStringAbi($page,'<MINI_CART>','</MINI_CART>');
  $page=str_replace($minicart,cart('minicart',$cat_id,$current_sub_id,'',$page_id,'',$minicart,'','',0,'',''),$page);
 }

 if($global_pagescr !== '') $page=str_replace('<!--scripts-->','<!--scripts-->'.$global_pagescr,$page);
 $page=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$page);

 $cat_string=GetFromString($page,'%LISTER_CATEGORIES(',')%');
 $page=str_replace('%LISTER_CATEGORIES('.$cat_string.')%','<a class="rvts4" href="'.$g_abs_path.$g_realname.'">'.$cat_string.'</a>',$page);
 $page=$page.'<!--<pagelink>/'.$g_abs_path.$g_realname.'?cat='.$cat_id.'&amp;page='.$page_id.$sbcat_str.'</pagelink>-->';
 $page=str_replace('<SHOP>','',$page);$page=str_replace('</SHOP>','',$page);
 $page=str_replace('<LISTER>','',$page);$page=str_replace('</LISTER>','',$page);
 $page=str_replace('<QUANTITY>','',$page);$page=str_replace('</QUANTITY>','',$page);
 $page=parse_page_id($page);
 $tmp='';
 if($g_subcat!== 'none')
 {
  for($i=0;$i<count($subcats_a);$i++)
  {
    if($subcats_a[$i]==$current_sub_id) $tmp .= $subcats_a[$i].' ';
    else $tmp .= '<a class="rvts12" href="'.$g_abs_path.$g_realname.'?cat='.$cat_id.'&amp;subcat='.$subcats_a[$i].'">'.$subcats_a[$i]."</a> ";
  }
 }
 $page=str_replace('%FIRST_PRODUCT_URL%',$first_url,$page);
 $page=str_replace('%SUBCATEGORIES%',$tmp,$page);
 $page=str_replace('%ITEMSONPAGE%',$counter,$page);
 build_logged_info($page);
 return $page;
}

function cart($action,$cat_id,$subcat_id,$item_id,$page_id,$searchstring,$sub_type,$sub_type1,$sub_type2,$item_count,$afteraction,$pt)
{
 global $session_on,$session_transaction_id,$g_id,$g_shoptarget,$g_shop_on;

 $result='';
 if(($session_on==false)&&($g_shop_on))
 {
  if(empty($_SESSION)){int_start_session();}
  $session_on=true;}

 if($g_shop_on)
 {
  $basket=new basket();
  if(session_is_registered("basket".$g_id))
  {
   $basket->fill_from_session($_SESSION["basketcat".$g_id],$_SESSION["basketid".$g_id],$_SESSION["basketamount".$g_id],$_SESSION["basketsubtype".$g_id],$_SESSION["basketsubtype1".$g_id],$_SESSION["basketsubtype2".$g_id]);
   $session_transaction_id=$_SESSION["transaction_id".$g_id];
  }
  else $_SESSION["transaction_id".$g_id]=0;
 }

 if($action=='pay'){$result=$basket->process_order();}
 else if($action=='return_ok')$result=$basket->return_ok($pt);
 else if($action=='download')$result=$basket->return_file();
 else if($action=='checkout')$result=$basket->checkout();
 else if($action=='delete')$basket->delete_cart();
 else if($action=='remove')$basket->delete_item($item_id,$sub_type,$sub_type1,$sub_type2);
 else if($action=='update')$basket->update_item_count($item_id,$sub_type,$sub_type1,$sub_type2,$item_count);
 else if($action=='add')$basket->add_item($cat_id,$item_id,$item_count,$sub_type,$sub_type1,$sub_type2);
 else if($action=='minicart')$result=$basket->show_minicart($cat_id,$page_id,$subcat_id,$sub_type,$searchstring,$afteraction);
 else if($action=='addandbasket')
 {
  $basket->add_item($cat_id,$item_id,1,$sub_type,$sub_type1,$sub_type2);
  $result=$basket->show_cart($g_shoptarget=='',-1,$subcat_id,$page_id,'basket',$searchstring);
 }
 else if(($action=='show')||($action=='show_final')) {$result=$basket->show_cart(true,$cat_id,$subcat_id,$page_id,$action,$searchstring);}
 else if($action=='basket'){$result=$basket->show_cart(false,$cat_id,$subcat_id,$page_id,$action,$searchstring);}
 else if($action=='item')$result=show_item($item_id);
 if($g_shop_on)
 {
  $_SESSION["basket".$g_id]=$basket;
  $_SESSION["basketcat".$g_id]=$basket->bas_catid;
  $_SESSION["basketid".$g_id]=$basket->bas_itemid;
  $_SESSION["basketamount".$g_id]=$basket->bas_amount;
  $_SESSION["basketsubtype".$g_id]=$basket->bas_subtype;
  $_SESSION["basketsubtype1".$g_id]=$basket->bas_subtype1;
  $_SESSION["basketsubtype2".$g_id]=$basket->bas_subtype2;
  $_SESSION["transaction_id".$g_id]=$session_transaction_id;
 }
 return $result;
}

function cart_from_cartstring($cart_string)
{
 global $session_on,$g_id,$g_shop_on;
 if(($session_on==false)&&($g_shop_on))
 {
  if(empty($_SESSION)) {int_start_session();}
  $session_on=true;
 }

 $basket=new basket();

 $cart_string_array=split("[|]",$cart_string);$count=count($cart_string_array);
 for ($i=0;$i<$count-1;$i++)
 {
  $basket_line_array=split("[,]",$cart_string_array[$i]);
  $basket->add_item($basket_line_array[0],$basket_line_array[1],$basket_line_array[2],$basket_line_array[3],$basket_line_array[4],$basket_line_array[5]);
 }

 $result=$basket->show_cart(true,'','','','show_final','');
 $_SESSION["basket".$g_id]=$basket;
 $_SESSION["basketcat".$g_id]=$basket->bas_catid;$_SESSION["basketid".$g_id]=$basket->bas_itemid;
 $_SESSION["basketamount".$g_id]=$basket->bas_amount;$_SESSION["basketsubtype".$g_id]=$basket->bas_subtype;
 $_SESSION["basketsubtype1".$g_id]=$basket->bas_subtype1;$_SESSION["basketsubtype2".$g_id]=$basket->bas_subtype2;
 return $result;
}

function get_fields()
{
 if($_SERVER['REQUEST_METHOD']=='POST') {$vars=$_POST;} else {$vars=$_GET;}
 foreach($vars as $k=>$v)
 {
  $vars[$k]=trim($v);
  if(strpos(strtolower(urldecode($vars[$k])),'mime-version') !== false) {die("Why ?? :(");}
  if(strpos(strtolower(urldecode($vars[$k])),'content-type:') !== false) {die("Why ?? :(");}
 }
 return $vars;
}

function _build_fields($vars,$delim) {$pa_fields="";foreach($vars as $rs=>$v) $pa_fields .= $rs."=$v".$delim;return $pa_fields;}

function get_shop_from()
{
 global $g_send_to,$g_shop_name;

 $result=$g_send_to;
 if(strpos($result,';') !== false) $result=GetFromString($result,'',';');
 if(strpos($result,'<') === false) $result='"'.$g_shop_name.'" <'.$result.'>';
 return $result;
}

function fsockPost($url,$data,$payment_method)
{
 global $g_checkout_str;

 $web=parse_url($url);
 $temp=GetFromString($g_checkout_str[$payment_method],'','?');
 $host=GetFromString($temp,'//','/');
 $port=443;
 $url=GetFromString($temp,$host,'');
 $header="POST $url HTTP/1.0\r\n";
 $header.="Content-Type: application/x-www-form-urlencoded\r\n";
 $header.="Content-Length: ".strlen($data)."\r\n\r\n";

 if(stristr(ini_get('disable_functions'),"fsockopen"))
 {
  print "fsockopen is disabled on this server, this script can not post information to the PayPal server for IPN confirmation.";
  exit;
 }
 // $fp=fsockopen ("ssl://$host",$port,$errno,$errstr,60);//for ssl servers
 $fp=fsockopen ($host,80,$errno,$errstr,30);

 if(!$fp) {$result="$errstr ($errno)";}
 else
 {
  fputs($fp,$header.$data);$output=fread($fp,1024);$status=socket_get_status($fp);$bytes_left=$status['unread_bytes'];
  if($bytes_left>0){$output.=fread($fp,$bytes_left);}
  fclose ($fp);
 }

// remove post headers if present.
 $output=preg_replace("'Content-type: text/plain'si","",$output);
 $output=preg_replace("'Content-type: text/html'si","",$output);
 return $output;
}

function lock_and_rewrite($file_name,$content)
{
 $result=false;
 $fp=fopen($file_name,'w+');
 if($fp && flock($fp,LOCK_EX)){fwrite($fp,$content);flock($fp,LOCK_UN);$result=true;fclose($fp);}
 return $result;
}

function lock_and_append($file_name,$newstring,$safe)
{
 $result=false;
 $fp=fopen($file_name,'r+');
 if($fp && ($safe || flock($fp,LOCK_EX)))
 {
  $old_content=fread($fp,filesize($file_name));
  $pos=strpos($old_content,'</ezg_file>');
  if($pos===false){$pos=filesize($file_name);}
  fseek($fp,$pos);fwrite($fp,'**'.$newstring.'</ezg_file>" ?>');
  if(!$safe) flock($fp,LOCK_UN);
  fclose($fp);
  $result=true;
 }
 return $result;
}

function writeto_file($content,$file_name,$append)
{
 $safe_mode=ini_get('safe_mode');
 $id=-1;$id_string='';
 if($append)
 {
  if(file_exists($file_name))
  {
   $result=lock_and_append($file_name,$content,$safe_mode);
   while($result===false){$result=lock_and_append($file_name,$content,$safe_mode);}
  }
  else {echo 'missing file : '.$file_name; exit;}
 }
 else
 {
  if($content=='<id>')
  {
   if(!$fp=fopen($file_name,'r')) {print "Cannot open file ($file_name)";exit;}
   $fs=filesize($file_name);$content=fread($fp,$fs);
   if(strpos($content,'<id>')===false) $content=str_replace('<ezg_file></ezg_file>','<ezg_file><id>1</id></ezg_file>',$content);
   $id_string=GetFromString($content,'<id>','</id>');
   $id=(int)$id_string;
   $id++;
   $content=str_replace('<id>'.$id_string.'</id>','<id>'.$id.'</id>',$content);
   fclose($fp);
   if($safe_mode)
   {
    if(!$handle=fopen($file_name,'w')) {print "Cannot open file ($file_name)";exit;}
    else {fwrite($handle,'<?php echo "hi"; exit; "<ezg_file><id>'.$id.'</id></ezg_file>" ?>');}
    fclose($handle);
   }
  }

  if(!$safe_mode)
  {
   $result=lock_and_rewrite($file_name,$content);
   while ($result===false) $result=lock_and_rewrite($file_name,$content);
  }
 }
 return $id;
}

function set_login_cookie()
{
 global $g_pwd,$g_id;

 $password=$_POST['password'];
 if($g_pwd==$password) {setcookie("ezg_shop".$g_id,$g_pwd,time()+72000);show_pending_orders();}
 else print getHtmlTemplate('<span class="rvts8">wrong password</span>','');
}

function login()
{
 global $g_realname,$g_abs_path;
 $login_page='<br><br><div align="center"><div style="border: 1px solid #C1CAC5;padding:10px;width:250px;text-align:left;"><span class="rvts8">Shop administration</span><br><br><form name="frm" method="POST" action="'.$g_abs_path.$g_realname.'?action=login">';
 $login_page=$login_page.'<span class="rvts8">Password: </span><input class="input1" type="password" name="password"> <input class="input1" name="save" type="submit" value=" Send "></form><br></div></div>';
 print getHtmlTemplate($login_page,'');
}

function check_admin($action)
{
 global $_COOKIE,$g_pwd,$g_id,$prot_page_info;//G

 if(empty($_SESSION)) int_start_session();
 if($prot_page_info[7]=='-1' || $prot_page_info[7]=='')
 {
   $admin_access=(isset($_COOKIE["ezg_shop".$g_id])&&($g_pwd==$_COOKIE["ezg_shop".$g_id]));
   if((!$admin_access)&&(isset($_GET['password']))) {$admin_access=$g_pwd==$_GET['password'];}
   if(!$admin_access) login();
 }
 else
 {
  if(!isset($_SESSION['SID_ADMIN']) || isset($_SESSION['HTTP_USER_AGENT'])&&($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) )
  {
   if(!isset($_SESSION['cur_user']) || has_access($_SESSION['cur_user'],'index')==false)
   {m_header('../documents/centraladmin.php?pageid='.$g_id.'&indexflag=index',true);exit;}
   else {$admin_access=true;}
  }
  else {$admin_access=true;}
 }             //G

if($admin_access)
 {
  if($action=='orders_delete') delete_order($_REQUEST['id']);
  elseif($action=='move_confirm') move_confirm($_REQUEST['id']);   
  elseif(isset($_REQUEST['id'])) show_order_details($_REQUEST['id'],true); 
  elseif(isset($_REQUEST['savesettings'])) savesettings();
  elseif(isset($_REQUEST['setup'])) setup();
  elseif(isset($_REQUEST['pending'])) show_pending_orders();
  else show_orders();
 }
}

function GetPaymentType($line)
{
  global $g_callback_str;

  $payment='';
  foreach($g_callback_str as $ind=>$val) {if(isset($g_callback_str[$ind]['SHOP_RET_ORDERID'])&&(strpos($line,'|'.$g_callback_str[$ind]['SHOP_RET_ORDERID'].'=')!==false)){$payment=$ind;break;};}
  return $payment;
}

function savesettings()
{
 global $g_id,$g_data_ext;
 $id=$_REQUEST['setup_id'];
 $subject=$_REQUEST['bwsubject'];
 $mess=$_REQUEST['bwmessage'];
 $file_name=$g_id.'_orderid'.$g_data_ext;
 if(!$handle=fopen($file_name,'w')) {print "Cannot open file ($file_name)";exit;}
 else {
  $data='shop<id>'.$id.'</id>';
  $data.='<bwsubject>'.$subject.'</bwsubject>';
  $data.='<bwmess>'.$mess.'</bwmess>';
  fwrite($handle,'<?php echo "hi"; exit; "<ezg_file>'.$data.'</ezg_file>" ?>');
 }
 fclose($handle);
 show_pending_orders();
}

function get_session($var)	{return (isset($_SESSION[$var])? $_SESSION[$var]: "");}
function is_logged($var) {return (""!=get_session($var));}

function adminpagemenu($type)
{
  global $g_realname,$g_id,$g_abs_path;
  $lin='<a class="rvts12" href="%s">%s</a>';$link=$lin.' :: ';
  $cap='<span class="rvts8">%s</span> :: ';

  $result='<br><div align="center">';
  $result.=($type=='pending')?sprintf($cap,'orders'):sprintf($link,$g_abs_path.$g_realname.'?action=orders&amp;pending','orders');
  $result.=($type=='orders')?sprintf($cap,'confirmed orders'):sprintf($link,$g_abs_path.$g_realname.'?action=orders','confirmed orders');
  $result.=($type=='setup')?sprintf($cap,'setup'):sprintf($link,$g_abs_path.$g_realname.'?action=orders&amp;setup','setup');  

  if(isset($_SESSION['SID_ADMIN'])) $logged_as=$_SESSION['SID_ADMIN'];
	else $logged_as=$_SESSION['cur_user'];
	
	$ld_link='../documents/centraladmin.php?pageid='.$g_id.'&amp;process='.((is_logged('SID_ADMIN'))?'logoutadmin':'logout');
	$result.=sprintf($lin,$ld_link,'logout').sprintf($cap,' ['.$logged_as.']');
	 
  $result.=sprintf($lin,'../documents/centraladmin.php?process=index','back to CENTRAL ADMIN').'<br><br>';
  return $result;
}

function setup()
{
 global $g_realname,$g_id,$g_data_ext,$g_abs_path,$defbwmess;
 
 $inp='<span class="rvts8">%s</span><br><input class="input1" type="text" name="%s" value="%s" style="width:%spx"><br><br>';
 $ta='<span class="rvts8">%s</span><br><textarea class="input" name="%s" cols="34" rows="10" style="width:%spx">%s</textarea><br><br>';
 $result=adminpagemenu('setup');
 $result.='<div align="center"><div style="border: 1px solid #C1CAC5;padding:10px;text-align:left;"><form name="frm" method="POST" action="'.$g_abs_path.$g_realname.'?action=orders&amp;savesettings">';

 $file_name=$g_id.'_orderid'.$g_data_ext;
 if(!$handle=fopen($file_name,'r')) {print "Cannot open file ($file_name)";exit;}
 $fs=filesize($file_name);
 $bwsubject='';$bwmess='';
 if($fs==0) $lastOrderId=0;
 else
 {
  $content=fread($handle,filesize($file_name));
  $id_string=GetFromString($content,'<id>','</id>');
  $lastOrderId=(int)$id_string;
  $bwsubject=GetFromString($content,'<bwsubject>','</bwsubject>');
  $bwmess=GetFromString($content,'<bwmess>','</bwmess>');
 }

 if($bwsubject=='')$bwsubject='Confirmation';
 if($bwmess=='') $bwmess=$defbwmess;
 
 fclose($handle);
 $result.=sprintf($inp,'Last Order ID','setup_id',$lastOrderId,'80');
 $result.=sprintf($inp,'Bank Wire Confirmation Subject','bwsubject',$bwsubject,'400');
 $result.=sprintf($ta,'Bank Wire Confirmation E-mail','bwmessage','400',$bwmess);
 $result.='<input class="input1" name="save" type="submit" value=" Save "></form></div></div></div>';
 $result=getHtmlTemplate($result,'');
 evalAndPrint($result);
}

function return_orders($pending)
{
  global $g_id,$g_data_ext;
  
  $p_name=$g_id.($pending?'_pending':'')."_orders".$g_data_ext;
  $fp=fopen($p_name,"r");while($fp===false){$fp=fopen($p_name,"r");};$orders=fread($fp,filesize($p_name));fclose($fp);
  $orders_a=explode("**",$orders);
  return $orders_a;
}

function return_order($id,$orders_a,$pending)
{
  global $g_callback_str;
  if(empty($orders_a))$orders_a=return_orders($pending);
  
  $count=count($orders_a);
  $result='';
  for($i=1;$i<($count);$i++)
  {
    $payment=GetPaymentType($orders_a[$i]); 
    $fx_id=(($payment=='')?'custom':$g_callback_str[$payment]['SHOP_RET_ORDERID']);

    if(strpos($orders_a[$i],$fx_id.'='.$id.'|') !== false) return $orders_a[$i];
    else if(strpos($orders_a[$i],$fx_id.'='.$id.'_') !== false) return $orders_a[$i];
  }
  return $result;
}

function show_orders()
{
 global $g_realname,$g_ret_fee,$g_callback_str,$table_cell_style,$g_abs_path;

 $orders_a=return_orders(false);
 $result=adminpagemenu('orders');
 $result.='<table cellspacing="0" class="tbl">';
 $result.='<tr><td width="24" class="td_h"><span class="rvts9">Id</span></td><td class="td_h"><span class="rvts9">Date</span></td><td class="td_h"><span class="rvts9">Amount</span></td><td class="td_h"><span class="rvts9">Currency</span></td><td class="td_h"><span class="rvts9">Paypal Fee</span></td><td class="td_h"><span class="rvts9">Name</span></td><td class="td_h"><span class="rvts9">Status</span></td><td class="td_h">&nbsp;</td><td class="td_h">&nbsp;</td></tr>';
 $count=count($orders_a);
 for ($i=0;$i<$count;$i++)
 {
  $fraud=false;
  $line=$orders_a[$count-1-$i];
  $payment=GetPaymentType($line);
  $fx_id=(($payment=='')?'custom':$g_callback_str[$payment]['SHOP_RET_ORDERID']);
  $fx_name=(($payment=='')?'address_name':$g_callback_str[$payment]['SHOP_RET_NAME']);
  $fx_date=(($payment=='')?'payment_date':$g_callback_str[$payment]['SHOP_RET_DATE']);
  $fx_fee=(($payment=='')?'mc_fee':$g_callback_str[$payment]['SHOP_RET_FEE']);
  $fx_gross=(($payment=='')?'mc_gross':$g_callback_str[$payment]['SHOP_RET_GROSS']);
  $fx_currency=(($payment=='')?'mc_currency':$g_callback_str[$payment]['SHOP_RET_CURRENCY']);
  $fx_status=(($payment=='')?'payment_status':$g_callback_str[$payment]['SHOP_RET_PAYMENTSTATUS']);

    $order_id=GetFromString($line,$fx_id.'=','|');
    if($order_id=='')
    {
     $order_id=GetFromString($line,$fx_id.'_fraud=','|');
     if($order_id != '') $fraud=true;
    }
    if(strpos($order_id,'_') !== false) $order_id=GetFromString($order_id,'','_');
    if($order_id != '')
    {
     $order_name=GetFromString($line,$fx_name.'=','|');
     $order_date=GetFromString($line,$fx_date.'=','|');
     $order_date=str_replace(' PDT','',$order_date);
     $mc_fee=GetFromString($line,$fx_fee.'=','|');
     $mc_gross=GetFromString($line,$fx_gross.'=','|');
     $mc_currency=GetFromString($line,$fx_currency.'=','|');
     $payment_status=GetFromString($line,$fx_status.'=','|');
     if(strpos($payment_status,'moved+email')!==false)$payment_status='moved+email';

     $fields=explode("|",$line);
     if($fraud) $cs='class="td_f"'; else $cs='class="td_c"';
     $result.='<tr><td '.$cs.'><span class="rvts8">'.$order_id.'</span></td><td '.$cs.'><span class="rvts8">'.$order_date.'&nbsp;</span></td><td '.$cs.'><span class="rvts8">'.$mc_gross.'&nbsp;</span></td><td '.$cs.'><span class="rvts8">'.$mc_currency.'&nbsp;</span></td><td '.$cs.'><span class="rvts8">'.$mc_fee.'&nbsp;</span></td><td '.$cs.'><span class="rvts8">'.$order_name.'</span></td><td '.$cs.'><span class="rvts8">'.$payment_status.'&nbsp;</span></td><td><a class="rvts12" href="'.$g_abs_path.$g_realname.'?action=orders&amp;id='.$order_id.'">Detail</a></td><td><a class="rvts12" href="'.$g_abs_path.$g_realname.'?action=orders_delete&amp;id='.$order_id.'">Delete</a></td></tr>';
    }
 }

 $result.='</table></div>';
 $result=getHtmlTemplate($result,$table_cell_style);
 evalAndPrint($result);
}

function show_pending_orders()
{
 global $g_realname,$g_id,$g_abs_path,$g_check_name,$g_check_email,$g_data_ext,$table_cell_style;
 $boldline='<span class="rvts9">%s</span><br>';
 $nline='<span class="rvts8">%s</span>';
 $datatd='<td class="td_c"><span class="rvts8">%s&nbsp;</span></td>';
 $headtd='<td class="td_h"%s><span class="rvts9">%s</span></td>';
 $actiontd='<a class="rvts12" href="'.$g_abs_path.$g_realname.'?action=%s">%s</a><span class="rvts8">&nbsp;</span>';

 $orders_a=return_orders(true);
 $orders_a_conf=return_orders(false);
 $result=adminpagemenu('pending');
 $result.=sprintf($boldline,'[Confirm] will add copy of order into confirmed orders.');
 $result.=sprintf($boldline,'[Confirm+Email] Bankwire orders only: will send confirmation email to custommer.');

 $result.='<table cellspacing="0" class="tbl"><tr>';
 $result.=sprintf($headtd,' width="22"','Id').sprintf($headtd,'','Date').sprintf($headtd,'','Name').sprintf($headtd,'','Email').
 sprintf($headtd,'','Payment').sprintf($headtd,'','').'</tr>';
 $count=count($orders_a);
 for ($i=0;$i<$count;$i++)
 {
  $line=$orders_a[$count-1-$i];
  $order_id=GetFromString($line,'<order_','>');
  if($order_id != '')
  {
   $confirmed=return_order($order_id,$orders_a_conf,false)!=='';   
   $order_name=GetFromString($line,$g_check_name.'=','|');
   $order_email=GetFromString($line,$g_check_email.'=','|');
   $order_date=GetFromString($line,'<date>','</date>');
   $payment_method=GetFromString($line,'ec_PaymentMethod=','|');
   $result.='<tr>'.sprintf($datatd,$order_id).sprintf($datatd,$order_date).sprintf($datatd,$order_name).sprintf($datatd,$order_email).sprintf($datatd,$payment_method).'<td>';
   $result.=sprintf($actiontd,'orders&amp;id='.$order_id,' Detail');
   $result.=($confirmed?'':sprintf($actiontd,'orders_delete&amp;pending=yes&amp;id='.$order_id,'Delete'));
   $result.=($confirmed?sprintf($nline,'Confirmed'):sprintf($actiontd,'move_confirm&amp;pending=yes&amp;id='.$order_id,'Confirm'));
   if(!$confirmed && $payment_method=='bankwire')$result.=sprintf($actiontd,'move_confirm&amp;em=1&amp;pending=yes&amp;id='.$order_id,'Confirm &amp; Email');
   $result.='</td></tr>';
  }
 }
 $result.='</table></div>';
 $result=getHtmlTemplate($result,$table_cell_style);
 evalAndPrint($result);
}

function move_confirm($id)
{
 global $g_realname,$g_id,$g_data_ext,$g_abs_path,$g_currency,$g_abs_path,$g_check_email,$defbwmess;

 if(return_order($id,'',false)==='')
 {
  $email=isset($_REQUEST['em']); 
  $page_name=$g_id."_pending_orders".$g_data_ext;
  $fp=fopen($page_name,"r");$orders=fread($fp,filesize($page_name));fclose($fp);

  $line=GetFromStringAbi($orders,'<order_'.$id.'>','</order_'.$id.'>');
  $payment_date=GetFromString($line,'<date>','</date>');
  $order_email=GetFromString($line,$g_check_email.'=','|');
  $status='moved';
  if(($email)&&($order_email)) //sending confirmation e-mail
  {
     $file_name=$g_id.'_orderid'.$g_data_ext;
     if(!$handle=fopen($file_name,'r')) {print "Cannot open file ($file_name)";exit;}
     $fs=filesize($file_name);
     $bwsubject='';$bwmess='';
     if($fs==0) $lastOrderId=0;
     else
     {
      $content=fread($handle,filesize($file_name));
      fclose($handle);
      $id_string=GetFromString($content,'<id>','</id>');
      $lastOrderId=(int)$id_string;
      $bwsubject=GetFromString($content,'<bwsubject>','</bwsubject');
      $bwmess=GetFromString($content,'<bwmess>','</bwmess');
     }
     if($bwsubject=='')$bwsubject='Confirmation';
     if($bwmess=='') $bwmess=$defbwmess;   
     $bwmess=str_replace(array('%SHOP_ORDER_ID%','%SHOP_ORDER_DATE%'),array($id,$payment_date),$bwmess);
     $bwsubject=str_replace(array('%SHOP_ORDER_ID%','%SHOP_ORDER_DATE%'),array($id,$payment_date),$bwsubject);

     $text_msg='';
     $cr=crypt($id,'jhsjdhj');
     $parsed_return=parse_returnpage('paypal',$id,true,$cr,$text_msg,true);
     $_send_from=get_shop_from();
     $bwmess='<div style="padding:10px">'.$bwmess.'</div>';
     $bwmess=str_replace(array("\r\n","\r"),'<br>',$bwmess); 
     $parsed_return=str_replace('<body>','<body>'.$bwmess.'<br>',$parsed_return);
     $result=send_mail($parsed_return,$bwmess.$text_msg,$bwsubject,$_send_from,$order_email);    
     
     if($result) $status='moved+email'.$cr;
  }
  $name=GetFromString($line,'last_name=','|');

  $items=GetFromString($line,'<items>','</items>');
  $count=count(explode('><',$items));
  $price_total=0.00;

  for ($i=1;$i<($count+1);$i++)
  {
   $item=GetFromString($line,'<'.$i.'>','</'.$i.'>');
   $items=explode('|',$item);
   $item_id=$items[0];
   $amount=str_replace(',','',$items[3]);
   $quantity=$items[2];

   if($item_id=='1000000') $price_total+=$amount;
   else $price_total+=($quantity*$amount);
  }

  $parsed_fields='custom='.$id.'|payment_date='.$payment_date.'|mc_gross='.$price_total.'|mc_currency='.$g_currency.'|payment_status='.$status.'|address_name='.$name.'|';
  writeto_file($parsed_fields,$g_id.'_orders'.$g_data_ext,true);
 }
 $url=$g_abs_path.$g_realname."?action=orders";
 m_header($url,false);
}

function delete_order($id)
{
 global $g_realname,$g_id,$g_callback_str,$g_data_ext,$g_abs_path;

 if(isset($_GET['pending']))
 {
  $page_name=$g_id."_pending_orders".$g_data_ext;
  $fp=fopen($page_name,"r");$orders=fread($fp,filesize($page_name));fclose($fp);

  $order_line=GetFromStringAbi($orders,'<order_'.$id.'>','</order_'.$id.'>');
  if($order_line !== '') {$orders=str_replace('**'.$order_line,'',$orders);$orders=str_replace('** '.$order_line,'',$orders);}

  if(!$fp=fopen($page_name,'w+')){print "Cannot open file ($page_name)";exit;}
  if(fwrite($fp,$orders)===FALSE){print "Cannot write to file ($page_name)";exit;}
 }

 $page_name=$g_id."_orders".$g_data_ext;
 $fp=fopen($page_name,"r");$orders=fread($fp,filesize($page_name));fclose($fp);
 $orders=GetFromString($orders,'<ezg_file>','</ezg_file>');
 $order_lines=explode("**",$orders);
 $count=count($order_lines);
 for ($i=0;$i<$count;$i++)
 {
  $order_line=$order_lines[$count-1-$i];
  if($order_line!=='')
  {
    $payment=GetPaymentType($order_line); 
    $fx_id=(($payment=='')?'custom':$g_callback_str[$payment]['SHOP_RET_ORDERID']);
    $order_id=GetFromString($order_line,$fx_id.'=','|');
    if(strpos($order_id,'_') !== false) $order_id=GetFromString($order_id,'','_');
    if($order_id==$id)
    {
     $order_line=str_replace('</ezg_file>" ?>','',$order_line);
     $orders=str_replace('**'.$order_line,'',$orders);
    }
  }
 }
 if(!$fp=fopen($page_name,'w+')){print "Cannot open file ($page_name)";exit;}
 if(fwrite($fp,'<?php echo "hi"; exit; "<ezg_file>'.$orders.'</ezg_file>" ?>')===FALSE) {print "Cannot write to file ($page_name)";exit;}

 if(isset($_GET['pending'])) $url=$g_abs_path.$g_realname."?action=orders&pending=yes";
 else $url=$g_abs_path.$g_realname."?action=orders";
 m_header($url,false);
}


function show_order_details($id,$admin)
{
 global $g_realname,$g_id,$g_currency,$g_callback2,$g_price_decimals,$g_data_ext,$g_abs_path;

 $trtd='<tr><td><span class="rvts0">';
 $trtdc='</span></td></tr>';
 $page_name=$g_id."_pending_orders".$g_data_ext;
 $fp=fopen($page_name,"r");$orders=fread($fp,filesize($page_name));fclose($fp);
 if($admin==false)
 {
  $temp_id=GetFromString($id,'','_');
  if(GetFromString($id,'_','')==crypt($temp_id,'jhjshdjhj98') ) {$id=$temp_id;}
  else  {print 'sorry';return;}
 }

 $order_line=GetFromString($orders,'<order_'.$id.'>','</order_'.$id.'>');
 $order_date=GetFromString($order_line,'<date>','</date>');
 $order_fields=GetFromString($order_line,'<form_fields>','</form_fields>');

 $result=adminpagemenu('detail');
 $page_name=($g_id+4).".html";
 $fp=fopen($page_name,"r");$page=fread($fp,filesize($page_name));fclose($fp);

 $page_body=GetFromString($page,'<LISTER_BODY>','</LISTER_BODY>');

 $items=GetFromString($order_line,'<items>','</items>');
 $count=count(explode('><',$items));
 $bcounter=0;$itemcounter=0;$price_total=0.00;$shop_shipping=0;$cart_string='';

 for($i=1;$i<($count+1);$i++)
 {
  $item=GetFromString($order_line,'<'.$i.'>','</'.$i.'>');
  $items=explode('|',$item);
  $item_id=$items[0];$cat_id=$items[1];$amount=str_replace(',','',$items[3]);$quantity=$items[2];
  if(isset($items[4])){$sub_type=$items[4];} else $sub_type='';
  if(isset($items[7])){$sub_type1=$items[7];} else $sub_type1='';
  if(isset($items[8])){$sub_type2=$items[8];} else $sub_type2='';

  if($item_id=='1000000')
  {
   $result.= $trtd.'sub_total: '.$price_total.' '.$g_currency.$trtdc;
   $result.= $trtd.'shipping: '.$amount.' '.$g_currency.$trtdc;
   $price_total=$price_total+$amount;
  }
  else
  {
  //getting record values
   $page_name=$g_id.'_'.$cat_id.".dat";
   $fp=fopen($page_name,"r");$data=fread($fp,filesize($page_name));$data=str_replace('<%23>','#',$data);fclose($fp);

   $record_line=GetRecordLine($data,$item_id);

   $itemid=$bcounter+1;
   $itemline=str_replace('%QUANTITY%',$quantity,$page_body);
   $itemline=ReplaceFields($itemline,false,$data,$item_id,$sub_type,$sub_type1,$sub_type2);
   $itemline=str_replace(GetFromString($itemline,'<SHOP_DELETE_BUTTON>','</SHOP_DELETE_BUTTON>'),'',$itemline);
   $itemline=str_replace('%SHOP_DELETE_BUTTON%','',$itemline);
   $itemline=str_replace(GetFromString($itemline,'<a href="">','</a>'),'',$itemline);
   $itemline=str_replace('%SHOP_DETAIL%',$g_abs_path.$g_realname.'?action=item&amp;iid='.$item_id.'&amp;cat='.$cat_id.'&amp;page=1',$itemline);
   $itemline=str_replace('%SHOP_CARTCURRENCY%',$g_currency,$itemline);
   $itemline=str_replace('%SHOP_CARTPRICE%',number_format(format_number($amount),$g_price_decimals),$itemline);
   $itemline=str_replace('%LINETOTAL%',number_format($quantity*$amount,$g_price_decimals),$itemline);

   $result .= '<table><tr><td>'.$itemline.'</td></tr>';

   $itemcounter=$itemcounter+$quantity;
   $price_total=$price_total+($quantity*$amount);
   $bcounter++;
  }
 }
 $result.=$trtd.'total: '.$price_total.' '.$g_currency.$trtdc;
 $result.=$trtd.'&nbsp;'.$trtdc;
 $result.=$trtd.'order id: '.$id.$trtdc;
 $result.=$trtd.'order date: '.$order_date.$trtdc;
 $result.=$trtd.'&nbsp;'.$trtdc;

 $order_fields=explode('|',$order_fields);
 $count=count($order_fields);
 for($i=0;$i<($count);$i++) $result.=$trtd.''.$order_fields[$i].$trtdc;

 $result.=$trtd.'&nbsp;'.$trtdc;
 if($admin) {if($g_callback2) $result .= $trtd.'go to </span><a class="rvts4" href="'.$g_abs_path.$g_realname.'?action=orders">orders</a></td></tr>';}
 $result.='</table></div>';
 $result=parse_page_id($result);
 $result=getHtmlTemplate($result,'');
 evalAndPrint($result);
}

function handle_paypal_post()
{
 global $script_url,$g_id,$g_send_to,$g_data_ext,$g_shopnitofication;

 $formfields=get_fields();
 $parsed_fields=_build_fields($formfields,'|');
 writeto_file($parsed_fields,$g_id.'_orders'.$g_data_ext,true);
 $parsed_fields=_build_fields($formfields,"\n");

 $abs_url=$script_url.'?action=orders';$abs_url=str_replace(" ","%20",$abs_url);
 $parsed_fields=str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;

 $_send_from=get_shop_from();
 $result=send_mail('',$parsed_fields,'callback',$_send_from,$g_send_to);
}

function handle_paypal_callback()
{
 global $script_url,$g_id,$g_send_to,$g_checkout_str,$g_callback_str,$g_data_ext,
        $g_callback_mail,$g_callback2,$g_return_subject,$g_price_decimals,$g_shopnitofication;

 $postdata='cmd=_notify-validate';
 reset($_POST);
 $post_string='';
 while(list($key,$val)=each($_POST))
 {
  $post_string.=$key.'='.$val.'&';
  $val=stripslashes($val);$val=urlencode($val);
  $postdata.= '&'.$key.'='.$val;
 }
 //todo check if order prices are correct
 $post_url=GetFromString($g_checkout_str['paypal'],'','?');
 $status=fsockPost($post_url,$postdata,'paypal');
 writeto_file($postdata.'-a-'.$status,$g_id.'_paypal'.$g_data_ext,true);

 if(strpos($status,'VERIFIED')!==false)
 {
  $g_callback2=true;
  $business=$_POST['receiver_email'];
  $shop_business=GetFromString($g_checkout_str['paypal'],'business=','&');
  $formfields=get_fields();
  $order_id=GetFromString($_POST[$g_callback_str['paypal']['SHOP_RET_ORDERID']],'','_');
  $payment_status=$_POST[$g_callback_str['paypal']['SHOP_RET_PAYMENTSTATUS']];
  $receiver_email=urldecode($_POST[$g_callback_str['paypal']['SHOP_RET_RECEIVER_EMAIL']]);
  $payment_gross=$_POST[$g_callback_str['paypal']['SHOP_RET_GROSS']]-$_POST['tax']-$_POST['mc_shipping']-$_POST['mc_handling'];
  $payment_gross=number_format($payment_gross,$g_price_decimals);
  $price_hash=GetFromString($_POST[$g_callback_str['paypal']['SHOP_RET_ORDERID']],'_','');
  $payment_gross=preg_replace('/[^0-9]/','_',$payment_gross).'_'.$order_id;
  $hash_result=(sha1($payment_gross)==$price_hash);
  if ($business==$shop_business)
  {
    $abs_url=$script_url.'?action=orders';$abs_url=str_replace(" ","%20",$abs_url);

    if($payment_status=='Completed')
    {
     $parsed_fields=_build_fields($formfields,'|');
     if($hash_result) writeto_file($parsed_fields,$g_id.'_orders'.$g_data_ext,true);
     else writeto_file(str_replace('custom=','custom_fraud=',$parsed_fields),$g_id.'_orders'.$g_data_ext,true);
     $parsed_fields=_build_fields($formfields,"\n");

     $parsed_fields=str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;

     $_send_from=get_shop_from();
     if($hash_result) $result=send_mail('',$parsed_fields,'callback',$_send_from,$g_send_to);
     else $result=send_mail('',$parsed_fields,'fraud order callback',$_send_from,$g_send_to);

     if($g_callback_mail && $hash_result)         //send e-mail to customer
     {
      $text_msg='';
      $parsed_return=parse_returnpage('paypal',$order_id,true,$_POST['payer_id'],$text_msg,false);
      $_send_from=get_shop_from();
      $result=send_mail($parsed_return,$text_msg,$g_return_subject,$_send_from,$_POST['payer_email']);
     }
    }
    else if($payment_status=='Pending')
    {
      $parsed_fields=_build_fields($formfields,"\n");
      $parsed_fields="Pending order: ".str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;
      $_send_from=get_shop_from();
      $result=send_mail('',$parsed_fields,'pending order callback',$_send_from,$g_send_to);
    }
  }
 }
}

function handle_eway_callback()
{
 global $g_callback2,$script_url,$g_id,$g_send_to,$g_callback_str,$g_data_ext,$g_shopnitofication;
 global $session_transaction_id,$g_price_decimals,$g_callback_mail,$g_currency,$g_return_subject;

 $formfields=get_fields();
 $order_id=$_POST[$g_callback_str['eway']['SHOP_RET_ORDERID']];
 $payment_status=$_POST[$g_callback_str['eway']['SHOP_RET_PAYMENTSTATUS']];
 $payment_gross=str_replace('$','',$_POST[$g_callback_str['eway']['SHOP_RET_GROSS']]);
 $payment_gross=number_format($payment_gross,$g_price_decimals);
 $price_hash=GetFromString($_POST['eWAYoption1'],'_','');
 $payment_gross=preg_replace('/[^0-9]/','_',$payment_gross).'_'.$order_id;
 $hash_result=(sha1($payment_gross)==$price_hash);

 if($payment_status=='True')  //approved
 {
  $g_callback2=true;
  $parsed_fields=_build_fields($formfields,'|');
  $parsed_fields.=$g_callback_str['eway']['SHOP_RET_DATE'].'='.date("Y-m-d_H:i:s").'|';
  $parsed_fields.=$g_callback_str['eway']['SHOP_RET_CURRENCY'].'='.$g_currency.'|';
  writeto_file($parsed_fields,$g_id.'_orders'.$g_data_ext,true);
  $parsed_fields=_build_fields($formfields,"\n");

  $abs_url=$script_url.'?action=orders';$abs_url=str_replace(" ","%20",$abs_url);
  $parsed_fields=str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;

  $_send_from=get_shop_from();
  if($hash_result) $result=send_mail('',$parsed_fields,'callback',$_send_from,$g_send_to);
  else $result=send_mail('',$parsed_fields,'fraud order callback',$_send_from,$g_send_to);

  if($g_callback_mail && $hash_result)         //send e-mail to customer
  {
   $text_msg='';
   $parsed_return=parse_returnpage('eway',$order_id,true,'',$text_msg,false);
   $_send_from=get_shop_from();
   $result=send_mail($parsed_return,$text_msg,$g_return_subject,$_send_from,$_POST[$g_callback_str['eway']['SHOP_RET_RECEIVER_EMAIL']]);
  }
  $session_transaction_id=$order_id;
  cart('return_ok',$g_id,'',$g_id,$g_id,'','','','',0,'','eway');
 }
 else
 {
   $parsed_fields=_build_fields($formfields,"\n");
   $cpt='Error';
   $parsed_fields=$cpt." order: ".str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;
   $_send_from=get_shop_from();
   $result=send_mail('',$parsed_fields,$cpt.' order callback',$_send_from,$g_send_to);
   return_cancel_file($_POST['eWAYresponseText']);
 }
}

function handle_anet_callback()
{
 global $g_callback2,$script_url,$g_id,$g_send_to,$g_callback_str,$g_data_ext,$session_transaction_id,$g_shopnitofication;

 $formfields=get_fields();
 $order_id=GetFromString($_POST[$g_callback_str['authorize.net']['SHOP_RET_ORDERID']],'','_');
 $payment_status=$_POST[$g_callback_str['authorize.net']['SHOP_RET_PAYMENTSTATUS']];

 if($payment_status=='1')  //approved 2 declined  3 error
 {
  $g_callback2=true;
  $parsed_fields=_build_fields($formfields,'|');
  writeto_file($parsed_fields,$g_id.'_orders'.$g_data_ext,true);
  $parsed_fields=_build_fields($formfields,"\n");

  $abs_url=$script_url.'?action=orders';$abs_url=str_replace(" ","%20",$abs_url);
  $parsed_fields=str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;

  $_send_from=get_shop_from();
  $result=send_mail('',$parsed_fields,'callback',$_send_from,$g_send_to);

  $session_transaction_id=$order_id;
  cart('return_ok',$g_id,'',$g_id,$g_id,'','','','',0,'','authorize.net');
 }
 else
 {
   $parsed_fields=_build_fields($formfields,"\n");
   if($payment_status=='2') $cpt='Declined';
   elseif($payment_status=='3') $cpt='Error';
   $parsed_fields=$cpt." order: ".str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;
   $_send_from=get_shop_from();
   $result=send_mail('',$parsed_fields,$cpt.' order callback',$_send_from,$g_send_to);
   return_cancel_file($_POST['x_response_reason_text']);
 }
}

function handle_worldpay_callback()
{
 global $g_callback2,$script_url,$g_id,$g_send_to,$g_callback_str,$g_data_ext,$session_transaction_id,$g_shopnitofication;

 $formfields=get_fields();
 $order_id=GetFromString($_POST[$g_callback_str['worldpay']['SHOP_RET_ORDERID']],'','_');
 $payment_status=$_POST[$g_callback_str['worldpay']['SHOP_RET_PAYMENTSTATUS']];

 if(($payment_status=='Y'))
 {
  $g_callback2=true;
  $parsed_fields=_build_fields($formfields,'|');
  writeto_file($parsed_fields,$g_id.'_orders'.$g_data_ext,true);
  $parsed_fields=_build_fields($formfields,"\n");

  $abs_url=$script_url.'?action=orders';$abs_url=str_replace(" ","%20",$abs_url);
  $parsed_fields=str_replace('%ABSURL%',$abs_url,$g_shopnitofication).$parsed_fields;

  $_send_from=get_shop_from();
  $result=send_mail('',$parsed_fields,'callback',$_send_from,$g_send_to);

  $session_transaction_id=$order_id;
  cart('return_ok',$g_id,'',$g_id,$g_id,'','','','',0,'','worldpay');
 }
 else return_cancel_file('');
}

function return_cancel_file($errors)
{
 global $g_id;

 $page_name=($g_id+1).".html";
 $fp=fopen($page_name,"r");
 $return_page=fread($fp,filesize($page_name));
 $return_page=str_replace('%ERRORS%',$errors,$return_page);
 evalAndPrint($return_page);
}

function unreg_session()
{
 global $session_on,$g_id;

 if(empty($_SESSION)) int_start_session();
 $session_on=true;
 session_unregister("basket".$g_id);
}

function move_ezg_file($file_name)
{
 global $g_data_ext;

 $move_file=false;
 $new_page_name=$file_name.$g_data_ext;$old_page_name=$file_name.'.ezg';
 if(file_exists($new_page_name)) {$fs=filesize($new_page_name);if($fs==0) $move_file=true;}
 else $move_file=true;
 if($move_file)
 {
  $content='';
  if(file_exists($old_page_name))
  {
   $fs=filesize($old_page_name);
   if($fs>0)
   {
    $fp=@fopen($old_page_name,'r');
    if($fp){$content=fread($fp,$fs);fclose($fp);}
    else {return false;exit('conversion failed');}
   }
  }
  $content='<?php echo "hi"; exit; "<ezg_file>'.$content.'</ezg_file>" ?>';
  $fp=fopen($new_page_name,'w+');
  if($fp)
  {
   if(fwrite($fp,$content)===FALSE) {fclose($fp);return false;}
   else
   {
    fclose($fp);
    $fstest=filesize($new_page_name);
    if($fstest > 0)
    {
     if(file_exists($old_page_name))
     {
      $fp2=@fopen($old_page_name,'w+');
      if($fp2)
      {
       if(fwrite($fp2,'')===FALSE) {fclose($fp2);return false;}
       else  fclose($fp2);
       @unlink($old_page_name);
       return true;
      }
      else {return false;}
     }
    }
   }
  }
  else {return false;}
 }
 else return true;
}
// -----------------------------------------------------
function get_page_params($page_id)
{
 $max_line_chars=25000;
 $sitemap='../sitemap.php';
 $temp='';

 if(file_exists($sitemap))
 {
  $fsize=filesize($sitemap);
  if($fsize > 0)
  {
    $fp=fopen($sitemap,'r');
    while ($data=fgetcsv($fp,$max_line_chars,'|'))
    {$data_str=implode('|',$data);if(strpos($data_str,'<id>'.$page_id.'|')!==false){$temp=$data;break;}}
    fclose($fp);
  }
 }
 return $temp;
}

$prot_page_info=get_page_params($g_id);//G
if(isset($prot_page_info[1])) $prot_page_name=$prot_page_info[1];else $prot_page_name='';//G

function format_users($users)
{//G
 $users_array=array();$details_arr=array();$i=1;

 while (strpos($users,'<user id="'.$i.'" ')!==false)
 {
  $all='<user id="'.$i.'" '. GetFromString($users,'<user id="'.$i.'" ','</user>');
  $basic=GetFromString($all,'<user id="'.$i.'" ','>').' ';
  $details=GetFromString($all,'<details ','></details>').' ';
  $access=GetFromString($all,'<access_data>','</access_data>').' ';

  list($username,$password)=explode (' ',$basic);
  $details_str=explode (' ',$details);
  foreach($details_str as $k=>$v)
    {if($v!='') {$details_arr [substr($v,0,strpos($v,'='))]=GetFromString($v,'="','"');}}
  $access_arr=array();$j=1;
  while(strpos($access, '<access id="'.$j.'" ')!==false)
  {
	$access_full=GetFromStringAbi($access,'<access id="'.$j.'" ','</access>');
	$page_access_arr=array(); $m=1;
	while(strpos($access_full,'<p id="'.$m.'" ')!==false) 
	{
		$page_access_str=GetFromStringAbi($access_full,'<p id="'.$m.'" ','>');
		$page_access_arr []=array('page'=>GetFromString($page_access_str,'page="','"'), 'type'=>GetFromString($page_access_str,'type="','"'));
		$m++;
	} 
	$access_str = GetFromString($access_full, '<access id="'.$j.'" ', '>');
	list($section, $type) = explode (' ', $access_str);
	$access_arr [] = array(substr($section, 0, strpos($section, '=')) => GetFromString($section, '="', '"'), substr($type, 0, strpos($type, '=')) => GetFromString($type, '="', '"'),'page_access'=>$page_access_arr);
	$j++;
  } $users_array[]=array('id'=>$i,'username'=>GetFromString($username,'="','"'),'password'=>GetFromString($password,'="','"'),'access'=>$access_arr,'details'=>$details_arr);
  $i++;
 }
 return $users_array;
}
function get_user($username)
{//G
 $users='';$users_arr=array();$specific_user=array();
 $filename="../documents/centraladmin.ezg.php";

 clearstatcache();
 if(file_exists($filename))
 {
  $fsize=filesize($filename);
  if($fsize>0)
  {
   $fp=fopen($filename,'r');$file_contents=fread( $fp,$fsize);
   $users=GetFromString($file_contents,'<users>','</users>');
   fclose($fp);
  }
 }
 if($users!='') {$users_arr=format_users($users);}
 if(!empty($users_arr))
 {
  foreach($users_arr as $k=>$v)
  {if(array_search($username,$v)!==false) {$specific_user=$v;break;}}
 }
 return $specific_user;	
}

function has_access($user,$indexflag='')
{// G
 global $prot_page_info,$prot_page_name,$g_id;
 $access_type=array('read','read&write');
 $auth=false;$section_flag=false;$write_flag=false;
 $user_account=array();
 $user_account=get_user($user);

 if($prot_page_info[7]>=0 && !empty($user_account))
 {
  if($user_account['access'][0]['section']!='ALL')
  {
   foreach($user_account['access'] as $k=>$v)
   {
    if( $prot_page_info[7]==$v['section'])
    {
    $section_flag=true;
    if($v['type']=='1') $auth=true;
    elseif($v['type']=='2' && isset($v['page_access'])) 
    {
      foreach($v['page_access'] as $key=>$val){ if($g_id==$val['page'] && $val['type']=='1') {$auth=true;break;} }
    }
    break;
	}
   }
   if($user_account['username']==$user && $section_flag===true)
   {
   	if($indexflag=='') $auth=true;
    else {if($write_flag==true) $auth=true;}
   }
  }
  else
  {
    if($user_account['username']==$user) 
   {
	  if($indexflag=='') $auth=true;
    elseif($user_account['access'][0]['type']=='1')	$auth=true;   
    }
  }
 }
 return $auth;
}

function ca_check()  //G
{
 global $g_id,$prot_page_info;
 if((isset($prot_page_info[6]))&&($prot_page_info[6]=='TRUE'))
 {
  if(empty($_SESSION)) int_start_session();
  if((!isset($_SESSION['cur_user']) || has_access($_SESSION['cur_user'])==false) )
  {
   if(!isset($_SESSION['SID_ADMIN']) || isset($_SESSION['HTTP_USER_AGENT'])&&($_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['HTTP_USER_AGENT'])) )
    {m_header('../documents/centraladmin.php?pageid='.$g_id,true);exit;}
  }
 }
}

function write_test($content){$fp=fopen('test.ezg.php','w+');fwrite($fp,$content);fclose($fp);}

function process_edit()
{
 global $g_send_to,$g_subcat,$script_url,$g_realname,$g_id,$session_on,$g_data_ext,$g_shop_on,$g_use_abs_path,$g_abs_path,$g_checkout_callback_on,$version;
 if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') $prefix='https://';
 else $prefix='http://';

 if(isset($_SERVER['SCRIPT_URI'])) $script_url=$_SERVER['SCRIPT_URI'];
 else if((isset($_SERVER['SCRIPT_NAME']))&&(strpos($_SERVER['SCRIPT_NAME'],$g_realname) !== false)) $script_url=$prefix.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
 else $script_url=$prefix.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.$g_realname;
 if($g_use_abs_path) $g_abs_path=dirname($script_url).'/';
 else $g_abs_path='';

 if($g_shop_on)
 {
  if($g_send_to=='your@email.com') {echo 'please define shop admin e-mail!';exit;}

  $success=move_ezg_file($g_id.'_orders');$success=move_ezg_file($g_id.'_orderid');
  $success=move_ezg_file($g_id.'_paypal');$success=move_ezg_file($g_id.'_pending_orders');
  if($success===false) {exit('conversion failed');}
 }

 if(isset($_REQUEST['cleanup'])) unreg_session();

 //callbacks
 if(isset($_POST['cartId'])) handle_worldpay_callback();
 elseif(isset($_POST['x_response_code'])) handle_anet_callback();
 elseif(isset($_REQUEST['ewayTrxnReference'])) handle_eway_callback();
 elseif(isset($_REQUEST['action']))
 {
  $a=$_REQUEST['action'];
  if($a=='pay') {ca_check();cart($a,$g_id,'',$g_id,$g_id,'','','','',0,'','');}
  elseif($a=='callback') handle_paypal_callback();     //paypal IPN callback
  elseif($a=='orders_delete') check_admin($a); 
  elseif($a=='move_confirm')  check_admin($a); 
  elseif($a=='orders')        check_admin($a);  
  elseif($a=='order')         {ca_check();show_order_details($_REQUEST['id'],false);}
  elseif($a=='return')        {
       $paypal=isset($_REQUEST['payer_status']);
       if(($paypal)&&($g_checkout_callback_on['paypal']=='FALSE')) handle_paypal_post();
       else if($paypal) cart('return_ok',$g_id,'',$g_id,$g_id,'','','','',0,'','paypal');
       else cart('return_ok',$g_id,'',$g_id,$g_id,'','','','',0,'','');
  }
  elseif($a=='return_ok')     {cart($a,$g_id,'',$g_id,$g_id,'','','','',0,'','');}
  elseif($a=='random')        return_random();
  elseif($a=='cancel')        {ca_check();return_cancel_file('');}
  elseif($a=='checkout')      {ca_check();$result=cart($a,$g_id,'',$g_id,$g_id,'','','','',0,'','');evalAndPrint($result);}
  elseif($a=='basket')        {ca_check();$result=cart($a,-1,'',$g_id,$g_id,'','','','',0,'','');evalAndPrint($result);}
  elseif($a=='list')          {ca_check();$result=show_list();evalAndPrint($result);}
  elseif($a=='login')         {set_login_cookie();}
  elseif($a=='download')      {ca_check();cart($a,$g_id,'',$g_id,$g_id,'','','','',0,'','');}
  elseif($a=="version")	      {echo $version;exit;}
  elseif($a=='item')
  {
   ca_check();
   $item_id=$_REQUEST['iid'];
   if(isset($_REQUEST['subcat'])) {$subcat_id=$_REQUEST['subcat'];} else $subcat_id=0;
   $result=cart($a,$g_id,$subcat_id,$item_id,$g_id,'','','','',0,'','');
   evalAndPrint($result);
  }
  else
  {
   ca_check();
   if(isset($_REQUEST['iid'])) {$item_id=$_REQUEST['iid'];} else $item_id=0;
   if(isset($_REQUEST['cat'])) {$cat_id=$_REQUEST['cat'];} else $cat_id=0;
   if(isset($_REQUEST['subcat'])) {$subcat_id=$_REQUEST['subcat'];} else $subcat_id='';
   if(isset($_REQUEST['page'])){$page_id=$_REQUEST['page'];} else $page_id=0;
   if(isset($_GET['search']))  {$searchstring=$_GET['search'];} else $searchstring='';
   if(isset($_GET['subtype'])) {$subtype=$_GET['subtype'];} else $subtype='';
   if(isset($_GET['subtype1'])){$subtype1=$_GET['subtype1'];} else $subtype1='';
   if(isset($_GET['subtype2'])){$subtype2=$_GET['subtype2'];} else $subtype2='';
   if(isset($_GET['quantity'])){$item_count=$_GET['quantity'];} else $item_count=0;
   $result=cart($a,$cat_id,$subcat_id,$item_id,$page_id,$searchstring,$subtype,$subtype1,$subtype2,$item_count,'','');
   if($a=='addandbasket') print $result;
   else
   {
    if($searchstring != '') $abs_url=$script_url.'?cat='.$cat_id.'&page='.$page_id.'&search='.$searchstring;
    elseif(($a=='remove')&&($page_id=='0')) $abs_url=$script_url.'?action=checkout';
    elseif(($a=='update')&&($page_id=='0')) $abs_url=$script_url.'?action=checkout';
    elseif($cat_id==0) $abs_url=$script_url;
    elseif($cat_id==-1) $abs_url=$script_url.'?action=basket';
    else
    {
      $abs_url=$script_url.'?cat='.$cat_id.'&page='.$page_id;
      if($g_subcat !== 'none')$abs_url.='&subcat='.$subcat_id;
    }
    m_header($abs_url,false);
   }
  }
 }
 elseif(isset($_GET['search'])) {ca_check();$result=search();evalAndPrint($result);}
 elseif(isset($_GET['cat'])&&($_GET['cat'] != '-1'))
 {
  ca_check();
  $cat_id=$_GET['cat'];  
  if(isset($_GET['subcat'])) $sub_cat_id=$_GET['subcat']; else $sub_cat_id='';
  $result=show_category($cat_id,$sub_cat_id);
  evalAndPrint($result);
 }
 else {ca_check();$result=show_list();evalAndPrint($result);}
}

process_edit();
?>
