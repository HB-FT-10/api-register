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

// TODO: Gérer erreur de requête SQL

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci ! 🎉</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900 dark:text-white">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <h1 class="text-5xl uppercase font-bold">Merci ! 🎉</h1>
            <p class="text-3xl mt-6">Votre inscription a bien été prise en compte.</p>
            <p class="text-2xl mt-3">
                Veuillez trouver votre token d'accès ci-dessous :
            </p>
            <div class="flex items-center gap-5 mt-6 text-xl bg-gray-200 dark:bg-gray-700 py-3 px-6 rounded-md">
                <code id="tokenCode"><?php echo $apiToken; ?></code>
                <span id="copyToken"
                    class="hover:cursor-pointer hover:bg-gray-400 dark:hover:bg-gray-900 transition-all p-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-copy-icon lucide-copy">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                    </svg>
                </span>
            </div>
        </div>
    </section>

    <script>
    const copyToken = document.querySelector('#copyToken');
    const tokenCode = document.querySelector('#tokenCode');

    copyToken.addEventListener('click', () => {
        navigator.clipboard.writeText(tokenCode.innerText);
        copyToken.innerHTML =
            `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>`;

        setTimeout(() => {
            copyToken.innerHTML =
                `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-copy-icon lucide-copy">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                    </svg>`;
        }, 2500);
    })
    </script>
</body>

</html>