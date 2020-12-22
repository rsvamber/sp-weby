<?php

global $tplData;
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

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

?>
<div class="container">
    <div class="row animated fadeIn">
        <div class='col'>
            <div class='card userInfoCard'>
                <?php
                // alerty
                if (isset($tplData['login'])) {
                    echo "<div class='alert alert-primary'>$tplData[login]</div>"; 
                }
                if (isset($tplData['register'])) {
                    echo "<div class='alert alert-primary'>$tplData[register]</div>"; 
                }
                ?>
                <div class="card-body">
                    <h2 class="card-title">Profil</h2>
                    <h6 class="card-text">Login </h6><?php echo $tplData['loggedUser']['login']; ?>
                    <h6 class="card-text">Jméno </h6><?php echo $tplData['loggedUser']['jmeno']; ?>
                    <h6 class="card-text">E-mail </h6><?php echo $tplData['loggedUser']['email']; ?>
                    <h6 class="card-text">Právo </h6> <?php echo $tplData['loggedUser']['pravoJmeno']; ?>
                    <br>
                    <br>
                    <form action="" method="POST">
                        <input class='btn btn-primary' type="submit" name="logoutAction" value="Odhlásit se">
                    </form>
                </div>
            </div>
        </div>
        <div class='card userInfoCard'>
                <div class="card-body">
                    <h2 class="card-title">Oprávnění</h2>
                    <?php echo $tplData['loggedUser']['popisPravo']; ?><br>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// alerty
$tplHeaders->getHTMLFooter($tplData['title']);
?>