<?php
/**
 * BoilerPlateDownloader
 *
 * @package    BoilerPlateDownloader
 * @subpackage Api
 */

namespace BoilerPlateDownloader\Api;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Controller to handle routes
 *
 * Each function is a callback which implements this signature
 * <code>
 *     public function callback(Request $request, Response $response, array $args): Response
 * </code>
 *
 * @package    BoilerPlateDownloader
 * @subpackage Api
 * @see        src/api/routes.php
 */
class Router
{
    /**
     * Logger
     *
     * @var Logger
     */
    private $logger;

    /**
     * Downloader
     *
     * @var Downloader
     */
    private $api;

    /**
     * Create a new Router controller
     *
     * @param \Interop\Container\ContainerInterface $container ContainerInterface
     */
    public function __construct(\Interop\Container\ContainerInterface $container)
    {
        $this->logger = $container->logger;
        $this->api = $container->api;
    }

    /**
     * [GET] Basic route to test the routing
     *
     * The Response is a json message "Hello $name" with the attribute 'name' of the request
     *
     * @param Request   $request  The current Request object
     * @param Response  $response The current Response object
     * @param Response  $args     The additional arguments
     * @return Response The updated Response object in (json)
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function hello(Request $request, Response $response, array $args): Response
    {
        // Sample log message
        $this->logger->info("Route '/' hello");

        $name = $request->getAttribute('name');

        $data = array('message' => "Hello $name");
        $response = $response->withJson($data);
        return $response;
    }

    /**
     * [GET] List all the files downloaded by the server
     *
     * The Response contains a json message with the attribute:
     * > directory: Relative directory where the files are stored
     * > files:     List of files
     *
     * @param Request   $request  The current Request object
     * @param Response  $response The current Response object
     * @param Response  $args     The additional arguments
     * @return Response The updated Response object in (json)
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function listFiles(Request $request, Response $response, array $args): Response
    {
        $this->logger->info("Route '/' list");

        $files = $this->api->listDir();

        $data = array(
            'directory' => $this->api->getDownloadDirectory(),
            'files' => $files);
        return $response->withJson($data);
    }

    /**
     * [PUT] Run a download request of a file
     *
     * The Request contains a json message with the attribute:
     * > file: The file to download, must start with http or ftp
     *
     * The Response contains a json message with the attribute:
     * > directory: Relative directory where the files are stored
     * > files:     List of files
     *
     * @param Request   $request  The current Request object
     * @param Response  $response The current Response object
     * @param Response  $args     The additional arguments
     * @return Response The updated Response object in (json)
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function download(Request $request, Response $response, array $args): Response
    {
        $this->logger->info("Route '/' download");

        $file = $request->getParsedBodyParam('file');

        if (is_null($file)) {
            $data = array('message' => 'The file to download was not found');
            return $response->withJson($data, 400);
        }

        if (!$this->api->setFileUrl($file)) {
            $data = array('message' => 'The file provided was not a http nor a ftp file');
            return $response->withJson($data, 400);
        }

        if (!$this->api->downloadRemoteFile()) {
            $data = array('message' => 'The download has failed');
            return $response->withJson($data, 400);
        }
        $files = $this->api->listDir();

        $data = array(
            'directory' => $this->api->getDownloadDirectory(),
            'files' => $files);
        return $response->withJson($data, 201);
    }

    /**
     * [DELETE] Delete selected files
     *
     * @param Request   $request  The current Request object
     * @param Response  $response The current Response object
     * @param Response  $args     The additional arguments
     * @return Response The updated Response object in (json)
     *
     * The Request contains a json message with the attribute:
     * > files: The list a the files to delete
     *
     * The Response contains a json message with the attribute:
     * > directory: Relative directory where the files are stored
     * > files:     List of files
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $this->logger->info("Route '/' delete");

        $files = $request->getParsedBodyParam('files');
        $this->api->deleteFiles($files);
        $files = $this->api->listDir();

        $data = array(
            'directory' => $this->api->getDownloadDirectory(),
            'files' => $files);
        return $response->withJson($data);
    }
}
