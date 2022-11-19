<?php

use App\Core\Controller;

class Users extends Controller{
    
    public function usersList($params){
        $userModel = $this->model("User");

        $users = $userModel->getListUsers($params);

        if(!$users) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($users);
        }
    }

    public function newUser(){
        $newUser = $this->getRequestBody();

        $userModel = $this->model("User");

        $new_user = $userModel->newUser($newUser);
        
        if(!$new_user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($new_user);
        };
    }

    public function getUser($id){
        $userModel = $this->model("User");
        
        $user = $userModel->getUser($id);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }

    public function userDrink($param){
        $drinkUser = $this->getRequestBody();
        $userModel = $this->model("User");

        $user = $userModel->drinkUser($param, $drinkUser);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }

    public function editUser($id){
        $editUser = $this->getRequestBody();
        $userModel = $this->model("User");

        $user = $userModel->editUser($editUser, $id);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }

    public function deleteUser($id){
        $userModel = $this->model("User");

        $user = $userModel->deleteUser($id);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }

    public function rankDrink($params){
        $date = $this->getRequestBody();
        $userModel = $this->model("User");

        $user = $userModel->rankDrink($date, $params);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }

    public function rankByDays($params){
        $date = $this->getRequestBody();
        $userModel = $this->model("User");

        $user = $userModel->rankByDays($params, $date);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }

    public function historyPerDay($params){
        $userModel = $this->model("User");

        $user = $userModel->historyPerDay($params);
        
        if(!$user) {
            http_response_code(204);
            exit;
        } else {
            echo json_encode($user);
        };
    }
}