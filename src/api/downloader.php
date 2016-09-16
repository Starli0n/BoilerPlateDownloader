<?php
/**
 * BoilerPlateDownloader
 *
 * @package    BoilerPlateDownloader
 * @subpackage Api
 */

namespace BoilerPlateDownloader\Api;

/**
 * Class to manage downloads on the server side
 *
 * @package    BoilerPlateDownloader
 * @subpackage Api
 * @see        src/api/routes.php
 */
class Downloader
{
    /**
     * Full path of the download directory
     *
     * @var string
     */
    private $downloadPath;

    /**
     * Relative path of the download directory
     *
     * @var string
     */
    private $downloadDirectory;

    /**
     * Relative path of the file to download
     *
     * @var string
     */
    private $downloadFile;

    /**
     * Extension added to the file to download
     *
     * @var string
     */
    private $extension;

    /**
     * URL of the file to download must start with 'http' or 'ftp'
     *
     * @var string
     */
    private $fileUrl;

    /**
     * Full path of the file to download
     *
     * @var string
     */
    private $filePath;

    /**
     * Create a new Downloader
     *
     * @param string $downloadPath      Full path of the download directory
     * @param string $downloadDirectory Relative path of the download directory
     * @param string $extension         Extension added to the file to download
     */
    public function __construct(string $downloadPath, string $downloadDirectory, string $extension)
    {
        $this->downloadPath = $downloadPath;
        $this->downloadDirectory = $downloadDirectory;
        $this->extension = $extension;
    }

    /**
     * Check whether a string is starting with a pattern
     *
     * @param string $haystack Original string to check
     * @param string $needle   Pattern to verify
     * @return bool True if $haystack starts with $needle, false otherwise
     */
    public function startsWith(string $haystack, string $needle): bool
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * Check whether a string is ending with a pattern
     *
     * @param string $haystack Original string to check
     * @param string $needle   Pattern to verify
     * @return bool True if $haystack ends with $needle, false otherwise
     */
    public function endsWith($haystack, $needle): bool
    {
        // search forward starting from end minus needle length characters
        return $needle === ""
            || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * Set the file URL
     *
     * @param string $fileUrl URL of the file to download must start with 'http' or 'ftp'
     * @return bool True if the setting was Ok, false otherwise
     */
    public function setFileUrl(string $fileUrl): bool
    {
        $this->fileUrl = $fileUrl;
        $baseName = basename($fileUrl) . $this->extension;
        $this->filePath = $this->downloadPath . $baseName;
        $this->downloadFile = $this->downloadDirectory . $baseName;
        $low = strtolower($fileUrl);

        if ($this->startsWith($low, 'http') || $this->startsWith($low, 'ftp')) {
            return true;
        }

        return false;
    }

    /**
     * Donwload the remote file
     *
     * Effectively download the file previously set by 'setFileUrl()'
     *
     * @return bool True if the download was Ok, false otherwise
     */
    public function downloadRemoteFile(): bool
    {
        try {
            $content = file_get_contents($this->fileUrl);
        } catch (\Exception $exception) {
            return false;
        }
        return file_put_contents($this->filePath, $content);
    }

    /**
     * List all the files downloaded on the server
     *
     * @return array The list of the files
     */
    public function listDir(): array
    {
        $files = scandir($this->downloadPath);
        $files = array_filter($files, function ($file) {
            return $this->endsWith($file, $this->extension);
        });
        return array_values($files);
    }

    /**
     * Delete the selected files downloaded
     *
     * @param array The list of the files to delete
     */
    public function deleteFiles(array $files)
    {
        foreach ($files as $file) {
            unlink($this->downloadPath . $file);
        }
        unset($file);
    }

    /**
     * Getter $downloadDirectory
     *
     * @return string $downloadDirectory
     */
    public function getDownloadDirectory(): string
    {
        return $this->downloadDirectory;
    }

    /**
     * Getter $downloadFile
     *
     * @return string $downloadFile
     */
    public function getDownloadFile(): string
    {
        return $this->downloadFile;
    }
}
