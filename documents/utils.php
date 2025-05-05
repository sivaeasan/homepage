<?php
$version="ezgenerator utils 1.11"; 
/*
  utils.php
  http://www.ezgenerator.com

  Copyright (c) 2005-2008 Image-line
*/

define('ER_FILENOTFOUND',1);
define('ER_NOFILE',2);
define('ER_BADFILEFORMAT',3);
define('STREAM_BUFFER',4096);
define('STREAM_TIMEOUT',86400);
define('USE_OB',false); 

function abnormalExit($errCode) {
	switch ($errCode) {
		case ER_NOFILE:	$msg='File not specified';break;
		case ER_FILENOTFOUND:	$msg='File not found';break;
		case ER_BADFILEFORMAT: $msg='Illegal file format';break;         	
		default: $msg='Unknown error';break;
	}
	die('ERROR: '.$msg);
}

function getMIME($fileName) {
	switch(strtolower(substr(strrchr($fileName,"."),1))) {
  	case "zip": $contentType="application/zip zip"; break;
    case "mp3": $contentType="audio/mpeg"; break;
    case "pdf": $contentType="application/pdf"; break;
    case "txt": $contentType="text/plain"; break;
    case "htm": $contentType="text/html"; break;
    case "html": $contentType="text/html"; break;
    case "jpg": $contentType="image/jpeg"; break;
    case "jpeg": $contentType="image/jpeg"; break;
    case "gif": $contentType="image/gif"; break;
    default: $contentType="application/octet-stream";
   }
 return $contentType;
}

function return_file()
{
    $fileName = stripslashes($_GET['filename']);
    switch(strtolower(substr(strrchr($fileName,"."),1))) 
    {
    case "php": abnormalExit(ER_BADFILEFORMAT);
    case "ezg": abnormalExit(ER_BADFILEFORMAT);
    }
    if(basename($fileName) != $fileName) abnormalExit(ER_BADFILEFORMAT);
    
    if(isset($_GET['dir'])) 
    {
    if(!preg_match('/^[0-9a-zA-Z_]+$/u',$_GET['dir']))abnormalExit(ER_BADFILEFORMAT);
    $fileName='../'.$_GET['dir'].'/'.$fileName;
    }
    else if((!file_exists($fileName))&&(file_exists('../'.$fileName))) $fileName='../'.$fileName;
    $file=@fopen($fileName,'r') or abnormalExit(ER_FILENOTFOUND);
    $fileSize=filesize($fileName);
    
    $sm=ini_get('safe_mode');
	if(!$sm && strpos(ini_get('disable_functions'),'set_time_limit')===false) set_time_limit(STREAM_TIMEOUT);  
    //if(!$sm && !in_array('set_time_limit',split(',\s*', ini_get('disable_functions')))) set_time_limit(STREAM_TIMEOUT);       

    $partialContent = false;
    if(isset($_SERVER['HTTP_RANGE'])) 
    {
        $rangeHeader=explode('-',substr($_SERVER['HTTP_RANGE'],strlen('bytes=')));	
        if($rangeHeader[0]>0){$posStart=intval($rangeHeader[0]);$partialContent=true;}
        else {$posStart=0;}
        if ($rangeHeader[1]>0){$posEnd=intval($rangeHeader[1]);$partialContent=true;}
        else {$posEnd=$fileSize-1;}
    } 
    else {$posStart=0;$posEnd=$fileSize-1;}
    /************** HEADERS ***************/
    header("Content-type: ".getMIME($fileName));
    header('Content-Disposition: attachment; filename="'.$fileName.'"');
    header("Content-Length: ".($posEnd - $posStart + 1));
    header('Date: '.gmdate('D, d M Y H:i:s \G\M\T',time()));
    header('Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T',filemtime($fileName)));
    header('Accept-Ranges: bytes');
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: ".gmdate("D, d M Y H:i:s \G\M\T", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y"))));
    if ($partialContent) {
        header("HTTP/1.0 206 Partial Content");
        header("Status: 206 Partial Content");
        header("Content-Range: bytes ".$posStart."-".$posEnd."/".$fileSize);
    }

    if ($sm) {fpassthru($file);}
    else
    {          
        fseek($file, $posStart);
        if (USE_OB) ob_start();
        while (($posStart + STREAM_BUFFER < $posEnd) && (connection_status()==0)) {
            echo fread($file,STREAM_BUFFER);
            if(USE_OB) ob_flush();
            flush();
            $posStart +=STREAM_BUFFER;
        }
        if (connection_status()==0) echo fread($file,$posEnd - $posStart + 1);
        if (USE_OB) ob_end_flush();
    }
    fclose($file);
}

function random_html($id,$root,$page_cat)
{
  $page_name='../rnd/rnd_'.$id.'.html';$page_cat=strtolower($page_cat);
  function xgfs($src,$sta,$sto) {$res=substr($src,strpos($src,$sta)+strlen($sta));$res=substr($res,0,strpos($res,$sto));return $res;}
  $fp=fopen($page_name,"r");$data=fread($fp,filesize($page_name));fclose($fp);
  $ita=explode('|',xgfs($data,'<ENTRIES_LIST>','<END>'));
  $cta=explode('|',strtolower(xgfs($data,'<CATLINKS>','<END>')));$ct=count($cta);
  if($ct>0){$cts=(in_array($page_cat,$cta))?$page_cat:'';$xt=array();foreach($cta as $k=>$v) {if($cta[$k]==$cts)$xt[]=$k;};$rnd_key=(count($xt)>0)?$xt[array_rand($xt)]:-1;}
  else $rnd_key=array_rand($ita); 
  $res=($rnd_key==-1)?'':xgfs($data,'<'.$ita[$rnd_key].'>','<END>');
  if($root=='1')$res=str_replace('../','',$res);
  $res=str_replace("\'","&#039;",$res);
  echo "document.write('".$res."');";
}

function process_it()
{
  global $version;
 if (isset($_GET['action']))
 {
  $action=$_GET['action'];
  if($action=='download'){return_file();}
  elseif($action=='random'){random_html($_REQUEST['id'],$_REQUEST['root'],$_REQUEST['cat']);}
  elseif($action=='phpinfo'){if((isset($_GET['pwd']))&&(crypt('admin',$_GET['pwd'])=='llRanR22sJYds')){phpinfo();}}
  elseif($action='version')echo $version;
 }
}

process_it();
?>


