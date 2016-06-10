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
    private $downloadUri;

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

    public function __construct(string $downloadPath, string $downloadUri, string $extension)
    {
        $this->downloadPath = $downloadPath;
        $this->downloadUri = $downloadUri;
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
        $baseName = basename($fileUrl) . $this->extension;
        $this->location = $this->downloadPath . $baseName;
        $this->downloadUri = $this->downloadUri . $baseName;
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
            return file_put_contents($this->location, $content);
        }
        return false;
    }

    public function location(): string
    {
        return $this->downloadUri;
    }
}
