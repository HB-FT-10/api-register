<?php

// TODO: valider les données entrées
[
    'username' => $username,
    'email' => $email,
    'password' => $password
] = $_POST;

// TODO: try catch sur la création de l'instance de PDO
[
    'DB_HOST' => $host,
    'DB_PORT' => $port,
    'DB_NAME' => $dbname,
    'DB_CHARSET' => $charset,
    'DB_USER' => $dbUser,
    'DB_PASSWORD' => $dbPassword
] = parse_ini_file(__DIR__ . '/conf/db.ini');

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
$pdo = new PDO($dsn, $dbUser, $dbPassword);

$apiToken = bin2hex(random_bytes(25)); // Octets => Chaîne Hexadécimale
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, email, `password`, api_token) VALUES (:username, :email, :pass, :api_token)");
$result = $stmt->execute([
    'username' => $username,
    'email' => $email,
    'pass' => $hashedPassword,
    'api_token' => $apiToken
]);

if ($result === false) {
    echo "Une erreur est survenue lors de l'inscription";
    exit;
}

echo "Merci pour votre inscription, votre token d'API est : $apiToken";
