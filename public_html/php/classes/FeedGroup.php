<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - setTyp()
* - setFeedItems()
* - setParams()
* - isGrupp()
* - getText()
* - getGrupp()
* - getKommun()
* - getParam()
* - getTyp()
* - getDatum()
* - listFeedItems()
* - setGrupp()
* - setKommun()
* Classes list:
* - FeedGroup
*/

class FeedGroup
{
	
	protected $typ;
	
	protected $feedItems;
	
	protected $grupp;
	
	protected $kommun;
	
	protected $paramsArray = array();
	
	protected $typer = array( // "self" avgör om man själv ska visas i gruppen

		"gattmedigrupp" => array(
			"text" => '%a gick med i %g',
			"url" => array(
				"Grupp",
				URL_VIEW
			) ,
			"self" => "false",
		) ,
		"lamnatgrupp" => array(
			"text" => '%a lämnade %g',
			"self" => 'false',
		) ,
		"stegrapportgrupp" => array(
			"text" => '%a i <a href="%u" title="Gå till %1">%1</a> gick sammanlagt %2 steg',
			"url" => array(
				"Grupp",
				URL_VIEW
			) ,
			"self" => "true",
		) ,
	);
	
	public function __construct($typ, $feedItems, $params, $grupp = null)
	{
		$this->setTyp($typ);
		$this->setParams($params);
		
		if ($grupp) $this->setGrupp($grupp);
		
		if ($this->typer[$this->getTyp() ]["self"] == "false") {
			global $USER;
			$result = array();
			foreach($feedItems as $key => $item) {
				
				if ($item->getMedlemId() != $USER->getId()) $result[$key] = $item;
			}
			$feedItems = $result;
		}
		$this->setFeedItems($feedItems);
	}

	// PRIVATE FUNCTIONS //////////////////////////////////////
	
	private function setTyp($typ)
	{
		$this->typ = $typ;
	}
	
	private function setFeedItems($feedItems)
	{
		$this->feedItems = Feed::sortFeeds($feedItems);
	}
	
	private function setParams($params)
	{
		$this->paramsArray = $params;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function isGrupp()
	{
		return true;
	}
	
	public function getText()
	{
		return Feed::makeText($this->typer[$this->getTyp() ], $this, $this->paramsArray);
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getGrupp()
	{
		return $this->grupp;
	}

	public function getGruppId()
	{
		return $this->grupp->getId();
	}	

	public function getKommun()
	{
		return $this->kommun;
	}
	
	public function getParam($index)
	{
		return $this->paramsArray[$index];
	}
	
	public function getTyp()
	{
		return $this->typ;
	}
	
	public function getDatum()
	{
		$datum = 0;
		foreach($this->listFeedItems() as $item) {
			
			if ($datum < strtotime($item->getDatum())) $datum = strtotime($item->getDatum());
		}
		return date("Y-m-d H:i:s", $datum);
	}
	
	public function listFeedItems()
	{
		return $this->feedItems;
	}
	
	public function setGrupp(Grupp $grupp)
	{
		$this->grupp = $grupp;
	}
	
	public function setKommun(Kommun $kommun)
	{
		$this->kommun = $kommun;
	}
}
?>
