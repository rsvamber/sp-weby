<?php

global $tplData;
$fileDir = "upload";
// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

?>
<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Vypis obsahu sablony -->
<?php

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);

// pro ucely navbaru
if ($this->db->isUserLogged()) {
    if ($this->db->isUserAdmin()) {
        $tplHeaders->getUserManagement();
    }
    $tplHeaders->getProfile();
} else {
    $tplHeaders->getLogin();
}

$authorized = false;

// vypis clanku
$res = "";

// bootstrap
$res .= "<div class='container'>";
$res .= "<div class='row'>";
$res .= "<div class='col'>";

// overeni, zda je uzivatel alespon autor
if (isset($tplData['loggedUser']['pravoJmeno'])) {
    if ($tplData['loggedUser']['id_pravo'] <= 4) {
        $res .= "<h1> <b>Přidat článek</b>  <a href='/novyPrispevek' title='Přidat článek'> <i class='fas fa-plus fa-xs'></i></a> </h1>";
    }
    if ($tplData['loggedUser']['id_pravo'] <= 3) {
        $authorized = true;
    }
}
// nastaveni, ktere prispevky uzivatel uvidi (publikovane ci nikoliv)
$articles = ($authorized) ? ('allArticles') : ('authorizedArticles');

// pro ucely alertu
if (isset($tplData['delete'])) {
    echo "<div class='alert alert-primary'>$tplData[delete]</div>";
}
if (isset($tplData['upvote'])) {
    echo "<div class='alert alert-primary'>$tplData[upvote]</div>";
}
if (isset($tplData['reviewers'])) {
    echo "<div class='alert alert-primary'>$tplData[reviewers]</div>";
}

