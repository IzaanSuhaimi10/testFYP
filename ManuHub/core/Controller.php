<?php
class Controller {
    // Load model
    public function loadModel($model, $db = null) {
        require_once "../app/models/$model.php";
        return new $model($db);
    }

    // Load view
    public function loadView($view, $data = []) {
        require_once "../app/views/$view.php";
    }
}
?>
