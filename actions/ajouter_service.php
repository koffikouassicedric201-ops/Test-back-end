
<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $tarif = filter_var($_POST['tarif_fcfa'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);

    if ($titre !== '' && $categorie !== '' && $tarif !== false) {
        if (addService($pdo, $titre, $categorie, $tarif)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Le service a bien été ajouté.',
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => "Impossible d'ajouter le service.",
            ];
        }
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Veuillez renseigner des champs valides.',
        ];
    }
}

header('Location: /tp_php/index.php');
exit;