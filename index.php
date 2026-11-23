<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/service.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$recherche = trim($_GET['q'] ?? '');
$categorieFiltre = trim($_GET['categorie'] ?? '');

$services = searchServices($pdo, $recherche, $categorieFiltre);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des services - TP PHP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body >
    <h1>Services disponibles</h1>
    <?php if ($flash !== null): ?>
    <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

    <!-- Formulaire d'ajout (POST) -->
    <form action="/tp_php/actions/ajouter_service.php" method="POST">
        <input type="text" name="titre" placeholder="Titre du service" required>
        <select name="categorie" required>
            <option value="Maintenance">Maintenance</option>
            <option value="Formation">Formation</option>
            <option value="Développement">Développement</option>
        </select>
        <input type="number" name="tarif_fcfa" placeholder="Tarif (FCFA)" min="0" step="500" required>
        <button type="submit">Ajouter</button>
    </form>

    <!-- Formulaire de recherche et filtre (GET) -->
    <form action="/tp_php/index.php" method="GET">
        <input
            type="text"
            name="q"
            value="<?= htmlspecialchars($recherche) ?>"
            placeholder="Rechercher un service..."
        >
        <select name="categorie">
            <option value="">Toutes les catégories</option>
            <option value="Maintenance" <?= $categorieFiltre === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
            <option value="Formation" <?= $categorieFiltre === 'Formation' ? 'selected' : '' ?>>Formation</option>
            <option value="Développement" <?= $categorieFiltre === 'Développement' ? 'selected' : '' ?>>Développement</option>
        </select>
        <button type="submit">Filtrer</button>
        <a class="reset" href="/tp_php/index.php">Réinitialiser</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Service</th>
                <th>Catégorie</th>
                <th>Tarif</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="5">Aucun service ne correspond à vos critères.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= (int) $service['id'] ?></td>
                        <td><?= htmlspecialchars($service['titre']) ?></td>
                        <td><?= htmlspecialchars($service['categorie']) ?></td>
                        <td><?= number_format((int) $service['tarif_fcfa'], 0, ',', ' ') ?> FCFA</td>
                    <td>
                            <form
                                action="/tp_php/actions/supprimer_service.php"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Supprimer ce service ?');"
                            >
                                <input type="hidden" name="id" value="<?= (int) $service['id'] ?>">
                                <button type="submit"> ballayez</button>
                            </form>
                    </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>