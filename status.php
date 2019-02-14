<?php
require_once(__DIR__."/functions.php");

if (isset($_POST['ethers'])) {
  save_ethers($_POST['ethers']);
  header("Location: /");
  exit;
}

if (isset($_POST['mode'])) {
  save_config($_POST);
  header("Location: /");
  exit;
}

?><html>
<body>
<h3>Verfügbare MACs</h3>
<pre>
<?php
echo implode("\n", macs_up());
?>
</pre>
<hr/>
<h3>Nicht eingetragene MACs</h3>
<pre>
<?php
echo implode("\n", macs_up_not_in_ethers());
?>
</pre>
<hr/>
<h3>Vorhandene Einträge</h3>
<pre>
<?php
echo implode("\n", read_entries());
?>
</pre>
<hr/>
<form method="post">
<table>
<tr>
<td rowspan="2">
<label><input type="radio" name="mode" value="full" <?php echo checked($config, 'mode', 'full'); ?>/>Voll</label><br/>
<label><input type="radio" name="mode" value="specified" <?php echo checked($config, 'mode', 'specified'); ?>/>Angegeben</label><br/>
<label><input type="radio" name="mode" value="auto" <?php echo checked($config, 'mode', 'auto'); ?>/>Automatisch</label><br/>
<label><input type="radio" name="mode" value="off" <?php echo checked($config, 'mode', 'off'); ?>/>Ausgeschaltet</label><br/>
</td>
<td>Up:</td>
<td><input type="number" name="up" min="1" value="<?php echo $config['up']; ?>"/>kb/s</td>
</tr>
<tr>
<td>Down:</td>
<td><input type="number" name="down" min="1" value="<?php echo $config['down']; ?>"/>kb/s</td>
</tr>
</table>
<br/>
<button>Speichern</button>
</form>
<hr/>

<form method="post">
<textarea name="ethers" style="width: 400px; height: 200px"><?php echo read_ethers(); ?></textarea>
<button>Speichern</button>
</form>
</body>
</html>
