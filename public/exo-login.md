1) mise en page avec bootstrap
2) validation de formulaire d'authentification
3)  si l'utilisateur est correctement authentifié :
    - son login et son password sont copiés dans la variable de session
    - il est redirigé vers la page private.php
    3.5) sinon un message générique est affiché :
        "Mot de passe ou login invalide"

    https://www.php.net/manual/fr/function.password-verify.php
    https://www.php.net/manual/fr/function.password-hash.php
