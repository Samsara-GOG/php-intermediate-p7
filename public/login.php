<?php

declare(strict_types = 1);

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
// use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

// activation du système d'autoloading de Composer une seule fois
require __DIR__.'/../vendor/autoload.php';

session_start();

// instanciation du chargeur de template
$loader = new FilesystemLoader(__DIR__. '/../templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
    // activation du mode debug
    // "debug" => true,
    // activation du mode de variables strictes
    "strict_variables" => true,
    // activation du cache pour la production
    // 'cache' => __DIR__.'/../var/cache',
]);

// chargement de l'extension DebugExtension
// $twig->addExtension(new DebugExtension());

// traitement des données
$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');

// Traitement des données
$formData = [
    "login" => '',
    "password" => '',
];

// dump($formData);

$errors = [];
$tryPassword = 11;

if ($_POST) {
    foreach($formData as $key => $value) {
        if(isset($_POST[$key])) {
            $formData[$key] = $_POST[$key];
        }
    }
    // dump($tryPassword);

    if (empty($_POST['login'])) {
        // le champs est-il vide ?
        $errors['login'] = "Veuillez entrer un login.";
        $tryPassword -= 1;
    }

    // dump($errors);  
    // dump($formData);  

    // $password = '123';
    // $password = 'swduffynrihygwtj';

    // $hash = '$2y$10$gPqGX1VBehD4ybRiFITjsuVg8DSRwroQY7avQz/ZSYjLWsIAOTLzO';
                // $_POST['password']

    if (empty($_POST['password'])) {
        $errors['password'] = 'Veuillez entrer un mot de passe.';
        $tryPassword -= 1;
    } elseif (!password_verify($_POST['password'], $config['password'])) {
        $errors['password'] = 'Mot de passe ou login invalide.';
        $tryPassword -= 1;
    }

    if (!$errors) {
        $_SESSION['login'] = $config['smtp']['login'];
        $_SESSION['password'] = $config['smtp']['password'];
        // loggé avec succé, renvoie vers page privée
        echo "<p>Vous vous êtes connecté avec succès, vous allez donc être redirigé vers la page privée dans 5sec.</p>";
        sleep(3);
        $url = 'private.php';
        header("Location: {$url}", true, 301);
        exit();
    }
    // dump($tryPassword);

    if ($tryPassword == 9) {
        // l'utilisateur ne peut pas accéder à la page
        // renvoi vers home page
        echo "<p>Vous avez dépassé le nombre autorisé d'essais pour login et mot de passe, vous allez donc être redirigé vers la page d'accueil dans 5sec.</p>";
        sleep(3);
        $url = 'index.php';
        header("Location: {$url}", true, 301);
        $tryPassword -= 1;
        exit();
    }   
}


// Affichage du rendu du template
echo $twig->render('login.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'formData' => $formData,
]);