if (array_key_exists($articles, $tplData)) {
    // id pro ucely recenzentu
    $id = -1;

    // pro kazdy clanek vypiseme kartu s obsahem
    foreach ($tplData[$articles] as $d) {
        $res .= "<div class='card animated fadeIn'>";

        $id += 1;

        // je uzivatel recenzentem tohoto clanku?
        $reviewerFlag = false;

        // hodnotil jiz uzivatel tento clanek?
        $reviewedFlag = false;

        // kontrola, zda je prihlaseny uzivatel recenzentem
        if (isset($d['reviewers']) && isset($tplData['loggedUser']['login'])) {
            foreach ($d['reviewers'] as $reviewer) {
                if (in_array($tplData['loggedUser']['login'], $reviewer)) {
                    $reviewerFlag = true;
                }
            }
        }

        $res .= "   <div class='card-body'>";
        $res .= "       <h2 class='card-title'>$d[nazev]</h2>";

        // pro ucely moznosti zmenit hodnoceni (kdyz clanek neni publikovan)
        if (isset($tplData['loggedUser']['pravoJmeno']) && $tplData['loggedUser']['id_pravo'] <= 3) {
            $res .= "<form action='' method='POST'>";
            foreach ($d['hodnoceno'] as $user) {
                if (in_array($_SESSION['current_user_id'], $user)) {
                    $reviewedFlag = true;
                    $positiveReview = $d['positive'];
                }
            }

            // pokud uz je clanek publikovan, nejde hodnotit
            if ($d['authorized'] == 'true') {
                $res .= "<button type='submit' name='upvoteArticleAction' id='upvoteButton' class='btn' disabled> <i style='color: gray;' class='fas fa-thumbs-up fa-l'></i> </button>";
                $res .= "<button type='submit' name='downvoteArticleAction' id='downvoteButton' class='btn' disabled> <i style='color: gray;' class='fas fa-thumbs-down fa-l'></i> </button>";
            } 
            // pokud muze hodnotit clanek ale jiz ho hodnotil
            else if($reviewerFlag && $reviewedFlag){
                
                //zanechal pozitivni hodnoceni?
                if($positiveReview){
                    $res .= "<button type='submit' name='upvoteArticleAction' id='upvoteButton' class='btn' disabled> <i style='color: green;' class='fas fa-thumbs-up fa-l'></i> </button>";
                    $res .= "<button type='submit' name='downvoteArticleAction' id='downvoteButton' class='btn'> <i style='color: gray;' class='fas fa-thumbs-down fa-l'></i> </button>";
                }
                else{
                    $res .= "<button type='submit' name='upvoteArticleAction' id='upvoteButton' class='btn'> <i style='color: gray;' class='fas fa-thumbs-up fa-l'></i> </button>";
                    $res .= "<button type='submit' name='downvoteArticleAction' id='downvoteButton' class='btn' disabled> <i style='color: red;' class='fas fa-thumbs-down fa-l'></i> </button>";
                }
                $res .= "<input type='hidden' name='voteArticleId' value=" . $d['id'] . ">";
                $res .= "<input type='hidden' name='voteUserId' value=" . $_SESSION['current_user_id'] . ">";
                $res .= "<input type='hidden' name='updateExistingVote' value=true>";

            }
            // pokud jeste nehodnotil
            else if ($reviewerFlag) {
                $res .= "<button type='submit' name='upvoteArticleAction' id='upvoteButton' class='btn'> <i style='color: green;' class='fas fa-thumbs-up fa-l'></i> </button>";
                $res .= "<button type='submit' name='downvoteArticleAction' id='downvoteButton' class='btn'> <i style='color: red;' class='fas fa-thumbs-down fa-l'></i> </button>";
                $res .= "<input type='hidden' name='voteArticleId' value=" . $d['id'] . ">";
                $res .= "<input type='hidden' name='voteUserId' value=" . $_SESSION['current_user_id'] . ">";
            }

            $res .= "</form>";
        }

        // kontrola, zda ma uzivatel pravo videt hodnoceni clanku a ci je schvalen + recenzentu     
        if (isset($tplData['loggedUser']['id_pravo']) && $tplData['loggedUser']['id_pravo'] <= 3 || isset($tplData['loggedUser']['id_uzivatel']) && ($d['autor_id'] == $tplData['loggedUser']['id_uzivatel'])) {
            $formatter = new NumberFormatter('en_GB', NumberFormatter::DECIMAL);
            $formatter->setTextAttribute(NumberFormatter::POSITIVE_PREFIX, '+');
            if ($d['authorized'] == 'true') {
                $res .= "<b style='color:green;'>Schváleno " . $formatter->format($d['score']) . "</b>  <br><br>";
            } else {
                $res .= "<b style='color:red;'>Neschváleno " . $formatter->format($d['score']) . "</b><br><br>";
            }
            // vypis recenzentu
            $res .= "<b>Recenzenti: </b>";

            // seznam recenzentu
            $reviewerList = [];
            if (isset($d['reviewers'])) {

                // pokud je uzivatel admin, tak muze urcovat recenzenty
                if (isset($tplData['loggedUser']['pravoJmeno']) && $tplData['loggedUser']['id_pravo'] <= 2) {
                    $res .= "<form method='post' id='confirmReviewers" . $id . "'>";
                    for ($k = 0; $k < 3; $k++) {
                        $selectName = "selectReviewer" . $k;
                        $optionName = "optionReviewer" . $k;
                        $res .= "<select name='$selectName' required>";
                        $selectedFlag = false;

                        // vybrani vsech recenzentu
                        foreach ($this->db->getAllReviewers() as $r) {
                            // vybrani recenzentu daneho clanku
                            foreach ($d['reviewers'] as $reviewer) {
                                    if(!$selectedFlag){
                                        $whitelist = array('login');

                                        // aby se hledalo pouze v 'login', nikoliv v celem poli s udaji uzivatele (jmeno atd)
                                        $reviewer = array_intersect_key($reviewer, array_flip($whitelist));
                                        if (in_array($r['login'], $reviewer) && !in_array($r['login'], $reviewerList)) {
                                    
                                            // vlajka pro duplicitni recenzenty (vice selectu)
                                            $reviewerFlag = true;
                                            $reviewerList[] = $r['login'];
                                        }
                                
                                
                                    }
                                }
                                // jako default se nastavi jiz vybrani recenzenti
                                if ($reviewerFlag) {
                                    $res .= "<option name='$optionName' value='$r[login]' selected>$r[jmeno]</option>";
                                    $selectedFlag = true;
                                } else {
                                    $res .= "<option name='$optionName' value='$r[login]'>$r[jmeno]</option>";
                                }
                                $reviewerFlag = false;
                            }
                        
                        
                        // defaultni vypis
                        if (count($reviewerList) <= $k || empty($reviewerList)) {
                            $res .= "<option disabled selected value> -- vyberte recenzenta -- </option>";
                        }
                        $res .= "</select>";
                        $reviewerFlag = false;
                    }
                    $reviewerList = [];
                    
                    $res .= "</form>";
                } else {
                    foreach ($d['reviewers'] as $reviewer) {
                        $res .= $reviewer['jmeno'];
                        $res .= ', ';
                    }
                }

                $reviewerFlag = false;
            }

            $res = rtrim($res, ", ");
            $res .= "<br>";
        }

        // vypis prilohy a moznost ji stahnout
        if ($d['priloha'] != "") {
            $res .= "<b>Příloha:</b> <a href='$fileDir/$d[priloha]' download>$d[priloha]</a><br><br>";
        }

        //vypis autora
        $res .= "<b>Autor:</b> $d[jmeno] (" . date("d. m. Y, H:i.s", strtotime($d['timestamp'])) . ")<br><br>";
        $res .= "<p class='card-text'> $d[obsah] </p>";
        $res .= "<div class='btn-group'>";

        // pokud je uzivatel admin, muze odstranovat clanky a potvrzovat recenzenty
        if (isset($tplData['loggedUser']['pravoJmeno']) && $tplData['loggedUser']['id_pravo'] <= 2) {
            $res .= "<form action='' method='POST'>";
            $res .= "<input type='hidden' name='deleteArticleId' value=" . $d['id'] . ">";
            $res .= "<button type='submit' name='deleteArticleAction' class='btn btn-danger'>Odstranit článek</button>";
            $res .= "</form>";
            $res .= "<input type='hidden' form='confirmReviewers" . $id . "' name='confirmReviewersArticleId' value=" . $d['id'] . ">";

            $res .= "<button type='submit' form='confirmReviewers" . $id . "' name='confirmReviewersAction' class='btn btn-success'>Potvrdit recenzenty</button><hr>";
        } 

        // pokud je uzivatel autorem a clanek neni dosud publikovan, muze ho editovat
        if (isset($tplData['loggedUser']['pravoJmeno']) && ($d['autor_id'] == $tplData['loggedUser']['id_uzivatel']) && $d['authorized'] == 'false') {
            $url = "/novyPrispevek/" . $d['id'];
            $res .= "<a href='" . $url . "' class='btn btn-success'>Editovat článek</a>";
        }
        $res .= "</div>";
        $res .= "</div>";
        $res .= "</div>";
        $res .= "<br>";
    }
} else {
    $res .= "Články nenalezeny";
}
echo $res;
echo "</div>";

$tplHeaders->getHTMLFooter($tplData['title']);

?>
