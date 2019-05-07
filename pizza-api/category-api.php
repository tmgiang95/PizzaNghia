<?php
/**
 * User: TranMinhGiang
 * Date: 05/07/2019
 * Time: 4:51 PM
 */

include "CategoryDao.php";

$method = $_SERVER['REQUEST_METHOD'];
$cateDao = new CategoryDao();
switch ($method) {
    case 'GET':
        $return = $cateDao->getAllCategories();
        header('Content-type: application/json');
        print $return;
        break;
    case 'PUT':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $cateDao->updateCategory($array);
        header('Content-type: application/json');
        print $return;
        break;
    case 'DELETE':
        $pathSegments = explode('/', $_SERVER['REQUEST_URI']);
        $id = $pathSegments[3];
        $return = $cateDao->deleteCategory($id);
        print $return;
        break;
    case 'POST':
        $entityBody = file_get_contents('php://input');
        $array = json_decode($entityBody, true);
        $return = $cateDao->insertNewCategory($array);
        header('Content-type: application/json');
        print $return;
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, PUT, DELETE');
        break;
}
