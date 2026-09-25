<?php


use Models\Sizes;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Identifiant de sneaker requis (/size/{sneakerId})']);
            break;
        }

        $size = new Sizes();

        try {
            $size->setSneaker($id);
            echo json_encode($size->getSizesBySneaker());
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
