<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: dev.mamun@gmail.com
 * Date: 4/13/2021
 * Time: 12:24 PM
 * Year: 2021
 */

date_default_timezone_set('Asia/Dhaka');

$action = $_REQUEST['action'];

switch ($action) {
    case 'add-item':
        $item = [
            "title" => $_POST['title'],
            "qty" => $_POST['qty'],
            "price" => $_POST['price'],
            "total" => ($_POST['qty'] * $_POST['price']),
            "created_at" => time()
        ];
        $items = getItems();
        array_push($items['items'], $item);
        $saved = file_put_contents("items.json", json_encode($items));
        response([
            "status" => true,
            "data" => $items,
            "msg" => "Item successfully saved."
        ]);
        break;
    case 'get-item':
        $items = getItems();
        response([
            "status" => true,
            "data" => $items,
            "msg" => "Item fetch successfully."
        ]);
        break;
    default:
        $data = [
            "status" => false,
            "msg" => "Could not process your request, please check again."
        ];
        response($data);
}

function getItems()
{
    return json_decode(file_get_contents("items.json"), true);
}

function response($data)
{
    header('Content-type: application/json');
    echo json_encode($data);
}