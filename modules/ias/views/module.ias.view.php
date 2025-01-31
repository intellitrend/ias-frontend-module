<link rel="stylesheet" type="text/css" href="zabbix.php?action=ias.file&file=ias.css" media="screen" />
<script src="zabbix.php?action=ias.file&amp;file=ias.js"></script>
<script>
$(function() {
	let urlFunction = function(serviceid, refresh) {
		let url = 'zabbix.php?action=ias.service';
		if (serviceid != -1) {
			url += '&serviceid=' + serviceid;
		}
		url += '&refresh=' + (refresh & 1);
		return url;
	}
	let ias = new IAS(urlFunction, '<?php echo $this->data['theme'] ?>');
	ias.start();
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
