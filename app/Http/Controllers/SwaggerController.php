<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwaggerController extends Controller
{
    /**
     * Display Swagger API documentation.
     *
     * @return mixed
     */
    public function index()
    {
        return view('swagger.index');
    }
}
