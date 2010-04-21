<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - printTabBox()
* - setId()
* - getAjaxTab()
* - getId()
* - getUrl()
* - getNumberOfTabs()
* - getTabs()
* - getStyle()
* - getRef()
* - getSelected()
* - getWidth()
* - getHeight()
* - addTab()
* - setSelected()
* - setWidth()
* - setHeight()
* - setStyle()
* Classes list:
* - TabBox
*/

class TabBox
{
	
	private $id;
	
	private $style;
	
	private $tabs = array();
	
	private $refs = array();
	
	private $ajaxTabs = array();
	
	private $ajaxParams = array();
	
	private $selected = 0;
	
	private $width;
	
	private $height;
	
	public function __construct($id, $width = null, $height = null)
	{
		$this->setId($id);
		$this->setWidth($width);
		$this->setHeight($height);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function printTabBox()
	{
?>

	
<div class="mmTabBox" id="<?php echo $this->getId() ?>" style="<?php echo $this->getStyle() ?>">
	<div class="mmTabBoxInfo" id="<?php echo $this->getId() ?>_info"><?php echo $this->getNumberOfTabs(); ?></div>
	<div class="mmTabWrapBox" id="<?php echo $this->getId() ?>_tabs" style="width: <?php echo $this->getWidth() + 14 ?>px;">
		<?php
		foreach($this->getTabs() as $i => $name) { ?>
		<div class="mmTabBoxTab<?php

			if ($this->getSelected() == $i) echo "Selected"; ?>" id="<?php echo $this->getId() ?>_tab_<?php echo $i ?>">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="mmTabBoxLeft"></td>
					<td class="mmTabBoxMid"><a href="#" onclick="mmTabBoxChangeTab('<?php echo $this->getId() ?>', <?php echo $i ?>, <?php echo $this->getAjaxTab($i) ?>); return false;"><?php echo $name ?></a></td>
					<td class="mmTabBoxRight"></td>
				</tr>
			</table>
		</div>
		<?php
		} ?>
	</div>
	<div class="mmTabBoxContent" style="height: <?php echo $this->getHeight() ?>px;width: <?php echo $this->getWidth() + 5; ?>px;overflow:hidden;">
	<?php
		foreach($this->getTabs() as $i => $name) { ?>
		<div id="<?php echo $this->getId() ?>_content_<?php echo $i ?>"<?php echo ($i != $this->getSelected()) ? ' class="hide"' : ""; ?>><?php

			if (!file_exists($this->getUrl($i))) echo "File not found";
			else
			if ($this->ajaxTabs[$i] == 0 || $this->getSelected() == $i) include ($this->getUrl($i));
?></div>
	<?php
		} ?>
	</div>
</div>

	<?php
	}

	// PRIVATE FUNCTION ///////////////////////////////////////

	private function setId($id)
	{
		$this->id = $id;
	}
	
	private function getAjaxTab($i)
	{
		
		if ($this->ajaxTabs[$i] == 1) {
			return "'" . substr($this->getUrl($i) , strlen($_SERVER["DOCUMENT_ROOT"])) . "', '" . $this->ajaxParams[$i] . "'";
		} else return "null";
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getId()
	{
		return "mmTabBox_" . $this->id;
	}
	
	public function getUrl($i)
	{
		return TAB_BOX_TABROOT . "/" . substr($this->getId() , 9) . "/" . $this->getRef($i) . ".php";
	}
	
	public function getNumberOfTabs()
	{
		return count($this->tabs);
	}
	
	public function getTabs()
	{
		return $this->tabs;
	}
	
	public function getStyle()
	{
		
		if ($this->style) return $this->style;
		else return "";
	}
	
	public function getRef($i)
	{
		return $this->refs[$i];
	}
	
	public function getSelected()
	{
		return $this->selected;
	}
	
	public function getWidth()
	{
		return $this->width;
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	
	public function addTab($tabName, $tabRef, $ajax = false, $params = false)
	{
		$this->tabs[] = $tabName;
		$this->refs[] = $tabRef;
		$this->ajaxTabs[] = ($ajax) ? 1 : 0;
		$this->ajaxParams[] = ($params) ? $params : "";
	}
	
	public function setSelected($id)
	{
		$this->selected = $id;
	}
	
	public function setWidth($width)
	{
		$this->width = $width;
	}
	
	public function setHeight($height)
	{
		$this->height = $height;
	}
	
	public function setStyle($style)
	{
		$this->style = $style;
	}
}
?>
