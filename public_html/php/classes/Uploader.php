<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - getTempFile()
* - getFilesize()
* - setFile()
* - setTargetDir()
* - setTargetName()
* - setMaximumSize()
* - setFileTypes()
* - setDimensions()
* - upload()
* Classes list:
* - Uploader
*/

class Uploader
{
	
	protected $file;
	
	protected $targetdir;
	
	protected $targetname;
	
	protected $maximumSize;
	
	protected $fileTypes = array();
	
	protected $dimensions = array();
	
	public function __construct($file, $targetdir, $targetname)
	{
		$this->setFile($file);
		$this->setTargetDir($targetdir);
		$this->setTargetName($targetname);
	}

	// SETTERS & GETTERS
	
	public function getTempFile()
	{
		return $this->tempdir . "/" . $this->tempname;
	}
	
	public function getFilesize()
	{
		return filesize($this->getTempFile());
	}
	
	public function setFile($file)
	{
		$this->file = $file;
	}
	
	public function setTargetDir($dir)
	{
		$this->targetdir = $dir;
	}
	
	public function setTargetName($name)
	{
		$this->targetname = $name;
	}
	
	public function setMaximumSize($size)
	{
		$this->maximumSize = $size;
	}
	
	public function setFileTypes($types)
	{
		$this->fileTypes = $types;
	}
	
	public function setDimensions($width, $height)
	{
		$this->dimensions = array(
			$width,
			$height
		);
	}
	
	public function upload()
	{
	}
}
?>
