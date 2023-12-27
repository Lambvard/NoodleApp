<?php 
$db_name="192.168.17.7";
$db_array=array("Database"=>"seasoning_scrap","UID"=>"sas","PWD"=>"Lambvard01###");
$db_conn_source=sqlsrv_connect($db_name,$db_array);


$db_name="192.168.17.217";
$db_array=array("Database"=>"seasoning_scrap","UID"=>"sa","PWD"=>"Lambvard01###");
$db_conn_destination=sqlsrv_connect($db_name,$db_array);



$get_from=sqlsrv_query($db_conn_source,"SELECT * FROM seasoning_scrap.dbo.transactionlog");
while ($get_from_f=sqlsrv_fetch_array($get_from,SQLSRV_FETCH_ASSOC)) {
	//echo $get_from_f['readingvalues'];

$saveScrapData="INSERT INTO dbo.transactionlog (materials,Dater,Timer,Shift,readingvalues,track_date,track_id) VALUES (?,?,?,?,?,?,?)";
$ScrapQuery=sqlsrv_prepare($db_conn_destination,$saveScrapData,array($get_from_f['materials'],$get_from_f['Dater'],$get_from_f['Timer'],$get_from_f['Shift'],$get_from_f['readingvalues'],$get_from_f['track_date'],$get_from_f['track_id']));
sqlsrv_execute($ScrapQuery);

}




/*

$db_name="192.168.17.217";
$db_array=array("Database"=>"seasoning_scrap","UID"=>"sa","PWD"=>"Lambvard01###");
$db_conn_destination=sqlsrv_connect($db_name,$db_array);


*/

































?>