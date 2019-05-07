<?php
include "SystemConfig.php";
class CategoryDao
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

    public function updateCategory($array){
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $id = $value['id'];
                $categoryname = $value['categoryname'];
                $sql = "UPDATE category SET categoryname='$categoryname' WHERE id=$id";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $value;
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }

    public function deleteCategory($id)
    {
        // $this->dbReference = new SystemConfig();
        // $this->dbConnect = $this->dbReference->connectDB();

        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $sql = "DELETE FROM category WHERE id = " . $id;
            if ($this->dbConnect->query($sql) == true) {
                return "Record deleted successfully";
            } else {
                return "Error deleting record: " . $this->dbConnect->error;
            }
        }

    }
    public function insertNewCategory($array)
    {
        // $this->dbReference = new SystemConfig();
        // $this->dbConnect = $this->dbReference->connectDB();

        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {
            $array_updated = [];
            foreach ($array as $value) {
                $name = $value['categoryname'];
                $sql = "INSERT INTO category (id, categoryname) VALUES (NULL,'$name')";
                if ($this->dbConnect->query($sql) == true) {
                    $array_updated[] = $value;
                }
            }
            $result = json_encode($array_updated, JSON_UNESCAPED_UNICODE);
            return $result;
        }
    }
    public function getCategoryByID($id)
    {
        // $this->dbReference = new SystemConfig();
        // $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM Category WHERE id = '$id'";
            $this->result = $this->dbConnect->query($sql);
            if ($this->result->num_rows > 0) {
                // output data of each row
                $resultSet = array();
                while ($row = $this->result->fetch_assoc()) {
                    return ['name' => $row['categoryname']];
                }
            } else {
                return null;
            }

        }
    }

    public function getAllCategories()
    {
        // $this->dbReference = new SystemConfig();
        // $this->dbConnect = $this->dbReference->connectDB();
        if ($this->dbConnect == null) {
            $this->dbReference->sendResponse(503, '{"error_message":' . $this->dbReference->getStatusCodeMeeage(503) . '}');
        } else {

            $sql = "SELECT * FROM Category";
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
                    $resultSet[] = [
                        'id' => $row['id'],
                        'name' => $row['categoryname'],
                    ];
                }
                $result = json_encode($resultSet, JSON_UNESCAPED_UNICODE);
                // $this->dbReference->sendResponse(200, '{"items":' . json_encode($resultSet, JSON_UNESCAPED_UNICODE) . '}');
                return '{"categories":' . $result . '}';
            } else {
                //echo "0 results";
                // $this->dbReference->sendResponse(200, '{"items":null}');
                return '{"categories":null}';
            }

        }
    }
}
