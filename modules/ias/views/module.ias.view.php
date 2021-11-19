<link rel="stylesheet" type="text/css" href="zabbix.php?action=ias.fetch&file=ias.css" media="screen" />
<script src="zabbix.php?action=ias.fetch&amp;file=ias.js"></script>
<script>
$(function() {
	iasRun('zabbix.php?action=ias.fetch&file=ias.services', '<?php echo $this->data['theme'] ?>');
});
</script>
<?php
if ($this->data['error'] != '') {
?>
<header class="header-title">
	<div><h1 id="page-title-general">IntelliTrend Advanced Services for Zabbix</h1></div>
</header>
<output class="msg-bad" role="contentinfo" aria-label="Error message">
	<span><?php echo $this->data['error'] ?></span>
</output>
<?php
} else {
	echo $this->data['main_block'];
}
?>
