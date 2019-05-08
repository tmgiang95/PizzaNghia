<?php
/**
 * User: TranMinhGiang
 * Date: 05/07/2019
 * Time: 4:51 PM
 */

include "BillDao.php";

$method = $_SERVER['REQUEST_METHOD'];
$billDao = new BillDao();
switch ($method) {
    case 'GET':
        $return = $billDao->getAllBills();
        header('Content-type: application/json');
        print $return;
        break;
    // case 'PUT':
    //     $entityBody = file_get_contents('php://input');
    //     $array = json_decode($entityBody, true);
    //     $return = $billDao->updateCategory($array);
    //     header('Content-type: application/json');
    //     print $return;
    //     break;
    // case 'DELETE':
    //     $pathSegments = explode('/', $_SERVER['REQUEST_URI']);
    //     $id = $pathSegments[3];
    //     $return = $billDao->deleteCategory($id);
    //     print $return;
    //     break;
    case 'POST':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $billDao->insertNewBill($array);
        header('Content-type: application/json');
        print $return;
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, POST');
        break;
}
