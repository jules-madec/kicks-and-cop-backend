<?php

use Models\Users;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (empty($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Identifiant utilisateur requis (/user?id=id)']);
            break;
        }

        $user = new Users();
        $user->setId((int) $_GET['id']);
        $data = $user->findById();

        if (!$data) {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur non trouvé']);
            break;
        }

        echo json_encode($data);
        break;

    case 'POST':
        $user = new Users();
        $errors = [];

        try {
            $user->setFirst_name($_POST['first_name'] ?? null);
        } catch (\Exception $e) {
            $errors['first_name'] = $e->getMessage();
        }
        try {
            $user->setLast_name($_POST['last_name'] ?? null);
        } catch (\Exception $e) {
            $errors['last_name'] = $e->getMessage();
        }
        try {
            $user->setEmail($_POST['email'] ?? null);
        } catch (\Exception $e) {
            $errors['email'] = $e->getMessage();
        }
        try {
            $user->setPassword($_POST['password'] ?? null);
        } catch (\Exception $e) {
            $errors['password'] = $e->getMessage();
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => $errors]);
            break;
        }

        $user->setRegistration_date();

        if ($user->register()) {
            http_response_code(201);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => "Erreur lors de l'inscription"]);
        }
        break;

    case 'PATCH':
        if (empty($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Identifiant utilisateur requis (/user?id=id)']);
            break;
        }

        parse_str(file_get_contents('php://input'), $data);
        $user = new Users();

        try {
            $user->setId((int) $_GET['id']);
            $user->setEmail($data['email'] ?? null);

            echo json_encode(['success' => $user->updateEmail()]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
