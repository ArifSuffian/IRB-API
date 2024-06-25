<?php

namespace App\Action\Account\Employee;
use App\Domain\Service\Account\Employee\EmployeeReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

final class ViewEmployee
{
    private $employeeReaderService;

    public function __construct(EmployeeReader $employeeReaderService)
    {
        $this->employeeReaderService = $employeeReaderService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $id = $route->getArgument('id');

        $data = $this->employeeReaderService->getEmployeeById($id);

        $result = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => $data->records
        ];

        $response->getBody()->write((string) json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}