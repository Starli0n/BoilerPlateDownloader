<?php

namespace BoilerPlateDownloader\Api;

class Downloader
{
    /**
     * @var string
     */
    private $downloadPath;

    /**
     * @var string
     */
    private $downloadDirectory;

    /**
     * @var string
     */
    private $downloadFile;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $fileUrl;

    /**
     * @var string
     */
    private $filePath;

    public function __construct(string $downloadPath, string $downloadDirectory, string $extension)
    {
        $this->downloadPath = $downloadPath;
        $this->downloadDirectory = $downloadDirectory;
        $this->extension = $extension;
    }

    public function startsWith(string $haystack, string $needle): bool
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function endsWith($haystack, $needle): bool
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public function setFileUrl(string $fileUrl): bool
    {
        $this->fileUrl = $fileUrl;
        $baseName = basename($fileUrl) . $this->extension;
        $this->filePath = $this->downloadPath . $baseName;
        $this->downloadFile = $this->downloadDirectory . $baseName;
        $low = strtolower($fileUrl);

        if ($this->startsWith($low, "http"))
            return true;

        if ($this->startsWith($low, "ftp"))
            return true;

        return false;
    }

    public function downloadRemoteFile(): bool
    {
        $content = file_get_contents($this->fileUrl);
        if ($content) {
            return file_put_contents($this->filePath, $content);
        }
        return false;
    }

    public function listDir(): array
    {
        $files = scandir($this->downloadPath);
        $files = array_filter($files, function ($file) {
            return $this->endsWith($file, $this->extension);
        });
        return array_values($files);
    }

    public function deleteFiles(array $files)
    {
        foreach ($files as $file) {
            unlink($this->downloadPath . $file);
        }
        unset($file);
    }

    public function getDownloadDirectory(): string
    {
        return $this->downloadDirectory;
    }

    public function getDownloadFile(): string
    {
        return $this->downloadFile;
    }
}
