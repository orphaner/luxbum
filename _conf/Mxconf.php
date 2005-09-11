<?php
//Configuration générale de ModeliXe
define('MX_FLAGS_TYPE', 'xml');         //Précise le mode d'écriture des templates par défaut (xml ou pear).
define('MX_OUTPUT_TYPE', 'html');		 //Précise le type de balisage en sortie.
//define('MX_TEMPLATE_PATH', '_structure'); //Précise le répertoire de template par défaut.
define('MX_DEFAULT_PARAMETER', '');     //Précise un fichier de paramètres par défaut.
define('MX_CACHE_PATH','');       //Précise le répertoire du cache.
define('MX_CACHE_DELAY', 0);            //Définit le délai de renouvellement du cache en secondes.
define('MX_SIGNATURE', 'off');          //Laisse la signature de ModeliXe dans la page HTML générée (on ou off).
define('MX_COMPRESS', 'off');            //Mets en oeuvre la compression des pages si le navigateur le supporte (on ou off).
define('MX_REWRITEURL', 'off');         //Uitilise le mode_rewrite pour créer les urls (on ou  off).
define('MX_PERFORMANCE_TRACER', 'on');  //Précise si on désire mettre en oeuvre le chronométrage des performances (on ou off).

//Configuration de la gestion des erreurs
define('ERROR_MANAGER_SYSTEM', 'on');    //Les erreurs sont remontées pour on, ignorées pour off.
define('ERROR_MANAGER_LEVEL', '2');      //Précise le niveau d'erreur toléré, plus il est bas, moins les erreurs sont tolérées.
define('ERROR_MANAGER_ESCAPE', 'html/erreur.html');      //Permet de spécifier une url locale de remplacement en cas de remontée d'erreurs.
define('ERROR_MANAGER_LOG', 'erreur.txt');         //Permet de définir un fichier de log.
define('ERROR_MANAGER_ALARME', '');  //Permet de définir une série d'adresse email à laquelle sera envoyé un mail d'alerte.
?>