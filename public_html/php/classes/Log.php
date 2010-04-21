<?php

class Log {


	protected $path;
	protected $file;
	
	public function __construct(){
	
		$this->path = $_SERVER["DOCUMENT_ROOT"] . "/log/log.log";
		$this->file = fopen($this->path, 'a');
	}
	
	public function log($msg){
	
		$msg = "Logged " . date("Y-m-d H:i:s") . " :: \n" . $msg . "\n\n";
	
		fwrite($this->file, $msg);
		fclose($this->file);
	
	}


}

?>
