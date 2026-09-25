<?php

use Models\Favorites;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (empty($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Identifiant utilisateur requis (/favorites?userId=id)']);
            break;
        }

        $favorites = new Favorites();

        try {
            $favorites->setUser($_GET['id']);
            echo json_encode($favorites->getFavoritesByUser());
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        $favorites = new Favorites();

        try {
            $favorites->setUser($_POST['user'] ?? null);
            $favorites->setSneaker($_POST['sneaker'] ?? null);

            $isNowFavorite = $favorites->toggleFavorite();
            echo json_encode(['favorite' => $isNowFavorite]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
