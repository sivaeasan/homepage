//[OPENPIC]

function openpic(title,url,lo,me,re,sc,st,to,closingtime,w,h,t,l)
{
 
 if(t<0 || l<0){l=Math.round((screen.availWidth-w)/2);t=Math.round((screen.availHeight-h)/2)-25;}
 aw=window.open('','','toolbar='+to+',menubar='+me+',scrollbars='+sc+',resizable='+re+',status='+st+',location='+lo+',width='+w+',height='+h+',top='+t+',left='+l);
 temp='<html><head><title>'+title+'<\/title><\/head><body style="margin:0;padding:0"><table border="0" cellspacing="0" cellpadding="0" style="width:100%;height:100%;background:#FFFFFF"><tr><td width="100%"><p align="center"><a href="javascript:window.close();"><img src="'+url+'" border="0"><\/a>';
 
 temp+='<\/td><\/tr><\/table><\/body><\/HTML>';aw.document.write(temp);
 if (closingtime!=0) aw.setTimeout('window.close()',closingtime);
}

//[END]
