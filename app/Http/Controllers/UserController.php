<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for User Management"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     operationId="getUsersList",
     *     summary="Get all users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return response()->json(['error' => 'Método não permitido. Use GET.'], 405);
        }

        try {
            $users = User::all();

            if ($users->count() > 0) {
                return response()->json($users, 200);
            } else {
                return response()->json(['error' => 'Usuário não encontrado.'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno no servidor.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    /**
     * Get user by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return response()->json(['error' => 'Método não permitido. Use GET.'], 405);
        }

        // Validate the ID
        if ($id === null || !filter_var($id, FILTER_VALIDATE_INT) || (int)$id <= 0) {
            return response()->json(['error' => 'ID do usuário inválido ou não fornecido. Deve ser um inteiro positivo.'], 400);
        }

        $id = (int)$id;

        try {
            $user = User::find($id);

            if ($user) {
                return response()->json($user, 200);
            } else {
                return response()->json(['error' => "Usuário com ID {$id} não encontrado."], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno no servidor.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return response()->json(['error' => 'Método não permitido. Use POST.'], 405);
        }

        // Get the request data
        $input = $request->json()->all();

        // Validate the data
        if (empty($input['nome']) || empty($input['email'])) {
            return response()->json(['error' => 'Nome e e-mail são obrigatórios.'], 400);
        }

        try {
            // Check if user already exists
            $existingUser = User::where('email', $input['email'])->first();

            if ($existingUser) {
                return response()->json(['error' => 'Já existente.'], 409);
            }

            // Create new user
            $user = new User();
            $user->name = $input['nome'];
            $user->email = $input['email'];
            $user->save();

            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno no servidor.', 'details' => $e->getMessage()], 500);
        }
    }
}
