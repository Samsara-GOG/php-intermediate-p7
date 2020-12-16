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

    $maxLength = 190;

    if (empty($_POST['login'])) {
        // le champ de l'email est-il vide ?
        $errors['login'] = "Veuillez entrer un login email";
        $tryPassword -= 1;
    } elseif (filter_var($_POST['login'], FILTER_VALIDATE_EMAIL) == FALSE) {
        // l'email n'est pas correcte ?
        $errors['login'] = "Merci de renseigner un email valide";
        $tryPassword -= 1;
    } elseif (strlen($_POST['login']) > $maxLength) {
        // la longueur de l'email est-elle hors des limites ?
        $errors['login'] = "Merci de rédiger une adresse mail dont la longueur maximale ne dépasse pas {$maxLength} caractères";
        $tryPassword -= 1;
    }

    // dump($errors);  
    // dump($formData);  

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
        // connecté avec succès, renvoi vers la page privée
        echo "<p>Vous vous êtes connecté avec succès, vous allez donc être redirigé vers la page privée dans 5sec.</p>";
        $url = 'private.php';
        header("Location: {$url}", true, 301);
        exit();
    }
    // dump($tryPassword);

    if ($tryPassword == 9) {
        // l'utilisateur ne peut pas accéder à la page
        // renvoi vers home page
        echo "<p>Vous avez dépassé le nombre autorisé d'essais pour login et mot de passe, vous allez donc être redirigé vers la page d'accueil dans 5sec.</p>";
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

