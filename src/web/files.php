<?php

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;

require "../vendor/autoload.php";

$request = Request::createFromGlobals();
$response = new Response();

if (!$request->isMethod('GET')) {
  $response->setStatusCode(405);
  $response->send();
  exit;
}

$root = '/opt/drupal/web';

$filePath = realpath($root . $request->getPathInfo());

if (!$filePath || !str_starts_with($filePath, $root)) {
  $response->isNotFound();
  $response->send();
  exit;
}

$revFilePath = strrev($filePath);
$fileExt = strrev(substr($revFilePath, 0, strpos($revFilePath, '.')));

if (in_array($fileExt, ['php', 'twig'])) {
  $response->isNotFound();
  $response->send();
  exit;
} 

$binaryFileResponse = new BinaryFileResponse($filePath);
$contentTypes = (new MimeTypes())->getMimeTypes($fileExt);
$binaryFileResponse->headers->set('Content-Type', $contentTypes[0] ?? 'application/octet-stream');
$binaryFileResponse->setMaxAge(31536000);
$binaryFileResponse->send();
exit;