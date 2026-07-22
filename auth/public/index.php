<?php
// Point d'entrée unique de l'API d'authentification

require_once __DIR__ . '/../init.php';

$api = new Api();

$api->deliverResponse('success', 200, 'auth ok');