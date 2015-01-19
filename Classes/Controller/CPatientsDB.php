<?php 


class CPatientsDB{

    /**
     *Contiene tutti i pazienti nel DB
     * cambiare nome in Paztients ?
     * 
     * @var type array
     */
    public $Pazienti=array();
    /**
     *Contiene tutte le visite nel DB
     * cambiare nome in Visits ?
     * 
     * @var type array
     */
    public $Visite=array();
    
    private $bodyHTML;

    
    /**
     *
     */
	public function __construct(){ 
		$VPatientsDB=Usingleton::getInstance('VPatientsDB');
		$this->fillArrays();

		$action=$VPatientsDB->get('action');
		switch($action) {

			case 'insert':
			$this->insertPatient();
			break;

			case 'search':
			$this->searchPatient();
			break;

			case 'getFullData':
			$this->showPatientDetails();
			break;
                    
                        case 'getChecks':
                            $this->showPatientChecks();
                        break;

			case 'printReport':
			$this->printReport();
			break;

			case 'modPat':
                            $this->modifyPatient();
			break;
                    
                        case 'modCheck':
                            $this->modifyCheck();
                        break;

			case 'delPat':
                            $this->deletePatient();
			break;
                    
                        case 'delCheck':
                            $this->deleteCheck();
			break;
                    
                        case 'newVisit':
                            $this->newVisit();
                        break;

			default:
                            $this->getHomePatients();   
		}

	}

        /**
         * Riempie gli array Pazienti e Visite
         */
        private function fillArrays(){ //OKv2
            $FPatient=  USingleton::getInstance('FPatient');
            $FCheckup=  USingleton::getInstance('FCheckup');
            
            $this->Pazienti=$FPatient->fillPatientsArray($this->Pazienti);
                
            for ( $i=0; $i<count($this->Pazienti); $i++){
                $this->Visite=$FCheckup->fillCheckupsArray($this->Visite,$this->Pazienti[$i]->getCf() );
            }
        }
        
    /**
     * 
     */    
    private function getHomePatients(){ // OKv2
        $VPatientsDB=Usingleton::getInstance('VPatientsDB');
        $USession=USingleton::getInstance('USession');
        
                for ( $i=0; $i<count($this->Pazienti); $i++){
                    $Patients[$i]=array('name'=>$this->Pazienti[$i]->getName(),'surname'=>$this->Pazienti[$i]->getSurname(),'cf'=>$this->Pazienti[$i]->getCf(),'dateBirth'=>$this->Pazienti[$i]->getDataN(),'link'=>md5($this->Pazienti[$i]->getCf()));
                }
                $this->bodyHTML=$VPatientsDB->fetchHomePatients($Patients);
    }

    /**
     * Inserisce una nuova riga sia nella tabella pazienti che in quella visite
     */
	private function insertPatient(){ //OKv2
		$VPatientsDB=Usingleton::getInstance('VPatientsDB');
		if( $VPatientsDB->get('sent') ){
                    $FPatient=  USingleton::getInstance('FPatient');
                    $FCheckup=  USingleton::getInstance('FCheckup');
                    
                    $arrayPatient=array('name'=>$VPatientsDB->get('name'),
                                        'surname'=>$VPatientsDB->get('surname'),
				        'gender'=>$VPatientsDB->get('gender'),
				        'dateBirth'=>$VPatientsDB->get('dateBirth'),
				        'CF'=>$VPatientsDB->get('CF'));
                    
		    $arrayCheck=array('CF'=>$VPatientsDB->get('CF'),
                                      'dateCheck'=>$VPatientsDB->get('dateCheck'),
			 	      'medHistory'=>$VPatientsDB->get('medHistory'),
			 	      'medExam'=>$VPatientsDB->get('medExam'),
				      'conclusions'=>$VPatientsDB->get('conclusions'),
				      'toDoExams'=>$VPatientsDB->get('toDoExams'),
				      'terapy'=>$VPatientsDB->get('terapy'),
				      'checkup'=>$VPatientsDB->get('checkup'));
                    
                    $FPatient->insertNewPatient($arrayPatient);
                    $FCheckup->insertNewCheckup($arrayCheck);
                    $message="Inserimento avvenuto con successo";
                    $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
		}
		else {
                    $this->bodyHTML=$VPatientsDB->showInsertForm();			
		}	
	}

