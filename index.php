<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TEST GIT</title>

<link rel="stylesheet" href="assets/css/bootstrap-responsive.css" />
<link rel="stylesheet" href="assets/css/bootstrap.css" />
<link rel="stylesheet" href="assets/css/app.css" />

<link rel="shortcut icon" href="qgat-favicon.png" />
<link rel="stylesheet" type="text/css" href="style.css"/>

<script language="javascript" src="script.js"></script>


</head>


<body>

    <div class="container-fluid" style="margin-top:10px;">        

        <div class="row-fluid">  
                  
            <div class="span8">
    		   <div style="font:bold 16px Tahoma, Geneva, sans-serif;">AMAZON URL</div>
               <input class="span12"  type="text" id="txtURL"   style="border:2px solid; font:16px Tahoma, Geneva, sans-serif; " />    
            </div>            
            <div class="span2">
    		   <div style="font:bold 16px Tahoma, Geneva, sans-serif;">LIMIT PAGE</div>
               <input class="span12" onkeypress="return numberOnly(event)" type="text" id="txtLimitPage"  value="1" style="border:2px solid; font:16px Tahoma, Geneva, sans-serif; " />    
            </div> 

            <div class="span2">
    		   <button  id="cmdSubmit"  class="btn btn-small btn-primary span12"  onclick="functGetData();"  style="width:100%; margin-top:18px; font:18px Tahoma, Geneva, sans-serif; cursor:pointer;"><i class="icon-play icon-white"></i>&nbsp;START GET DATA&nbsp;&nbsp;</button>
            </div>        
            
      	</div>


        <div class="row-fluid">            
            <div class="span12">
                <div id="divLoad" class="span12" style="background:url(image/loader.gif) no-repeat; float:left; display:none;  ">
                   <div style="margin-left:20px; float:left;">Please wait few minute .... Total Asin Data : <span id='divTotalCount'>0</span></div>
                </div>
                
                <div style="font:bold 18px Tahoma, Geneva, sans-serif; ">RESULT DATA</div>
                <textarea class="span12" id="divResult" style="font:14px Tahoma, Geneva, sans-serif; border:2px solid;  height:350px; overflow:auto;  "></textarea>   
            </div>            
      	</div>  



        <div class="row-fluid" style="margin-left:-999999px; clear:both; height:0px;"  >            
            <div class="span12">
                    Category Name <input type="text" id="txtCategory" value="0" style="width:500px;"  />   <br />  
                    All Count<input type="text"  id="txtMainCount" value="0" />  
                    Unique Count<input type="text"  id="txtUniqueCount" value="0" /> <br />     
                    ResultPage<input type="text"  id="txtResultPage" value="0" />  
                    Page<input type="text" id="txtPage" value="1"  />
                    Max Page<input type="text"  id="txtMaxPage" value="0" /><br />
                    Select URL&nbsp;<input type="text"  id="txtSelectURL" style="width:800px;" value="" />       
            </div>            
      	</div>    


	</div>


</body>
</html>
