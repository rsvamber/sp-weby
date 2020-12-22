<?php

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 */
class UserManagementController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        // inicializace prace s DB
        require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    /**
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        // nazev
        $tplData['title'] = $pageTitle;
        if($this->db->isUserLogged()){
            $pravoId = $this->db->getUserById($_SESSION['current_user_id'])['id_pravo'];
            echo $pravoId;
            if($pravoId != 1){
                echo "<script>alert('Nemáte oprávnění spravovat uživatele');
            window.location.replace('http://localhost/prispevky');
            </script>";
            }
        }
        else{
            echo "<script>alert('Nemáte oprávnění spravovat uživatele');
            window.location.replace('http://localhost/prispevky');
            </script>";
        }

        // zajisteni smazani uzivatele
        if(isset($_POST['action']) and $_POST['action'] == "delete"
            and isset($_POST['id_uzivatel'])
        ){
            $ok = $this->db->deleteUser(intval($_POST['id_uzivatel']));
            // provedlo se smazani?
            if($ok){
                $tplData['delete'] = "OK: Uživatel s ID:$_POST[id_uzivatel] byl smazán z databáze.";
            } else {
                $tplData['delete'] = "CHYBA: Uživatele s ID:$_POST[id_uzivatel] se nepodařilo smazat z databáze.";
            }
        }
        // zajisteni zmeny prav uzivatele
        if(isset($_POST['selectPravo']) && isset($_POST['id_uzivatel'])){
            $ok = $this->db->updateRight($_POST['id_uzivatel'], $_POST['selectPravo']);
            if($ok){
                $tplData['update'] = "OK: Uživateli s ID:$_POST[id_uzivatel] se podařilo nastavit nová práva.";
            } else {
                $tplData['update'] = "CHYBA: Uživateli s ID:$_POST[id_uzivatel] se nepodařilo nastavit práva.";
            }
        }
        // nacitani dat uzivatelu
        $tplData['users'] = $this->db->getAllUsers();
        for($i = 0; $i < count($tplData['users']); $i++){
            // prirazeni prav uzivatelum
            $tplData['users'][$i]['pravoId'] = $this->db->getPravoById($this->db->getUserById($tplData['users'][$i]['id_uzivatel'])['id_pravo'])['id_pravo'];
            $tplData['users'][$i]['pravo'] = $this->db->getPravoById($this->db->getUserById($tplData['users'][$i]['id_uzivatel'])['id_pravo'])['nazev'];

        } 

        // nacteni vsech roli
        $tplData['vsechnyRole'] = $this->db->getAllRights();

        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();

        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/UserManagementTemplate.tpl.php");
        
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}
