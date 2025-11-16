<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Identity Service API Documentation",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk Identity & Authentication Service"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

abstract class Controller
{
    //
}
