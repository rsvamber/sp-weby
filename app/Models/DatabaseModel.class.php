
<?php


/**
 * Trida spravujici databazi.
 */
class DatabaseModel
{

    /** @var PDO $pdo  Objekt pracujici s databazi prostrednictvim PDO. */
    private $pdo;
    /** @var MySession $mySession  Vlastni objekt pro spravu session. */
    private $SessionModel;
    /** @var string $userSessionKey  Klicem pro data uzivatele, ktera jsou ulozena v session. */
    private $userSessionKey = "current_user_id";
    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct()
    {
        // inicializace DB
        $this->pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        // vynuceni kodovani UTF-8
        $this->pdo->exec("set names utf8");
        require_once("SessionModel.class.php");
        $this->SessionModel = new SessionModel();
    }    
    /**
     * Vraceni vsech prav
     *
     * @return array      vysledek dotazu
     */
    public function getAllRights() :array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_PRAVO);
        // provede dotaz
        if(!$vystup->execute()){ 
            return null; 
        }
        return $vystup->fetchAll();

    }
    
    /**
     * Ziskani uzivatele podle id
     *
     * @param  mixed $userId    id uzivatele
     * @return array            vysledek dotazu
     */
    public function getUserById(int $userId): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_USER." WHERE id_uzivatel=:id;");
        $params = array(':id' => $userId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetch();
        return isset($vystupniPole) ? $vystupniPole : array();
    }    
    /**
     * Ziskani uzivatele podle 'login'
     *
     * @param  mixed $login     prihlasovaci jmeno
     * @return array            vysledek dotazu
     */
    public function getUserByLogin(string $login): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_USER." WHERE login=:login;");
        $params = array(':login' => $login);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetch();

        return !empty($vystupniPole) ? $vystupniPole : array();
    }    
    /**
     * Ziskani uzivatele podle 'email'
     *
     * @param  mixed $email     email uzivatele
     * @return array            vysledek dotazu
     */
    public function getUserByEmail(string $email): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_USER." WHERE email=:email;");
        $params = array(':email' => $email);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetch();
        return !empty($vystupniPole) ? $vystupniPole : array();
    }
    
    /**
     * Ziskani prava podle 'id'
     *
     * @param  mixed $pravoId       id prava
     * @return array                vysledek dotazu
     */
    public function getPravoById(int $pravoId): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_PRAVO." WHERE id_pravo=:pravoId;");
        $params = array(':pravoId' => $pravoId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetch();
        return isset($vystupniPole) ? $vystupniPole : array();
    }    
    /**
     * Odhlaseni uzivatele
     *
     * @return void
     */
    public function userLogout()
    {
        unset($_SESSION[$this->userSessionKey]);
    }
    
    /**
     * Pridani noveho uzivatele
     *
     * @param  mixed $login         prihlasovaci jmeno uzivatele
     * @param  mixed $heslo         heslo uzivatele
     * @param  mixed $jmeno         jmeno uzivatele
     * @param  mixed $email         email uzivatele
     * @param  mixed $idPravo       id prava (default autor)
     * @return bool                 probehl dotaz?
     */
    public function addNewUser(string $login, string $heslo, string $jmeno, string $email, int $idPravo = 4)
    {
        $dotaz = "INSERT INTO ".TABLE_USER." (id_pravo, jmeno, login, heslo, email) VALUES (?,?,?,?,?);";
        $res = $this->pdo->prepare($dotaz);
        $jmeno = htmlspecialchars($jmeno);
        $login = htmlspecialchars($login);
        $email = htmlspecialchars($email);
        return $res->execute(array($idPravo, $jmeno, $login, password_hash($heslo, PASSWORD_BCRYPT), $email));
    }
    
    /**
     * Pridani noveho clanku
     *
     * @param  mixed $autor_id          id autora clanku
     * @param  mixed $nazev             titulek clanku
     * @param  mixed $obsah             obsah clanku
     * @param  mixed $filename          pripadne jmeno souboru
     * @return bool                     probehl dotaz?
     */
    public function addNewArticle(string $autor_id, string $nazev, string $obsah, string $filename)
    {

        $dotaz = "INSERT INTO ".TABLE_ARTICLES." (nazev, autor_id, obsah, filename) VALUES (?,?,?,?);";
        $res = $this->pdo->prepare($dotaz);

        // diky ckeditoru nemusime pouzivat funkci i na obsah - dela to interne
        $nazev = htmlspecialchars($nazev);
        $filename = htmlspecialchars($filename);

        // provedu dotaz a vratim jeho vysledek
        return $res->execute(array($nazev, $autor_id, $obsah, $filename));

    }
    
    /**
     * Ziskani clanku podle 'article_id'
     *
     * @param  mixed $article_id        id clanku
     * @return array                    vysledek dotazu
     */
    public function getArticle(string $article_id) : array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_ARTICLES." WHERE id=:article_id;");
        $params = array(':article_id' => $article_id);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetch();
        return isset($vystupniPole) ? $vystupniPole : array();
    }
    
    /**
     * Funkce vraci, zda je uzivatel admin
     *
     * @return bool         vysledek dotazu
     */
    public function isUserAdmin(): bool
    {
        if ($this->isUserLogged()) {
            $userId = $_SESSION[$this->userSessionKey];

            $user = $this->getUserById($userId);
            if ($user['id_pravo'] <= 2) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Kontrola, zda je uzivatel prihlasen
     *
     * @return void
     */
    public function isUserLogged()
    {   
        return isset($_SESSION[$this->userSessionKey]);
    }

        
    /**
     * Editace clanku
     *  
     * @param  mixed $articleId         id clanku k editaci
     * @param  mixed $obsah             novy editovany obsah
     * @param  mixed $titulek           novy editovany titulek
     * @return bool                     vysledek dotazu
     */
    public function editArticle(string $articleId, string $obsah, string $titulek): bool
    {
        $vystup = $this->pdo->prepare("UPDATE ".TABLE_ARTICLES." SET obsah=:obsah, nazev=:titulek WHERE id=:article_id;");
        $params = array(':obsah' => $obsah, ':titulek' => $titulek,':article_id' => $articleId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;
    }    
    /**
     * Update prava u uzivatele
     *
     * @param  mixed $userId        id uzivatele na update
     * @param  mixed $pravoId       nove pravo
     * @return bool                 vysledek dotazu
     */
    public function updateRight(int $userId, int $pravoId): bool
    {
        $vystup = $this->pdo->prepare("UPDATE ".TABLE_USER." SET id_pravo=:pravoId WHERE id_uzivatel=:userId;");
        $params = array(':userId' => $userId, ':pravoId' => $pravoId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;
    }
    
    /**
     * Prihlaseni uzivatele
     *
     * @param  mixed $login         login uzivatele
     * @param  mixed $heslo         heslo uzivatele
     * @return bool                 vysledek dotazu
     */
    public function userLogin(string $login, string $heslo)
    {
        $user = $this->getUserByLogin($login);
        // pokud uzivatel neexistuje, vracime false
        if(empty($user)){
            return false;
        }
        // verifikace hash
        if (!password_verify($heslo, $user['heslo'])) {
            return false;
        }
        // ziskal jsem uzivatele?
        if (count($user)) {
            // ziskal - ulozim ho do session
            $_SESSION[$this->userSessionKey] = $user['id_uzivatel']; 
            return true;
        } else {
            // neziskal jsem uzivatele
            return false;
        }
    }
        
    /**
     * Vrati vsechny clanky z databaze
     *
     * @return array        vysledek dotazu
     */
    public function getAllArticles(): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_ARTICLES);
        // provede dotaz
        if(!$vystup->execute()){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();

    }
    
    /**
     * Vrati vsechny terminy z databaze
     *
     * @return array        vysledek z dotazu
     */
    public function getAllEvents(): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_TERMINY);
        // provede dotaz
        if(!$vystup->execute()){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();
    }
    
    /**
     * Vrati pouze publikovane clanky
     *
     * @return array        vysledek dotazu
     */
    public function getAuthorizedArticles(): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_ARTICLES." WHERE authorized='true'");
        // provede dotaz
        if(!$vystup->execute()){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();
    }
    
    /**
     * Vrati vsechny recenzenty pro dany clanek
     *
     * @param  mixed $articleId         id clanku
     * @return array                    vysledek dotazu
     */
    public function getReviewers(int $articleId): array
    {
        // pripravim dotaz
        $vystup = $this->pdo->prepare("SELECT 
                `uzivatel`.* 
                FROM 
                    " . TABLE_USER . " 
                    JOIN " . TABLE_RECENZOVANO . " ON " . TABLE_USER . ".`id_uzivatel` = " . TABLE_RECENZOVANO . ".`uzivatel` 
                WHERE 
                " . TABLE_RECENZOVANO . ".`clanky`=:articleId;");
        $params = array(':articleId' => $articleId);

        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();

    }
    
    /**
     * Hodnoceni clanku uzivatelem
     *
     * @param  mixed $articleId         id clanku
     * @param  mixed $userId            id uzivatele
     * @param  mixed $positive          je hodnoceni positivni?
     * @return bool                     probehl dotaz?
     */
    public function voteArticleByUser(int $articleId, int $userId, bool $positive): bool
    {

        $dotaz = "INSERT INTO ".TABLE_HODNOCENO." (uzivatel, clanky, positive) VALUES (?,?, ?);";
        $res = $this->pdo->prepare($dotaz);
        return $res->execute(array($userId, $articleId, $positive));

    }
    
    /**
     * Aktualizovat hodnoceni uzivatele
     *
     * @param  mixed $articleId         id clanku na ohodnoceni
     * @param  mixed $userId            id recenzenta
     * @param  mixed $positive          bylo pozitivni?
     * @return bool                     vysledek dotazu
     */
    public function updateVoteByUser(int $articleId, int $userId, bool $positive): bool
    {
        $vystup = $this->pdo->prepare("UPDATE ".TABLE_HODNOCENO." SET positive=:positive WHERE clanky=:articleId AND uzivatel=:userId;");
        $params = array(':articleId' => $articleId, ':userId' => $userId, 'positive' => $positive);

        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;

    }    
    /**
     * Publikovani clanku
     *
     * @param  mixed $articleId         id clanku k publikaci
     * @return bool                     probehl dotaz?
     */
    public function authorizeArticle(int $articleId): bool
    {

        $vystup = $this->pdo->prepare("UPDATE ".TABLE_ARTICLES." SET authorized = 'true' WHERE id=:articleId;");
        $params = array(':articleId' => $articleId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;
    }
    
    /**
     * Zmenit skore clanku
     *
     * @param  mixed $articleId         id clanku
     * @param  mixed $score             skore clanku k aktualizaci 
     * @param  mixed $positive          je pozitivni?
     * @return bool                     probehl dotaz ok?
     */
    public function changeArticleScore(int $articleId, int $score, bool $positive): bool
    {
        $vystup = $this->pdo->prepare("UPDATE ".TABLE_ARTICLES." SET score =:score WHERE id=:articleId;");
        if($positive){
            $score += 1;
        }
        else{
            $score -= 1;
        }

        $params = array(':articleId' => $articleId, ':score' => $score);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;
    }
    
    /**
     * Uuzivatele, kteri clanek jiz recenzovali 
     *
     * @param  mixed $articleId     id clanku
     * @return array                vysledek dotazu
     */
    public function getUpvoteStatus(int $articleId): array
    {
        $vystup = $this->pdo->prepare("SELECT 
                `uzivatel`.* 
                FROM 
                    " . TABLE_USER . " 
                    JOIN " . TABLE_HODNOCENO . " ON " . TABLE_USER . ".`id_uzivatel` = " . TABLE_HODNOCENO . ".`uzivatel` 
                WHERE 
                " . TABLE_HODNOCENO . ".`clanky`=:articleId;");
        $params = array(':articleId' => $articleId);

        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();
    }    
    /**
     * Bylo hodnoceni uzivatele u clanku pozitivni?
     *
     * @param  mixed $userId        id uzivatele
     * @param  mixed $articleId     id clanku
     * @return bool                 vysledek dotazu
     */
    public function getTypeOfRating(int $userId, int $articleId): bool
    {
        $vystup = $this->pdo->prepare("SELECT `positive` FROM " . TABLE_HODNOCENO . " WHERE uzivatel=:userId AND clanky=:articleId;");
        $params = array(':userId' => $userId, ':articleId' => $articleId);

        if(!$vystup->execute($params)){ 
            return false; 
        }
        $vystup = $vystup->fetch();
        return isset($vystup['positive']) ? $vystup['positive'] : false;
    }
    
    /**
     *  Vrati seznam vsech uzivatelu
     *  @return array          vysledek dotazu
     */
    public function getAllUsers(): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_USER);
        // provede dotaz
        if(!$vystup->execute()){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();
    }

    /**
     *  Smaze daneho uzivatele z DB.
     *  @param int $userId          id uzivatele.
     */
    public function deleteUser(int $userId): bool
    {
        $vystup = $this->pdo->prepare("DELETE FROM ".TABLE_USER." WHERE id_uzivatel=:id_uzivatel;");
        $params = array(':id_uzivatel' => $userId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;
    }
    
    /**
     * Vrati vsechny recenzenty
     *
     * @return array        vysledek dotazu
     */
    public function getAllReviewers(): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_USER." WHERE id_pravo=3");
        // provede dotaz
        if(!$vystup->execute()){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();
    }
    
    /**
     * Priradi recenzenty k calnku
     *
     * @param  mixed $rev1_id       id prvniho recenzenta
     * @param  mixed $rev2_id       id druheho recenzenta
     * @param  mixed $rev3_id       id tretiho recenzenta
     * @param  mixed $article_id    id clanku
     * @return bool                 vysledek dotazu
     */
    public function addReviewers(int $rev1_id, int $rev2_id, int $rev3_id, int $article_id): bool
    {
        // odstranime dosavadni recenzenty
        $vystup = $this->pdo->prepare("DELETE FROM ".TABLE_RECENZOVANO." WHERE clanky=:article_id;");
        $params = array(':article_id' => $article_id);
        
        // pridame nove recenzenty
        if($vystup->execute($params)){ 
            $dotaz = "INSERT IGNORE INTO ".TABLE_RECENZOVANO." (uzivatel, clanky) VALUES (?,?);";
            $res = $this->pdo->prepare($dotaz);
            if($res->execute(array($rev1_id, $article_id)) && $res->execute(array($rev2_id, $article_id)) && $res->execute(array($rev3_id, $article_id))) {
                return true;
            }
            return false;
        }
        else {
            return false;
        }

    }
    
    /**
     * Smaze clanek z databaze
     *
     * @param  mixed $articleId     id clanku
     * @return bool                 vysledek dotazu
     */
    public function deleteArticle(int $articleId): bool
    {
        $vystup = $this->pdo->prepare("DELETE FROM ".TABLE_ARTICLES." WHERE id=:articleId;");
        $params = array(':articleId' => $articleId);
        // provede dotaz
        if(!$vystup->execute($params)){ 
            return false; 
        }
        return true;
    }    
    /**
     * Ziskat clanky napsane uzivatelem
     *
     * @param  mixed $authorId      id autora
     * @return array                vysledek dotazu
     */
    public function getArticlesByAuthor(int $authorId): array
    {
        $vystup = $this->pdo->prepare("SELECT * FROM ".TABLE_ARTICLES." WHERE autor_id=:authorId");
        $params = array(':authorId' => $authorId);

        // provede dotaz
        if(!$vystup->execute($params)){ 
            return array(); 
        }
        $vystupniPole = $vystup->fetchAll();
        return isset($vystupniPole) ? $vystupniPole : array();
    }
}
