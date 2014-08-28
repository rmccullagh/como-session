<?php namespace Como\Cavalry;

use Como\Cavalry\AbstractSession;

class Session extends AbstractSession 
{
	const FLASH_NS = '__COMO_FLASH_DATA__';
	
	public function id()
	{
		return session_id();
	}
	
	public function setFlashData($mixed)
	{
		if(! isset($this->data[self::FLASH_NS])) {		
			$this->data[self::FLASH_NS] = array();
		}
		
		$this->data[self::FLASH_NS][] = $mixed;
	}
	
	public function getFlashData()
	{
		if(! isset($this->data[self::FLASH_NS]))
			return false;
		
		$data = $this->data[self::FLASH_NS];
		
		if(count($data) > 0) {
			unset($this->data[self::FLASH_NS]);
		  return $data;
		}
		return false;
	}
	
}
