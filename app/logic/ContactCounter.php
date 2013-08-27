<?php

class ContactCounter
{
	protected $counterDB;
	protected $counterList;
	
	public function saveCounters()
	{	
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		foreach ($this->counterDB as $idDbase => $contents)
		{
			
			$db = Dbase::findFirstByIdDbase($idDbase);

			$actTotal = $db->Ctotal;
			$actActive = $db->Cactive;
			$actUnsubscribed = $db->Cunsubscribed;
			$actBounced = $db->Cbounced;
			$actSpam = $db->Cspam;

				$parameters = array('idDbase' => $idDbase, 
									'Ctotal' => $contents["Ctotal"] + $actTotal, 
									'Cactive' => $contents["Cactive"] + $actActive, 
									'Cunsubscribed' => $contents["Cunsubscribed"] + $actUnsubscribed,
									'Cbounced' => $contents["Cbounced"] + $actBounced,
									'Cspam' => $contents["Cspam"] + $actSpam
				);

				$query = "UPDATE Dbase SET Ctotal = :Ctotal:, Cactive = :Cactive:, Cunsubscribed =  :Cunsubscribed:, Cbounced = :Cbounced:, Cspam = :Cspam: WHERE idDbase = :idDbase:";
				$query2 = $modelManager->createQuery($query);
				$result = $query2->execute($parameters);
		}
		
		foreach ($this->counterList as $idList => $contents)
		{
			
			$list = Contactlist::findFirstByIdList($idList);	
			
			$actTotal = $list->Ctotal;
			$actActive = $list->Cactive;
			$actUnsubscribed = $list->Cunsubscribed;
			$actBounced = $list->Cbounced;
			$actSpam = $list->Cspam;

				$parameters = array('idList' => $idList, 
									'Ctotal' => $contents["Ctotal"] + $actTotal, 
									'Cactive' => $contents["Cactive"] + $actActive, 
									'Cunsubscribed' => $contents["Cunsubscribed"] + $actUnsubscribed,
									'Cbounced' => $contents["Cbounced"] + $actBounced,
									'Cspam' => $contents["Cspam"] + $actSpam
				);

				$query = "UPDATE Contactlist SET Ctotal = :Ctotal:, Cactive = :Cactive:, Cunsubscribed =  :Cunsubscribed:, Cbounced = :Cbounced:, Cspam = :Cspam: WHERE idList = :idList:";
				$query2 = $modelManager->createQuery($query);
				$result = $query2->execute($parameters);
		}
	}
	
	public function newContactToDbase($contact)
	{
		/*
		 * Tomar el contacto nuevo que se creara en la base de datos del usuario
		 * y aumentar el contador de dicha base de datos.
		 * 
		 * Verifica que el contador de la base de datos no este inicializada (especial
		 * para casos de imoportacion) y de no estarlo la inicializa con la informacion necesaria
		 * para aumentar los contadores.
		 * 
		 * $this->newContact($contact);
		 * 
		 * Si el contador ya inicio entonces suma los nuevos contadores al ya existente para 
		 * de esta forma tener un solo contador al final con todos los incrementos
		 * 
		 * $this->sumaArray();
		 */
		
		$oper = $this->newContact($contact);
		if(!isset($this->$counterDB[$contact->idDbase])) {
			$this->counterDB[$contact->idDbase] = $oper;
		} else {
			$this->counterDB[$contact->idDbase] = $this->sumArray($this->counterDB[$contact->idDbase], $oper);
		}

	}
	
	public function newContactToList($contact, $list)
	{
		/*
		 * Igual que en los contadores de las base de datos se inicializa el contador
		 * y en caso de ya tener valores se actualizan los contadores de las listas
		 */
		
		$oper = $this->newContact($contact);
		if(!isset($this->counterList[$list->idList])) {
			$this->counterList [$list->idList] = $oper;
		} else {
			$this->counterList[$list->idList] = $this->sumArray($this->counterList[$list->idList], $oper);
		}
	}
	
