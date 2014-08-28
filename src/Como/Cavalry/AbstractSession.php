<?php namespace Como\Cavalry;

abstract class AbstractSession implements \IteratorAggregate
{
	private $data = array();
	
	private $name;
	
	public function __construct($name = null)
	{
		if(session_status() === PHP_SESSION_NONE) {
			if(isset($name)) {
				$valid = false;
				//can't consist of digits only, at least one letter must be present
				if(preg_replace('/[^0-9]/', '', $name) === $name) {
					// passed first test
					$valid = true;
				} else {
					if(preg_replace('/[^a-zA-Z]/', '', $name) === '') {
						// failed
						$valid = false;
					} else {
						// pass
						$valid = true;
					}
				}
				if(true === $valid) {
					$this->name = $name;
					session_name($name);
				}
			}
			session_start();
		}
		
		$this->data = &$_SESSION;
		$this->mount();
	}
	
	private function mount()
	{
		if(! isset($this->data['csrfmiddlewaretoken'])) {
		
			$this->data['csrftokencreated'] = time();
			$this->data['csrfmiddlewaretoken'] = md5(uniqid(rand(), true));
		}	
	}
	
	public function set($mixed, $value = null)
	{
		if(is_array($mixed)) {
			foreach($mixed as $key => $value) {
				$this->data[$key] = $value;
			}
		} else if(isset($mixed) && isset($value)) {
			$this->data[$mixed] = $value;
		}
	}
	
	public function get($key)
	{
		if(isset($key)) {
			if(isset($this->data[$key])) {
				return $this->data[$key];
			} else {
				return null;
			}
		}
    return null;
	}
	
	public function all() 
	{	
		return $this->data;
	}
	
	public function has($key)
	{		
		return $this->get($key) != null;
	}	
	
	public function name()
	{
		return session_name();
	}
	
	public function destroy()
	{
		if(session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		
		$this->data = array();
		
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		
		session_destroy();
	}
	
	
	public function getIterator()
  {
    return new \ArrayIterator($this->data);
  }
	
	public abstract function setFlashData($mixed);
	
	public abstract function getFlashData();
	
}
