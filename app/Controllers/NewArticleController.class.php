<?php


/**
 * Ovladac zajistujici vypsani formulare pro pridani noveho clanku.
 */
class NewArticleController implements IController
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
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle): string
    {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];


        // nazev
        $tplData['title'] = $pageTitle;

        // zajisteni nahrani pdf souboru uzivatelem
        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['name'] != '') {
            $this->uploadFiles();
        }

        // zajisteni editace clanku
        if (isset($_POST['editArticleAction']) && isset($_POST['editArticleId'])) {
            $res = $this->db->editArticle($_POST['editArticleId'], $_POST['newArticleText'], $_POST['newTitle']);
            $tplData['edit'] = $res ? "OK: Článek byl upraven." : "ERROR: Editace článku se nezdařila.";
        }

        // kontrola, zda prihlaseny uzivatel ma pravo clanek editovat
        if (isset($_GET['id']) && $this->db->getArticle($_GET['id'])['autor_id'] == $_SESSION['current_user_id']) {
            $tplData['editovanyClanek'] = $this->db->getArticle($_GET['id']);
        } elseif(isset($_GET['id'])) {
            // presmerovani neautorizovaneho uzivatele
            echo "<script>alert('K editaci tohoto článku nemáte oprávnění');
            window.location.replace('http://localhost/prispevky');
            </script>";
        }

        // zajisteni pridani noveho clanku
        if (isset($_POST['newArticleAction']) && $this->db->isUserLogged()) {
            // prirazeni filename pokud je nahrany soubor
            $filename = $_FILES['fileToUpload']['name'] ?: "";
            $res = $this->db->addNewArticle($_SESSION['current_user_id'], $_POST['newTitle'], $_POST['newArticleText'], $filename);
            $tplData['article'] = $res ? "OK: Článek byl přidán do databáze." : "ERROR: Uložení článku se nezdařilo.";
            
        }

        // vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS . "/NewArticleTemplate.tpl.php");
        
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }
    private function uploadFiles(): void{
        // slozka a soubor pro upload
        $target_dir = "uploads";
        $target_file = $target_dir . "//" . basename($_FILES["fileToUpload"]["name"]);

        // pripona souboru
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Kontrola zda je pripona pdf
        if ($imageFileType == "pdf") {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
        }
    }
}

?>