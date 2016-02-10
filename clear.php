<html>
<body>

<?php
	require_once('variables.php');

	// Disable the button if there is no file to delete
	function disabled($dir)
	{
		$files = scandir($dir);
		if (sizeof($files) <= 2)
		{
			echo "disabled";
		}
	}

	// Clear all files if the page was submited
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$files = scandir($download_dir);
		$max = sizeof($files);
		for ($i = 2; $i < $max; $i++)
		{
			unlink($download_dir . $files[$i]);
		}
		echo "Clear !";
		exit();
	}

	// Show a confirmation form
?>

<form action="clear.php" method="post">
<input type="submit" value="Clear all downloaded files"  <?php disabled($download_dir) ?>>
</form>

</body>
</html>
