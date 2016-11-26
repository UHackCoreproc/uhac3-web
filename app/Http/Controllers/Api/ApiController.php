<?php

namespace UHacWeb\Http\Controllers\Api;

use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

}
