<?php

use Models\Cart;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Identifiant utilisateur requis (/cart/{userId})']);
            break;
        }

        $cart = new Cart();

        try {
            $cart->setUser($id);
            echo json_encode([
                'items' => $cart->getCartByUser(),
                'total' => $cart->getTotal(),
                'count' => $cart->countItems(),
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        $cart = new Cart();

        try {
            $cart->setUser($_POST['user'] ?? null);
            $cart->setSneaker($_POST['sneaker'] ?? null);
            $cart->setSize($_POST['size'] ?? null);

            if ($cart->register()) {
                http_response_code(201);
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => "Erreur lors de l'ajout au panier"]);
            }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $cart = new Cart();

        try {
            $cart->setUser($data['user'] ?? null);
            $cart->setSneaker($data['sneaker'] ?? null);
            $cart->setSize($data['size'] ?? null);

            echo json_encode(['success' => $cart->removeFromCart()]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}