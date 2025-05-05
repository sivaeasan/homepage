var arVersion=navigator.appVersion.split("MSIE");var version=parseFloat(arVersion[1]);var strGif="tpixel.gif";var strFilter="progid:DXImageTransform.Microsoft.AlphaImageLoader";
if((version>=5.5)&&(document.body.filters)){for(var i=0;i<document.images.length;i++){var img=document.images[i];var imgName=img.src.toUpperCase();
if(imgName.substring(imgName.length-3,imgName.length)=="PNG"){var imgID=(img.id)?"id='"+img.id+"' ":"";var imgClass=(img.className)?"class='"+img.className+"' ":"";var imgTitle=(img.title)?"title='"+img.title+"' ":"title='"+img.alt+"' ";
var imgStyle="display:inline-block;"+img.style.cssText; 
if(img.align=="left")imgStyle="float:left;"+imgStyle;if(img.align=="right")imgStyle="float:right;"+imgStyle;if(img.parentElement.href) imgStyle="cursor:hand;"+imgStyle;
var strNewHTML="<span "+imgID+imgClass+imgTitle+" style=\""+"width:"+img.width+"px; height:"+img.height+"px;"+imgStyle+";"+"filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"+"(src=\'"+img.src+"\', sizingMethod='scale');\"></span>";img.outerHTML=strNewHTML;i=i-1;}}}

function findImgInputs(oParent){var oChildren=oParent.children;if(oChildren){for(var i=0;i<oChildren.length;i++){var oChild=oChildren(i);if((oChild.type=='image')&&(oChild.src)&&(oChild.src.substring(oChild.src.length-3,oChild.src.length)=="PNG")){var origSrc=oChild.src;oChild.src=strGif;oChild.style.filter=strFilter+"(src='"+origSrc+"')";};findImgInputs(oChild);}}}
for(i=0;i<document.forms.length;i++) findImgInputs(document.forms(i));