	protected function sumArray($array1, $array2)
	{
		/*
		 * Recibe como parametros los arreglos a los cuales desea sumar su
		 * contenido, en primera instacia recibe el Arreglo de la base de datos o de la lista
		 * que esta llevando el conteo de los anteriores contadores, y como segundo parametro 
		 * esta el arreglo al cual se le incrementara o disminuira a este contador.
		 * 
		 * sumArray($this->counterDB, $oper)
		 * 
		 * Retorna la suma de los arreglos.
		 */
		$array1["Ctotal"]+= $array2["Ctotal"];
		$array1["Cactive"]+= $array2["Cactive"];
		$array1["Cunsubscribed"]+= $array2["Cunsubscribed"];
		$array1["Cbounced"]+= $array2["Cbounced"];
		$array1["Cspam"]+= $array2["Cspam"];
		return $array1;
	}

	protected function newContact($contact)
	{
		/*
		 * Crea el nuevo contador que se insertara al ya existente o de no haber ninguno 
		 * es con el cual se va a incrementear en la base de datos o lista del usuario.
		 * 
		 * Tiene en cuenta el factor de prioridad en los contadores, esto quiere decir que
		 * si el nuevo contacto tiene estado de SPAM y esta DES-SUSCRITO se aumentara solamente 
		 * para el contandor del SPAM.
		 * 
		 * Retorna los contadores de un nuevo contacto para ser sumado al contador general.
		 */
		$oper["Ctotal"]=1;
		$oper["Cspam"] = ($contact->spam != 0)?1:0;
		if($oper["Cspam"] == 0) {
			$oper["Cbounced"] = ($contact->bounced != 0)?1:0;
			if($oper["Cbounced"] == 0) {
				$oper["Cunsubscribed"] = ($contact->unsubscribed != 0)?1:0;
				if($oper["Cunsubscribed"] == 0) {
					if ($contact->status != 0) {
						$oper["Cactive"] = 1;
					} 
				}
			}
		}
		return $oper;
	}
	
	public function deleteContactFromDbase($contact)
	{
		$oper = $this->deleteContact($contact);
		if(!isset($this->$counterDB[$contact->idDbase])) {
			$this->counterDB[$contact->idDbase] = $oper;
		} else {
			$oper = $this->deleteContact($contact);
			
			$this->counterDB[$contact->idDbase] = $this->sumArray($this->counterDB[$contact->idDbase], $oper);
		}
	}
	
	public function deleteContactFromList($contact, $list)
	{
		$oper = $this->deleteContact($contact);
		if(!isset($this->counterList[$list->idList])) {
			$this->counterList [$list->idList] = $oper;
		} else {
			$oper = $this->deleteContact($contact);
		
			$this->counterList[$list->idList] = $this->sumArray($this->counterList[$list->idList], $oper);
		}
	}
	
	
	protected function deleteContact($contact)
	{
		$oper["Ctotal"]= -1;
		$oper["Cspam"] = ($contact->spam != 0)?-1:0;
		if($oper["Cspam"] == 0) {
			$oper["Cbounced"] = ($contact->bounced != 0)?-1:0;
			if($oper["Cbounced"] == 0) {
				$oper["Cunsubscribed"] = ($contact->unsubscribed != 0)?-1:0;
				if($oper["Cunsubscribed"] ==0) {
					if ($contact->status != 0) {
						$oper["Cactive"] = -1;
					}
				}
			}
		}	
		return $oper;
	}

