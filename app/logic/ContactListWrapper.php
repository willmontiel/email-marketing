<?php
class ContactListWrapper extends ControllerBase
{
	/**
	 * Crea un arreglo con la informacion del objeto Contactlist
	 * para que pueda ser convertido a JSON
	 * @param Contactlist $contactlist
	 * @return array
	 */
	public function convertListToJson($contactlist)
	{
		$object = array();
		$object['id'] = $contactlist->idList;
		$object['name'] = $contactlist->name;
		$object['description'] = $contactlist->description;
		$object['createdon'] = $contactlist->createdon;
		$object['updatedon'] = $contactlist->updatedon;

		return $object;
	}
	
	public function findContactListByList($idAccount, $limit, $page)
	{
		$query2 = $this->modelsManager->createQuery("SELECT COUNT(*) AS cnt FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = '$idAccount'");
        $result = $query2->execute()->getFirst();
				
		$total = $result->cnt;
		$availablepages = ceil($total/$limit);
		
		if($page <= 0){
			$start = 0;
			$page = 1;
		}
		
		else if($page > $availablepages){
			$start = $availablepages*$limit;
			$page = $start;
		}
		
		else{
			$start = ($page-1)*$limit;
		}
		
		
		
		$lista = array();
		
        $query = $this->modelsManager->createQuery("SELECT Contactlist.* FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = $idAccount LIMIT $start, $limit");
        $contactlists = $query->execute();
		
		
		
		if ($contactlists) {
			foreach ($contactlists as $contactlist) {
				$lista[] = $this->convertListToJson($contactlist);
			}
		}
		
		return array('contactlist' => $lista, 'meta' => array( 'pagination' => array('page' => $page, 'limit' =>$limit, 'total' => $total,'availablepages' => $availablepages) ) ) ;
		
	}
}

