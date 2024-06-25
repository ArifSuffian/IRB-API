<?php

namespace App\Action\Account\Employee;
use App\Domain\Service\Account\Employee\EmployeeReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListEmployees
{
  private $employeeReaderService;

  public function __construct(EmployeeReader $employeeReaderService)
  {
    $this->employeeReaderService = $employeeReaderService;
  }

  public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
  {
    $data = $this->employeeReaderService->getAllEmployees();

    $result = [
      'success' => $data->success,
      'message' => $data->message,
      'data' => $data->records
    ];

    $response->getBody()->write((string) json_encode($result));

    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
  }
}