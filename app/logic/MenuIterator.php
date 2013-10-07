<?php
class MenuIterator implements Iterator
{
    private $var = array();
	private $con;
    
    public function __construct($array, $controller)
    {
        if (is_array($array)) {
            $this->var = $array;
			$this->con = $controller;
        }
    }

    public function rewind()
    {
        reset($this->var);
    }

    public function current()
    {
        $var = current($this->var);
        return $var;
    }

    public function key()
    {
        $var = key($this->var);
        return $var;
    }

    public function next()
    {
        $var = next($this->var);
        return $var;
    }

    public function valid()
    {
        $key = key($this->var);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
	
	public function getClass() {
		foreach ($this->var as $caption => $option) {
			foreach($option['controller'] as $c){
				if ($c == $this->con) {
					$this->var[$caption]['class'] = "active";
				}
			}
        }
		return $this->var;
	}
}