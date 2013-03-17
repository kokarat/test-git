<?
$files = glob ( "*.html" );
	foreach ($files as $file ) {
		echo "<ul>";
			echo "<li><a href='#'>".$file."</a></li>";
		echo "</ul>";
	}
?>