        /**
         * La ricerca avviene per nome, cognome o codice fiscale, a seconda di cosa viene inserito nel campo cerca
         */
	private function searchPatient(){ //OKv2
		$VPatientsDB=Usingleton::getInstance('VPatientsDB');
                
		if($VPatientsDB->get('keyValue')==null) {
                    $this->bodyHTML=$VPatientsDB->fetchSerachForm();
		}
		else {
                    $FPatient=  USingleton::getInstance('FPatient');
		    $searchKey=$VPatientsDB->get('keyValue');
                    $searchResult=$FPatient->findPatient($searchKey);
                    $numResults=count($searchResult);
                    if ( $numResults!=0 ) {
                            $message="la ricerca ha prodotto ".$numResults." risultato/i";
                            $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
                            //$this->bodyHTML=$VPatientsDB->getSearchResult($message,$searchResult,$numResults);
                    }
                    else {
                            $message="La ricerca non ha prodotto nessun risultato";
                            $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
                    }
	    }
	}

        /**
         * Visualizza i dettagli di una visita di un paziente
         */
	private function showPatientDetails(){ //OKv2
		$VPatientsDB=Usingleton::getInstance('VPatientsDB');
                $FPatient=  USingleton::getInstance('FPatient');
		$encCF=$VPatientsDB->get('p');
                $encCheck=$VPatientsDB->get('ch');
                
                $patientDetail=$this->buildInfoArray($encCF,$encCheck);
                $this->bodyHTML=$VPatientsDB->getPatientDetail($patientDetail);
	}
        
        /**
         * Mette insieme i dati recuperati dall'array Pazienti e da quello Visite in un unico array che poi viene 
         * passato alla view per stamparlo. Utilizzata sia da showPatientDetails che da printReport
         * 
         * @param type $encryptedCF Codice fiscale preso dalla view
         * @param type $encryptedDateCheck Data della visita presa dalla view
         * @return type array
         */
        public function buildInfoArray($encryptedCF,$encryptedDateCheck){ //OKv2
            $posCF=$this->getCfPosition($encryptedCF);
            $cfPatient=$this->Pazienti[$posCF]->getCF();
            
            $posCH=$this->getDateCheckPosition($encryptedDateCheck,$cfPatient);
            $dateCH=$this->Visite[$cfPatient][$posCH]->getDateCheck();
            
            $array=array('name'=>$this->Pazienti[$posCF]->getName(),
                                 'surname'=>$this->Pazienti[$posCF]->getSurname(),
                                 'gender'=>$this->Pazienti[$posCF]->getSex(),
                                 'dateBirth'=>$this->Pazienti[$posCF]->getDataN(),
                                 'CF'=>$this->Pazienti[$posCF]->getCF(),
                                 'dateCheck'=>$this->Visite[$cfPatient][$posCH]->getDateCheck(),
                                 'medHistory'=>$this->Visite[$cfPatient][$posCH]->getMedHistory(),
                                 'medExam'=>$this->Visite[$cfPatient][$posCH]->getMedExam(),
                                 'conclusions'=>$this->Visite[$cfPatient][$posCH]->getConclusions(),
                                 'toDoExams'=>$this->Visite[$cfPatient][$posCH]->getToDoExam(),
                                 'terapy'=>$this->Visite[$cfPatient][$posCH]->getTerapy(),
                                 'checkup'=>$this->Visite[$cfPatient][$posCH]->getCheckup());
            return $array;
        }
        
        /**
         * Mostra tutte le visite fatte da un paziente
         */
        public function showPatientChecks(){ //OKv2
            $VPatientsDB=Usingleton::getInstance('VPatientsDB');
            $FPatient=  USingleton::getInstance('FPatient');
            $encCF=$VPatientsDB->get('p');            
            $posCF=$this->getCfPosition($encCF);
            $cfPatient=$this->Pazienti[$posCF]->getCF();
            $name=$this->Pazienti[$posCF]->getName();
            $surname=$this->Pazienti[$posCF]->getSurname();            
            
            for ( $i=0;$i<count($this->Visite[$cfPatient]);$i++ ){
                $patChecks[]=$this->Visite[$cfPatient][$i]->getDateCheck(); //tutte le date delle visite del paziente
            }
            
            $this->bodyHTML=$VPatientsDB->getPatientChecks($name,$surname,$patChecks,$encCF);
        }


