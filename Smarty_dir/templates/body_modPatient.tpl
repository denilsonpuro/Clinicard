<div class="title">
    <p>MODIFICA I DATI DEL PAZIENTE</p>
</div>

<div class="spacing"></div>

<!--<form clas method="POST" class="mod-pat" action="index.php?control=manageDB&action=modPat&mod=completed&p={$link}">
    <table>
        
        <tr>
            <td class="field-label"> Nome: </td>
            <td> <input class="input-field" type="text" name="name" id="name-pat"/> </td>
        </tr>
        
        <tr>
            <td class="field-label"> Cognome: </td>
            <td> <input class="input-field" type="text" name="surname" id="surname-pat"/> </td>
        </tr>
        
        <tr>
            <td class="field-label">Sesso: </td>
            <td>
                <input type="radio" name="gender" id="male" value="M"> M 
                <input type="radio" name="gender" id="female" value="F"> F 
            </td>
        </tr>
        
        <tr>
            <td class="field-label">Data di nascita:</td>
            <td> <input class="input-field" type="date" name="dateBirth" id="dateB-pat" /> </td>
        </tr>
        
        <tr>
            <td class="field-label"> Codice Fiscale: </td>
            <td> <input class="input-field" type="text" name="CF" id="cf-pat"/> </td>
        </tr>
        
        <tr>
            <td></td>
        </tr>
        
        <tr>
            <td> <button class="button" type="submit"/>invia dati</button> </td>
            <td> <button class="button" type="reset"/>reset</button> </td>
        </tr>
        
    </table>
</form>-->

<form id="modPatient" method="POST" action="index.php?control=manageDB&action=modPat&mod=completed&p={$link}">
    <div class="row">
        <div class="row-element">
            <p class="label"><label>Nome</label></p>
            <p><input class="input-field" type="text" name="name" id="name"/></p>
        </div>
    </div>
    
    <div class="row">
        <div class="row-element">
            <p class="label"><label>Cognome</label></p>
            <p><input class="input-field" type="text" name="surname" id="surname"/></p>
        </div>
    </div>
    
    <div class="row">
        <div class="row-element">
            <p class="label"><label>Sesso</label></p>
            <p>
                <input type="radio" name="gender" value="M"> M
                <input type="radio" name="gender" value="F"> F
            </p>
        </div>
    </div>
    
    <div class="row">
        <div class="row-element">
            <p class="label"><label>Data di nascita</label></p>
            <p><input class="input-field" type="date" name="dateBirth" id="dateB"/></p>
        </div>
    </div>
    
    <div class="row">
        <div class="row-element">
            <p class="label"><label>Codice Fiscale</label></p>
            <p><input class="input-field" type="text" name="CF" id="CF"/></p>
        </div>
    </div>
    
    <div class="row">
        <p> <button class="a_demo_three" type="submit"/>invia dati</button>
         <button class="a_demo_three" type="reset"/>reset</button> </p>
    </div>
</form>
    
<div class="spacing"></div>