	protected function assignDataToUpdateContact($oldcontact, $newcontact)
	{
		$oper =	array(
			"Ctotal" => 0,
			"Cactive" => 0,
			"Cunsubscribed" => 0,
			"Cbounced" => 0,
			"Cspam" => 0
		);
		
		/*
		 * Se inicializa el arreglo que se va a enviar para adjuntar los incrementos del contador.
		 * Despues de esto se establece la prioridad de los estados, en caso de que el contacto pase
		 * a estar SPAM y estaba en un principio como DES-SUSCRITO, incrementara el contador de SPAM, pero 
		 * disminuira el contador de DES-SUSCRITO dada la prioridad, y de igual forma se realiza en caso de
		 * tomar un nuevo estado de NO SPAM pero seguir o adquirir un nuevo estado de DES-SUSCRITO
		 * aumneta este ultimo.
		 * 
		 * Las prioridades son: 
		 * SPAM, BOUNCED, UNSUBSCRIBED, INACTIVE(Sin confirmar), ACTIVE
		 *
		 */

		if($newcontact->spam != $oldcontact->spam) {
			
			$oper["Cspam"] = ($newcontact->spam != 0)?1:-1;
			if($oper["Cspam"] == 1){
				$oper["Cbounced"] = ($oldcontact->bounced != 0)?-1:0;
				if($oper["Cbounced"] == 0) {
					$oper["Cunsubscribed"] = ($oldcontact->unsubscribed != 0)?-1:0;
					if($oper["Cunsubscribed"] == 0) {
						$oper["Cactive"] = ($oldcontact->status != 0)?-1:0;
					}
				}
			} else {
				$oper["Cbounced"] = ($newcontact->bounced != 0)?1:0;
				if($oper["Cbounced"] == 0) {
					$oper["Cunsubscribed"] = ($newcontact->unsubscribed != 0)?1:0;
					if($oper["Cunsubscribed"] == 0) {
						$oper["Cactive"] = ($newcontact->status != 0)?1:0;
					}
				}
			}
		} elseif ($newcontact->bounced != $oldcontact->bounced && $newcontact->spam == 0) {
			
			$oper["Cbounced"] = ($newcontact->bounced != 0)?1:-1;
			if($oper["Cbounced"] == 1) {
				$oper["Cunsubscribed"] = ($oldcontact->unsubscribed != 0)?-1:0;
				if($oper["Cunsubscribed"] == 0) {
					$oper["Cactive"] = ($oldcontact->status != 0)?-1:0;
				}
			} else {
				$oper["Cunsubscribed"] = ($newcontact->unsubscribed != 0)?1:0;
				if($oper["Cunsubscribed"] == 0) {
					$oper["Cactive"] = ($newcontact->status != 0)?1:0;
				}
			}
			
		} elseif ($newcontact->unsubscribed != $oldcontact->unsubscribed && $newcontact->bounced == 0 && $newcontact->spam == 0) {
			
			$oper["Cunsubscribed"] = ($newcontact->unsubscribed != 0)?1:-1;
			if($oper["Cunsubscribed"] == 1) {
				$oper["Cactive"] = ($oldcontact->status != 0)?-1:0;
			} else {
				$oper["Cactive"] = ($newcontact->status != 0)?1:0;
			}
			
		} elseif($newcontact->status != $oldcontact->status && $newcontact->bounced == 0 && $newcontact->spam == 0 && $newcontact->unsubscribed ==0) {
			
			$oper["Cactive"] = ($newcontact->status != 0)?1:-1;

		}
		return $oper;
	}

	public function updateContact($oldcontact, $newcontact)
	{
		$oper = $this->assignDataToUpdateContact($oldcontact, $newcontact);
				
		if(!isset($this->$counterDB[$newcontact->idDbase])) {
			$this->counterDB[$newcontact->idDbase] = $oper;
		} else {
			$oper = $this->newContact($contact);

			$this->counterDB[$contact->idDbase] = $this->sumArray($this->counterDB[$contact->idDbase], $oper);
		}
		
		$associations = Coxcl::findByIdContact($newcontact->idContact);
		
		foreach ($associations as $association) {
			
			$list = Contactlist::findFirstByIdList($association->idList);
			
			if(!isset($this->counterList[$list->idList])) {				
				$this->counterList[$list->idList] = $oper;				
			} else {				
				$this->counterList[$list->idList] = $this->sumArray($this->counterList[$list->idList], $oper);				
			}
		}
	}
	
	public static function getInactive($obj)
	{
		$inactive = $obj->Ctotal - ($obj->Cactive + $obj->Cunsubscribed + $obj->Cbounced + $obj->Cspam);
		
		return $inactive;
	}
}