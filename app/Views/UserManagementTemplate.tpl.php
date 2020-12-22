<?php
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  ///////////
///////////////////////////////////////////////////////////////////////////

//// pozn.: sablona je samostatna a provadi primy vypis do vystupu:
// -> lze testovat bez zbytku aplikace.
// -> pri vyuziti Twigu se sablona obejde bez PHP.

/*
////// Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
//// UKAZKA DAT: Uvod bude vypisovat informace z tabulky, ktera ma nasledujici sloupce:
// id, date, author, title, text
$tplData['title'] = "Sprava uživatelů (TPL)";
$tplData['users'] = [
    array("id_user" => 1, "first_name" => "František", "last_name" => "Noha",
            "login" => "frnoha", "password" => "Tajne*Heslo", "email" => "fr.noha@ukazka.zcu.cz", "web" => "www.zcu.cz")
];
$tplData['delete'] = "Úspěšné mazání.";
define("DIRECTORY_VIEWS", "../Views");
const WEB_PAGES = array(
    "uvod" => array("title" => "Sprava uživatelů (TPL)")
);
////// KONEC: Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
*/


//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

?>
<!-- ------------------------------------------------------------------------------------------------------- -->
<?php

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);
if ($this->db->isUserLogged()) {
    if ($this->db->isUserAdmin()) {
        $tplHeaders->getUserManagement();
    }
    $tplHeaders->getProfile();
} else {
    $tplHeaders->getLogin();
}

$res = "<div class='container'>"; 
$res .= "<div class='row animated fadeIn'>"; 
$res .= "<div class='mt-2 text-center table-responsive'>";

// alerty
if (isset($tplData['delete'])) {
    echo "<div class='alert alert-primary'>$tplData[delete]</div>";
}
if (isset($tplData['update'])) {
    echo "<div class='alert alert-primary'>$tplData[update]</div>";
}
$res .= "<table class='table table-bordered table-sm table-hover'>";
$res .= "<thead class='thead-dark'><tr><th scope='col'>ID</th><th scope='col'>Jméno</th><th scope='col'>Login</th><th>E-mail</th><th scope='col'>Role</th><th colspan='2' scope='col'>Akce</th></tr></thead>";

// vypsani jednotlivych uzivatelu do tabulky
foreach ($tplData['users'] as $u) {
    $res .= "<tr><td>$u[id_uzivatel]</td><td>$u[jmeno]</td><td>$u[login]</td><td>$u[email]</td>";
    $res .= "<td> <form method='post'> <select name='selectPravo' required>";
    foreach ($this->db->getAllRights() as $r) {
        if ($r['id_pravo'] == $u['id_pravo']) {
            $res .= "<option value='$r[id_pravo]' selected>$r[nazev]</option>";
        } else {
            $res .= "<option value='$r[id_pravo]'>$r[nazev]</option>";
        }
    }

    $res .= "</select></td>";


    $res .= "<td>"
        . "<input type='hidden' name='id_uzivatel' value='$u[id_uzivatel]'>"
        . "<button class='btn btn-success' type='submit' name='update'><i class='far fa-save'></i></button>"
        . "</form></td>"
        . "<td><form method='post'>"
        . "<input type='hidden' name='id_uzivatel' value='$u[id_uzivatel]'>"
        . "<button class='btn btn-danger' type='submit' name='action' value='delete'><i class='fas fa-trash'></i></button>"
        . "</form></td></tr>";
}

$res .= "</table>";
echo $res;

// paticka
$tplHeaders->getHTMLFooter($tplData['title']);
?>