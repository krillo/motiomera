<?php /* Smarty version 2.6.19, created on 2010-04-19 16:54:55
         compiled from laddauppforetagsbild.tpl */ ?>
<h1>Ladda upp företagslogga</h1>



<div class="mmFileUpload">
	<?php if ($this->_tpl_vars['foretagsid'] == ""): ?>
	<form action="<?php echo $this->_tpl_vars['urlHandler']->getUrl('CustomForetagsbild','URL_SAVE'); ?>
" method="post" enctype="multipart/form-data">
	<?php else: ?>
	<form action="<?php echo $this->_tpl_vars['urlHandler']->getUrl('CustomForetagsbild','URL_ADMIN_SAVE',$this->_tpl_vars['foretagsid']); ?>
" method="post" enctype="multipart/form-data">
	<?php endif; ?>
		<input type="hidden" name="fid" value="<?php echo $this->_tpl_vars['foretagsid']; ?>
" />
		<input type="file" name="image" /><br />
		<input type="submit" value="Ladda upp" class="mmMarginTop" />
	</form>
</div>