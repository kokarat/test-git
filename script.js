// JavaScript Document
var xmlHttp;
function createXMLHttpRequest(){
	if (window.ActiveXObject){xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");} 
	else if (window.XMLHttpRequest){xmlHttp = new XMLHttpRequest();}}



// ------------------ กรอกได้เฉพาะตัวเลข
function numberOnly( e ) { //พิมพ์จุดได้

  if( window.event ) {
    key = e.keyCode; // IE
  } else if( e.which ) { 
    key = e.which; // Netscape/Firefox/Opera
   }

    return ( ( key > 47 && key < 58 ) || key == 8 || key == 46 );
}

// ------------------ กรอกได้เฉพาะตัวเลข
function numberNoDot( e ) { //พิมพ์จุด ไม่ได้

  if( window.event ) {fa
    key = e.keyCode; // IE
  } else if( e.which ) { 
    key = e.which; // Netscape/Firefox/Opera
   }

    return ( ( key > 47 && key < 58 ) || key == 8 );
}



function ltrim(str){
	if (str==null){return null;}
	for(var i=0;str.charAt(i)==" ";i++);
	return str.substring(i,str.length);
}

function rtrim(str){
	if (str==null){return null;}
	for(var i=str.length-1;str.charAt(i)==" ";i--);
	return str.substring(0,i+1);
}

function trim(str){
	return ltrim(rtrim(str));
}



// ################ FUNCTION GET DATA #####################
function functGetData(){	

	var tmpURL = document.getElementById('txtURL').value;
	var tmpLimitPage = document.getElementById('txtLimitPage').value;	
	var tmpPage = document.getElementById('txtPage').value;	
	
	if(tmpURL  == ""){
		alert("Require Amazon URL");
		document.getElementById('txtURL').focus();
		return;
	}

	if(tmpLimitPage  == ""){
		alert("Require Limit Page");
		document.getElementById('txtLimitPage').focus();
		return;
	}

	if(tmpPage == '1'){		
		document.getElementById('txtLimitPage').disabled = true;
		document.getElementById('txtURL').disabled = true;
		document.getElementById('txtMainCount').value = '0';	
		document.getElementById('txtUniqueCount').value = '0';	
		document.getElementById('txtResultPage').value = '0';		
		document.getElementById('divTotalCount').innerHTML = '0';	
		document.getElementById('txtMaxPage').value = '0';	

		document.getElementById('divResult').value = '';		
	}
		
	var tmpMaxPage = document.getElementById('txtMaxPage').value;
	var tmpMainCount = document.getElementById('txtMainCount').value;	
	
	var tmpData = tmpURL + "|||" + tmpLimitPage  + "|||" + tmpPage +  "|||" + tmpMaxPage +  "|||" + tmpMainCount;	
	var totalData = encodeURIComponent(tmpData);	
	document.getElementById('divLoad').style.display = 'block';	
	
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFunctGetData;	
	xmlHttp.open("POST","index-gen.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 	
	
}

function stateFunctGetData(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		var tmpMainData = xmlHttp.responseText;		
		var tmpDetail = tmpMainData.split("|||");
	
//echo $tmpMainData . "|||" . $tmpMainCount . "|||" . $tmpNextPage . "|||" . $tmpLimitPage  . "|||" . $tmpResultPage . "|||" . $tmpCategoryName . "|||" . $tmpMaxPage . "|||" .  $tmpFinish ;

		// Check Unique Count
		var tmpUniqueCount = document.getElementById('txtUniqueCount').value;			
		var tmpAsinData = tmpDetail[0];
			var tmpMainDataDetail = tmpAsinData.split("\n");
			for(i=0;i<tmpMainDataDetail.length;i++){
				tmpMainDataDetail2 = tmpMainDataDetail[i].split(",");
				tmpMainDataDetail3 = tmpMainDataDetail2[0];
				
				var tmpDivResult = document.getElementById('divResult').value;	
				if(tmpDivResult.indexOf(tmpMainDataDetail3) == -1){
					if(tmpMainDataDetail[i] != ''){
						tmpUniqueCount++;
						document.getElementById('divResult').value = tmpDivResult + tmpMainDataDetail[i] + "\n";
					}
				}
				
			}
			
			
		var tmpMainCount = tmpDetail[1];
		var tmpPage = tmpDetail[2];
		var tmpLimitPage = tmpDetail[3];
		var tmpResultPage = tmpDetail[4];
		var tmpCategoryName = tmpDetail[5];
		var tmpMaxPage = tmpDetail[6];
		var tmpFinish = tmpDetail[7];			

		if(tmpPage == 2 || tmpPage == 1){
			document.getElementById('txtMaxPage').value =tmpMaxPage ;
			document.getElementById('txtCategory').value = tmpCategoryName;				
		}
		

		document.getElementById('txtUniqueCount').value = tmpUniqueCount;
		document.getElementById('divTotalCount').innerHTML =tmpUniqueCount ;
		document.getElementById('txtPage').value = tmpPage;
		document.getElementById('txtMainCount').value =tmpMainCount ;		
		document.getElementById('txtResultPage').value =tmpResultPage ;	
		

		
		if(tmpFinish == "1"){ 							
			
				document.getElementById('txtPage').value = 1;
				document.getElementById('txtURL').disabled = false;
				document.getElementById('txtLimitPage').disabled = false;
				document.getElementById('divLoad').style.display = 'none';	
				alert("MISSION COMPLETE");				
				return;
	
						
		}else{
				
				functGetData();				
				
		 }	 
		 
			 				
	}
			
}








// ======  Random SAVE *.CSV =======
function funcDemoSaveCSV(){
		
	var tmpFilename = document.getElementById('txtCategory').value;	
	if(tmpFilename  == "" ){
		alert("Require Filename");
		return;
	}	


	createXMLHttpRequest();	
	var tmpAsinID = document.getElementById('divResult').value;	
	var tmpDivTotal = document.getElementById('txtLimitAsin').value;	
	//var tmpVipCode = document.getElementById('txtVipCode').value;
	//document.getElementById('divTotalCount').innerHTML = 'Wait a minute for EXPORT FILE .....';		

	if(tmpAsinID  == "" ){
		alert("Require Data");
		document.getElementById('divResult').focus();
		return;
	}	

	var tmpData = tmpAsinID + "|&|&|" + tmpFilename + "|&|&|" + tmpDivTotal;
	var totalData = encodeURIComponent(tmpData);
	xmlHttp.onreadystatechange = stateFuncDemoSaveCSV;	
	xmlHttp.open("POST","qgat-csv.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData);
	
}

function stateFuncDemoSaveCSV(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){	
		//var tmpVipCode = document.getElementById('txtVipCode').value	
		var tmpMainData = xmlHttp.responseText;	
		//window.location= 'qgat-csv-file/' + tmpMainData + ".csv";	
		//alert(tmpMainData);
		
			var tmpDownload = document.getElementById('divDownload').innerHTML;
			document.getElementById('divDownload').innerHTML = tmpDownload + tmpMainData;

			var tmpMainUrl = '';
			var tmpURL = document.getElementById('txtURL').value;
			var tmpArrayURL = tmpURL.split("\n");
			var tmpCountArrayURL = tmpArrayURL.length;
			
			if(tmpCountArrayURL == 1){ // No URL
				document.getElementById('txtSelectURL').value = "";
				document.getElementById('txtURL').disabled = false;
				document.getElementById('txtURL').value = "";
				document.getElementById('divTotalCount').innerHTML = '';	
				document.getElementById('divLoad').style.display = 'none';	
				alert("MISSION COMPLETE");				
				return;
			}			

			for(i=1;i<tmpCountArrayURL;i++){
				if(i==1){
					tmpMainUrl = tmpMainUrl + tmpArrayURL[i];	
				}else{
					tmpMainUrl = tmpMainUrl + "\n"  + tmpArrayURL[i];
				}
			}	
		
			
			document.getElementById('txtURL').value = "";
			document.getElementById('txtURL').value	= tmpMainUrl;							
			document.getElementById('txtSelectURL').value	= tmpArrayURL[1]; // select next url

			document.getElementById('txtPage').value = '1';
			
			document.getElementById('txtMainCount').value = '0';	
			document.getElementById('txtResultPage').value = '0';
			document.getElementById('txtMaxPage').value = '0';	
			document.getElementById('txtLimitAsin').value = '0';
			document.getElementById('divResult').value = '';
			document.getElementById('divTotalCount').innerHTML = '0' ; 	
			
			funcDemoGet();	


		
//		document.getElementById('divTotalCount').innerHTML = 'MISSION COMPLETE';		
//		document.getElementById('txtURL').value = "";		
	
	}
}





// =============================================================
// ======================== QGAT LOGIN/LOGOUT=======================
// =============================================================

function funcQgatLogin(){
	var tmpVipCode = document.getElementById('txtVipCode').value;
	if(tmpVipCode  == ""){
		alert("Require QGAT Vip Code");
		document.getElementById('txtVipCode').focus();
		return;
	}
	
	var tmpData = tmpVipCode;
	var totalData = encodeURIComponent(tmpData);
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncQgatLogin;	
	xmlHttp.open("POST","qgat-login.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 	
}


function stateFuncQgatLogin(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		var tmpMainData = xmlHttp.responseText;	
		var tmpDetail = tmpMainData.split("|");
		if(tmpDetail[0] == "1"){
			document.getElementById('divUser').innerHTML = tmpDetail[2];
			//document.getElementById('divShowPostPoint').innerHTML = tmpDetail[3];
			alert("ยินดีต้อนรับ " + tmpDetail[2]);
			window.location.reload();
		}else{
			document.getElementById('divUser').innerHTML = '';
			//document.getElementById('divShowPostPoint').innerHTML = '';
			alert("ไม่มีรหัส QGAT VIP CODE นี้ หรือไม่ท่านอาจกรอกผิด กรุณาตรวจสอบ");
		}
	}
}





function funcQgatLogout(){

	if(confirm("ต้องการออกจาก ระบบ ใช่หรือไม่ กรุณายืนยัน") == false){
		return;
	}
					
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncQgatLogout;	
	xmlHttp.open("POST","qgat-logout.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send(); 	
}

function stateFuncQgatLogout(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		window.location.reload();
	}
}








// =============================================================
// ======================== GET ASIN ID TOOL ========================
// =============================================================
function funcShowTotalTime(tmpVipNo){

	if(tmpVipNo == ""){		
		//document.getElementById('divCredit').innerHTML = "0";
		document.getElementById('divTotalTime').innerHTML =  "0";			
		return;
	}
	
	createXMLHttpRequest();
	var totalData = encodeURIComponent(tmpVipNo);
	xmlHttp.onreadystatechange = stateFuncShowTotalTime;	
	xmlHttp.open("POST","manage-asinid-total.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 	
}

function stateFuncShowTotalTime(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){		
		document.getElementById('divTotalTime').innerHTML  = xmlHttp.responseText;	
		var tmpMainData = xmlHttp.responseText;		
		var tmpDetail = tmpMainData.split("|||");
		//document.getElementById('divCredit').innerHTML = tmpDetail[0];
		document.getElementById('divTotalTime').innerHTML = tmpDetail[1];		
	}
}



// ################ STOP STOP STOP GET ###################
function funcGetStop(){	

	if(confirm("Are you sure you want to stop.") == false){
		return;
	}

	document.getElementById('txtStop').value = 'stop';
}





// ################ START GET ASIN ID #####################
// ################ START GET ASIN ID #####################
// ################ START GET ASIN ID #####################
function funcGetAsinID(tmpLimit, tmpPage, tmpMissAsin){	
		
	//check url
	var tmpUrl = document.getElementById('txtURL').value;
	if(tmpUrl  == "" ){
		alert("Require Amazon URL");
		document.getElementById('txtURL').focus();
		return;
	}		

	// check vip code
	var tmpVipCode = document.getElementById('txtVipCode').value;	
	if(tmpLimit > 5){		
		if(tmpVipCode == ""){
			alert("Require VIP CODE");
			document.getElementById('txtVipCode').focus();
			return;			
		}
	}


	if(tmpPage == 1){
		document.getElementById('divResult').value = "";
		document.getElementById('divAllOnlyAsin').innerHTML = "";		
		document.getElementById('divTotal').innerHTML = "0";
		document.getElementById('txtStop').value = '0';
		//document.getElementById('txtCheckDivResult').value = '0'; // Check Div result_
	}

	// ---- Check Title
	var tmpChkTitle = document.getElementById('chkTitle').checked;	
	if(tmpChkTitle == true){
		var tmpGetTitle = 1; // get title

		// check vip code
		var tmpMaxTitle = document.getElementById('txtMaxTitle').value;
		if(tmpMaxTitle == ""){
			tmpMaxTitle = 0;
		}
		
	}else{
		var tmpGetTitle = 0; // get title
		tmpMaxTitle = 0;
	}
		
	//disable object
	//disableObj();	
	
	//var tmpCheckDivResult = document.getElementById('txtCheckDivResult').innerHTML;
	var tmpAllOnlyAsin = document.getElementById('divAllOnlyAsin').innerHTML;
	var tmpMainCount = document.getElementById('divTotal').innerHTML;
	var tmpResult = document.getElementById('lstResult').value;

	var tmpData = tmpUrl + "|||" + tmpLimit + "|||" + tmpPage + "|||" + tmpMainCount + "|||" + tmpVipCode + "|||" + tmpAllOnlyAsin + "|||" + tmpGetTitle  + "|||" + tmpMissAsin + "|||" + tmpMaxTitle + "|||" + tmpResult;		
	
	var totalData = encodeURIComponent(tmpData);	
	document.getElementById('divLoad').style.display = 'block';	
	
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncGetAsinID;	
	xmlHttp.open("POST","qgat-gen.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 	
	
}

function stateFuncGetAsinID(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		var tmpMainData = xmlHttp.responseText;		
		var tmpDetail = tmpMainData.split("|||");
		
		//echo "1" . "|||" . $tmpGetAllData . "|||" .  $tmpLimit . "|||" . $tmpPage . "|||" . $tmpCheckTotal  . "|||" . $tmpOnlyAsin .  "|||" . $tmpProgress . "|||" . $tmpMissAsin;			
		var tmpStatus = tmpDetail[0];
		var tmpProgress = tmpDetail[6];
			document.getElementById('divProgress').style.width = tmpProgress + "%";
		var tmpMissAsin = tmpDetail[7];
		

		
		if(tmpStatus == "0"){
			
				alert("WRONG VIP CODE !!! Please check. ");
				document.getElementById('divLoad').style.display = 'none';			
				enableObj();  //enabled object
				return;
			
		}else if(tmpStatus == "1"){
	
				var tmpGetAllData = tmpDetail[1];
				var tmpLimit = tmpDetail[2];
				var tmpPage = tmpDetail[3];
				var tmpCheckTotal = tmpDetail[4];
				var tmpOnlyAsinID = tmpDetail[5];
			
				var tmpDivResult = document.getElementById('divResult').value;		
				document.getElementById('divResult').value = tmpDivResult + tmpGetAllData;
	
				//var tmpMainCount = document.getElementById('divTotal').innerHTML;
				//document.getElementById('divTotal').innerHTML = parseInt(tmpMainCount) + parseInt(tmpCount);			
				document.getElementById('divTotal').innerHTML = tmpCheckTotal;
				
//				var tmpDivOnlyAsin = document.getElementById('divOnlyAsin').innerHTML;		
//				document.getElementById('divOnlyAsin').innerHTML = tmpDivOnlyAsin + tmpOnlyAsinID;					
	
				document.getElementById('divLoad').style.display = 'none';												
				//alert(tmpPage + "/" + tmpLimit);
						
				if(document.getElementById('txtStop').value == 'stop'){
					alert("Stop Get Asin");						
					enableObj(); //enabled object		
					document.getElementById('txtStop').value = '0';				
					return;
				}
						
				if(tmpPage == tmpLimit){							
					//funcShowTotalTime(tmpVipNo);							
					alert("Mission Complete");						
					//enableObj(); //enabled object						
					return;
				}else{
					//funcShowTotalTime(tmpVipNo);
					var tmpNextPage = parseInt(tmpPage) + 1;
					funcGetAsinID(tmpLimit, tmpNextPage, tmpMissAsin);				
				}		
				
		 }else if(tmpStatus == "2"){
			 
			 	alert("System No Asin For Get" );
				document.getElementById('divLoad').style.display = 'none';
				//funcShowTotalTime(tmpVipNo);
	
				alert("Mission Complete");				
				//enableObj(); //enabled object					
				return;
			 
		 }
			 				
	}
			
}



//disabled object
function disableObj(){
	document.getElementById('txtURL').disabled = true;	
	document.getElementById('chkTitle').disabled = true;	
	document.getElementById('txtVipCode').disabled = true;	
	document.getElementById('lstLimit').disabled = true;	
	document.getElementById('cmdExport').disabled = true;	
	//document.getElementById('cmdSelectAll').disabled = true;		
	//document.getElementById('divResult').disabled = true;	
	document.getElementById('cmdStart').disabled = true;		
}

//enabled object
function enableObj(){
	document.getElementById('txtURL').disabled = false;	
	document.getElementById('chkTitle').disabled = false;	
	document.getElementById('txtVipCode').disabled = false;	
	document.getElementById('lstLimit').disabled = false;	
	document.getElementById('cmdExport').disabled = false;		
	//document.getElementById('cmdSelectAll').disabled = false;		
	//document.getElementById('divResult').disabled = false;	
	document.getElementById('cmdStart').disabled = false;					
}


// ======  Random VIP CODE =======
function funcAdminRandomVipCode(){
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncAdminRandomVipCode;	
	xmlHttp.open("POST","manage-asinid-vipcode.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send(); 	
}

function stateFuncAdminRandomVipCode(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		var tmpMainData = xmlHttp.responseText;		
		document.getElementById('txtVipCode').value = tmpMainData;
	}
}


function funcAddVipCode(){

	var tmpEmail = document.getElementById('txtEmail').value;
	//var tmpVipCode = document.getElementById('txtVipCode').value
	var tmpConfirmPassword = document.getElementById('txtConfirmPassword').value	

	if(tmpEmail  == "" ){
		alert("Require Email");
		document.getElementById('txtEmail').focus();
		return;
	}	
	
	/*
	if(tmpVipCode  == "" ){
		alert("Require VIP CODE");
		document.getElementById('txtVipCode').focus();
		return;
	}	
	*/

	if(tmpConfirmPassword  == "" ){
		alert("Require ConfirmPassword");
		document.getElementById('txtConfirmPassword').focus();
		return;
	}	
					
	//var tmpData = tmpEmail + "|&|&|" + tmpVipCode + "|&|&|" + tmpConfirmPassword ;
	var tmpData = tmpEmail + "|&|&|" +  tmpConfirmPassword ;
	var totalData = encodeURIComponent(tmpData);
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncAddVipCode;	
	xmlHttp.open("POST","manage-asinid-register-save.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 
}

function stateFuncAddVipCode(){	
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		var tmpMainData = xmlHttp.responseText;	
		var tmpMainDetail = tmpMainData.split("|||");
		if(tmpMainDetail[0] == '1'){
			alert(tmpMainDetail[1]);
			window.location.reload();
		}else{
			alert(tmpMainDetail[1]);
		}		
		
	}	
}



function funcAddCredit(tmpVipNo, tmpVipCode, tmpVipCredit){
	var tmpConfirmPassword = document.getElementById('txtConfirmPassword').value	

	if(tmpConfirmPassword  == "" ){
		alert("Require ConfirmPassword");
		document.getElementById('txtConfirmPassword').focus();
		return;
	}	
					
	var tmpData = tmpVipNo + "|&|&|" + tmpVipCode + "|&|&|" + tmpConfirmPassword + "|&|&|" + tmpVipCredit ;
	var totalData = encodeURIComponent(tmpData);
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncAddCredit;	
	xmlHttp.open("POST","manage-asinid-register-add-credit.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 
}

function stateFuncAddCredit(){	
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		alert("Add Credit Vip Succeed");
		window.location.reload();
	}	
}





// ======  SEND EMAIL =======
function funcVipSendEmail(tmpEmail, tmpVipCode, tmpRegDate, tmpExpDate){
	
	var tmpConfirmPassword = document.getElementById('txtConfirmPassword').value;	
	if(tmpConfirmPassword  == "" ){
		alert("CONFIRM PASSWORD");
		document.getElementById('txtConfirmPassword').focus();
		return;
	}			

	var tmpData = tmpEmail + "|&|&|" + tmpVipCode + "|&|&|" + tmpConfirmPassword + "|&|&|" + tmpRegDate + "|&|&|" + tmpExpDate;
	var totalData = encodeURIComponent(tmpData);
	createXMLHttpRequest();
	xmlHttp.onreadystatechange = stateFuncVipSendEmail;	
	xmlHttp.open("POST","manage-asinid-email.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData); 	
}

function stateFuncVipSendEmail(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){				
		var tmpMainData = xmlHttp.responseText;		
		alert(tmpMainData);
	}
}


// ======  Random SAVE *.CSV =======
function funcSaveCSV(){
	
	var tmpFilename;
	var name=prompt("Export file name (เฉพาะภาษาอังกฤษ และห้ามใส่สัญลักษณ์ น่ะครับ)");	
	if (name!=null){
		tmpFilename = name;
	}else{
		return;
	}
	
	
	createXMLHttpRequest();
	var tmpAsinID = document.getElementById('divResult').value;	
	var tmpDivTotal = document.getElementById('divTotal').innerHTML;	
	//var tmpVipCode = document.getElementById('txtVipCode').value;
	
	if(tmpAsinID  == "" ){
		alert("Require Data");
		document.getElementById('divResult').focus();
		return;
	}	

	//if(tmpVipCode  == "" ){
	//	alert("Require VIP CODE");
	//	document.getElementById('txtVipCode').focus();
	//	return;
	//}	

	//var tmpData = tmpAsinID + "|&|&|" + tmpVipCode;
	var tmpData = tmpAsinID + "|&|&|" + tmpFilename + "|&|&|" + tmpDivTotal;
	var totalData = encodeURIComponent(tmpData);
	xmlHttp.onreadystatechange = stateFuncSaveCSV;	
	xmlHttp.open("POST","qgat-csv.php?dummy=" + Math.random(),true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.send("totalData=" + totalData);
	
}

function stateFuncSaveCSV(){
	if(xmlHttp.readyState==4 && xmlHttp.status==200){	
		//var tmpVipCode = document.getElementById('txtVipCode').value	
		var tmpMainData = xmlHttp.responseText;	
		window.location= 'qgat-csv-file/' + tmpMainData + ".csv";
	}
}






