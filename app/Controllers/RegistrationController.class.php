<?php


/**
 * Ovladac zajistujici vypsani registracniho formulare.
 * @package kivweb\Controllers
 */
class RegistrationController implements IController
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
        global $tplData;
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;

        // seznam vsech prav z databaze
        $tplData['rights'] = $this->db->getAllRights();

        // provedeni akce odhlaseni
        if (isset($_POST['logoutAction'])) {
            $this->db->userLogout();
        }
        
        // zajisteni registrace uzivatele
        if (isset($_POST['registrationAction'])) {
            // mam vsechny pozadovane hodnoty?
            if (
                isset($_POST['login']) && isset($_POST['heslo']) && isset($_POST['heslo2'])
                && isset($_POST['jmeno']) && isset($_POST['email'])
                && $_POST['heslo'] == $_POST['heslo2']
                && $_POST['login'] != "" && $_POST['heslo'] != "" && $_POST['jmeno'] != "" && $_POST['email'] != ""

            ) {

                // overeni, ze login ci email jiz neni zaregistrovan
                if (isset($this->db->getUserByEmail($_POST['email'])['id_uzivatel']) || isset($this->db->getUserByLogin($_POST['login'])['id_uzivatel'])) {
                    $tplData['register'] = "ERROR: Uživatel či email je již zaregistrován.";

                // pokud neni, uzivatele pokusim zaregistrovat
                } else {
                    $res = $this->db->addNewUser($_POST['login'], $_POST['heslo'], $_POST['jmeno'], $_POST['email']);
                    $tplData['register'] = $res ? "OK: Uživatel byl přidán do databáze." : "ERROR: Uložení uživatele se nezdařilo.";
                    $this->db->userLogin($_POST['login'], $_POST['heslo']);
                }
            } else {
                // nemam vsechny atributy pro registraci
                $tplData['register'] = "ERROR: Nebyly přijaty požadované atributy uživatele.";
            }
        }

        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();

        // pokud uzivatel neni prihlaseny, vypiseme mu registracni formular
        if (!$this->db->isUserLogged()) {
            require(DIRECTORY_VIEWS . "/RegistrationTemplate.tpl.php");
            // ziskam obsah output bufferu, tj. vypsanou sablonu
            $obsah = ob_get_clean();

            // vratim sablonu naplnenou daty
            return $obsah;
        }

        // pokud prihlaseny je, presmerujeme ho na informace o uzivateli
        $tplData['loggedUser'] = $this->db->getUserById($_SESSION['current_user_id']);
        $idPravo = $tplData['loggedUser']['id_pravo'];
        $tplData['loggedUser']['pravoJmeno'] = $this->db->getPravoById($idPravo)[1];
        $tplData['loggedUser']['popisPravo'] = $this->db->getPravoById($idPravo)['popisPravo'];

        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS . "/UserInfoTemplate.tpl.php");

        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }
}

?>