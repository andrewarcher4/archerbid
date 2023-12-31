<?php


//instruct the PHP script to allow a request from ANY domain by using the wildcard
header('Access-Control-Allow-Origin:*');

session_start();
$id_user = $_SESSION['userID'];
$id_auction=$_SESSION["auctionID"];


//picking up parameters from post
//$id_auction=$_REQUEST["id_auction"];
//$id_user=$_REQUEST["id_user"];

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

try{
    //$stmt = 'INSERT INTO traffic (ID_AUCTION, ID_USER, DATETIME) VALUES ( \'' . $id_auction . '\', \'' . $id_user . '\', Now() )';
    $query = 'INSERT INTO traffic (ID_AUCTION, ID_USER, DATETIME) VALUES ( ?, ?, Now() )';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1,$id_auction, PDO::PARAM_STR);
    $stmt->bindParam(2,$id_user, PDO::PARAM_STR);
    $stmt->execute();

}
catch(PDOException $e)
{
    echo $e -> getMessage();
}


//Attempt to query
try{
    //$stmt = $pdo->query('SELECT ite.PIC, ite.TITLE, ite.DESCRIPTION, auc.START_PRICE, UNIX_TIMESTAMP(auc.START_TIMESTAMP) AS START_TIMESTAMP, UNIX_TIMESTAMP(auc.EXPIRATION_TIME) AS EXPIRATION_TIME, cat.CATEGORY, sta.STATE FROM (((item ite
//INNER JOIN auction auc ON auc.ID_ITEM = ite.ID_ITEM)
//INNER JOIN category cat ON ite.ID_CATEGORY = cat.ID_CATEGORY)
//INNER JOIN state sta ON ite.ID_STATE = sta.ID_STATE)
//WHERE auc.ID_AUCTION = \'' .$id_auction. '\'');

    $query2 = 'SELECT
  ite.PIC,
  ite.TITLE,
  ite.DESCRIPTION,
  auc.START_PRICE,
  UNIX_TIMESTAMP(auc.START_TIMESTAMP) AS START_TIMESTAMP,
  UNIX_TIMESTAMP(auc.EXPIRATION_TIME) AS EXPIRATION_TIME,
  cat.CATEGORY,
  sta.STATE,
  auc.ID_SELLER,
  acc.FNAME,
  acc.LNAME
FROM ((((item ite
  INNER JOIN auction auc ON auc.ID_ITEM = ite.ID_ITEM)
  INNER JOIN category cat ON ite.ID_CATEGORY = cat.ID_CATEGORY)
  INNER JOIN state sta ON ite.ID_STATE = sta.ID_STATE)
  INNER JOIN user acc ON acc.ID_USER = auc.ID_SELLER)
WHERE auc.ID_AUCTION = ?';
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindParam(1,$id_auction, PDO::PARAM_STR);
    $stmt2->execute();

    while($row = $stmt2 -> fetch(PDO::FETCH_OBJ))
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
