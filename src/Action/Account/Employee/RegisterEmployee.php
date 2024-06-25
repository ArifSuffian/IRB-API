<?php

namespace App\Action\Account\Employee;
use App\Domain\Service\Account\Employee\EmployeeCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RegisterEmployee
{
    private $employeeCreatorService;

    public function __construct(EmployeeCreator $employeeCreatorService)
    {
        $this->employeeCreatorService = $employeeCreatorService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $status = null;
        $data = (array) $request->getParsedBody();

        $workEmail = (string) ($data['WorkEmail'] ?? null);
        $fullName = (string) ($data['FullName'] ?? null);
        $employmentStatusId = (int) ($data['EmploymentStatus_Id'] ?? null);
        $jobTitle = (string) ($data['JobTitle'] ?? null);
        $accessLevel = (string) ($data['AccessLevel'] ?? null);

        if (empty($workEmail)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Work email is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($fullName)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Full Name is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($employmentStatusId)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Employment Status is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($jobTitle)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Job title is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }
        if (empty($accessLevel)) {
            $status = 400;
            $result = [
                'success' => false,
                'message' => 'Access level is required.'
            ];
            return $this->sendResponse($response, $result, $status);
        }

        $data = $this->employeeCreatorService->create($workEmail, $fullName, $employmentStatusId, $jobTitle, $accessLevel);
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