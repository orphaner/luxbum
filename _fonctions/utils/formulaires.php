<?php

  /**
   *
   */
function protege_input ($in) {
   if (!get_magic_quotes_gpc())
      $in=addslashes(trim($in));
//    $in = str_replace ("'", "''", $in);
//   $in = htmlentities ($in, ENT_NOQUOTES);
   $in = htmlspecialchars($in);
   return $in;
}

/**
 *
 */
function unprotege_input ($in) {
   //$in=htmlspecialchars($in,ENT_NOQUOTES);
   return htmlentities(stripslashes(trim($in)));
}

/**
 *
 */
function get_post ($elt, &$var) {
   if (isset ($_POST[$elt])) {
      $var = protege_input ($_POST[$elt]);
   }
   else {
      $var = '';
   }
}

/**
 *
 */
function verif_non_vide ($var, $val) {
   global $page;
   global $err_vide;
   if ($val == '') {
      $page->MxText ('err_'.$var, 'Champ vide !!');
      $err_vide = true;
      return true;
   }
   return false;
}

/**
 *
 */
function verif_date ($var, $val, $write=true) {
   global $page;
   if (!ereg("[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}", $val)) {
      if ($write) {
         $page->MxText ('err_'.$var, 'Mauvais format de date !!');
      }
      return false;
   }
   $tab = explode ('/', $val);

   if ($tab[0] <= 0 || $tab[0] > 31) {
      if ($write) {
         $page->MxText ('err_'.$var, 'Le jour doit être comprit entre 1 et 31 !!');
      }
      return false;
   }

   if ($tab[1] <= 0 || $tab[1] > 12) {
      if ($write) {
         $page->MxText ('err_'.$var, 'Le mois doit être comprit entre 1 et 12 !!');
      }
      return false;
   }

   return true;
}

/**
 * Vérifie si un code hexa est correct
 */
function is_hex_color ($code_hex) {
   return ereg ("[0-9a-fA-F]{6}", $code_hex);
}

/**
 * Vérifie si une email est valide syntaxiquement
 */
function verifEmail ($email) {
   return preg_match('/^[a-zA-Z0-9_+-]+(\.[a-zA-Z0-9_+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/', $email);
}

/**
 * Vérifie si un site est correct syntaxiquement
 */
function verifSite ($site) {
   return ereg ("^http[s]?://.*\.(.){2,4}(\/.*)?$", $site);
}

?>