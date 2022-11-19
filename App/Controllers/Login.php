<?php

use App\Core\Controller;

class Login extends Controller{
    
    public function newLogin(){
        $login = $this->getRequestBody();
        
        $userModel = $this->model("ClassLogin");

        $newLogin = $userModel->newLogin($login);
        
        if(!$newLogin) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($newLogin);
        };
    }
}