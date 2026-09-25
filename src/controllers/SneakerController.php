<?php

use Models\Sneakers;
use Models\Size;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sneaker = new Sneakers();

        if ($id) {
            $data = $sneaker->getById($id);

            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'Sneaker non trouvée']);
                break;
            }

            $size = new Size();
            $size->setSneaker($id);

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