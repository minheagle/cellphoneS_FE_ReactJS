<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    require_once '../../config/database.php';
    require_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->getConnection();

    $category = new Categories($db);

    $requestData = json_decode(file_get_contents("php://input"));

    if (!empty($requestData->id)) {
        if (!empty($requestData->categoryName)) {
            $category->id = $requestData->id;
            $category->categoryName = $requestData->categoryName;
            if (!$category->checkCategoryExist()) {
                if ($category->updateCategory()) {
                    http_response_code(200);
                    $res = array(
                        'message' => 'Cập nhật danh mục thành công'
                    );
                    echo json_encode($res);
                } else {
                    html_entity_decode(404);
                    $res = array(
                        'message' => 'Cập nhật danh mục không thành công'
                    );
                    echo json_encode($res);
                }
            } else {
                http_response_code(404);
                $res = array(
                    'message' => 'Danh mục đã tồn tại'
                );
                echo json_encode($res);
            }
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Các mục không được để trống'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Danh mục không tồn tại'
        );
        echo json_encode($res);
    }
} else {
    http_response_code(503);
    $res = array(
        'message' => 'Phương thức không hợp lệ'
    );
    echo json_encode($res);
}
