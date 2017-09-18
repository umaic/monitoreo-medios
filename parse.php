<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//require('../config/db.php');
$mysql_host='localhost';
$mysql_usr='monitor';
$mysql_pass='Myp4$M0';
$mysql_db='monitormedios';

$connsqli = new mysqli($mysql_host, $mysql_usr, $mysql_pass, $mysql_db);

$debug=0; /// debug for watch alert´s {1 on 0 off }
 /// array´s for search key words
$arrkw[]=array();
$arrti[]=array();
$arrco[]=array();
///

////verify connection, if error condition
  if($connsqli->connect_errno)
  {
	  
   if($debug==1)  printf("Error conn: %s\n", $mysqli->connect_error);
    exit();
	
  }
  ///chartset
  /* utf8 */
if (!$connsqli->set_charset("utf8")) {
   if($debug==1) printf("Error utf8: %s\n", $connsqli->error);
    exit();
} else {
    //printf("actual: %s\n", $connsqli->character_set_name());
}
  
////read sources
$sqlsrc="SELECT src.url, page.page, page.id, page.src_id, page.typ_id , page.node, page.title, page.description, page.link, page.pubDate FROM sources as src inner join srcpages as page on src.id=page.src_id where page.sta_id=1 and page.typ_id in (1,2)";// and spa_src_id=10";

if($debug==1) echo $sqlsrc."</br>";

$querysrc = mysqli_query($connsqli,$sqlsrc);

  $keyw="";
//begin read active`s sources
	while ($rowsrc = mysqli_fetch_array($querysrc)){
		
		///load var of sources
	$typ_id	=$rowsrc['typ_id'];	
	$src_id	=$rowsrc['src_id'];
	$urload= $rowsrc['url'].$rowsrc['page'];
	$spa_id = $rowsrc['id'];
	$node=$rowsrc['node'];
	$title=$rowsrc['title'];
	$description=$rowsrc['description'];
	$link=$rowsrc['link'];
	$pubDate=$rowsrc['pubDate'];
	
	
if($debug==1) echo $urload."</br>";
 
 $arrFeeds = array();///array for content and RSS
 
 
 ///////////////////////
# Use the Curl extension to query Google and get back a page of results

$url = $urload;
$ch = curl_init();
$timeout = 15;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSLVERSION, 4);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);


/*
        $curl = curl_init((string) $url);

        curl_setopt($curl, CURLOPT_SSLVERSION, 4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
*/


$page = curl_exec($ch);

if(curl_errno($ch)) // check for execution errors
{
    echo 'Scraper error: ' . curl_error($ch);
   continue;// exit;
}
curl_close($ch);

