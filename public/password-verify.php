<?php

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// activation du système d'autoloading de Composer une seule fois
require_once __DIR__.'/../vendor/autoload.php';

session_start();

// instanciation du chargeur de template
$loader = new FilesystemLoader(__DIR__. '/../templates');

// instanciation du moteur de template
$twig = new Environment($loader);

// traitement des données
$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');

dump($config);

// $_POST['password']
// $password = '123';
$password = 'swduffynrihygwtj';
$errors = [];
$hash = '$2y$10$gPqGX1VBehD4ybRiFITjsuVg8DSRwroQY7avQz/ZSYjLWsIAOTLzO';
            // $_POST['password']

if (!password_verify($password, $config['password'])) {
    $errors['password'] = 'Mot de passe ou login invalide.';
    dump($errors);
}

// if (password_verify('swduffynrihygwtj', $hash)) {
//     echo 'Le mot de passe est valide !';
// } else {
//     echo 'Le mot de passe est invalide.';
// }

// Affichage du rendu du template
echo $twig->render('password-verify.html.twig', [
    // transmission de données au template
    'errors' => $errors,
]);

// if (password_verify('rasmuslerdorf', $hash)) {
//     echo 'Le mot de passe est valide !';
// } else {
//     echo 'Le mot de passe est invalide.';
// }

// $password = password_hash($_POST['swduffynrihygwtj'], PASSWORD_DEFAULT);

// Voir l'exemple fourni sur la page de la fonction password_hash()
// pour savoir d'où cela provient.
// $hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';

// if (empty($_POST['password])) {
    // message error
// }

// a faire
// if (empty($_POST['password'])) {
//     echo 'Le mot de passe est invalide.';
// } elseif (!password_verify($password, $config['password'])) {
//     echo 'Le mot de passe est invalide.';
// }

