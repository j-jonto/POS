<?php

class Controller {
    /**
     * Load a model file.
     * @param string $model The name of the model to load.
     * @return object The model object.
     */
    public function model($model) {
        $modelFile = ROOT_PATH . '/app/models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            // Handle model not found error
            die("Model not found: " . $model);
        }
    }

    /**
     * Load a view file.
     * @param string $view The name of the view to load.
     * @param array $data The data to pass to the view.
     */
    public function view($view, $data = []) {
        // Extract data to variables
        extract($data);

        $viewFile = ROOT_PATH . '/app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Start output buffering
            ob_start();
            // Include the view file
            require_once $viewFile;
            // Get the content from the buffer
            $content = ob_get_clean();

            // Include the main layout
            $layoutFile = ROOT_PATH . '/app/views/layouts/main.php';
            if (file_exists($layoutFile)) {
                require_once $layoutFile;
            } else {
                // If layout is missing, just echo the content
                echo $content;
            }

        } else {
            // Handle view not found error
            die("View not found: " . $view);
        }
    }
}
