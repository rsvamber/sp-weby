<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////


//// Pripojeni k databazi ////

/** Adresa serveru. */
define("DB_SERVER","localhost"); // https://students.kiv.zcu.cz lze 147.228.63.10, ale musite byt na VPN
/** Nazev databaze. */
define("DB_NAME","databaze");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");


//// Nazvy tabulek v DB ////

define("TABLE_ARTICLES", "clanky");
/** Tabulka s uzivateli. */
define("TABLE_USER", "uzivatel");

define("TABLE_PRAVO","pravo");

define("TABLE_TERMINY","terminy");

define("TABLE_HODNOCENO","hodnoceno");

define("TABLE_RECENZOVANO","recenzovano");



//// Dostupne stranky webu ////

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "app\Controllers";
/** Adresar modelu. */
const DIRECTORY_MODELS = "app\Models";
/** Adresar sablon */
const DIRECTORY_VIEWS = "app\Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Dostupne webove stranky. */
const WEB_PAGES = array(
    "prispevky" => array(
        "title" => "Přehled příspěvků",

        //// kontroler
        "file_name" => "ArticlesController.class.php",
        "class_name" => "ArticlesController",
    ),

    "error" => array(
        "title" => "Error",

        //// kontroler
        "file_name" => "ErrorPageController.class.php",
        "class_name" => "ErrorPageController",
    ),

    "sprava" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "file_name" => "UserManagementController.class.php",
        "class_name" => "UserManagementController",
    ),

    "novyPrispevek" => array(
        "title" => "Přidat nový příspěvek",

        //// kontroler
        "file_name" => "NewArticleController.class.php",
        "class_name" => "NewArticleController",
    ),

    "uvod" => array(
        "title" => "Úvodní stránka",

        //// kontroler
        "file_name" => "IntroductionController.class.php",
        "class_name" => "IntroductionController",
    ),

    "konference" => array(
        "title" => "O nás",

        //// kontroler
        "file_name" => "ConferenceController.class.php",
        "class_name" => "ConferenceController",
    ),

    "login" => array(
        "title" => "Přihlášení",

        //// kontroler
        "file_name" => "LoginController.class.php",
        "class_name" => "LoginController",



    ),

    "userInfo" => array(
        "title" => "Informace o uživateli",

        //// kontroler
        "file_name" => "UserInfoController.class.php",
        "class_name" => "UserInfoController",

    ),

    "terminy" => array(
        "title" => "Termíny konference",

        //// kontroler
        "file_name" => "CalendarController.class.php",
        "class_name" => "CalendarController",

    ),

    "registration" => array(
        "title" => "Registrace",

        //// kontroler
        "file_name" => "RegistrationController.class.php",
        "class_name" => "RegistrationController",



    ),

    "sprava" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "file_name" => "UserManagementController.class.php",
        "class_name" => "UserManagementController",
    ),
);

?>
