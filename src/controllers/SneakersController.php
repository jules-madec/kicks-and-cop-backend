<?php

use Models\Sneakers;
use Models\Sizes;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sneaker = new Sneakers();

        if (!empty($_GET['id'])) {
            $data = $sneaker->getById($_GET['id']);

            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'Sneaker non trouvée']);
                break;
            }

            $size = new Sizes();
            $size->setSneaker($_GET['id']);

            echo json_encode([
                'sneaker' => $data,
                'sizes'   => $size->getSizesBySneaker(),
            ]);
        } else {
            echo json_encode($sneaker->getAllSneakers());
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
