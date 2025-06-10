<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="PHP API Training",
 *     description="API Documentation for PHP Training",
 *     @OA\Contact(
 *         name="API Support",
 *         email="info@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="API Server"
 * )
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="User",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="nome", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00+00:00")
 *     ),
 *     @OA\Response(
 *         response="NotFound",
 *         description="The requested resource was not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Resource not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response="ValidationError",
 *         description="The submitted data did not pass validation",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Validation error"),
 *             @OA\Property(
 *                 property="details",
 *                 type="object",
 *                 @OA\AdditionalProperties(
 *                     type="array",
 *                     @OA\Items(type="string")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
