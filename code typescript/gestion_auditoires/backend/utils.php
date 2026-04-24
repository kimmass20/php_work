<?php

/**
 * Génère un ID unique
 */
function genererID() {
    return uniqid('', true);
}

/**
 * Valide et nettoie les données d'entrée
 */
function nettoyerDonnees($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Envoie une réponse JSON
 */
function envoyerJSON($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Redirige vers une page
 */
function rediriger($page, $message = '') {
    $url = $page;
    if ($message) {
        $url .= '?message=' . urlencode($message);
    }
    header("Location: $url");
    exit;
}
