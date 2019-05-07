<?php
/**
 * User: TranMinhGiang
 * Date: 05/07/2019
 * Time: 5:51 PM
 */

include "ProductDao.php";

$method = $_SERVER['REQUEST_METHOD'];
$productDao = new ProductDao();
switch ($method) {
    case 'GET':
        $return = $productDao->getAllProducts();
        header('Content-type: application/json');
        print $return;
        break;
    case 'PUT':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $productDao->updateProducts($array);
        header('Content-type: application/json');
        print $return;
        break;
    case 'DELETE':
        $pathSegments = explode('/', $_SERVER['REQUEST_URI']);
        $id = $pathSegments[3];
        $return = $productDao->deleteProduct($id);
        print $return;
        break;
    case 'POST':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $productDao->insertNewProducts($array);
        header('Content-type: application/json');
        print $return;
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, PUT, DELETE');
        break;
}
