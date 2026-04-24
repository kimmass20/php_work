<?php

require_once '../backend/utils.php';
require_once '../backend/generate_planning.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('/index.php');
}

genererPlanning();

rediriger('/frontend/pages/afficher_planning.php?message=Planning généré avec succès');
