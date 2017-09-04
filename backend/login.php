<?php
header("Access-Control-Allow-Origin: *");

$ubase='http://localhost/v2';
$mysql_host='localhost';
$mysql_usr='root';
$mysql_pass='';
$mysql_db='monitormedios';

header('Content-type: application/json; charset=utf8');///format json

$arrerror=array();  ///content list errors
$arrinfo=array();  ///content info about process
$resjson=array(); ///content response Json
$arr=array();

  $connsqli = new mysqli($mysql_host, $mysql_usr, $mysql_pass, $mysql_db);
  
//$var=json_decode($_GET['var']);  
  
//echo $var->username; 
  
  

  
  ////verify connection, if error condition
    if ($connsqli->connect_errno ) {

       

    } else {

////if connected and not error

///chartset
        /* utf8 */
        if (!$connsqli->set_charset('utf8')) {
          
            //exit();
        } else {

            
        }
  
     		
		$sql = "SELECT id, token from user where name='".$_GET['username']."' and token='".$_GET['password']."'";
	//	echo $sql;
		

		$querysrc = mysqli_query($connsqli,$sql);

			$resjson=['id'=>0,'token'=>''];
		
			while ($rowsrc = mysqli_fetch_array($querysrc)){
		
	$id	=$rowsrc['id'];
	$token= $rowsrc['token'];
	$resjson=['id'=>$id,'token'=>'test-token-user'.$token];
	
				}
	
echo json_encode($resjson);
	}
?>