<?php

/**
 * Ovladac zajistujici vypsani clanku.
 */
class ArticlesController implements IController
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
        // vsechna data sablony budou globalni
        global $tplData;
        // slozka pro upload souboru
        $fileDir = "uploads";
        $tplData = [];

        // nazev stranky
        $tplData['title'] = $pageTitle;

        // nacteni prava prihlaseneho uzivatele
        if ($this->db->isUserLogged()) {
            $tplData['loggedUser'] = $this->db->getUserById($_SESSION['current_user_id']);
            $idPravo = $tplData['loggedUser']['id_pravo'];
            $tplData['loggedUser']['pravoJmeno'] = $this->db->getPravoById($idPravo)[1];
        }
        // prirazeni recenzentu k clanku
        if (isset($_POST['confirmReviewersAction']) && isset($_POST['confirmReviewersArticleId'])) {
            $res = $this->addReviewers();
            // vrati 1 - vse ok
            if ($res == 1) {
                $tplData['reviewers'] = "Recenzenti uloženi";

            // vrati 2 - duplicitni recenzenti
            } elseif ($res == 2) {
                $tplData['reviewers'] = "Uložení recenzentů se nepodařilo, recenzenti nesmějí být duplicitní";

                // vrati 3 - nebyli vyplneni vsichni recenzenti
            } elseif ($res == 3) {
                $tplData['reviewers'] = "Uložení recenzentů se nepodařilo, vyplňte všechny recenzenty";

            }
        }
        // odstraneni clanku
        if (isset($_POST['deleteArticleAction']) && $this->db->isUserLogged()) {
            // vrati true - vse ok, vrati false - nekde se stala chyba
            $res = $this->deleteArticle($fileDir);
            $tplData['delete'] = $res ? "OK: článek byl odstraněn" : "ERROR: Odstranění článku se nezdařilo";
        }

        if ((isset($_POST['upvoteArticleAction']) || isset($_POST['downvoteArticleAction'])) && $this->db->isUserLogged()) {
            $res = $this->upvoteArticleByUser();
            $tplData['upvote'] = $res ? "OK: článek byl ohodnocen" : "ERROR: Ohodnocení článku se nezdařilo";

        } 
        $tplData['allArticles'] = $this->loadAllArticles();
        $tplData['authorizedArticles'] = $this->loadAuthorizedArticles();

        // vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS . "/ArticlesTemplate.tpl.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
        }

        /**
         * Prida recenzenty do databaze.
         * @return int vysledek, 1 - uspech, 2 - duplicitni recenzenti, 3 - nedostatek recenzentu
         */
        private function addReviewers(): int
        {
            if (isset($_POST['selectReviewer0']) && isset($_POST['selectReviewer1']) && isset($_POST['selectReviewer2'])) {

                $selectedReviewers = array($_POST['selectReviewer0'], $_POST['selectReviewer1'], $_POST['selectReviewer2']);
                    if (count(array_unique($selectedReviewers)) == 3) {
                        $rev1_id = $this->db->getUserByLogin($_POST['selectReviewer0'])['id_uzivatel'];
                        $rev2_id = $this->db->getUserByLogin($_POST['selectReviewer1'])['id_uzivatel'];
                        $rev3_id = $this->db->getUserByLogin($_POST['selectReviewer2'])['id_uzivatel'];
                        $this->db->addReviewers($rev1_id, $rev2_id, $rev3_id, $_POST['confirmReviewersArticleId']);
                        return 1;
                    } else {
                        return 2;
                    }
            } else {
                return 3;
            }
        }
        /**
         * Odstrani clanek z databaze.
         * @return bool uspech smazani
         */
        private function deleteArticle(string $fileDir): bool
        {
            $filename = $this->db->getArticle($_POST['deleteArticleId'])['filename'];
            if ($filename != "") {
                $path = $fileDir . "//" . $filename;
                    if (file_exists($path)) {
                        unlink($path);
                    }
            }
        return $this->db->deleteArticle($_POST['deleteArticleId']);
        }

        /**
         * Ohodnoti clanek uzivatelem
         * @return bool uspech ohodnoceni
         */
        private function upvoteArticleByUser(): bool
        {
            $flag = isset($_POST['upvoteArticleAction']) ? true : false;
            if(isset($_POST['updateExistingVote'])){
                $res = $this->db->updateVoteByUser($_POST['voteArticleId'], $_POST['voteUserId'], $flag);
            }
            else {
                $res = $this->db->voteArticleByUser($_POST['voteArticleId'], $_POST['voteUserId'], $flag);
            }
            $res2 = $this->db->changeArticleScore($_POST['voteArticleId'], $this->db->getArticle($_POST['voteArticleId'])['score'], $flag);
            if ($this->db->getArticle($_POST['voteArticleId'])['score'] >= 3) {
                $this->db->authorizeArticle($_POST['voteArticleId']);
            }
            return ($res && $res2);
        }
        /**
         * Nacte vsechny clanky z databaze (vcetne nepublikovanych).
         * @return array data clanku
         */
        private function loadAllArticles(): array
        {
            $res = $this->db->getAllArticles();
            for ($i = 0; $i < count($res); $i++) {
                $res[$i]['jmeno'] = $this->db->getUserById($res[$i]['autor_id'])['jmeno'];
                $res[$i]['hodnoceno'] = $this->db->getUpvoteStatus($res[$i]['id']);
                if($this->db->isUserLogged()){
                    $res[$i]['positive'] = $this->db->getTypeOfRating($_SESSION['current_user_id'], $res[$i]['id']);
                }
                $res[$i]['reviewers'] = $this->db->getReviewers($res[$i]['id']);
                $res[$i]['priloha'] = $this->db->getArticle($res[$i]['id'])['filename'];
            }
            return $res;
        }
        /**
         * Nacte publikovane clanky z databaze (bez nepublikovanych).
         * @return array data clanku
         */
        private function loadAuthorizedArticles(): array
        {
            $res = $this->db->getAuthorizedArticles();
            if($this->db->isUserLogged()){
                $res2 = $this->db->getArticlesByAuthor($_SESSION['current_user_id']);
                foreach($res2 as $a){
                    if(!in_array($a, $res)){
                        $res[] = $a;
                    }
                }
            }
            
            for ($i = 0; $i < count($res); $i++) {
                $res[$i]['jmeno'] = $this->db->getUserById($res[$i]['autor_id'])['jmeno'];
                $res[$i]['hodnoceno'] = $this->db->getUpvoteStatus($res[$i]['id']);
                $res[$i]['reviewers'] = $this->db->getReviewers($res[$i]['id']);
                $res[$i]['priloha'] = $this->db->getArticle($res[$i]['id'])['filename'];
            }
            return $res;
        }
    }


?>
