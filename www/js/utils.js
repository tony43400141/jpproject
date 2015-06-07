/* $Id : utils.js 5052 2007-02-03 10:30:13Z weberliu $ */

var Browser = new Object();

Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

var Utils = new Object();

Utils.htmlEncode = function(text)
{
  return text.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

Utils.trim = function( text )
{
  if (typeof(text) == "string")
  {
    return text.replace(/^\s*|\s*$/g, "");
  }
  else
  {
    return text;
  }
}

Utils.isEmpty = function( val )
{
  switch (typeof(val))
  {
    case 'string':
      return Utils.trim(val).length == 0 ? true : false;
      break;
    case 'number':
      return val == 0;
      break;
    case 'object':
      return val == null;
      break;
    case 'array':
      return val.length == 0;
      break;
    default:
      return true;
  }
}

Utils.isNumber = function(val)
{
  var reg = /^[\d|\.|,]+$/;
  return reg.test(val);
}

Utils.isInt = function(val)
{
  if (val == "")
  {
    return false;
  }
  var reg = /\D+/;
  return !reg.test(val);
}

Utils.isEmail = function( email )
{
  var reg1 = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;

  return reg1.test( email );
}

Utils.isTel = function ( tel )
{
  var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等

  return reg.test( tel );
}

Utils.fixEvent = function(e)
{
  var evt = (typeof e == "undefined") ? window.event : e;
  return evt;
}

Utils.srcElement = function(e)
{
  if (typeof e == "undefined") e = window.event;
  var src = document.all ? e.srcElement : e.target;

  return src;
}

Utils.isTime = function(val)
{
  var reg = /^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}$/;

  return reg.test(val);
}

function rowindex(tr)
{
  if (Browser.isIE)
  {
    return tr.rowIndex;
  }
  else
  {
    table = tr.parentNode.parentNode;
    for (i = 0; i < table.rows.length; i ++ )
    {
      if (table.rows[i] == tr)
      {
        return i;
      }
    }
  }
}

document.getCookie = function(sName)
{
  // cookies are separated by semicolons
  var aCookie = document.cookie.split("; ");
  for (var i=0; i < aCookie.length; i++)
  {
    // a name/value pair (a crumb) is separated by an equal sign
    var aCrumb = aCookie[i].split("=");
    if (sName == aCrumb[0])
      return decodeURIComponent(aCrumb[1]);
  }

  // a cookie with the requested name does not exist
  return null;
}

document.setCookie = function(sName, sValue, sExpires)
{
  var sCookie = sName + "=" + encodeURIComponent(sValue);
  if (sExpires != null)
  {
    sCookie += "; expires=" + sExpires;
  }

  document.cookie = sCookie;
}

document.removeCookie = function(sName,sValue)
{
  document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
}

function $(id){
	return document.getElementById(id);
}


function getPosition(o)
{
    var t = o.offsetTop;
    var l = o.offsetLeft;
    while(o = o.offsetParent)
    {
        t += o.offsetTop;
        l += o.offsetLeft;
    }
    var pos = {top:t,left:l};
    return pos;
}

function cleanWhitespace(element)
{
  var element = element;
  for (var i = 0; i < element.childNodes.length; i++) {
   var node = element.childNodes[i];
   if (node.nodeType == 3 && !/\S/.test(node.nodeValue))
     element.removeChild(node);
   }
}
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }


  /**
   * 新增一个图片
   */
  function addImg(obj,table)
  {
      var src  = obj.parentNode.parentNode;
      var idx  = rowindex(src);
      var tbl  = document.getElementById(table);
      var row  = tbl.insertRow(idx + 1);
      var cell = row.insertCell(-1);
      cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addImg)(.*)(\[)(\+)/i, "$1removeImg$3$4-");
  }

  /**
   * 删除图片上传
   */
  function removeImg(obj,table)
  {
      var row = rowindex(obj.parentNode.parentNode);
      var tbl = document.getElementById(table);

      tbl.deleteRow(row);
  }

  /**
   * 删除图片
   */
  function dropImg(imgId)
  {
	 if(confirm('确定要删除照片吗？')){
    Ajax.call('ajax.php?act=drop_image', "img_id="+imgId, dropImgResponse, "GET", "JSON");
	 }
  }

  function dropImgResponse(result)
  {
      if (result.error == 0)
      {
          document.getElementById('gallery_' + result.content).style.display = 'none';
      }
  }

  function updateImg(ac,imgId)
  {

	  	var v="";
		if(ac == 'uporders'){
			v = document.getElementById('order_'+imgId	).value;
		}else if(ac == 'upjd'){
			v = document.getElementById('jd_'+imgId	).value;
		}
		Ajax.call('ajax.php?act=updateimg', "ac="+ac+"&img_id="+imgId+"&v="+v, updateImgResponse, "GET", "JSON");
  }

  function updateImgResponse(result){
	  if(result.error == 0)
	  {

	  }
  }

  function sendmsg(){
	Ajax.call('ajax.php?act=sendmsg', "", sendmsgs, "GET", "JSON");
  }
  function sendmsgs(result){
	 document.getElementById('aaa').innerHTML = result.result;
  }
  function urlencode(str)
 {
   var ret="";
   var strSpecial="!\"#$%&'()*+,/:;<=>?[]^`{|}~%";
   for(var i=0;i<str.length;i++)
   {
  var chr = str.substring(i,i+1);
     var c=str2asc(chr);
     if(parseInt("0x"+c) > 0x7f){
       ret+="%"+c.slice(0,2)+"%"+c.slice(-2);
     }else{
       if(chr==" ")
         ret+="+";
       else if(strSpecial.indexOf(chr)!=-1)
         ret+="%"+c.toString(16);
       else
         ret+=chr;
     }
   }
   return ret;
 }

 document.getElementsByClassName = function(className) {
var children = document.getElementsByTagName('*') || document.all;
var elements = new Array();

for (var i = 0; i < children.length; i++) {
   var child = children[i];
   var classNames = child.className.split(' ');
   for (var j = 0; j < classNames.length; j++) {
    if (classNames[j] == className) {
     elements.push(child);
     break;
    }
   }
}
return elements;
}

function checkfill(){
	var objs= document.getElementsByClassName("must_input");
	var objs1= document.getElementsByClassName("must_select");
	for(var i=0;i<objs.length;i++){
		if(objs[i].value == ""){
			alert("the input box with blue background must be filled");
			return false;
		}
	}
	for(var i=0;i<objs1.length;i++){
		if(objs1[i].options[objs1[i].selectedIndex].value == ""){
			alert("背景色为淡蓝色的选择框必须选择一项");
			return false;
		}
	}
	return true;
}


function updates(ids,v){
	Ajax.call('ajax.php?act=update_orderdate', "id="+ids+"&v="+v, showResponse, "GET", "JSON");
}

function updates_price(ids,v){
	Ajax.call('ajax.php?act=update_price', "id="+ids+"&v="+v, showResponse, "GET", "JSON");
}

function setlive(){
	// Ajax.call('ajax.php?act=setlive', "", showResponselive, "GET", "JSON");
}

function showResponselive(result){
	return false;
}


function checkbox_all(name, type){
	if( type == 2 ){
		$("input[name="+name+"]").each(function(){
			$(this).attr('checked', '');
		});
	} else {
		$("input[name="+name+"]").each(function(){
			$(this).attr('checked', 'checked');
		});
	}
}

