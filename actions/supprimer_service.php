

<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($id !== false && deleteService($pdo, $id)) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Le service a bien été supprimé.',
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Impossible de supprimer ce service.',
        ];
    }
}

header('Location: /tp_php/index.php');
exit;