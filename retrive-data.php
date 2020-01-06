<?php
function connectDb(){
    try {
        $conn = new PDO("mysql:host=$servername;dbname=", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
        }
    catch(PDOException $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }
}


function getManufactureCount($manufacture){
    $con= connectDb();
    $result = $con->prepare("SELECT count(*) FROM Stock WHERE manufacture=:manufacture");
    $result ->execute(['manufacture'=> $manufacture]); 
    $number_of_rows = $result->fetchColumn(); 
    return $number_of_rows ;
}


function getCategoryCount($category){
    $con= connectDb();
    $result = $con->prepare("SELECT count(*) FROM Stock WHERE category=:category");
    $result ->execute(['category'=> $category]); 
    $number_of_rows = $result->fetchColumn(); 
    return $number_of_rows ;
}


function priceFilter($priceFrom,$priceTo){
    $con= connectDb();
    $result = $con->prepare("SELECT count(*) FROM Stock WHERE category=:category");
    $result ->execute(['category'=> $category]); 
    $number_of_rows = $result->fetchColumn(); 
    return $number_of_rows ;
}


function getStock(){
    $con= connectDb();
    $result = $con->prepare("SELECT DISTINCT Stock.fuel,Stock.engine_size,Stock.date_Added,Stock.colour, Stock.manufacture,Stock.mileage,Stock.vehicle_Name, Stock.regYear, Stock.fob_Price, Images.Stock_Id, image_name, image_path
                                        FROM Images
                                        INNER JOIN Stock ON Images.Stock_Id = Stock.Stock_Id
                                        GROUP BY Stock_Id  ORDER BY date_Added DESC LIMIT 12");
    $result ->execute(); 
    $row = $result->fetch();
    return $row;
}


?>