        /**
         * Stampa il report di una visita di un paziente
         */
	private function printReport(){ //OKv2
		$VPatientsDB=USingleton::getInstance('VPatientsDB');
                $encCF=$VPatientsDB->get('pat');
                $encCH=$VPatientsDB->get('ch');
                
		if ( $VPatientsDB->get('fields')=="sent" ){
                    $Updf=USingleton::getInstance('Updf');
                    $patArray=$this->buildInfoArray($encCF, $encCH);
                    $patInfo=$patArray['name']." ".$patArray['surname'].", ".$patArray['dateBirth']." \n".$patArray['CF'];
                    $arrayToPrint=array();
                    
                    foreach ($patArray as $key=>$value) {
                        if ( $VPatientsDB->get($key) ) {
                            $arrayPrint[$key]=$value;                            
                        }
                    }
                    $Updf->printPage($patInfo,$arrayPrint);
		}
		else {
                    $this->bodyHTML=$VPatientsDB->getReportFields($encCF,$encCH);			
		}
	}


        
	private function modifyPatient() { //OKv2
            $VPatientsDB=  USingleton::getInstance('VPatientsDB');
            $encCF=$VPatientsDB->get('p');
            
            $posCF=$this->getCfPosition($encCF);
            $cfPatient=$this->Pazienti[$posCF]->getCF();
            
            if ( $VPatientsDB->get('mod')=="completed" ){
                $FPatient=  USingleton::getInstance('FPatient');
                
                $arrayPatient=array('name'=>$_REQUEST['name'],
                                    'surname'=>$_REQUEST['surname'],
				    'gender'=>$_REQUEST['gender'],
				    'dateBirth'=>$_REQUEST['dateBirth'],
				    'CF'=>$_REQUEST['CF']);
                
                if ( $arrayPatient['CF']!=$cfPatient ){ //se modifico il codice fiscale devo modificare anche quello nella tabella visite
                    $FCheckup=  USingleton::getInstance('FCheckup');
                    $FCheckup->updateCheckCF($arrayPatient['CF'],$cfPatient);
                }
                
                $FPatient->updatePatient($arrayPatient,$cfPatient);
                $message="modifica completata con successo";
                $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
            }
            else {
                $this->bodyHTML=$VPatientsDB->getPatientModPage($cfPatient);
            }
	}
        
        public function modifyCheck(){//OKv2
            $VPatientsDB=  USingleton::getInstance('VPatientsDB');
            $encCF=$VPatientsDB->get('p');
            $encCH=$VPatientsDB->get('ch');
            
            $posCF=$this->getCfPosition($encCF);
            $PatientInfo=array("CF"=>$this->Pazienti[$posCF]->getCF(),
                               "name"=>$this->Pazienti[$posCF]->getName(),
                               "surname"=>$this->Pazienti[$posCF]->getSurname() );
            
            $posCH=$this->getDateCheckPosition($encCH,$PatientInfo['CF']);
            $dateCH=$this->Visite[$PatientInfo['CF']][$posCH]->getDateCheck();
            
            if ( $VPatientsDB->get('mod')=="completed" ){
                $FCheckup=  USingleton::getInstance('FCheckup');
                
                $arrayCheck=array('dateCheck'=>$VPatientsDB->get('dateCheck'),
			 	  'medHistory'=>$VPatientsDB->get('medHistory'),
			 	  'medExam'=>$VPatientsDB->get('medExam'),
				  'conclusions'=>$VPatientsDB->get('conclusions'),
				  'toDoExams'=>$VPatientsDB->get('toDoExams'),
				  'terapy'=>$VPatientsDB->get('terapy'),
				  'checkup'=>$VPatientsDB->get('checkup'));
                
                $FCheckup->UpdateCheck($arrayCheck,$PatientInfo['CF'],$dateCH);
                $message="modifica completata con successo";
                $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
            }
            else {
                $this->bodyHTML=$VPatientsDB->getCheckModPage($PatientInfo,$dateCH);
            }
            
        }

        
	private function deletePatient(){
            $VPatientsDB=USingleton::getInstance('VPatientsDB');
            $encCF=$VPatientsDB->get('p');
            
            $posCF=$this->getCfPosition($encCF);
            $cfPatient=$this->Pazienti[$posCF]->getCF();
            
                
                if ( $VPatientsDB->get('conf')=="yes" ){
                    $FPatient=USingleton::getInstance('FPatient');
                    $FCheckup=  USingleton::getInstance('FCheckup');
                    
                    $FPatient->deletePatient($cfPatient);
                    $FCheckup->deleteCheckup($cfPatient,"all"); //oltre al paziente cancello anche tutte le sue visite
                    $message="eliminazione completata con successo";
                    $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
                }
                else {
                    $this->bodyHTML=$VPatientsDB->showPatientConfirmPage($cfPatient);
                }
		}
                
                
        public function deleteCheck(){
            $VPatientsDB=  USingleton::getInstance('VPatientsDB');
            $encCF=$VPatientsDB->get('p');
            $encCH=$VPatientsDB->get('ch');
            
            $posCF=$this->getCfPosition($encCF);
            $cfPatient=$this->Pazienti[$posCF]->getCF();
            
            $posCH=$this->getDateCheckPosition($encCH,$cfPatient);
            $dateCH=$this->Visite[$cfPatient][$posCH]->getDateCheck();
            
            if ( $VPatientsDB->get('conf')=="yes" ){
                $FCheckup=  USingleton::getInstance('FCheckup');
                if (count($this->Visite[$cfPatient])==1){ //Se viene cancellata l'unica visita del paziente viene cancellato anche il paziente stesso
                    $FPatient=  USingleton::getInstance('FPatient');
                    $FPatient->deletePatient($cfPatient);
                }
                else {
                    $FCheckup->deleteCheckup($cfPatient,$dateCH);
                }
                $message="eliminazione completata con successo";
                $this->bodyHTML=$VPatientsDB->showInfoMessage($message);
            }
            else {
                $this->bodyHTML=$VPatientsDB->showCheckConfirmPage($cfPatient,$dateCH);
            }
            
        }
                
