<?php
header("Access-Control-Allow-Origin: *");

$ubase='http://localhost:8888/v2';
$mysql_host='localhost';
$mysql_usr='root';
$mysql_pass='';
$mysql_db='news_monitor';

header('Content-type: application/json; charset=utf8');///format json

$arrerror=array();  ///content list errors
$arrinfo=array();  ///content info about process
$resjson=array(); ///content response Json
$arr=array();

  $connsqli = new mysqli($mysql_host, $mysql_usr, $mysql_pass, $mysql_db);
  
$var=json_decode($_GET['var']);  
  
//echo $var->username; 
  
  

  
  ////verify connection, if error condition
    if ($connsqli->connect_errno ) {

        $infoarr[]= array("ConnSql" => $connsqli->connect_error);

    } else {

////if connected and not error

///chartset
        /* utf8 */
        if (!$connsqli->set_charset("utf8")) {
            $errorarr[]= array("Error utf8" => $connsqli->error);
            //exit();
        } else {

            $infoarr[]= array("CharSet" => $connsqli->character_set_name());
        }
  
     		
		$sql = "SELECT usr_id, usr_token from usr_user where usr_name='".$var->username."' and usr_password='".$var->password."'";;
		//echo $sql;
		

		$querysrc = mysqli_query($connsqli,$sql);

			$resjson=["id"=>0,"token"=>''];
		
			while ($rowsrc = mysqli_fetch_array($querysrc)){
		
	$id	=$rowsrc['usr_id'];
	$token= $rowsrc['usr_token'];
	$resjson=["id"=>$id,"token"=>'test-token-user'.$token];
				}
		
   //// return json
echo json_encode($resjson, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}
?>