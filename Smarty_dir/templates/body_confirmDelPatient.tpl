<div class="title infomsg">
    <img src="images/warning.png">
    <p>Confermi di voler cancellare il paziente selezionato ?</p>
    <p>Verrano eliminate anche tutte le sue visite</p>
    <p>Non sar&aacute possibile recuperare i dati in futuro</p>
</div>

<div class="spacing"></div>

<form method="POST" class="button-form">
    <button class="button" type="submit" formaction="index.php?control=manageDB&action=delPat&conf=yes&p={$cf}">conferma</button>
    <button class="button" type="submit" formaction="index.php?control=manageDB">annulla</button>
</form>
    
<div class="spacing"></div>