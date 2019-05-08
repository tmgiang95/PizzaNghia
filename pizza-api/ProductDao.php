<?php
include "CategoryDao.php";
class ProductDao
{
    private $dbReference;
    public $dbConnect;
    public $result;
    public function __construct()
    {
        $this->dbReference = new SystemConfig();
        $this->dbConnect = $this->dbReference->connectDB();
    }

    public function __destruct()
    {

    }
    public function deleteProduct($id)
    {
        // $this->dbReference = new SystemConfig();
        // $this->dbConnect = $this->dbReference->connectDB();

        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = "DELETE FROM product WHERE productid = " . $id;
            if ($this->dbConnect->query($sql) == true) {
                return "Record deleted successfully";
            } else {
                return "Error deleting record: " . $this->dbConnect->error;
            }
        }

    }
    public function updateProducts($array){
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $setString = '';
                
                if (SystemConfig::isHasKey($value,'productid')){
                    $productid = $value['productid'];
                } else {
                    return '{"error_message": "you need enter Product id to update"}';
                }
                if (SystemConfig::isHasKey($value,'name')){
                    $name = $value['name'];
                   $setString = $setString . "name='$name',";
                }
                if (SystemConfig::isHasKey($value,'price')){
                    $price = $value['price'];
                    $setString = $setString . "price='$price',";
                }
                if (SystemConfig::isHasKey($value,'description')){
                    $description = $value['description'];
                    $setString = $setString . "description='$description',";
                }
                if (SystemConfig::isHasKey($value,'categoryID')){
                    $categoryID = $value['categoryID'];
                    $setString = $setString . "categoryID='$categoryID'";
                }
                $endString = substr($setString,strlen($setString)-1,strlen($setString));
                if ($endString ==','){
                    $setString = substr($setString,0,strlen($setString)-1);
                }                 
                $sql = "UPDATE product SET ".$setString." WHERE productid=$productid";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $value;
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }

    public function insertNewProducts($array)
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $name = (SystemConfig::isHasKey($value, 'name')) ? $value['name'] : NULL; 
                $price = (SystemConfig::isHasKey($value, 'price')) ? $value['price'] : 0; 
                $description = (SystemConfig::isHasKey($value, 'description')) ? $value['description'] : NULL; 
                $categoryID = (SystemConfig::isHasKey($value, 'categoryID')) ? $value['categoryID'] : NULL; 
                $sql = "INSERT INTO product (productid, name,price,description,categoryID) VALUES (NULL,'$name','$price','$description','$categoryID')";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $value;
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }
    public function getProductByID($id)
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM product WHERE productid = $id";
            // $number_per_page = $_POST["number_per_page"];
            // $page = ($_POST["page"] - 1) * $number_per_page + 1;
            // $page_next = $_POST["page"] * $number_per_page;
            // //echo "$page";

            // if ($page != null && $number_per_page != null) {
            //     //echo "viva for";
            //     $sql = "SELECT * FROM Category WHERE id BETWEEN $page AND $page_next";
            // } /*else{
            // echo "0 results";
            // return;
            // }*/
            $this->result = $this->dbConnect->query($sql);
            if ($this->result->num_rows > 0) {
                // output data of each row
                $resultSet = array();
                while ($row = $this->result->fetch_assoc()) {
                    $cateID = (SystemConfig::isHasKey($row, 'categoryID')) ? $row['categoryID'] : ''; 
                    $productid = (SystemConfig::isHasKey($row, 'productid')) ? $row['productid'] : ''; 
                    $name = (SystemConfig::isHasKey($row, 'name')) ? $row['name'] : ''; 
                    $price = (SystemConfig::isHasKey($row, 'price')) ? $row['price'] : ''; 
                    $description = (SystemConfig::isHasKey($row, 'description')) ? $row['description'] : ''; 
                    $cateDao = new CategoryDao();
                    $cateName = $cateDao->getCategoryByID($cateID);
                    $resultSet = [
                        'productid' => $productid,
                        'name' => $name,
                        'price' => $price ,
                        'description' => $description,
                        'category' => $cateName['name']
                    ];
                }
                
                $result = json_encode($resultSet, JSON_UNESCAPED_UNICODE);
                // $this->dbReference->sendResponse(200, '{"items":' . json_encode($resultSet, JSON_UNESCAPED_UNICODE) . '}');
                return $result;
            } else {
                //echo "0 results";
                // $this->dbReference->sendResponse(200, '{"items":null}');
                return 'null';
            }

        }
    }

    public function getAllProducts()
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM product";
            // $number_per_page = $_POST["number_per_page"];
            // $page = ($_POST["page"] - 1) * $number_per_page + 1;
            // $page_next = $_POST["page"] * $number_per_page;
            // //echo "$page";

            // if ($page != null && $number_per_page != null) {
            //     //echo "viva for";
            //     $sql = "SELECT * FROM Category WHERE id BETWEEN $page AND $page_next";
            // } /*else{
            // echo "0 results";
            // return;
            // }*/
            $this->result = $this->dbConnect->query($sql);
            if ($this->result->num_rows > 0) {
                // output data of each row
                $resultSet = array();
                while ($row = $this->result->fetch_assoc()) {
                    $cateID = (SystemConfig::isHasKey($row, 'categoryID')) ? $row['categoryID'] : ''; 
                    $productid = (SystemConfig::isHasKey($row, 'productid')) ? $row['productid'] : ''; 
                    $name = (SystemConfig::isHasKey($row, 'name')) ? $row['name'] : ''; 
                    $price = (SystemConfig::isHasKey($row, 'price')) ? $row['price'] : ''; 
                    $description = (SystemConfig::isHasKey($row, 'description')) ? $row['description'] : ''; 

                    $cateDao = new CategoryDao();
                    $cateName = $cateDao->getCategoryByID($cateID);
                    $resultSet[] = [
                        'productid' => $productid,
                        'name' => $name,
                        'price' => $price ,
                        'description' => $description,
                        'category' => $cateName['name']
                    ];
                }
                $result = json_encode($resultSet, JSON_UNESCAPED_UNICODE);
                // $this->dbReference->sendResponse(200, '{"items":' . json_encode($resultSet, JSON_UNESCAPED_UNICODE) . '}');
                return $result;
            } else {
                //echo "0 results";
                // $this->dbReference->sendResponse(200, '{"items":null}');
                return 'null';
            }

        }
    }
}
