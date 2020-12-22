<?php

/**
 * Ovladac zajistujici vypsani udaje o konferenci.
 */
class ConferenceController implements IController {

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
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):string {
        // vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        // nazev
        $tplData['title'] = $pageTitle;    
        ob_start();
        
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/ConferenceTemplate.tpl.php");

        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;

        
    }
    
}
