<?php
// includes/lang_manager.php

require_once __DIR__ . '/translations.php';

// Déterminer la langue (Cookie > Défaut 'fr')
$current_lang = $_COOKIE['site_lang'] ?? 'fr';

// S'assurer que la langue existe dans le dictionnaire
if (!isset($translations[$current_lang])) {
    $current_lang = 'fr';
}

/**
 * Traduit une clé en fonction de la langue actuelle.
 * @param string $key La clé de traduction.
 * @param array $args Arguments pour sprintf si nécessaire.
 * @return string
 */
function __t($key, $args = []) {
    global $translations, $current_lang;
    
    $text = $translations[$current_lang][$key] ?? $key;
    
    if (!empty($args)) {
        return vsprintf($text, (array)$args);
    }
    
    return $text;
}

/**
 * Retourne la langue actuelle.
 */
function getLang() {
    global $current_lang;
    return $current_lang;
}
?>
