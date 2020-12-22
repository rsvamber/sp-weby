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
<div class='container'>
    <div class='row'>
        <div class='col'>
            <div class='card'>
                <div class='card-body'>
                <?php 
                // alerty pri akcich
                if (isset($tplData['logout'])) {
                    echo "<div class='alert alert-primary'>$tplData[logout]</div>"; 
                }
                if (isset($tplData['login'])) {
                    echo "<div class='alert alert-primary'>$tplData[login]</div>"; 
                }
            ?>
                    <div class="prihlaseni scene animated fadeIn">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="inputLogin">Uživatelské jméno</label>
                                <input type="text" name="userlogin" class="form-control" id="inputLogin" placeholder="Zadejte jméno">
                            </div>
                            <div class="form-group">
                                <label for="inputPassword">Heslo</label>
                                <input type="password" name="hesloLogin" class="form-control" id="inputPassword" placeholder="Heslo">
                            </div>
                            <button type="submit" name="loginAction" class="btn btn-primary">Odeslat</button>
                            <p>Nový uživatel? <a href="/registration"> Zaregistrovat se</a></p>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</div>
<?php $tplHeaders->getHTMLFooter($tplData['title']);
?>