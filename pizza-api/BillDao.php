<?php
include "BillDetailDao.php";
class BillDao
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

    // public function updateProducts($array){
    //     if ($this->dbConnect == null) {
    //         $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
    //     } else {
    //         $array_updated = [];
    //         foreach ($array as $value) {
    //             $setString = '';
                
    //             if (SystemConfig::isHasKey($value,'billid')){
    //                 $billid = $value['billid'];
    //             } else {
    //                 return '{"error_message": "you need enter Product id to update"}';
    //             }
    //             if (SystemConfig::isHasKey($value,'date')){
    //                 $date = $value['date'];
    //                $setString = $setString . "date='$date',";
    //             }
    //             if (SystemConfig::isHasKey($value,'customerid')){
    //                 $customerid = $value['customerid'];
    //                 $setString = $setString . "customerid='$customerid',";
    //             }
    //             if (SystemConfig::isHasKey($value,'totalprice')){
    //                 $totalprice = $value['totalprice'];
    //                 $setString = $setString . "totalprice='$totalprice',";
    //             }
    //             if (SystemConfig::isHasKey($value,'billdetails')){
    //                 $billdetails = $value['billdetails'];
    //                 $setString = $setString . "billdetails='$billdetails'";
    //             }
    //             $endString = substr($setString,strlen($setString)-1,strlen($setString));
    //             if ($endString ==','){
    //                 $setString = substr($setString,0,strlen($setString)-1);
    //             }                 
    //             $sql = "UPDATE bill SET ".$setString." WHERE billid=$billid";
    //             if ($this->dbConnect->query($sql) == true) {
    //                 $array_updated[] = $value;
    //                 $billdetailDao = new BillDetailDao();
    //                 $billdetailDao->insertNewBillDetail($billdetails);
    //             }
    //         }
    //         $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
    //         return $result;
    //     }
    // }

    public function insertNewBill($array)
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $billid = (SystemConfig::isHasKey($value, 'billid')) ? $value['billid'] : NULL; 
                $date = (SystemConfig::isHasKey($value, 'date')) ? $value['date'] : NULL; 
                $customerid = (SystemConfig::isHasKey($value, 'customerid')) ? $value['customerid'] : NULL; 
                $totalprice = (SystemConfig::isHasKey($value, 'totalprice')) ? $value['totalprice'] : NULL; 
                $billdetails = (SystemConfig::isHasKey($value, 'billdetails')) ? $value['billdetails'] : NULL; 
               
                $sql = "INSERT INTO bill (billid,date, customerid,totalprice) VALUES ('$billid','$date','$customerid','$totalprice')";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $value;

                    $billdetailDao = new BillDetailDao();
                    $billdetailDao->insertNewBillDetail($billdetails,$billid);
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }

   
    public function getAllBills()
    {
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM bill";
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
                    $billid = (SystemConfig::isHasKey($row, 'billid')) ? $row['billid'] : '';
                    $date = (SystemConfig::isHasKey($row, 'date')) ? $row['date'] : '';
                    $customerid = (SystemConfig::isHasKey($row, 'customerid')) ? $row['customerid'] : '';
                    $totalprice	 = (SystemConfig::isHasKey($row, 'totalprice')) ? $row['totalprice'] : '';
                    $billdetail = new BillDetailDao();
                    $detail = json_decode($billdetail->getAllBillDetailsByBillID($billid));
                    $resultSet[] = [
                        'billid' => $billid,
                        'date' => $date,
                        'customerid' => $customerid,
                        'billdetails'=> $detail,
                        'totalprice' => $totalprice
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