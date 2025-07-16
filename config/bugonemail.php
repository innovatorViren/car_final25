<?php
return [
	'project_name' => env("APP_NAME", "Laravel") ." Bug",
   	'notify_emails' => ['support@mnstechnologies.com'],
   	'email_template' => 'errors.notifyException',
   	'notify_environment' => ['production','localhost', 'dev', 'live'],
   	'prevent_exception' => [
   		'Illuminate\Session\TokenMismatchException',
   		'LogicException', 'ErrorException',
   		'Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
   		'Illuminate\Auth\AuthenticationException',
   		'Illuminate\Auth\Access\AuthorizationException',
   		'Symfony\Component\HttpKernel\Exception\HttpException',
   		'Illuminate\Database\Eloquent\ModelNotFoundException',
   		'Illuminate\Validation\ValidationException',
   	],
];
