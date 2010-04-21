<?php /* Smarty version 2.6.19, created on 2010-02-22 10:18:22
         compiled from laddauppvisningsbild.tpl */ ?>
<h1>Ladda upp visningsbild</h1>





<div class="mmFileUpload">

	<form action="<?php echo $this->_tpl_vars['urlHandler']->getUrl('CustomVisningsbild','URL_SAVE'); ?>
" method="post" enctype="multipart/form-data" onsubmit="return mm_visningsbild_checkExtension();">
		<input type="file" name="image" id="mmLaddaUppBild" /><br />
		<input type="submit" value="Ladda upp" class="mmMarginTop" />
	</form>
</div>