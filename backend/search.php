<?php
header("Access-Control-Allow-Origin: *");

$mysql_host='localhost';
$mysql_usr='root';
$mysql_pass='';
$mysql_db='monitormedios';

//header('Content-type: application/json; charset=utf8');///format json

$resjson=array(); ///content response Json
$arr=array();
$arrreturn=array();
$resp=array();

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
		  $var="";
	  	   	if (isset($_GET['cat']) && $_GET['cat']>0) {
						$sqlc="SELECT keywords from categories where id=".$_GET['cat'];
		//echo $sqlc."</br>";
		$querysrc = mysqli_query($connsqli,$sqlc);	
						$listvar="";
						while ($rowsrcc = mysqli_fetch_array($querysrc)){
				$listvar=$rowsrcc['keywords'];
							}
			 if(trim($listvar)!="") $var=$listvar;
		}
	     if (isset($_GET['var']) && $_GET['var']!='') {
	        $var=$_GET['var'];
}
	     	   
       $arrv=  explode(",",$var);
 	$sql2=" 1=1 ";
	$cint=0;
	foreach ($arrv as $valor) {
		if($cint==0){ $sql2.=" and  ( news.description like '%".$valor."%'" ; $cint=1;}else{
			$sql2.=" or news.description like '%".$valor."%'" ; 	
	}
			}
	$sql2.=" )"; 
		$sql3="";
    if (isset($_GET['src']) && $_GET['src']>0) {
		$sql3=" and src_id=".$_GET['src'];	
		}
	  
    $sql="SELECT news.id,news.title, news.description ,news.link , news.pubdate, news.keywords, sources.name  from news inner join srcpages on spa_id=srcpages.id inner join sources on src_id=sources.id where ".$sql2." ".$sql3." order by news.id desc";
		//echo $sql."</br>";
		$querysrc = mysqli_query($connsqli,$sql);	
		while ($rowsrc = mysqli_fetch_array($querysrc)){
	
	$id	=$rowsrc['id'];
$titulo=mberegi_replace("[\n|\r|\n\r|\t||\x0B]", "",$rowsrc['title']);
$descripcion=mberegi_replace("[\n|\r|\n\r|\t||\x0B]", "",$rowsrc['description']);

	$nlink=$rowsrc['link'];
	$fecha=$rowsrc['pubdate'];
	$pclave=$rowsrc['keywords'];
	$resp[]=array('id'=>$id, 'titulo'=>$titulo,'descripcion'=>$descripcion, 'nlink'=>$nlink, 'fecha'=>$fecha,'pclave'=>$pclave);
					}
	$respjson=["resp"=>$resp, "key"=>$var];		

echo json_encode($respjson);
	}
?>