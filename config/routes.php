<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


return function (App $app) {
  
  // Account
  $app->group('/account', function (RouteCollectorProxy $group) {

    $group->post('/signin', \App\Action\Account\Authenticate::class);
    $group->options('/signin', function (ServerRequestInterface $request, ResponseInterface $response) {
      return $response;
    });                                                     

    $group->post('/', \App\Action\Account\Employee\RegisterEmployee::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->get('/', \App\Action\Account\Employee\ListEmployees::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->options('/', function (ServerRequestInterface $request, ResponseInterface $response) {
      return $response;
    });

    $group->get('/{id}', \App\Action\Account\Employee\ViewEmployee::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->put('/{id}', \App\Action\Account\Employee\UpdateEmployee::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->options('/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
      return $response;
    });
  });

  // Document
  $app->group('/document', function (RouteCollectorProxy $group) {

    $group->post('/', \App\Action\Document\AddDocument::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->get('/', \App\Action\Document\ListDocuments::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->options('/', function (ServerRequestInterface $request, ResponseInterface $response) {
      return $response;
    });

    $group->get('/{id}', \App\Action\Document\DownloadDocument::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->delete('/{id}', \App\Action\Document\DeleteDocument::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->put('/{id}', \App\Action\Document\UpdateDocument::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->options('/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
      return $response;
    });
  });

  // Log
  $app->group('/log', function (RouteCollectorProxy $group) {

    $group->get('/', \App\Action\Log\ListLogs::class)->add(\App\Middleware\AuthMiddleware::class);
    $group->options('/', function (ServerRequestInterface $request, ResponseInterface $response) {
      return $response;
    });
  });
};
