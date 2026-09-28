<?php

class eduFlow_Sub_User_Router {
    private array $routes = [];

    public function add(string $method, string $path, array|callable $handler): void {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(string $uri, string $method): void {
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Strip /public from URI if server runs outside document root
        $path = str_replace('/public', '', $path);
        if (empty($path)) {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['path'] === $path && $route['method'] === strtoupper($method)) {
                $handler = $route['handler'];

                // Handle anonymous functions / Closures
                if (is_callable($handler)) {
                    call_user_func($handler);
                    return;
                }

                // Handle array controller handlers [ControllerClass, MethodName]
                if (is_array($handler)) {
                    [$controllerName, $action] = $handler;
                    
                    // Dynamically include the file based on the exact controller class name
                    $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                    }

                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        $controller->$action();
                        return;
                    } else {
                        die("Error: Class '{$controllerName}' could not be loaded from {$controllerFile}");
                    }
                }
            }
        }

        http_response_code(404);
        echo "<h2>404 - Page Not Found</h2>";
    }
}