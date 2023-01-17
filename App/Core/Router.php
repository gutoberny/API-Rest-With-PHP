<?php

namespace App\Core;

class Router
{

    private $controller;

    private $method;

    private $controllerMethod;

    private $params = [];

    function __construct()
    {

        $url = $this->parseURL();

        if (file_exists("../App/Controllers/" . ucfirst($url[1]) . ".php")) {

            $this->controller = $url[1];
        } elseif (empty($url[1])) {

            echo "Mosyle API by Gberny";

            exit;
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Recurso não encontrado"]);
            exit;
        }

        require_once "../App/Controllers/" . ucfirst($this->controller) . ".php";

        $this->controller = new $this->controller;

        $this->method = $_SERVER["REQUEST_METHOD"];
        $headers = apache_request_headers();

        switch ($this->method) {
            case "GET":
                if (isset($url[2]) && $url[2] == 'rankDrink' && $url[1] == 'users') {
                    $this->controllerMethod = "rankDrink";
                    $this->params[] = [
                        "token" => $headers["token"]
                    ];
                } elseif (isset($url[2]) && $url[2] == 'rankByDays' && $url[1] == 'users') {
                    $this->controllerMethod = "rankByDays";
                    $this->params[] = [
                        "token" => $headers["token"]
                    ];
                } elseif (isset($url[2]) && $url[2] == 'historyPerDay' && $url[1] == 'users') {
                    $this->controllerMethod = "historyPerDay";
                    $this->params[] = [
                        "token" => $headers["token"]
                    ];
                } elseif ($url[1] == 'users' && !isset($url[2])) {
                    $this->controllerMethod = "usersList";
                    $this->params[] = [
                        "token" => $headers["token"]
                    ];
                } elseif (($url[1] == 'users') && isset($url[2])) {

                    $this->controllerMethod = "getUser";
                    $this->params[] = [
                        "token" => $headers["token"],
                        "id" => $url[2]
                    ];
                }
                break;

            case "POST":
                if (isset($url[3]) && $url[3] == 'drink') {
                    $this->controllerMethod = "userDrink";
                    $this->params[] = [
                        "token" => $headers["token"],
                        "id" => $url[2]
                    ];
                    break;
                }
                if (isset($url[1])) {
                    switch ($url[1]) {
                        case 'users':
                            $this->controllerMethod = "newUser";
                            break;
                        case 'login':
                            $this->controllerMethod = "newLogin";
                            break;
                    }
                }
                break;
            case "PUT":
                if (isset($url[1]) && $url[1] == 'users') {
                    $this->controllerMethod = "editUser";
                    $this->params[] = [
                        "token" => $headers["token"],
                        "id" => $url[2]
                    ];
                }
                break;
            case "DELETE":
                if (isset($url[1]) && $url[1] == 'users') {
                    if (isset($url[2]) && $url[2]) {
                        $this->params[] = [
                            "token" => $headers["token"],
                            "id" => $url[2]
                        ];
                        $this->controllerMethod = "deleteUser";
                    } else {
                        http_response_code(400);
                        echo json_encode(["erro" => "É necessário informar um id"]);
                        exit;
                    }
                }
                break;

            default:
                echo "Método não suportado";
                exit;
                break;
        }

        call_user_func_array([$this->controller, $this->controllerMethod], $this->params);
    }

    private function parseURL()
    {
        return explode("/", $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
    }
}
