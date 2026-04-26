<?php
require_once __DIR__ . '/../backend/utils.php';

deconnecterUtilisateur();
rediriger('/login.php?message=Vous êtes déconnecté.');