# Create a DOM parser object
$DOM = new DOMDocument();
# Parse the HTML from url.
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
libxml_use_internal_errors(true);
  
 
 switch($typ_id){
	 
case 1:
////get data of RSS  
$DOM = new DOMDocument();
//$doc->load($urload);
$DOM->loadxml($page);
/*

if (!$DOM->loadxml($page,LIBXML_PARSEHUGE))
	{
		$errors="";
	    foreach (libxml_get_errors() as $error)  {
			//$errors.=$error->message."<br/>"; 
		}
		
	
		libxml_clear_errors();
		print "libxml errors:<br>";//.$errors;
	
		//return;
		  
	}
*/

foreach ($DOM->getElementsByTagName($node) as $node) {

///get data of XML and put in ARRAY $arrFeeds
$itemRSS = array ( 
'title' => strip_tags($node->getElementsByTagName($title)->item(0)->nodeValue),
'description' => strip_tags($node->getElementsByTagName($description)->item(0)->nodeValue),
'link' =>strip_tags($node->getElementsByTagName($link)->item(0)->nodeValue),
'pubDate' =>strip_tags($node->getElementsByTagName($pubDate)->item(0)->nodeValue),
);
array_push($arrFeeds, $itemRSS);
}


break;

case 2:
///////////////////////

if (!$DOM->loadHTML($page))
	{
		$errors="";
	    foreach (libxml_get_errors() as $error)  {
			$errors.=$error->message."<br/>"; 
		}
		libxml_clear_errors();
		print "libxml errors:<br>".$errors;
		return;
	}

$xpath = new DOMXPath($DOM);

$tagsl = $xpath->query($node.$link);
$tagst = $xpath->query($node.$title);
$tagsc = $xpath->query($node.$description);
$tagsd = $xpath->query($node.$pubDate);

//rint_r($tagsc);

$ll= $tagsl->length;
$tl= $tagst->length;
$cl= $tagsc->length;
$dl= $tagsd->length;

//echo "largo:".$ll."</br>";

$values = array((int)$ll, 
                (int)$tl, 
				(int)$cl, 
				(int)$dl);

if(count(array_unique($values)) === 1 && $ll>0){

$results = array();

 //echo "ver:".$tagsc[5]->nodeValue."</br>";
for($i=0; $i<$ll; $i++) {
 
 //echo $i."ver:".$tagst[$i]->nodeValue."</br>";
  ///get data of XML and put in ARRAY $arrFeeds
$itemRSS = array ( 
'title' => strip_tags($tagst[i]->nodeValue),
'description' => strip_tags($tagsc[i]->nodeValue),
'link' =>strip_tags($tagsl[i]->nodeValue),
'pubDate' =>strip_tags($tagsd[i]->nodeValue),
);
array_push($arrFeeds, $itemRSS);
}


}else{
	
//return some error	
}

///////////////////////

break;


 }

 
 if(sizeof($arrFeeds)>0){
 
 
 ///SQL for get keywords in DB
$sqlkw="SELECT keywords FROM categories";
 
  /// read data set info sql
//if ($result=$connsqli->query($sqlkw))
 // {
  // Get field information for all fields
 // $fieldinfo=$result->fetch_fields();
  
// foreach ($fieldinfo as $val)
 //   {	
 //   $keyw=$val->name;
//    }

//}
////end read data set content sql

$query = mysqli_query($connsqli,$sqlkw);

  $keyw="";
$arrkw[]=array();
	while ($row = mysqli_fetch_array($query)){
		
	$keyw.= $row['keywords'];
	
	}
	//echo $keyw;
	$arrkw=explode(",",$keyw);
 	
	//print_r($arrkw);
	//$arrkw=explode(",",$keyw);
  // Free result set
 // mysqli_free_result($result);


// cont news
$cont=0;
 
// iterate xml array.

foreach ($arrFeeds as $key => $value) {

$title=sanitize($value['title']);
$description=sanitize($value['description']);

if(trim($description)=="") $description="No proporcionada por la fuente, ver en link";

	$arrti=explode(" ",$title); //convert text to array by space
//	$arrco=explode(" ",$description); ////

//array_push($arrti, $arrco); /// concat arrays of xml


$resarr = array_intersect($arrti, $arrkw); //compare arrays

if(count($resarr)>0){///find keywords

$listkeyw=implode(",", $resarr);

	if($debug==1){	
print_r($result);
echo '</br>'.$cont.'-'.$value['title'].'-'.$value['description'].'-'.$value['link'].'-'.$value['pubDate'].'-'.$listkeyw.'</br>';	
$cont++;	
}


////validate existing news in DB
$sqlkw="SELECT id FROM news where trim((UPPER(title)))='".trim(strtoupper($title))."'";


$query = mysqli_query($connsqli,$sqlkw);

  $sure=0;
while ($row = mysqli_fetch_array($query)){
		$sure=1;	
	}
///if already news not insert again

if($sure==0){
	
	$sqlins="INSERT INTO news (title, description, link, pubdate, insdate, spa_id, keywords) VALUES ('".$title."','".$description."','".$value['link']."','".$value['pubDate']."',now(),".$spa_id.",'".$listkeyw."')";
	
	if($debug==1){ 
	echo $sqlins."</br>";
	mysqli_query($connsqli,$sqlins);
	}else{
    mysqli_query($connsqli,$sqlins);
	
	}
}//end if

}//only if have keyword
	
}
///end iterate xml array

	}

	} //// end read sources
	
mysqli_close($connsqli);//close connection

function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // delete javascript
    '@<[\/\!]*?[^<>]*?>@si',            // delete HTML
    '@<style[^>]*?>.*?</style>@siU',    // delete style
    '@<![\s\S]*?--[ \t\n\r]*>@'         // delete comments multy-líne
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }
 
 
function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $output  = cleanInput($input);
        //$output = mysql_real_escape_string($input);
    }
    return $output;
}
 



?>