<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;

class HealthCheckController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/health",
     *     summary="Verificar saúde da aplicação",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="Sistema está saudável",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="ok"),
     *             @OA\Property(property="services", type="object",
     *                 @OA\Property(property="database", type="string", example="ok"),
     *                 @OA\Property(property="cache", type="string", example="ok"),
     *                 @OA\Property(property="storage", type="string", example="ok")
     *             ),
     *             @OA\Property(property="version", type="string", example="1.0.0"),
     *             @OA\Property(property="uptime", type="number", example=3600),
     *             @OA\Property(property="memory", type="object",
     *                 @OA\Property(property="usage", type="number", example=15.5),
     *                 @OA\Property(property="limit", type="number", example=128)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Sistema com problema",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="services", type="object",
     *                 @OA\Property(property="database", type="string", example="error"),
     *                 @OA\Property(property="cache", type="string", example="ok"),
     *                 @OA\Property(property="storage", type="string", example="ok")
     *             ),
     *             @OA\Property(property="message", type="string", example="Problemas de conexão com o banco de dados")
     *         )
     *     )
     * )
     */
    public function check(Request $request)
    {
        $startTime = microtime(true);

        $checks = [];
        $status = 'ok';
        $httpCode = 200;
        $message = null;
        $error = false;

        // Verificar banco de dados
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'ok';
        } catch (Exception $e) {
            $checks['database'] = 'error';
            $status = 'error';
            $httpCode = 500;
            $message = 'Problema de conexão com o banco de dados: ' . $e->getMessage();
            $error = true;
        }

        // Verificar cache
        try {
            Cache::put('health_check', true, 10);
            if (Cache::get('health_check') === true) {
                $checks['cache'] = 'ok';
            } else {
                $checks['cache'] = 'warning';
                if (!$error) {
                    $status = 'warning';
                    $message = 'Problema com o sistema de cache';
                }
            }
        } catch (Exception $e) {
            $checks['cache'] = 'error';
            if (!$error) {
                $status = 'error';
                $httpCode = 500;
                $message = 'Problema com o sistema de cache: ' . $e->getMessage();
            }
        }

        // Verificar acesso a storage
        try {
            $testFile = storage_path('health_check.txt');
            file_put_contents($testFile, 'test');
            if (file_exists($testFile) && file_get_contents($testFile) === 'test') {
                $checks['storage'] = 'ok';
                unlink($testFile);
            } else {
                $checks['storage'] = 'error';
                if (!$error) {
                    $status = 'error';
                    $httpCode = 500;
                    $message = 'Problema de escrita/leitura no sistema de arquivos';
                }
            }
        } catch (Exception $e) {
            $checks['storage'] = 'error';
            if (!$error) {
                $status = 'error';
                $httpCode = 500;
                $message = 'Problema com o sistema de arquivos: ' . $e->getMessage();
            }
        }

        // Informações do sistema
        $responseData = [
            'status' => $status,
            'services' => $checks,
            'version' => config('app.version', '1.0.0'),
            'environment' => app()->environment(),
            'date' => now()->toIso8601String(),
            'response_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms' // Tempo em ms
        ];

        // Adicionar estatísticas de memória
        if (function_exists('memory_get_usage')) {
            $responseData['memory'] = [
                'usage' => round(memory_get_usage() / 1024 / 1024, 2), // MB
                'peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) // MB
            ];
        }

        // Adicionar mensagem de erro se houver
        if ($message) {
            $responseData['message'] = $message;
        }

        return response()->json($responseData, $httpCode);
    }

    /**
     * @OA\Get(
     *     path="/api/health/ping",
     *     summary="Verifica se a aplicação está respondendo",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="Aplicação está respondendo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="pong"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function ping()
    {
        return response()->json([
            'status' => 'pong',
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/health/info",
     *     summary="Retorna informações detalhadas sobre o sistema",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="Informações do sistema",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="app", type="object",
     *                 @OA\Property(property="name", type="string", example="Laravel"),
     *                 @OA\Property(property="version", type="string", example="1.0.0"),
     *                 @OA\Property(property="environment", type="string", example="production")
     *             ),
     *             @OA\Property(property="php", type="object",
     *                 @OA\Property(property="version", type="string", example="7.4.0"),
     *                 @OA\Property(property="extensions", type="array", @OA\Items(type="string"))
     *             ),
     *             @OA\Property(property="database", type="object",
     *                 @OA\Property(property="type", type="string", example="pgsql"),
     *                 @OA\Property(property="version", type="string", example="12.6")
     *             ),
     *             @OA\Property(property="server", type="object",
     *                 @OA\Property(property="software", type="string", example="nginx/1.18.0"),
     *                 @OA\Property(property="os", type="string", example="Linux 5.4.0")
     *             )
     *         )
     *     )
     * )
     */
    public function info()
    {
        $dbVersion = null;
        try {
            if (DB::connection()->getPdo()) {
                $dbVersion = DB::select('SELECT version()')[0]->version;
            }
        } catch (Exception $e) {
            $dbVersion = 'Não disponível: ' . $e->getMessage();
        }

        $info = [
            'app' => [
                'name' => config('app.name'),
                'version' => config('app.version', '1.0.0'),
                'environment' => app()->environment(),
                'locale' => app()->getLocale(),
                'laravel_version' => app()->version(),
            ],
            'php' => [
                'version' => phpversion(),
                'extensions' => get_loaded_extensions(),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time') . 's',
            ],
            'database' => [
                'connection' => config('database.default'),
                'driver' => config('database.connections.' . config('database.default') . '.driver'),
                'version' => $dbVersion,
            ],
            'server' => [
                'software' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Desconhecido',
                'os' => php_uname('s') . ' ' . php_uname('r'),
                'time' => now()->toIso8601String(),
                'timezone' => config('app.timezone')
            ]
        ];

        return response()->json($info);
    }
}
