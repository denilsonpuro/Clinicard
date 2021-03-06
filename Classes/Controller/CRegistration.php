<?php

class CRegistration {
    private $bodyHTML;
    private $error;


    public function newUser(){
            $VRegistration=USingleton::getInstance('VRegistration');
            $USession=USingleton::getInstance('USession');
            $this->bodyHTML=$VRegistration->loadRegistrationForm();
            
            if ( $USession->get('username') ) {
                $message= "Esegui il logout prima di registrare un nuovo utente";
                $this->bodyHTML=$VRegistration->getErrorMessage($message);             
            }
            else {
                $action=$VRegistration->get('action');
                if ($action=="addUser") {
                    $this->addNewUser();
               }else {
                    $this->loadRegForm();
                }
            }
            return$this->bodyHTML;
                
        }
        
    public function loadRegForm(){
        $VRegistration=  USingleton::getInstance('VRegistration');
        $this->bodyHTML=$VRegistration->loadRegistrationForm();
    }

    public function addNewUser(){
        $VRegistration=  USingleton::getInstance('VRegistration');
        $FRegistration=  USingleton::getInstance('FRegistration');

        //getting data..
        $data=$VRegistration->getFormValues();

        //checking data consistency..
        foreach ($data as $key => $value) {
            //ogni caso assegna l'errore
            switch ($key) {
                case "name":
                    $name=ucfirst($value);
                    break;
                case "surname":
                    $surname=ucfirst($value);
                    break;
                case "cf":
                    $cf=$value;
//                    if(!$this->checkCF($cf)){ la validazione del cf è fatta lato client
//                        $this->dataError("Codice Fiscale");
//                    }
                    break;
                case "email":
                    $mail=$value;
//                    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)){
//                        $this->dataError("Email");
//                    }
                    break;
                case"username":
                    //$FUtente=USingleton::getInstance("FUtente");
                    
                    $user=$value;
//                    if(!$FUtente->usernameIsAvaiable($user)){ //fatta sia lato client che server
//                        $this->dataError("Username");
//                    }
                    break;
                case "password1":
                    $pass1=$value;
//                    if(!$this->validatePassword($pass)){ fatto lato client
//                        $this->dataError("Password");
//                    }
                    
                    break;
                
                case 'password2':
                    $pass2=$value;
                    
                default :
                    debug("L'Array ha restituito dei dati inattesi nome: ".$key." valore:".$value);

                    break;
            }
        }
//        if($this->error){
//            $this->bodyHTML=$VRegistration->getRegistrationErrorMessage($this->error);
//        }
        
        $valid=$this->validateRegForm($name, $surname, $cf, $mail, $user, $pass1,$pass2);
        if ($valid==false){
            $message="Registrazione non effettuata, i seguenti dati non sono corretti :"."<br>".$this->error;
            $this->bodyHTML=$VRegistration->getErrorMessage($message,true);
        }
        else{
            //saving data..
            $FRegistration->insertUser($name,$surname,$cf,$mail,$user,$pass1);
            $message="registrazione avvenuta con successo";
            $this->bodyHTML=$VRegistration->getInfoMessage($message);
            
        }

    }
    
    public function validateRegForm($name, $surname, $cf, $mail, $user, $pass1,$pass2){
        $FUtente=  USingleton::getInstance('FUtente');
        
        $valid=true;
        if (preg_match('/[^A-Za-z\s\']/', $name)){
            $valid=false;
            $this->dataError("nome");
        }
        
        if (preg_match('/[^A-Za-z\s\']/', $surname)){
            $valid=false;
            $this->dataError("cognome");
        }
        
        if (strlen($cf)!=16 || preg_match('/[^A-Z\d]/', $cf)|| !$FUtente->codiceFiscaleIsAvailable($cf)){
            $valid=false;
            $this->dataError("codice fiscale");
        }
        
        if (!$FUtente->emailIsAvailable($mail)){
            $valid=false;
            $this->dataError("email");
        }
        
        if (!$FUtente->usernameIsAvailable($user)){
            $valid=false;
            $this->dataError("username");
        }
        
        if ($pass1!=$pass2 || strlen($pass1)<5){
            $valid=false;
            $this->dataError("password");
        }
        return $valid;
    }
    
