<?php
header("Access-Control-Allow-Origin: *");

$ubase='http://localhost:8888/monitor_medios';
$mysql_host='localhost';
$mysql_usr='root';
$mysql_pass='';
$mysql_db='news_monitor';

header('Content-type: application/json; charset=utf8');///format json

$resjson=array(); ///content response Json
$arr=array();

  $connsqli = new mysqli($mysql_host, $mysql_usr, $mysql_pass, $mysql_db);
  
  ////verify connection, if error condition
    if ($connsqli->connect_errno) {


    } else {

////if connected and not error

///chartset
        /* utf8 */
        if (!$connsqli->set_charset("utf8")) {
           
         
        } else {

            
        }
  
       $var=$_GET['var'];
       $arrv=  explode(" ",$var);
 
	$sql2="";
	$cint=0;
foreach ($arrv as $valor) {
	
	if($cint==0){ $sql2.=" new_description like '%".$valor."%'" ; }else{
		
	$sql2.=" or new_description like '%".$valor."%'" ; 	
	}
		
	}		 
	  
     		
		$sql = "SELECT new_id,new_title, new_description ,new_link , new_pubdate, new_keywords, src_name  from new_news inner join spa_srcpages on new_spa_id=spa_id inner join src_sources on spa_src_id=src_id where ".$sql2." order by new_id desc";
	
		
 $res = $connsqli->query($sql);
 $ressql[]= array("resp" => $res->fetch_all(MYSQLI_ASSOC));
                  
			$returnarr=array("result_sql"=>$ressql);
			$arr[]= array("return_sql" => $returnarr);
		    $resjson=["resp"=>$arr];
  
echo json_encode($resjson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}
?>