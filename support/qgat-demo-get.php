<?PHP

//session_start();
set_time_limit(5000000);
header("Content-type:text-plain; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");   
header("Cache-Control: post-check=0, pre-check=0", false);   
require_once("simple_html_dom.php");

$getData  = $_POST['totalData'];
$detail = explode("|||",$getData);
$tmpUrl = trim($detail[0]);

$tmpLimitAsin = trim($detail[1]);
$tmpResultPage = trim($detail[2]);
$tmpPage = trim($detail[3]);

$tmpMainCount = trim($detail[4]);
$tmpMaxPage = trim($detail[5]);

$tmpTrueUrl = str_replace("page=","",$tmpUrl);
$tmpTrueUrl = str_replace("pg=","",$tmpTrueUrl);
$tmpTrueUrl = str_replace("ref=sr_pg_","",$tmpTrueUrl);
$tmpTrueUrl = str_replace("#","",$tmpTrueUrl); // UK

$tmpPagePg = "&ref=sr_pg_" . $tmpPage . "&page=" . $tmpPage . "&pg=" . $tmpPage . "&#" . $tmpPage ;		
$tmpMainURL = $tmpTrueUrl . $tmpPagePg;
$tmpFinish = '0';



// Check Showing Total Result; topBar
if($tmpPage == 1){			

	for($k=0;$k<10;$k--){		
			
		$html = file_get_html($tmpMainURL);
		$tmpResult = $html->getElementById("result_0");
		
		if($tmpResult <> ""){
			break;
		}else{
			$html->clear();
		}
		
	}


	// GET CATEGORY NAME
	$tmpCatName = $html->getElementById("breadCrumb");
	preg_match_all("@<a href=\"(.*)<\/a>@U",$tmpCatName,$tmpDetail);
	$countCatName = count($tmpDetail[1]);
	
	// For Max Name
	/*	
	foreach($tmpDetail[1] as $tmpDetail2){
		$tmpDetail3 = explode("\">",$tmpDetail2);
		$tmpAllCatName .= $tmpDetail3[1] . "_";
	}
	*/	
			
	preg_match_all("@<\/span> (.*)<\/h1>@U",$tmpCatName,$tmpCatMainName);
	$tmpCatMainName2 = explode("</span>",$tmpCatMainName[1][0]);
	$tmpCatMainName3 = $tmpCatMainName2[count($tmpCatMainName2)-1];
	
		$tmpCategoryName =  $tmpAllCatName . trim($tmpCatMainName3);
		$tmpCategoryName = str_replace("&amp;","&",$tmpCategoryName);	
		$tmpCategoryName = str_replace("&quot;","\"",$tmpCategoryName);				
		$tmpCategoryName = str_replace("&reg;","",$tmpCategoryName);
		$tmpCategoryName = str_replace(",","",$tmpCategoryName);	
		$tmpCategoryName = str_replace(" ","_",$tmpCategoryName);	
		$tmpCategoryName = str_replace("  ","_",$tmpCategoryName);	
		


	// GET Result Check Max Page
	$tmpTopBar = $html->getElementById("topBar");
	preg_match_all("@Showing(.*)Results@U",$tmpTopBar,$tmpDetail);

	$tmpDetail1 = $tmpDetail[1][0];

			$checkPos = strpos($tmpDetail1, 'of'); // มีสินค้ามากกว่า 1 หน้าจะมี of
			if ($checkPos === false) { // ไม่เจอ
				$tmpTotalProduct = $tmpDetail1;
				$tmpResultPage = $tmpTotalProduct;
				$tmpMaxPage = '1';
				
			}else{ // เจอ
			
				$tmpDetail2 = explode('of',$tmpDetail1);
				$tmpDetail3 = $tmpDetail2[1];	
				$tmpTotalProduct = trim(str_replace(",","",$tmpDetail3));
						
				// FOR GET TOTAL PRODUCT
				$tmpDetail4 =  explode('-',$tmpDetail2[0]);
				$tmpResultPage = trim($tmpDetail4[1]);

				// FOR GET MAX PAGE
				$tmpHtmlPagn = $html->getElementById("pagn");
				preg_match_all("@pagnDisabled\">(.*)</span>@U",$tmpHtmlPagn,$tmpDetail);
				$tmpMaxPage  = trim($tmpDetail[1][0]);
				
					if($tmpMaxPage == ""){ // if หาค่า max ไม่เจอ
						preg_match_all("@<a href=\"(.*)<\/a>@U",$tmpHtmlPagn,$tmpDetail);
						$tmpMaxPage  = count($tmpDetail[0]) + 1;
					}
				
				
			}


			$tmpMaxGetProduct = $tmpMaxPage * $tmpResultPage; // จำนวนสินค้าที่สามารถเก็บได้มากที่สุด
			if($tmpTotalProduct > $tmpMaxGetProduct){
				$tmpLimitAsin = $tmpMaxGetProduct - 5;
			}else{
				$tmpLimitAsin = $tmpTotalProduct - 5;
			}
	
}


