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
        
        $url = $_SERVER['REQUEST_URI'];
        $query_str = parse_url($url, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        $return = $customerDao->getCustomersByPhone($query_params['phone']);
        header('Content-type: application/json');
        print $return;
        break;
   
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET');
        break;
}