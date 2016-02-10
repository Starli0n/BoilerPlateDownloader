<html>
<body>

<?php
	require_once('variables.php');

	// Initialization
	//$debug = true;
	$link = $_POST["file"];
	$file = basename($link) . ".zip";
	$location = $download_dir . $file;

	// Functions
	function startsWith($haystack, $needle)
	{
	    // search backwards starting from haystack length characters from the end
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}

	function assert_remote_file($file_url)
	{
		$low = strtolower($file_url);

		if (startsWith($low, "http"))
			return true;

		if (startsWith($low, "ftp"))
			return true;

		return false;
	}

	function download_remote_file_dummy($file_url, $save_to)
	{
		// Do nothing for debug purpose
	}

	function download_remote_file($file_url, $save_to)
	{
		$content = file_get_contents($file_url);
		file_put_contents($save_to, $content);
	}

	function download_remote_file_with_curl($file_url, $save_to)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch,CURLOPT_URL, $file_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$file_content = curl_exec($ch);
		curl_close($ch);

		$downloaded_file = fopen($save_to, 'w');
		fwrite($downloaded_file, $file_content);
		fclose($downloaded_file);
	}

	function download_remote_file_with_fopen($file_url, $save_to)
	{
		$in = fopen($file_url, "rb");
		$out = fopen($save_to, "wb");

		while ($chunk = fread($in,8192))
		{
			fwrite($out, $chunk, 8192);
		}

		fclose($in);
		fclose($out);
	}

	function download_local_file($load_from)
	{
		if (!file_exists($load_from))
		{
			echo 'The file has not been downloaded properly.';
			die();
		}

		if (filesize($load_from) == 0)
		{
			echo 'The downloaded file has a size of 0KB.';
			die();
		}

		header('Location: ' . $load_from);
	}

	// Main
	if (isset($debug))
	{
		echo '$link = ' . $link . '<br>';
		echo '$file = ' . $file . '<br>';
		echo '$location = ' . $location . '<br>';
		echo '<br>';
	}

	if (assert_remote_file($link))
	{
		echo 'Downloading... <br>';
	}
	else
	{
		echo "It is not a remote file.";
		die();
	}

	download_remote_file($link, $location);
	download_local_file($location);
?>

</body>
</html>
