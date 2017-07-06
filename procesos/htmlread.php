<?php


# Use the Curl extension to query Google and get back a page of results

//$url = "http://diariodelsur.com.co/noticias/nacional";

$url = "https://www.google.com/search?q=venezuela+location%3Acolombia&lr=lang_es&hl=es&gl=co&as_drrb=b&authuser=0&biw=1440&bih=834&source=lnt&tbs=lr%3Alang_1es%2Ccdr%3A1%2Ccd_min%3A1%2F1%2F2015%2Ccd_max%3A12%2F31%2F2015&tbm=nws#q=venezuela+-futbol+location:colombia&lr=lang_es&hl=es&gl=co&as_drrb=b&authuser=0&tbs=lr:lang_1es,cdr:1,cd_min:1/1/2015,cd_max:12/31/2015&tbm=nws&start=0";


/*
  $Query = "Egypt";
   $Month = "3";
   $FromDay = "2";
   $ToDay = "4";
   $Year = "15";
   $Month2 = "3";
   $FromDay2 = "2";
   $ToDay2 = "25";
   $Year2 = "15";
   $url='https://www.google.com/search?pz=1&cf=all&ned=us&hl=en&tbm=nws&gl=us&as_q='.$Query.'&as_occt=any&as_drrb=b&as_mindate='.$Month.'%2F'.$FromDay.'%2F'.$Year.'&as_maxdate='.$Month2.'%2F'.$ToDay2.'%2F'.$Year2.'&tbs=cdr%3A1%2Ccd_min%3A3%2F1%2F13%2Ccd_max%3A3%2F2%2F13&as_nsrc=Gulf%20Times&authuser=0';
   */
   
   echo $url."</br>";


$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);

$page = curl_exec($ch);
if(curl_errno($ch)) // check for execution errors
{
    echo 'Scraper error: ' . curl_error($curl);
    exit;
}
curl_close($ch);

# Create a DOM parser object
$DOM = new DOMDocument();
# Parse the HTML from url.
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
libxml_use_internal_errors(true);

if (!$DOM->loadHTML($page))
	{
		$errors="";
	    foreach (libxml_get_errors() as $error)  {
			$errors.=$error->message."<br/>"; 
		}
		libxml_clear_errors();
		print "libxml errors:<br>$errors";
		return;
	}

$xpath = new DOMXPath($DOM);

/*
$tagsl = $xpath->query('//div[@class="node node-article node-reviewed clearfix t-mode-search_result"]/div[@class="content"]/div[@class="field field-title"]/div[@class="field-items"]/div[@class="field-item"]/h2/a/attribute::href');
$tagst = $xpath->query('//div[@class="node node-article node-reviewed clearfix t-mode-search_result"]/div[@class="content"]/div[@class="field field-title"]');
$tagsc= $xpath->query('//div[@class="node node-article node-reviewed clearfix t-mode-search_result"]/div[@class="content"]/div[@class="field field-name-body field-type-text-with-summary field-label-hidden"]');
$tagsd = $xpath->query('//div[@class="node node-article node-reviewed clearfix t-mode-search_result"]/div[@class="content"]/div[@class="field field-datetime"]');
*/


//$tagsl = $xpath->query('//div[@class="g"]/div[@class="ts _V6c _anc _XO _knc _d7c"]/div[@class="_cnc"]/h3/a/attribute::href');
$tagsl = $xpath->query('//div[@class="slp"]');

$tagst = $xpath->query('//div[@class="g"]/div[@class="ts _V6c _anc _XO _knc _d7c"]/div[@class="_cnc"]/h3/a');
$tagsc = $xpath->query('//div[@class="g"]/div[@class="ts _V6c _anc _XO _knc _d7c"]/div[@class="_cnc"]/div[@class="st"]');
$tagsd = $xpath->query('//div[@class="g"]/div[@class="ts _V6c _anc _XO _knc _d7c"]/div[@class="_cnc"]/div[@class="slp"]');


print_r($tagsl);
$ll= $tagsl->length;

for($i=0; $i<$ll; $i++) {
 
 echo $i." ver: ".$tagsl[$i]->nodeValue."</br>";


}


/*
echo $tagsl->length."</br>";
echo $tagst->length."</br>";
echo $tagsc->length."</br>";
echo $tagsd->length."</br>";
*/

 $arrFeeds = array();///array for content and RSS

$ll= $tagsl->length;
$tl= $tagst->length;
$cl= $tagsc->length;
$dl= $tagsd->length;

$values = array((int)$ll, 
                (int)$tl, 
				(int)$cl, 
				(int)$dl);

if(count(array_unique($values)) === 1 && $ll>0){

//echo $tagsl[1]->nodeValue."</br>";
$results = array();
$cont=0;

foreach ($tagsl as $tag) {
   //echo print_r($tag);
  //echo $tag->getAttribute("href");  
  //echo $tag->nodeValue."</br>"; 
  
   $cont++;
  
}

for($i=0; $i<$ll; $i++) {
 
 echo $i." ver: ".$tagst[$i]->nodeValue."</br>";
  ///get data of XML and put in ARRAY $arrFeeds
$itemRSS = array ( 
'title' => strip_tags($tagst[$i]->nodeValue),
'description' => strip_tags($tagsc[$i]->nodeValue),
'link' =>strip_tags($tagsl[$i]->nodeValue),
'pubDate' =>strip_tags($tagsd[$i]->nodeValue),
);
array_push($arrFeeds, $itemRSS);
}



}else{
	
//return some error	
}




?>