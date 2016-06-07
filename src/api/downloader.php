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
    private $extension;

    /**
     * @var string
     */
    private $fileUrl;

    /**
     * @var string
     */
    private $location;

    public function __construct(string $downloadPath, string $extension)
    {
        $this->downloadPath = $downloadPath;
        $this->extension = $extension;
    }

    public function startsWith(string $haystack, string $needle): bool
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function setFileUrl(string $fileUrl): bool
    {
        $this->fileUrl = $fileUrl;
        $this->location = $this->downloadPath . basename($fileUrl) . $this->extension;
        $low = strtolower($fileUrl);

        if ($this->startsWith($low, "http"))
            return true;

        if ($this->startsWith($low, "ftp"))
            return true;

        return false;
    }

    public function downloadRemoteFile(): bool
    {
        $content = file_get_contents($this->$fileUrl);
        return file_put_contents($this->location, $content);
    }
}
