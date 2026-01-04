<?php
class Controller {
    // Helper to generate correct URLs regardless of folder depth
    public function baseUrl($path = '') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        // Adjust 'manuhub/public' if your folder name is different
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . "/manuhub/public/" . ltrim($path, '/');
    }

    public function loadModel($model, $db = null) {
        require_once "../app/models/$model.php";
        return new $model($db);
    }

    public function loadView($view, $data = []) {
        require_once "../app/views/$view.php";
    }
}