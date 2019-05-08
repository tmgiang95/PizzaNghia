<?php
include "ProductDao.php";
class BillDetailDao
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

   

    public function insertNewBillDetail($array,$billid)
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $productid = (SystemConfig::isHasKey($value, 'productid')) ? $value['productid'] : NULL; 
                $quantity = (SystemConfig::isHasKey($value, 'quantity')) ? $value['quantity'] : NULL; 
                $price = (SystemConfig::isHasKey($value, 'price')) ? $value['price'] : NULL; 
                
                $sql = "INSERT INTO billdetail (billdetaiid,billid, productid,quantity,price) VALUES (NULL,'$billid','$productid','$quantity','$price')";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $value;
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }
    public function getAllBillDetailsByBillID($billid)
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM billdetail WHERE billid = $billid";
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
                    $billdetaiid = $row['billdetaiid'];
                    $billid = (SystemConfig::isHasKey($row, 'billid')) ? $row['billid'] : '';
                    $productid = (SystemConfig::isHasKey($row, 'productid')) ? $row['productid'] : '';
                    $quantity = (SystemConfig::isHasKey($row, 'quantity')) ? $row['quantity'] : '';
                    $price = (SystemConfig::isHasKey($row, 'price')) ? $row['price'] : '';
                    $product = json_decode((new ProductDao())->getProductByID($productid),true);
                    $resultSet[] = [
                        'productname' => $product['name'],
                        'productprice' => $product['price'],
                        'quantity' => $quantity,
                        'price' => $price
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
    public function getAllBillDetails()
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM billdetail";
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
                    $billdetaiid = $row['billdetaiid'];
                    $billid = (SystemConfig::isHasKey($row, 'billid')) ? $row['billid'] : '';
                    $productid = (SystemConfig::isHasKey($row, 'productid')) ? $row['productid'] : '';
                    $quantity = (SystemConfig::isHasKey($row, 'quantity')) ? $row['quantity'] : '';
                    $price = (SystemConfig::isHasKey($row, 'price')) ? $row['price'] : '';
                    $resultSet[] = [
                        'billdetaiid' => $billdetaiid,
                        'billid' => $billid,
                        'productid' => $productid,
                        'quantity' => $quantity,
                        'price' => $price
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
?>