    //invece di fare in questa maniera barbara, si può fa na funzione error
    //globale oppure in view e falo fare a lei così lo formatta in html
    public function dataError($field) {
        $this->error=($this->error.$field."<br>");
        
    }
    
    public function checkUsername(){ //usato da ajax
        $VRegistration=  USingleton::getInstance('VRegistration');
        $FRegistration=  USingleton::getInstance('FRegistration');
        
        $user=$VRegistration->get('user');
        $result=$FRegistration->checkUsername($user);
        echo json_encode($result);
        exit;
    }
        
    public function checkEmail(){ //usato da ajax
        $VRegistration=  USingleton::getInstance('VRegistration');
        $FRegistration=  USingleton::getInstance('FRegistration');
        
        $mail=$VRegistration->get('mail');
        $result=$FRegistration->checkEmail($mail);
        echo json_encode($result);
        exit;
    }
    
    public function checkCFUser(){
        $VRegistration=  USingleton::getInstance('VRegistration');
        $FUtente=  USingleton::getInstance('FUtente');
        
        $cf=$VRegistration->get('cf');
        $result=$FUtente->codiceFiscaleIsAvailable($cf);
        echo json_encode($result);
        exit;
    }
    
    /**
     * Validate the string format and after that it checks if there is yet an
     * user registered with the same CF. The archive is checked lastly to reduce
     * load on serverside. If the string is ok returns TRUE, if it's not
     * returns FALSE
     * 
     * @param string $cf
     * @return boolean TRUE means valid CF string. FALSE if it's not a valid CF.
     */
    /*public function checkCF($cf){
     if($cf=='')
	return false;

     if(strlen($cf)!= 16)
	return false;

     $cf=strtoupper($cf);
     if(!preg_match("/[A-Z0-9]+$/", $cf))
	return false;
     $s = 0;
     for($i=1; $i<=13; $i+=2){
	$c=$cf[$i];
	if('0'<=$c and $c<='9')
	     $s+=ord($c)-ord('0');
	else
	     $s+=ord($c)-ord('A');
     }

     for($i=0; $i<=14; $i+=2){
	$c=$cf[$i];
	switch($c){
             case '0':  $s += 1;  break;
	     case '1':  $s += 0;  break;
             case '2':  $s += 5;  break;
	     case '3':  $s += 7;  break;
	     case '4':  $s += 9;  break;
	     case '5':  $s += 13;  break;
	     case '6':  $s += 15;  break;
	     case '7':  $s += 17;  break;
	     case '8':  $s += 19;  break;
	     case '9':  $s += 21;  break;
	     case 'A':  $s += 1;  break;
	     case 'B':  $s += 0;  break;
	     case 'C':  $s += 5;  break;
	     case 'D':  $s += 7;  break;
	     case 'E':  $s += 9;  break;
	     case 'F':  $s += 13;  break;
	     case 'G':  $s += 15;  break;
	     case 'H':  $s += 17;  break;
	     case 'I':  $s += 19;  break;
	     case 'J':  $s += 21;  break;
	     case 'K':  $s += 2;  break;
	     case 'L':  $s += 4;  break;
	     case 'M':  $s += 18;  break;
	     case 'N':  $s += 20;  break;
	     case 'O':  $s += 11;  break;
	     case 'P':  $s += 3;  break;
             case 'Q':  $s += 6;  break;
	     case 'R':  $s += 8;  break;
	     case 'S':  $s += 12;  break;
	     case 'T':  $s += 14;  break;
	     case 'U':  $s += 16;  break;
	     case 'V':  $s += 10;  break;
	     case 'W':  $s += 22;  break;
	     case 'X':  $s += 25;  break;
	     case 'Y':  $s += 24;  break;
	     case 'Z':  $s += 23;  break;
	}
    }

    if( chr($s%26+ord('A'))!=$cf[15] )
	return false;
    //check if the CF is yet in the data archive
    $FUtente=  USingleton::getInstance("FUtente");
    if(!$FUtente->codiceFiscaleIsAvaiable($cf)){
        return false;
    }

    return true;
}
        */
        
}
