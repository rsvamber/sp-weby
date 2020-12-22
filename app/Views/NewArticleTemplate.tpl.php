<?php

global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();


?>
<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Vypis obsahu sablony -->
<?php
// muze se hodit: strtotime($d['date'])

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);

// navbar ucely
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
            <?php 

                // alerty
                if (isset($tplData['edit'])) {
                    echo "<div class='alert alert-primary'>$tplData[edit]</div>"; 
                }
                if (isset($tplData['article'])) {
                        echo "<div class='alert alert-primary'>$tplData[article]</div>"; 
                }
                if (isset($tplData['upload'])) {
                    echo "<div class='alert alert-primary'>$tplData[upload]</div>"; 
            }
            ?>
            <div class="prihlaseni scene animated fadeInUp">
                <form action="" method="POST" id="newArticleForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="newTitle">Titulek</label>
                        <input type="text" name="newTitle" class="form-control" placeholder="Zadejte titulek" id='titulekInput' required>
                    </div>
                    <div class="form-group">
                        <label for="newArticleText">Text článku</label>
                        <textarea name="newArticleText" id="editor1" rows="10" cols="80">
                </textarea>
                        <?php if (!isset($tplData['editovanyClanek'])) {                ?>


                            <label id="fileUploadLabel" for="fileToUpload">Přiložit PDF soubor</label>
                            <input name="fileToUpload" accept="application/pdf" id="fileUploadInput" type="file" style='border:0px; margin-left:-12px' class="form-control">
                            <script>
                                // kontrola, zda je soubor pdf
                                $('#fileUploadInput').change(function() {
                                    var fileExtension = $('#fileUploadInput').val().split('\\').pop().split('.').pop();
                                    if (fileExtension != 'pdf') {
                                        $('#sendNewArticleBtn').prop('disabled', true);
                                        alert("Vyberte prosím pdf soubor");
                                    } else {
                                        $('#sendNewArticleBtn').prop('disabled', false);

                                    }


                                });
                                <?php } ?>
                            </script>
                    </div>
                    <?php if (!isset($tplData['editovanyClanek'])) { ?>
                        <button id='sendNewArticleBtn' type="submit" name="newArticleAction" class="btn btn-primary">Odeslat</button>

                    <?php } else { ?>
                        <input type='hidden' name='editArticleId' value='<?php echo $tplData['editovanyClanek']['id'] ?>'>
                        <button id='editArticleBtn' type="submit" name="editArticleAction" class="btn btn-primary">Editovat</button>

                    <?php } ?>

                </form>
            </div>
        </div>
    </div>
</div>


</div>

<script>
    CKEDITOR.replace('editor1');

    <?php if (isset($tplData['editovanyClanek'])) {
        // jinak se CKEditor rozpadl
        $html = str_replace("\r\n", "", $tplData['editovanyClanek']['obsah']);
    ?>
        // nastaveni dat do CKEditoru pri uprave clanku
        CKEDITOR.instances.editor1.setData('<?php echo $html ?>');
        document.getElementById("titulekInput").value = '<?php echo $tplData['editovanyClanek']['nazev'] ?>';


    <?php } ?>
</script>

<?php 
// paticka
$tplHeaders->getHTMLFooter($tplData['title']);
?>