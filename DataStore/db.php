<?PHP

$db_name="DUF-COR-TUNDE";
$db_array=array("Database"=>"distribution","UID"=>"sa","PWD"=>"Lambvard01###");
$db_conn=sqlsrv_connect($db_name,$db_array);
if(!$db_conn){
echo "Error Connecting";
}//else{
	//echo "Connected";

//}

?>