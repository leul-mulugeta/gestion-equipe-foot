<?php
// Point d'entrée unique de l'API du serveur de données

require_once __DIR__ . '/../init.php';

$api = new Api();

$api->deliverResponse('success', 200, 'ok');