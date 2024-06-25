<?php

namespace App\Action\Account\Employee;

use App\Domain\Service\Account\Employee\EmployeeUpdater;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

final class UpdateEmployee
{
    private $employeeUpdater;

    public function __construct(EmployeeUpdater $employeeUpdater)
    {
        $this->employeeUpdater = $employeeUpdater;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $status = null;

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $id = $route->getArgument('id');

        $data = (array) $request->getParsedBody();

        $workEmail = (string) ($data['workEmail'] ?? null);
        $fullName = (string) ($data['fullName'] ?? null);
        $employmentStatusId = (int) ($data['employmentStatusId'] ?? -1);

        $data = $this->employeeUpdater->updateEmployee($id, $workEmail, $fullName, $employmentStatusId);

        if ($data->success) {
            $status = $data->status;
            $result = [
                'success' => $data->success,
                'message' => $data->message
            ];
        } else {
            $status = $data->status;
            $result = [
                'success' => $data->success,
                'message' => $data->message
            ];
        }

        return $this->sendResponse($response, $result, $status);
    }

    private function sendResponse($response, $result, $status)
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string) json_encode($result));

        return $response->withStatus($status);
    }
}