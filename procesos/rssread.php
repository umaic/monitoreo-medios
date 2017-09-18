<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//require('../config/db.php');

$mysql_host='localhost';
$mysql_usr='monitor';
$mysql_pass='Myp4$M0';
$mysql_db='monitormedios';

$connsqli = new mysqli($mysql_host, $mysql_usr, $mysql_pass, $mysql_db);

$debug=1; /// debug for watch alert´s {1 on 0 off }
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
$sqlsrc="SELECT src_url, spa_page, spa_id, spa_src_id, spa_v_node, spa_v_title, spa_v_description, spa_v_link, spa_v_pubDate FROM src_sources inner join spa_srcpage on src_id=spa_src_id where spa_sta_id=1 and spa_src_id=11";

echo $slsrc;

$querysrc = mysqli_query($connsqli,$sqlsrc);

  $keyw="";
//begin read active`s sources
	while ($rowsrc = mysqli_fetch_array($querysrc)){
		
		///load var of sources
	$src_id	=$rowsrc['spa_src_id'];
	$urload= $rowsrc['src_url'].$rowsrc['spa_page'];
	
if($debug==1) echo $urload."</br>";
 
////get data of RSS  
$doc = new DOMDocument();
$doc->load($urload);
$arrFeeds = array();///array for content RSS
foreach ($doc->getElementsByTagName('item') as $node) {

///get data of XML and put in ARRAY $arrFeeds
$itemRSS = array ( 
'title' => strip_tags($node->getElementsByTagName('title')->item(0)->nodeValue),
'description' => strip_tags($node->getElementsByTagName('description')->item(0)->nodeValue),
'link' => strip_tags($node->getElementsByTagName('link')->item(0)->nodeValue),
'pubDate' =>strip_tags($node->getElementsByTagName('pubDate')->item(0)->nodeValue),
);
array_push($arrFeeds, $itemRSS);
}
 
 ///SQL for get keywords in DB
$sqlkw="SELECT kew_words FROM kew_keywords";
 
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

	while ($row = mysqli_fetch_array($query)){
		
	$keyw= $row['kew_words'];

	}
	
	$arrkw=explode(",",$keyw);
  // Free result set
 // mysqli_free_result($result);


// cont news
$cont=0;
 
// iterate xml array.

foreach ($arrFeeds as $key => $value) {

$title=sanitize($value['title']);
$description=sanitize($value['description']);

	$arrti=explode(" ",$title); //convert text to array by space
	$arrco=explode(" ",$description); ////

array_push($arrti, $arrco); /// concat arrays of xml

$resarr = array_intersect($arrkw, $arrti); //compare arrays
if(count($resarr)>0 || 1==1){///find keywords

	if($debug==1){	
print_r($result);
echo '</br>'.$cont.'-'.$value['title'].'-'.$value['description'].'-'.$value['link'].'-'.$value['pubDate'].'</br>';	
$cont++;	
}


////validate existing news in DB
$sqlkw="SELECT new_id FROM new_news where trim((UPPER(new_title)))='".trim(strtoupper($title))."'";

echo $sqlkw;
$query = mysqli_query($connsqli,$sqlkw);

  $sure=0;
while ($row = mysqli_fetch_array($query)){
		$sure=1;	
	}
///if already news not insert again

if($sure==0){
	$sqlins="INSERT INTO new_news (new_title, new_description, new_link, new_pubdate, new_insdate, new_src_id) VALUES ('".$title."','".$description."','".$value['link']."','".$value['pubDate']."',now(),".$src_id.")";
	if($debug==1) echo $sqlins."</br>";
	mysqli_query($connsqli,$sqlins);
}//end if

}//only if have keyword
	


}
///end iterate xml array



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
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}
?>