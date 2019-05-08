<?php
/**
 * User: TranMinhGiang
 * Date: 05/07/2019
 * Time: 5:51 PM
 */

include "CustomerDao.php";

$method = $_SERVER['REQUEST_METHOD'];
$customerDao = new CustomerDao();
switch ($method) {
    case 'GET':
        $return = $customerDao->getAllCustomers();
        header('Content-type: application/json');
        print $return;
        break;
    case 'PUT':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $customerDao->updateCustomers($array);
        header('Content-type: application/json');
        print $return;
        break;
    case 'DELETE':
        $pathSegments = explode('/', $_SERVER['REQUEST_URI']);
        $id = $pathSegments[3];
        $return = $customerDao->deleteCustomer($id);
        print $return;
        break;
    case 'POST':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $customerDao->insertNewProducts($array);
        header('Content-type: application/json');
        print $return;
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, PUT, DELETE');
        break;
}