if($tmpPage  >=  $tmpMaxPage){ // ถ้าเท่ากับจำนวนที่ตั้งไว้

		$tmpFinish = '1';

}else{

		
		for($k=0;$k<10;$k--){		
			
			/*	
			$html = file_get_html($tmpMainURL);
			$tmpResult = $html->getElementById("result_" . $tmpMainCount);
			
			if($tmpResult <> ""){
				break;
			}else{
				$html->clear();
			}
			*/
			
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
						preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)<\/a> <\/h3>@U",$revcontent,$chkTitle);	
						$tmpTitleDetail = $chkTitle[0][0];
						$chkTitle2 = explode("class=\"lrg bold\" title=\"",$tmpTitleDetail);
						$tmpTitleDetail2 = str_replace(",","",$chkTitle2[1]);
						preg_match_all("@\">(.*)<\/span>@U",$tmpTitleDetail2,$chkTitle2);	
						$tmpTitle = str_replace(",","",$chkTitle2[1][0]);		
					}


				// ONLY IMAGE
				preg_match_all("@\/dp\/" . $tmpAsinID . "(.*)class=\"productImage\"@U",$revcontent,$chkImage);				
				$tmpImageDetail = $chkImage[1][0];
				preg_match_all("@src=\"(.*)\"@U",$tmpImageDetail,$tmpImageDetail2);						
				$tmpImageDetail3 = $tmpImageDetail2[1][0];
				preg_match_all("@\/I\/(.*)\.@U",$tmpImageDetail3,$tmpImageDetail4);	
				$tmpImage = trim("http://ecx.images-amazon.com/images/I/" . $tmpImageDetail4[1][0] . ".jpg");
				//echo $tmpImage . "<br>";
				//$tmpTitle = str_replace(",","",$chkTitle2[1]);
				
				//http://ecx.images-amazon.com/images/I/41UVw1aetJL._SL246_SX190_CR0,0,190,246_.jpg
				
				if($tmpAsinID <> "" && $tmpTitle <> "" && $tmpImageDetail4[1][0] <> ""){
					$tmpMainData .=  $tmpAsinID . "," . $tmpTitle . "," . $tmpImage . "\n";										
				}
				$tmpMainCount++;		

	
				/*
	
					for($b=0;$b<10;$b--){	
							$tmpResult = $html->getElementById("result_" . $tmpMainCount);				
							preg_match_all("@name=\"(.*)\">@U",$tmpResult,$tmpDetail);	
							$tmpAsinID = $tmpDetail[1][0];
				
							if(trim($tmpAsinID) <> ""){
								break;
							}	
												
					}	
					
							
					// ----- TITLE
					preg_match_all("@class=\"lrg bold\">(.*)<\/span>@U",$tmpResult,$tmpDetail);	
					$tmpTitle = $tmpDetail[1][0];
				
					if(trim($tmpTitle) == ""){
						preg_match_all("@class=\"lrg bold\" title=\"(.*)\">@U",$tmpResult,$tmpDetail);	
						$tmpTitle = $tmpDetail[1][0];
					}
					
						// -------- TITLE FOR BOOK
						if(trim($tmpTitle) == ""){
							preg_match_all("@class=\"title\" href=\"(.*)<\/a>@U",$tmpResult,$tmpDetail);	
							$tmpTitle1 = $tmpDetail[1][0];
							$tmpTitle2 = explode("\">",$tmpTitle1);
							$tmpTitle = str_replace("<span title=\"","",$tmpTitle2[1]);						
						}
		
		
					$tmpTitle = str_replace("&amp;","&",$tmpTitle);	
					$tmpTitle = str_replace("&quot;","\"",$tmpTitle);				
					$tmpTitle = str_replace("&reg;","",$tmpTitle);
					$tmpTitle = str_replace(",","",$tmpTitle);	
					
					
					

					
					preg_match_all("@<img  src=\"(.*)\"@U",$tmpResult,$tmpDetail);	
					$tmpImageURL = $tmpDetail[1][0];
					
					if(trim($tmpImageURL) == ""){
						preg_match_all("@src=\"(.*)\"@U",$tmpResult,$tmpDetail);	
						$tmpImageURL = $tmpDetail[1][0];
					}
					
					
					$tmpDetail2 = explode("._",$tmpImageURL);
					$tmpImageURL = $tmpDetail2[0] . ".jpg" ;
					
					$tmpImageURL = str_replace(",","",$tmpImageURL);
				
					
				*/
					
					
					
					
					
					// ------- IMAGE URL
					// .com http://images.amazon.com/images/P/0971633894.01._PE99_SCLZZZZZZZ_.jpg
					///$tmpImageURL = "http://ecx.images-amazon.com/images/I/" . $tmpAsinID . ".01._PE100_SCLZZZZZZZ_.jpg";
					
					
					// ------- PRICE
					//preg_match_all("@<span class=\"bld lrg red\">(.*)</span>@U",$tmpResult,$tmpDetail);	
					//$tmpPrice = $tmpDetail[1][0];	
					//$tmpPrice = str_replace("$","",$tmpPrice);		
					
		
		/*
		
					// ------- OFFER PRICE
					preg_match_all("@red\">(.*)</span>@U",$tmpResult,$tmpDetail);	
					$tmpOfferPrice = $tmpDetail[1][0];	
					$tmpOfferPrice = str_replace("$","",$tmpOfferPrice);
					$tmpOfferPrice = str_replace(",","",$tmpOfferPrice);		
						
						// -------- OFFER PRICE FOR BOOK
						if(trim($tmpOfferPrice) == ""){
							preg_match_all("@class=\"toeOurPrice\">(.*)<\/td>@U",$tmpResult,$tmpDetail);	
							$tmpOfferPrice1 = $tmpDetail[1][0];
							$tmpOfferPrice2 = explode("\">",$tmpOfferPrice1);
							$tmpOfferPrice = str_replace("$","",$tmpOfferPrice2[1]);	
							$tmpOfferPrice = str_replace("</a>","",$tmpOfferPrice);	
							$tmpOfferPrice = str_replace(",","",$tmpOfferPrice);	
						}
		
		
						// -------- PRICE
						if(trim($tmpOfferPrice) == ""){
							preg_match_all("@Buy<\/a>(.*)<\/span>@U",$tmpResult,$tmpDetail);	
							$tmpOfferPrice1 = $tmpDetail[1][0];
							$tmpOfferPrice2 = explode("price\">&nbsp;",$tmpOfferPrice1);
							//$tmpOfferPrice = str_replace("$","",$tmpOfferPrice1);	
							//$tmpOfferPrice = str_replace("&nbsp;","",$tmpOfferPrice);	
							
							$tmpOfferPrice = str_replace("$","",$tmpOfferPrice2[1]);	
							$tmpOfferPrice = str_replace(",","",$tmpOfferPrice);	
							//$tmpOfferPrice = str_replace("</a>","",$tmpOfferPrice);	
						}
		
			
		
		
					// ------- LIST PRICE
					preg_match_all("@class=\"grey\">(.*)</del>@U",$tmpResult,$tmpDetail);	
					$tmpListPrice = $tmpDetail[1][0];		
					$tmpListPrice = str_replace("$","",$tmpListPrice);	
					$tmpListPrice = str_replace(",","",$tmpListPrice);		
		
						// -------- LIST PRICE FOR BOOK
						//$checkListPrice = strpos($tmpResult,'strike');
						if(trim($tmpListPrice) == "" ){
							preg_match_all("@class=\"toeListPrice\">(.*)<\/strike>@U",$tmpResult,$tmpDetail);						
								$tmpListPrice1 = $tmpDetail[1][0];					
								$tmpListPrice2 = explode(">",$tmpListPrice1);
								$checkListPrice = strpos($tmpListPrice2[1],'$');
								if($checkListPrice !== false){
									$tmpListPrice = str_replace("$","",$tmpListPrice2[1]);	
									//$tmpListPrice = $checkListPrice;
								}
								
								$tmpListPrice = str_replace(",","",$tmpListPrice);		
							//}else{
								//$tmpListPrice = "";
							//}
							//$tmpListPrice = $checkListPrice;
							//$tmpOfferPrice = str_replace("</a>","",$tmpOfferPrice);	
						}
					
		
		
					// ------- RATING
					preg_match_all("@<a alt=\"(.*)out of@U",$tmpResult,$tmpDetail);	
					$tmpRating = $tmpDetail[1][0];	
					$tmpRating = str_replace(",","",$tmpRating);	
		
		
					// ------- REVIEW URL
					preg_match_all("@rvwCnt\">\(<a href=\"(.*)\">@U",$tmpResult,$tmpDetail);	
					$tmpReviewURL = $tmpDetail[1][0];	
					$tmpReviewURL = str_replace(",","",$tmpReviewURL);	
		
						// -------- REVIEW URL FOR BOOK
						if(trim($tmpReviewURL) == ""){
							preg_match_all("@class=\"longReview\" href=\"(.*) customer@U",$tmpResult,$tmpDetail);	
							$tmpReviewURL1 = $tmpDetail[1][0];
							$tmpReviewURL2 = explode("\">",$tmpReviewURL1);
							$tmpReviewURL = $tmpReviewURL2[0];	
							$tmpReviewURL = str_replace(",","",$tmpReviewURL);	
							//$tmpOfferPrice = str_replace("</a>","",$tmpOfferPrice);	
						}
		
		
					// ------- TOTAL REVIEW 
					preg_match_all("@<span class=\"rvwCnt\">(.*)<\/a>@U",$tmpResult,$tmpDetail);	
					$tmpDetail2 = $tmpDetail[1][0];
					$tmpDetail3 = explode("\">",$tmpDetail2);			
					$tmpTotalReview = $tmpDetail3[1];												
		
						// -------- TOTAL REVIEW URL FOR BOOK
						if(trim($tmpTotalReview) == ""){
							$tmpTotalReview = $tmpReviewURL2[1];	
						}
		
						// -------- TOTAL REVIEW URL FOR BOOK
						if(trim($tmpTotalReview) == ""){
							preg_match_all("@showViewpoints\=1\">['0-9']*<\/a>@U",$tmpResult,$tmpDetail);	
							$tmpTotalReview1 = $tmpDetail[0][0];
							$tmpTotalReview2 = explode("\">",$tmpTotalReview1);
							$tmpTotalReview = $tmpTotalReview2[1];
							$tmpTotalReview = str_replace("</a>","",$tmpTotalReview);	
						}				
		
		
		
		
					// ------- NEW PRICE
					preg_match_all("@class=\"price bld\">(.*)</span> new@U",$tmpResult,$tmpDetail);	
					$tmpNewPrice = $tmpDetail[1][0];		
					$tmpNewPrice = str_replace("$","",$tmpNewPrice);		
					$tmpNewPrice = str_replace(",","",$tmpNewPrice);					
						
						// -------- NEW PRICE FOR BOOK
						if(trim($tmpNewPrice) == ""){
							preg_match_all("@class=\"toeNewPrice\">(.*)<\/td>@U",$tmpResult,$tmpDetail);	
							$tmpNewPrice1 = $tmpDetail[1][0];
							$tmpNewPrice2 = explode("\">",$tmpNewPrice1);
							$tmpNewPrice = str_replace("$","",$tmpNewPrice2[1]);	
							$tmpNewPrice = str_replace("</a>","",$tmpNewPrice);	
							$tmpNewPrice = str_replace(",","",$tmpNewPrice);	
						}
					
		
					// ------- NEW URL
					$tmpCheckNewURL = "";
					$tmpNewURL = "";
					preg_match_all("@mkp2\">(.*)condition=new\">@U",$tmpResult,$tmpDetail);				
					$tmpCheckNewURL = $tmpDetail[0][0];		
					preg_match_all("@<a href=\"(.*)\">@U",$tmpCheckNewURL,$tmpDetail);	
					$tmpNewURL =	$tmpDetail[1][0] ;	
					$tmpNewURL = str_replace(",","",$tmpNewURL);	
					
							if(trim($tmpNewURL) == "" ){
								preg_match_all("@mkp\">(.*)\">@U",$tmpResult,$tmpDetail);				
								$tmpCheckNewURL = $tmpDetail[0][0];		
								preg_match_all("@<a href=\"(.*)\">@U",$tmpCheckNewURL,$tmpDetail);	
								$tmpNewURL =	$tmpDetail[1][0] ;
								$tmpNewURL = str_replace(",","",$tmpNewURL);					
							}
			
							// -------- NEW URL FOR BOOK
							if(trim($tmpNewURL) == "" && trim($tmpNewPrice) <> ""){
								$tmpNewURL = $tmpNewPrice2[0];
								$tmpNewURL = str_replace("<a href=\"","",$tmpNewURL);	
								$tmpNewURL = str_replace(",","",$tmpNewURL);	
							}
		
		
		
		
					// ======== NEW OFFER
					$tmpNewOffer = "";
					if(trim($tmpNewURL)  <> ""){
						preg_match_all("@new <span(.*)offer@U",$tmpResult,$tmpDetail);	
						$tmpCheckNewOffer = $tmpDetail[1][0];
						preg_match_all("@class=\"grey\">\((.*) @U",$tmpCheckNewOffer,$tmpDetail);	
						$tmpNewOffer =	$tmpDetail[1][0] ;	
						$tmpNewOffer = str_replace(",","",$tmpNewOffer);	
					}
		
				
					// ------- USE PRICE
					preg_match_all("@used\"><span class=\"price bld\">(.*)</span> used@U",$tmpResult,$tmpDetail);	
					$tmpUsedPrice = $tmpDetail[1][0];		
					$tmpUsedPrice = str_replace("$","",$tmpUsedPrice);	
					$tmpUsedPrice = str_replace(",","",$tmpUsedPrice);		
							
						// -------- USED PRICE FOR BOOK
						if(trim($tmpUsedPrice) == ""){
							preg_match_all("@class=\"toeUsedPrice\">(.*)<\/td>@U",$tmpResult,$tmpDetail);	
							$tmpUsedPrice1 = $tmpDetail[1][0];
							$tmpUsedPrice2 = explode("\">",$tmpUsedPrice1);
							$tmpUsedPrice = str_replace("$","",$tmpUsedPrice2[1]);	
							$tmpUsedPrice = str_replace("</a>","",$tmpUsedPrice);	
							$tmpUsedPrice = str_replace(",","",$tmpUsedPrice);		
						}
		
					
					// ------- USED URL
					$tmpCheckUsedURL = "";
					$tmpUsedURL = "";
					preg_match_all("@mkp2\">(.*)condition=used\">@U",$tmpResult,$tmpDetail);		
					$tmpCheckUsedURL = $tmpDetail[0][0];		
					preg_match_all("@med grey mkp2\">         <a href=\"(.*)\">@U",$tmpCheckUsedURL,$tmpDetail);	
					$tmpUsedURL =	$tmpDetail[1][0] ;	
					$tmpUsedURL = str_replace(",","",$tmpUsedURL);	
		
						// -------- USED URL FOR BOOK
						if(trim($tmpUsedURL) == "" && trim($tmpUsedPrice) <> ""){
							$tmpUsedURL = $tmpUsedPrice2[0];
							$tmpUsedURL = str_replace("<a href=\"","",$tmpUsedURL);	
							$tmpUsedURL = str_replace(",","",$tmpUsedURL);	
						}
									
			
					// ======== USED OFFER
					$tmpCheckUsedOffer = "";
					$tmpUsedOffer = "";
					if(trim($tmpNewURL)  <> ""){
						preg_match_all("@used <span(.*)offer@U",$tmpResult,$tmpDetail);	
						$tmpCheckUsedOffer = $tmpDetail[1][0];
						preg_match_all("@class=\"grey\">\((.*) @U",$tmpCheckUsedOffer,$tmpDetail);	
						$tmpUsedOffer =	$tmpDetail[1][0] ;	
						$tmpUsedOffer = str_replace(",","",$tmpUsedOffer);	
					}				
			
			
			*/		
			
			/*		
					if($tmpAsinID <> ""){
						$tmpMainData .= trim($tmpAsinID) . "," . trim($tmpTitle) . "," . trim($tmpImageURL)  . "," . trim($tmpRating) . "," . trim($tmpListPrice) . "," . trim($tmpOfferPrice) .  "," . trim($tmpTotalReview)  . "," . trim($tmpReviewURL)  . "," . trim($tmpNewPrice) . "," .  trim($tmpNewOffer) . "," . trim($tmpNewURL)   . "," . trim($tmpUsedPrice) . "," .  trim($tmpUsedOffer) . "," . trim($tmpUsedURL)    . "\n";										
						$tmpMainCount++;		
					}					
			*/		
		
					
		
		}

}

$tmpNextPage = $tmpPage + 1;
echo $tmpMainData . "|||" . $tmpMainCount . "|||" . $tmpNextPage . "|||" . $tmpLimitAsin  . "|||" . $tmpResultPage . "|||" . $tmpCategoryName . "|||" . $tmpMaxPage . "|||" .  $tmpFinish ;


?>