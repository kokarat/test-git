<?PHP

//session_start();
header("Content-type:text-plain; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");   
header("Cache-Control: post-check=0, pre-check=0", false);   

$getData  = $_POST['totalData'];
$detail = explode("|||",$getData);

$tmpUrl = trim($detail[0]);
$tmpLimitPage = trim($detail[1]);
$tmpPage = trim($detail[2]);
$tmpMaxPage = trim($detail[3]);
$tmpMainCount = trim($detail[4]);


// Create URL
$tmpTrueUrl = str_replace("page=","",$tmpUrl);
$tmpTrueUrl = str_replace("pg=","",$tmpTrueUrl);
$tmpTrueUrl = str_replace("ref=sr_pg_","",$tmpTrueUrl);
$tmpTrueUrl = str_replace("#","",$tmpTrueUrl);  // UK
$tmpPagePg = "&ref=sr_pg_" . $tmpPage . "&page=" . $tmpPage . "&pg=" . $tmpPage . "&#" . $tmpPage ;		
$tmpMainURL = $tmpTrueUrl . $tmpPagePg;
$tmpFinish = '0';


// Check Showing Total Result; topBar
if($tmpPage == 1){		
	

				for($k=0;$k<10;$k--){				
					$revcontent = @file_get_contents ($tmpMainURL);				
					preg_match_all("@breadCrumb(.*)>@U",$revcontent,$chkFound);
			
					if($chkFound[0][0] <> ""){
						break;
					}
				}


				// GET CATEGORY NAME
				preg_match_all("@<title>(.*)</title>@U",$revcontent,$tmpCheckCat);
				$tmpCheckCat2 = explode(":",$tmpCheckCat[1][0]);
				$tmpCountCheckCat = count($tmpCheckCat2);						
				
				$tmpCategoryName = trim($tmpCheckCat2[$tmpCountCheckCat-1]);
				
				$checkPos = strpos($tmpCheckCat2[1], '-');
					if ($checkPos === false) { // ไม่เจอ
						$tmpCategoryName .= "-" . $tmpCheckCat2[1];
										
					}else{ // เจอ
						$tmpExplode = explode("-",$tmpCheckCat2[1]);
						$tmpCategoryName .= " -" . $tmpExplode[1];
					
					}
					
					$tmpCategoryName = str_replace("&amp;","&",trim($tmpCategoryName));	
					$tmpCategoryName = str_replace("&quot;","\"",$tmpCategoryName);				
					$tmpCategoryName = str_replace("&reg;","",$tmpCategoryName);
					$tmpCategoryName = str_replace("&#039;","",$tmpCategoryName);	
					$tmpCategoryName = str_replace(",","",$tmpCategoryName);	
					$tmpCategoryName = str_replace("-","",$tmpCategoryName);	
					$tmpCategoryName = str_replace("  ","_",$tmpCategoryName);	
					$tmpCategoryName = str_replace(" ","_",$tmpCategoryName);	
					$tmpCategoryName = str_replace("/","",$tmpCategoryName);	
					$tmpCategoryName = str_replace("__","_",$tmpCategoryName);
					
					

				// GET Result Check Max Page
				preg_match_all("@pagnDisabled\">(.*)</span>@U",$revcontent,$tmpCheckMaxPage);
				$tmpMaxPage = trim($tmpCheckMaxPage[1][0]);
				if($tmpMaxPage == ''){
							
							// GET total result product
							preg_match_all("@<span>Showing(.*)Result@U",$revcontent,$tmpCheckResult);
							$tmpCheckResult2 = $tmpCheckResult[1][0];				
							$tmpCheckResult3 = strpos($tmpCheckResult2, 'of');
							if ($tmpCheckResult3 === false) { // not found
									$tmpMaxPage = 1;
										
							}else{ // found
									
									$tmpMainResult = explode('of',$tmpCheckResult2);
									$tmpMaxResult = $tmpMainResult[1];	
									$tmpMaxResult = trim(str_replace(",","",$tmpMaxResult));
												
										// FOR GET TOTAL PRODUCT
										$tmpResultPage =  explode('-',$tmpMainResult[0]);
										$tmpResultPage = trim($tmpResultPage[1]);
										
										// FOR MAX PAGE
										$tmpMaxPage = floor($tmpMaxResult/$tmpResultPage);
									
							}
				
				} 

	
}// END if tmpPage = 1



