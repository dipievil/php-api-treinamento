<?php

require_once ROOT_PATH . '/core/Controller.php';

class HealthChecker extends Controller
{
    public function check()
    {
        $healthCheck = [
            'status' => 'ok',
            'message' => 'Service is running smoothly.',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->view('health/check', $healthCheck);
    }
}