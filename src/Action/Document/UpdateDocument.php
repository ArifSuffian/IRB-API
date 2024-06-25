<?php

namespace App\Action\Document;
use App\Domain\Service\Document\DocumentUpdater;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use InvalidArgumentException;

// final class UpdateArchive
// {
//     private $documentUpdaterService;

//     public function __construct(DocumentUpdater $documentUpdaterService)
//     {
//         $this->documentUpdaterService = $documentUpdaterService;
//     }

//   public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
//   {
//     // Collect input from the HTTP request
//     $routeContext = RouteContext::fromRequest($request);
//     $route = $routeContext->getRoute();
//     $id = $route->getArgument('id');

//     $data = (array) $request->getParsedBody();
//     $name = (string) ($data['name'] ?? null);

//     // Validation
//     if (empty($id)) {
//       throw new InvalidArgumentException('Id is required');
//     }

//     // Invoke the Domain with inputs and retain the result
//     $updateSuccess = $this->documentUpdaterService->updateDocument($id, $name, $updatedBy);

//     // Transform the result into the JSON representation
//     $result = [
//       'success' => $updateSuccess
//     ];

//     // Build the HTTP response
//     $response->getBody()->write((string) json_encode($result));

//     return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
//   }
// }