if($tmpPage  >  $tmpLimitPage || $tmpPage  >  $tmpMaxPage){ 


		$tmpFinish = '1';		


}else{

		
		for($k=0;$k<10;$k--){		
			
			$revcontent = @file_get_contents ($tmpMainURL);				
			preg_match_all("@breadCrumb(.*)>@U",$revcontent,$chkFound);

			if($chkFound[0][0] <> ""){
				break;
			}			
			
		}
		

		preg_match_all("@\"result_(.*)>@U",$revcontent,$chkResult);
		$tmpCountAsin = count($chkResult[0]);
		for($i=0;$i<$tmpCountAsin;$i++){
			
			
				// ONLY ASIN				
				preg_match_all("@name=\"(.*)\">@U",$chkResult[0][$i],$tmpDetail);
				$tmpAsinID = trim($tmpDetail[1][0]);
				
				
				// ONLY TITLE
				preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)</span><\/a>@U",$revcontent,$chkTitle);
				$tmpTitleDetail = $chkTitle[1][0];
				$chkTitle2 = explode("class=\"lrg bold\">",$tmpTitleDetail);
				$tmpTitle = trim(str_replace(",","",$chkTitle2[1]));
					
					if($tmpTitle == ""){
						$chkTitle2 = explode("class=\"lrg bold\">",$tmpTitleDetail);
						$tmpTitle = str_replace(",","",$chkTitle2[1]);
					}
	
					if(trim($tmpTitle) == ""){
						preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)</span></a>@U",$revcontent,$chkTitle);							
						$tmpTitleDetail = $chkTitle[1][0];						
						$chkTitle2 = explode(">",$tmpTitleDetail);
						$tmpTitle = $chkTitle2[2];		
					}	
	
					if($tmpTitle == ""){
						preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)</a> <span class=\"ptBrand\">@U",$revcontent,$chkTitle);	
						$tmpTitleDetail = $chkTitle[1][0];
						$chkTitle2 = explode("\">",$tmpTitleDetail);
						$tmpTitle = str_replace(",","",$chkTitle2[1]);							
					}
				
					if(trim($tmpTitle) == ""){
						preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)<\/a> <\/h3>@U",$revcontent,$chkTitle);	
						$tmpTitleDetail = $chkTitle[0][0];
						$chkTitle2 = explode("class=\"lrg bold\" title=\"",$tmpTitleDetail);
						$tmpTitleDetail2 = str_replace(",","",$chkTitle2[1]);
						preg_match_all("@\">(.*)<\/span>@U",$tmpTitleDetail2,$chkTitle2);	
						$tmpTitle = str_replace(",","",$chkTitle2[1][0]);		
					}

					$tmpTitle = str_replace("&amp;","&",$tmpTitle);	
					$tmpTitle = str_replace("&quot;","\"",$tmpTitle);				
					$tmpTitle = str_replace("&reg;","",$tmpTitle);
					$tmpTitle = str_replace(",","",$tmpTitle);	
					

				// ONLY IMAGE
				preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)class=\"productImage\"@U",$revcontent,$chkImage);				
				$tmpImageDetail = $chkImage[1][0];
				preg_match_all("@src=\"(.*)\"@U",$tmpImageDetail,$tmpImageDetail2);						
				$tmpImageDetail3 = $tmpImageDetail2[1][0];
				preg_match_all("@\/I\/(.*)\.@U",$tmpImageDetail3,$tmpImageDetail4);	
				$tmpImage = trim("http://ecx.images-amazon.com/images/I/" . $tmpImageDetail4[1][0] . ".jpg");
				
				if($tmpAsinID <> "" && $tmpTitle <> "" && $tmpImageDetail4[1][0] <> ""){
					$tmpMainData .=  $tmpAsinID . "," . $tmpTitle . "," . $tmpImage . "\n";										
				}
				$tmpMainCount++;		
	
		
		}		

}

$tmpNextPage = $tmpPage + 1;
echo $tmpMainData . "|||" . $tmpMainCount . "|||" . $tmpNextPage . "|||" . $tmpLimitPage  . "|||" . $tmpResultPage . "|||" . $tmpCategoryName . "|||" . $tmpMaxPage . "|||" .  $tmpFinish ;


?>