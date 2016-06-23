<div class="container">
<h1>
<?php echo lang('page_title') ; ?><br>
<?php echo lang('form_username') ; ?><br>
<?php echo $currentLang; ?><br>
</h1>
<span>$data['currentLang']</span>: <span><?php echo $currentLang; ?></span><br>
<span>$data['uriString']</span>: <span><?php echo $uriString; ?></span><br>
<span>$data['enUrl']</span>: <span><?php echo $enUrl; ?></span><br>
<span>EN URL</span>: <span><a href="<?php echo base_url().$enUrl; ?>">English ver.</a></span><br>
<span>TC URL</span>: <span><a href="<?php echo base_url().$tcUrl; ?>">TChinese ver.</a></span><br>
<span>Logout URL</span>: <span><a href="<?php echo base_url()."welcome/logout"; ?>"><?php echo base_url()."welcome/logout"; ?></a></span><br>
<span>Admin Panel URL</span>: <span><a href="<?php echo base_url().$currentLang."/welcome/adminTest"; ?>"><?php echo base_url().$currentLang."/welcome/adminTest"; ?></a></span><br>
<!-- Good version below -->
<?php echo anchor($this->lang->switch_uri('tc'),'TChinese'); echo ' | '.anchor($this->lang->switch_uri('en'),'English');?>

<?php echo anchor($this->lang->switch_uri($currentLang),'Current Language'); ?>

<?php echo $test; ?>
</div>
