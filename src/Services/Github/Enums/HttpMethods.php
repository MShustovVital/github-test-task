<?php

namespace App\Services\Github\Enums;

enum HttpMethods: string
{
	case GET = 'GET';
	case POST = 'POST';
	case PUT = 'PUT';
	case DELETE = 'DELETE';
	case PATCH = 'PATCH';
	case OPTIONS = 'OPTIONS';
}
