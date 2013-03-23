<?PHP
header("Content-type:text-plain; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");   
header("Cache-Control: post-check=0, pre-check=0", false);   
require_once("simple_html_dom.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>QGAT DEMO</title>

<link rel="stylesheet" href="assets/css/bootstrap-responsive.css" />
<link rel="stylesheet" href="assets/css/bootstrap.css" />
<link rel="stylesheet" href="assets/css/app.css" />

<link rel="shortcut icon" href="qgat-favicon.png" />
<link rel="stylesheet" type="text/css" href="style.css"/>

<script language="javascript" src="qgat-script.js"></script>
<script type="text/javascript">
	
function select_all() {
	document.getElementById('divResult').focus();
	document.getElementById('divResult').select();
}
</script>

</head>


<body style="margin:0;">

<div style="margin:10px auto 10px auto; width:980px;">

	<!--<div style="float:left;">
        <div style="font:bold 18px Tahoma, Geneva, sans-serif;">EXPORT FILE NAME</div>
        <input type="text" style=" border:2px solid #0C0; font:bold 18px Tahoma, Geneva, sans-serif; width:933px;" id="txtFileName"  />
	</div>-->

	<div style="float:left;">
        <div style="font:bold 18px Tahoma, Geneva, sans-serif;">AMAZON URL</div>
        <textarea type="text" id="txtURL"  style="border:2px solid; height:30px;  font:14px Tahoma, Geneva, sans-serif; width:933px; height:350px; overflow:auto;"></textarea>
        </
	</div>

    Category Name <input type="text" id="txtCategory" value="0"  />    
    Maincount<input type="text"  id="txtMainCount" value="0" />  
    LimitAsin<input type="text"  id="txtLimitAsin" value="0" />    <br />  
    ResultPage<input type="text"  id="txtResultPage" value="0" />  
    Page<input type="text" id="txtPage" value="0"  />
    Max Page<input type="text"  id="txtMaxPage" value="0" /><br />
    Select URL&nbsp;<input type="text"  id="txtSelectURL" style="width:700px;" value="" />      

	<div style=" clear:both;"></div>

	<button id="cmdSubmit"  class="btn btn-small btn-primary"  onclick="funcDemoGet('1');"  style="float:left; width:642px; height:50px; font:18px Tahoma, Geneva, sans-serif; cursor:pointer; margin:0px 10px 20px 0"><i class="icon-play icon-white"></i>&nbsp;START GET DATA&nbsp;&nbsp;</button>

	<button id="cmdSubmit"  class="btn btn-small btn-warning"  onclick="funcDemoSaveCSV();"  style="float:left; width:300px; height:50px; font:18px Tahoma, Geneva, sans-serif; cursor:pointer; margin:0px 10px 20px 0"><i class="icon-play icon-white"></i>&nbsp;EXPORT FILE&nbsp;&nbsp;</button>    

<!--	<button id="cmdExport"  class="btn btn-small btn-success"  onclick="funcDemoSaveCSV();"  style="float:left; height:40px;  height:50px;   font:18px Tahoma, Geneva, sans-serif; cursor:pointer; margin:0px 0 0 0;"><i class="icon-chevron-up icon-white"></i>&nbsp;EXPORT *.CSV FILE&nbsp;&nbsp;</button>-->
    
<br />
<br />


   <div id="divLoad" style="width:500px; height:20px; background:url(image/loader.gif) no-repeat; float:left; margin:auto; margin-top:5px; display:none;  ">
      	<div style="margin-left:20px; float:left;">Please wait few minute .... total page : <span id='divTotalCount'>0</span></div>
	</div>

<textarea id="divResult" style="width:935px; margin:0px 0 10px 0px; font:14px Tahoma, Geneva, sans-serif; border:2px solid  #F00 ;  height:350px; overflow:auto;  "></textarea>

<div id="divDownload" style="width:935px; margin:0px 0 10px 0px; font:14px Tahoma, Geneva, sans-serif; border:2px solid  #F00 ;  height:250px;   ">fasdf</div>

</div>





</body>
</html>
