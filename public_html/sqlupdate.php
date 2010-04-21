<?php
	require_once('php/init.php');
	if ($_SERVER['SERVER_NAME'] == 'motiomera.se') {
		throw new UserException("Behörighet saknas", "Du har inte behörighet att använda denna sida");
	}

	$sqlscript_dir = '../db/sql-script/';
	$sqlscript_done = '../db/processed-sql-scripts/';

	function getUnincludedSQLS() {
		global $sqlscript_dir;
		global $sqlscript_done;

		$files = opendir($sqlscript_dir);
		$retfiles = array();
		
		while ($file = readdir($files)) {
			if (is_file($sqlscript_dir.$file)) {
				if (!file_exists($sqlscript_done.$file)) {
					$retfiles[$file] = $file;
				}
			}
		}
		asort($retfiles);
		return $retfiles;
		
	}
	
	function IncludeSQLS() {
		global $sqlscript_dir;
		global $sqlscript_done;
		$count = 0;
		$files = getUnincludedSQLS();
		foreach ($files as $filename) {
			$file = fopen($sqlscript_dir.$filename,'r');
			$content = fread($file, filesize($sqlscript_dir.$filename));
			$content = str_replace('motiomera', (isset($_GET['dbname']) && strlen($_GET['dbname'])) ? $_GET['dbname'] : 'motiomera', $content);
			echo 'Importerar <b>' . $filename . '</b> till <b>' . ((isset($_GET['dbname']) && strlen($_GET['dbname'])) ? $_GET['dbname'] : 'motiomera') . '</b>...<ul>';
			flush();
			$content = explode(';',$content);
			$all_worked = true;
			foreach ($content as $sql) {
				if (strlen(trim($sql))) {
					if (!substr_count(strtolower($sql), 'use ')) {
						$sql = trim($sql).';';
						echo '<xmp>'.$sql.'</xmp>	';
						if (!mysql_query($sql)) {
							$all_worked = false;
							break;
						}
					} else {
						echo 'Skipping `use`-statement to fall back to site config.';
					}	
				}
			}
			if ($all_worked) {
				echo '</ul><span style="color:#0C0; font-weight: bold;">Klart</span>! ';
				$count ++;
			} else {
				echo '<span style="color:#C00; font-weight: bold;">MISSLYCKADES!</span>';
				echo '<br /><br />';
				echo mysql_error();
				exit;
			}
			if (copy($sqlscript_dir.$filename, $sqlscript_done.$filename)) {
				echo '(och kopierat)';
			} else {
					echo '<span style="color:#C00; font-weight: bold;">KUNDE INTE KOPIERA FILEN!</span>';
				exit;
			}
			echo '<br />';
			echo '<br />';
			flush();	
		}
		echo '<br /><b>All done!</b><br /> ' . $count . ' fil'.($count == 1 ? '' : 'er').' hanterades.';
	};
		
	if (isset($_GET['run']) && $_GET['run'] == 'true') {
		IncludeSQLS();
	} else {
?>
<html>
<head>
<title>SQL Update - by Mikael Gr&ouml;n</title>
</head>
<body>
<h1>SQL Update</h1>
<p>Detta script går igenom katalogen 'db/sql-script' och letar efter oimporterade SQL-script.</p>
<p>När det importerat ett script läggs en kopia av det i 'db/processed-sql-scripts'.</p>
<p>När en ny databasdump läggs till SVN ska även sql-script som ligger i 'db/processed-sql-scripts' samt ingår i dumpen läggas till SVN.</p>
<p>Om detta används som det ska kommer det vara MYCKET enkelt för oss utvecklare att hålla ordning på vilka script som finns och inte. 
	Om vi tillexempel får ett SQL-relaterat fel på vår utvecklingsversion av siten kan vi enkelt surfa till /sqlupdate.php?run=true så är problemet löst.</p>
<p>
	<a href="?run=true">Klicka här för att köra scriptet</a><br />
	Om du har ett annat databasnamn än 'motiomera' på din lokala server skriver du:
	<ul>sqlupdate.php?run=<em>true</em><u>&dbname=<em>[databasnamn]</em></u></ul>
</p>
<p>sqlupdate.php är låst då siten körs på motiomera.se</p>
<h2><a href="?run=true">SQL-filer som kommer importeras:</a> <em>&lt;- klicka</em></h2>
<p>
	<?php $files = getUnincludedSQLS(); ?>
	<?php foreach ($files as $filename) { ?>
		<xmp>* db/sql-script/<?php echo $filename; ?></xmp>
	<?php } ?>
	<?php if (!count($files)) { ?>
		Alla SQL-filer verkar vara importerade i databasen!
	<?php } ?>
</p>
</body>
</html>
<?php } ?>
