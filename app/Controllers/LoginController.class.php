<?php


/**
 * Ovladac zajistujici vypsani prihlasovaciho formulare.
 */
class LoginController implements IController
{

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct()
    {
        // inicializace prace s DB
        require_once(DIRECTORY_MODELS . "/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle): string
    {
        // vsechna data budou globalni
        global $tplData;
        $tplData = [];

        // nazev
        $tplData['title'] = $pageTitle;

        // provedeni akce odhlaseni
        if (isset($_POST['logoutAction'])) {
            $this->db->userLogout();
            $tplData['logout'] = "OK: Uživatel byl odhlášen";
        }
        if (isset($_POST['loginAction']) && isset($_POST['userlogin']) && isset($_POST['hesloLogin'])) {
            $res = $this->db->userLogin($_POST['userlogin'], $_POST['hesloLogin']);
            $tplData['login'] = $res ? "OK: Uživatel přihlášen" : "ERROR: Přihlášení uživatele se nezdařilo";      
        }

        // pokud uzivatel neni prihlaseny, ukazeme mu prihlasovaci formular
        if (!$this->db->isUserLogged()) {
            ob_start();
            require(DIRECTORY_VIEWS . "/LoginTemplate.tpl.php");
            $obsah = ob_get_clean();
            return $obsah;
        }

        // pokud prihlaseny je, presmerujeme ho na informace o uctu
        $tplData['loggedUser'] = $this->db->getUserById($_SESSION['current_user_id']);
        $idPravo = $tplData['loggedUser']['id_pravo'];
        $tplData['loggedUser']['pravoJmeno'] = $this->db->getPravoById($idPravo)[1];
        $tplData['loggedUser']['popisPravo'] = $this->db->getPravoById($idPravo)['popisPravo'];

        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS . "/UserInfoTemplate.tpl.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }
}

?>