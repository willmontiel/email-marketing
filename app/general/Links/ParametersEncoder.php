<?php

namespace EmailMarketing\General\Links;

class ParametersEncoder
{
	public $baseUri;
	
	public function setBaseUri($baseUri) 
	{
		$this->baseUri = $baseUri;
	}
	
	/**
	 * This function return a link decoded with a md5 in the end
	 * @param string $action
	 * @param array $parameters
	 * @return string
	 */
	public function encodeLink($action, $parameters)
	{
		$src = $this->baseUri . $action . '/' . implode('-', $parameters);
		return $src . '-' . $this->getMD5($src);
	}
	
	public function decodeLink($action, $paramstr)
	{
		$parameters = explode("-", $paramstr);
		$version = $parameters[0];
		
		$md5 = $parameters[count($parameters)-1];
		unset($parameters[count($parameters)-1]);
		
		
		$src = $this->baseUri . $action . '/' . implode('-', $parameters);
		$vermd5 = $this->getMD5($src);
		
		if ($md5 !== $vermd5) {
			throw new \InvalidArgumentException('MD5 no concuerda');
		}
		
		return $parameters;
	}
	
	private function getMD5($data)
	{
		return md5($data . '-Sigmamovil_Rules');
	}

}
