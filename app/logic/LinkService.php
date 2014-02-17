<?php
class LinkService
{
	protected $account;
	protected $urlManager;
	protected $mail;


	protected $mappings;
			
	public function __construct(Account $account, Mail $mail, UrlManagerObject $urlManager) 
	{
		$this->account = $account;
		$this->urlManager = $urlManager;
		$this->mail = $mail;
		
		$this->mappings = array();
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getUrlMappings()
	{
		/*
		 * $this->mappings =
		 *		[
		 *			'URL'  =>  [ '$$$x_sigma$$$', 'http://URLBASE/track/click/etc...' ]
		 *		]
		 */
		$map = array();
		foreach ($this->mappings as $maps) {
			// $maps[0] = '$$$1_sigma$$$'
			// $maps[1] = 'http://,......../track/click/etc...'
			$map[$maps[0]] = $maps[1];
		}
		/*
		 * El resultado debe ser:
		 * [   '$$$1_sigma$$$' => 'base',
		 *		...
		 * ]
		 */
		return $map;
	}
	
	/**
	 * Retorna la marca
	 * @param string $url
	 * return string
	 */
	public function getPlatformUrl($url) 
	{
//		echo 'Me llegó esto: ' . $url . PHP_EOL;
		/*
		 * $this->mappings =
		 *		[
		 *			'URL'  =>  [ '$$$x_sigma$$$', 'http://URLBASE/track/click/etc...' ]
		 *		]
		 */
		/* 1) Validar LINK (ej: no nulo, no en lista de hosts a no hacer seguimiento .. etc */
		$valid = $this->validateHost($url);

		if (!$valid) {
			return false;
		}
		/* 2) Verificar si esta en la lista local (mappings) si es asi, retornar el key */
		if (isset($this->mappings[$url])){
			$mark = $this->mappings[$url][0];
		}
		else {
		/* 3) Buscar en BD, si esta crear en lista local (mappings) y retornar el key */
		/* 4) No esta en BD, crear en BD y etc */
			$idMailLink = $this->saveLink($url);
			$mark = $this->createMark($idMailLink);
			$base = $this->createUrlBase($idMailLink);
			$this->mappings[$url] = array($mark, $base);
		}
		return $mark;
	}
	
	private function saveLink($url)
	{
		$maillink = Maillink::findFirst(array(
			'conditions' => 'idAccount = ?1 AND link = ?2',
			'bind' => array(1 => $this->account->idAccount, 
							2 => $url)
		));
		
		if (!$maillink) {
			unset($maillink);
			$maillink = new Maillink();
			$maillink->idAccount = $this->account->idAccount;
			$maillink->link = $url;
			$maillink->createdon = time();

			if (!$maillink->save()) {
				foreach ($maillink->getMessages() as $msg) {
					Phalcon\DI::getDefault()->get('logger')->log('Error saving link: ' . $msg);
				}
				throw new InvalidArgumentException('Error while saving Maillink');
			}
		}
		
		$mxl = new Mxl();
		$mxl->idMail = $this->mail->idMail;
		$mxl->idMailLink = $maillink->idMailLink;

		if (!$mxl->save()) {
			foreach ($mxl->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log('Error saving Mxl: ' . $msg);
			}
			throw new InvalidArgumentException('Error while saving Mxl');
		}
		return $mxl->idMailLink;
	}
	
	private function createMark($id)
	{
		return $newUrl = '$$$' . $id . '_sigma_url_$$$';
	}
	
	private function createUrlBase($id)
	{
		return $this->urlManager->getBaseUri(true) . 'track/click/1-' . $id;
	}
	
	private function validateHost($url)
	{
		$parts = parse_url($url);
		
		if (isset($parts['host'])) {
			if ($parts['host'] !== null && !preg_match('/[^\/]*\.*facebook.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*twitter.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*linkedin.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*plus.google.com.*$/', $parts['host'])) {
				return true;
			}
		}
		return false;
	}
}
