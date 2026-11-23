<?php
function getAllServices(PDO $pdo): array
{
    $stmt = $pdo->query(
        "SELECT id, titre, categorie, tarif_fcfa, created_at
         FROM services
         ORDER BY id DESC"
    );
    return $stmt->fetchAll();
}

function searchServices(PDO $pdo, string $recherche = '', string $categorie = ''): array
{
    $sql = "SELECT id, titre, categorie, tarif_fcfa, created_at
            FROM services
            WHERE 1=1";
    $params = [];

    if ($recherche !== '') {
        $sql .= " AND titre LIKE :recherche";
        $params['recherche'] = '%' . $recherche . '%';
    }

    if ($categorie !== '') {
        $sql .= " AND categorie = :categorie";
        $params['categorie'] = $categorie;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function addService(PDO $pdo, string $titre, string $categorie, int $tarifFcfa): bool
{
    $sql = "INSERT INTO services (titre, categorie, tarif_fcfa)
            VALUES (:titre, :categorie, :tarif_fcfa)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'titre'      => $titre,
        'categorie'  => $categorie,
        'tarif_fcfa' => $tarifFcfa,
    ]);
}
function deleteService(PDO $pdo, int $id): bool
{
    $sql = "DELETE FROM services WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'id' => $id,
    ]);
}