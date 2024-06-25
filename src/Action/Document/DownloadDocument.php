<?php

namespace App\Action\Document;
use App\Domain\Repository\DocumentRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Stream;
use Slim\Routing\RouteContext;

final class DownloadDocument
{
    private $documentRepo;
    public function __construct(DocumentRepository $documentRepo) 
    {
        $this->documentRepo = $documentRepo;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $id = $route->getArgument('id');

        $document = $this->documentRepo->getDocumentById($id);
        
        if ($document['Path'] == null || $document['Path'] == '') $file =  __DIR__ . "/../../../public_html/Repository/". $document['Name'];
            else $file =  __DIR__ . "/../../../public_html/Repository/" . $document['Path'] . "/". $document['Name'];
        $fh = fopen($file, 'rb');

        $stream = new Stream($fh); 

        return $response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
}