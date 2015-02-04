<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
    <head>
	<title>Clinicard</title>
        <link href='http://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
        <!--<link rel="shortcut icon" type="image/x-icon" href="images/pageicon.png" /><!-- website icon -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon">
        <link href="css/style.css" rel="stylesheet" type="text/css"  media="all" />
        <link href="css/myStyle.css" rel="stylesheet" type="text/css"  media="all" />
        <link href="css/style4.css" rel="stylesheet" type="text/css"  media="all" />
        <link href="css/style3.css" rel="stylesheet" type="text/css"  media="all" />
        <meta name="keywords" content="Healthy iphone web template, Andriod web template, Smartphone web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
        <link rel="stylesheet" href="css/responsiveslides.css">
        <link rel="stylesheet" href="css/myStyle.css">
        <script src="./script/jquery.js"></script>
        <script src="./script/jquery.cookie.js"></script>
        <script src="./script/script.js"></script>
        {if isset($header)}
            {$header}
        {/if}
        
        <noscript>
            <div id="script-disabled" class="errormsg">
                Per avere a disposizione tutte le funzionalit&agrave; di questa applicazione web &egrave necessario abilitare
                Javascript. Qui ci sono tutte le <a href="http://www.enable-javascript.com/it/"
                target="_blank"> istruzioni su come abilitare JavaScript nel tuo browser</a>.
            </div>
        </noscript>
        
        <div id="cookie-disabled" class="errormsg" hidden>
            I cookie sono disabilitati. Affinch&egrave; questa applicazione funzioni al meglio &egrave; necessario
            che siano attivati.
        </div>
        
        <div id="cookie-bar" hidden>
            <p>Questa applicazione web utilizza i cookie. Cliccando accetta acconsenti al loro utilizzo.<a href="" id="cookie-accepted" class="cb-enable">Accetta</a></p>
        </div>


    </head>
    
    <body>
        <div class="header">
            <div class="wrap">
                <div class="logo">
                    <a href="index.php"><img src="images/logo_mine.png" title="logo" /></a>
                </div>

<!-- login -->
                <div id="loginBox">
                    {$loginBox}
                </div>





            </div>
            <div class="clear"> </div>
        </div>

        <div class="topnav">
            <ul id="topnav">
                <li class="nav_top">
                    <a href="index.php">Home</a>
                </li>
                <li class="nav_top">
                    <a href="index.php?control=manageDB">DB</a>
                </li>
                <li class="nav_top">
                    <a href="index.php?control=VisitBooking#topnav">Prenota visita</a>
                </li>
                <li class="nav_top">
                    <a href="index.php?control=Registration">Registrazione</a>
                </li>

                <li class="nav_top">
                    <a href="index.php?control=Contacts">Contatti</a>
                </li>
            </ul>
        </div>

        {$body}

        <div class="separate"></div>
        <div class="footer">
            <div class="left-content">
                <a href="index.php"><img src="images/logo_footer.png" title="logo" /></a>
            </div>
            <div class="right-content">
                <p>Healthy  &#169	 All Rights Reserved | Design By <a href="http://w3layouts.com/">W3Layouts</a></p>
            </div>
            <div class="clear"> </div>
        </div>

    </body>
</html>

