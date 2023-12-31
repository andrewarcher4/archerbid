<?php


//instruct the PHP script to allow a request from ANY domain by using the wildcard
header('Access-Control-Allow-Origin:*');

//picking up parameters from post
//$id_auction=$_REQUEST["id_auction"];
session_start();
$id_auction=$_SESSION["auctionID"];

$data = array();

//Define database connection parameters
    $hn = 'efastdbs.mysql.database.azure.com';
    $un = 'efast@efastdbs'; //username of database here
    $pwd = 'Gv3-LST-nZU-JyP'; //password for database here
    $db = 'efast_main'; //name for database here
    $cs = 'utf8';

//Set up the PDO parameters
$dsn = "mysql:host=" . $hn . ";port=3306;dbname=" . $db . ";charset=" . $cs;
$opt = array(
    PDO::ATTR_ERRMODE   =>  PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE    =>  PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES  =>  false,
);

//Create a PDO instance (connect to the database)
$pdo = new PDO($dsn, $un, $pwd, $opt);


//Attempt to query tests table and retrieve set of text files associated with tests_id
try{
    //$stmt = $pdo->query('SELECT PRICE, UNIX_TIMESTAMP(TIME) AS TIME FROM bid WHERE ID_AUCTION = \'' .$id_auction. '\' ORDER BY PRICE ASC');
    $query = 'SELECT PRICE, UNIX_TIMESTAMP(TIME) AS TIME FROM bid WHERE ID_AUCTION = ? ORDER BY PRICE ASC';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1,$id_auction, PDO::PARAM_STR);
    $stmt->execute();


    while($row = $stmt -> fetch(PDO::FETCH_OBJ))
    {
        //Assign each row of data to associative array
        $data[] = $row;
    }

    echo json_encode($data);

}
catch(PDOException $e)
{
    echo $e -> getMessage();
}


?>