        /**
         * Aggiunge una nuova visita alla lista delle visite di un paziente
         */
        public function newVisit(){ //OKv2
            $VPatientsDB=  USingleton::getInstance('VPatientsDB');
            $FCheckup=  USingleton::getInstance('FCheckup');            
            $encCF=$VPatientsDB->get('p');
            
            $posCF=$this->getCfPosition($encCF);
            $cfPatient=$this->Pazienti[$posCF]->getCF();
            $name=$this->Pazienti[$posCF]->getName();
            $surname=$this->Pazienti[$posCF]->getSurname();
            
            if ( $VPatientsDB->get('sent')=="y"){              
                
                $arrayCheck=array('CF'=>$VPatientsDB->get('CF'),
                                  'dateCheck'=>$VPatientsDB->get('dateCheck'),
			 	  'medHistory'=>$VPatientsDB->get('medHistory'),
			 	  'medExam'=>$VPatientsDB->get('medExam'),
				  'conclusions'=>$VPatientsDB->get('conclusions'),
				  'toDoExams'=>$VPatientsDB->get('toDoExams'),
				  'terapy'=>$VPatientsDB->get('terapy'),
				  'checkup'=>$VPatientsDB->get('checkup'));
                
                $FCheckup->insertNewCheckup($arrayCheck);
                
                $message="inserimento avvenuto con successo";
                $this->bodyHTML=$VPatientsDB->showInfoMessage($message);                
            }
            else {
                $this->bodyHTML=$VPatientsDB->showCheckForm($cfPatient,$name,$surname);                
            }
        }
        
        public function getBody() {
            return $this->bodyHTML;            
        }
        
        /**
         * 
         * @param type $encryptedCF string md5 del cf
         * @return int indice del paziente nell'array Pazienti
         */
        public function getCfPosition($encryptedCF){
            
            for ( $i=0; $i<count($this->Pazienti);$i++ ){ 
                if ( md5($this->Pazienti[$i]->getCF() )==$encryptedCF ) {
                    $position=$i;
                    }                
                }
                return $position;
        }
        
        /**
         * Visite è un array associativo, ogni elemento è a sua volta un array con tutte le visite del paziente
         * 
         * @param type $encryptedDateCheck string md5 della data della visita
         * @param type $cf string codice fiscale del paziente
         * @return int indice della visita nell'array Visite[$cf]
         */
        public function getDateCheckPosition($encryptedDateCheck,$cf){
            for ( $i=0;$i<count($this->Visite[$cf]);$i++ ){
                if ( md5($this->Visite[$cf][$i]->getDateCheck())==$encryptedDateCheck ) {
                    $position=$i;
                }
            }
            return $position;
        }
                
                
                
                
                
                
                
                
                
                
                
                
                
}




