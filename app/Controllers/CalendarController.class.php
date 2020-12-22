<?php

/**
 * Ovladac zajistujici vypsani terminu.
 */
class CalendarController implements IController {

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
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;
        
        // nacitani informaci o prihlasenem uzivateli
        if($this->db->isUserLogged()){
            $tplData['loggedUser'] = $this->db->getUserById($_SESSION['current_user_id']);
            $idPravo = $tplData['loggedUser']['id_pravo'];
            $tplData['loggedUser']['pravoJmeno'] = $this->db->getPravoById($idPravo)[1]; 
        }

        // nacteni vsech terminu
        $tplData['allEvents'] = $this->db->getAllEvents();

        // pridani autora terminu skrz jeho id
        for($i = 0; $i < count($tplData['allEvents']); $i++){
            $tplData['allEvents'][$i]['jmeno'] = $this->db->getUserById($tplData['allEvents'][$i]['autor_id'])['jmeno'];
        } 

        // vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();

        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/CalendarTemplate.tpl.php");
        
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }
    
}
