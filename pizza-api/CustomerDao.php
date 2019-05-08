<?php
include "SystemConfig.php";
class CustomerDao
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

    public function deleteCustomer($id)
    {
        // $this->dbReference = new SystemConfig();
        // $this->dbConnect = $this->dbReference->connectDB();

        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = "DELETE FROM customer WHERE customerid = " . $id;
            if ($this->dbConnect->query($sql) == true) {
                return "Record deleted successfully";
            } else {
                return "Error deleting record: " . $this->dbConnect->error;
            }
        }

    }

    public function updateCustomers($array){
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $setString = '';
                
                if (SystemConfig::isHasKey($value,'customerid')){
                    $customerid = $value['customerid'];
                } else {
                    return '{"error_message": "you need enter Product id to update"}';
                }
                if (SystemConfig::isHasKey($value,'name')){
                    $name = $value['name'];
                   $setString = $setString . "name='$name',";
                }
                if (SystemConfig::isHasKey($value,'birthday')){
                    $birthday = $value['birthday'];
                    $setString = $setString . "birthday='$birthday',";
                }
                if (SystemConfig::isHasKey($value,'phone')){
                    $phone = $value['phone'];
                    $setString = $setString . "phone='$phone',";
                }
                if (SystemConfig::isHasKey($value,'address')){
                    $address = $value['address'];
                    $setString = $setString . "address='$address',";
                }
                if (SystemConfig::isHasKey($value,'balance')){
                    $balance = $value['balance'];
                    $setString = $setString . "balance='$balance'";
                }
                $endString = substr($setString,strlen($setString)-1,strlen($setString));
                if ($endString ==','){
                    $setString = substr($setString,0,strlen($setString)-1);
                }                 
                $sql = "UPDATE customer SET ".$setString." WHERE customerid=$customerid";
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
            foreach ($array as $row) {
                $name = (SystemConfig::isHasKey($row, 'name')) ? $row['name'] : '';
                $birthday = (SystemConfig::isHasKey($row, 'birthday')) ? $row['birthday'] : '';
                $phone = (SystemConfig::isHasKey($row, 'phone')) ? $row['phone'] : '';
                $address = (SystemConfig::isHasKey($row, 'address')) ? $row['address'] : '';
                $balance = (SystemConfig::isHasKey($row, 'balance')) ? $row['balance'] : '0';
                $sql = "INSERT INTO customer (customerid, name,birthday,phone,address,balance) VALUES (NULL,'$name','$birthday','$phone','$address','$balance')";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $row;
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }

    public function getAllCustomers()
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM customer";
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
                    $customerid = $row['customerid'];
                    $name = (SystemConfig::isHasKey($row, 'name')) ? $row['name'] : '';
                    $birthday = (SystemConfig::isHasKey($row, 'birthday')) ? $row['birthday'] : '';
                    $phone = (SystemConfig::isHasKey($row, 'phone')) ? $row['phone'] : '';
                    $address = (SystemConfig::isHasKey($row, 'address')) ? $row['address'] : '';
                    $balance = (SystemConfig::isHasKey($row, 'balance')) ? $row['balance'] : '0';
                    $resultSet[] = [
                        'customerid' => $customerid,
                        'name' => $name,
                        'birthday' => $birthday,
                        'phone' => $phone,
                        'address' => $address,
                        'balance' => $balance,
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
