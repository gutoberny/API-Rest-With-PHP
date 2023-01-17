<?php

use App\Core\Model;

require_once('ClassLogin.php');
class User
{

    public $iduser;
    public $name;
    public $email;
    public $password;
    /**
     * Function to get List of Users
     * @params param to get token
     */
    public function getListUsers($params)
    {
        ClassLogin::checkToken($params['token']);
        $sql = " SELECT * FROM users ORDER BY name ASC";

        $read = Model::getConn()->prepare($sql);
        $read->execute();
        $count = $read->rowCount();
        $result = $read->fetchAll(PDO::FETCH_OBJ);
        $array = json_decode(json_encode($result), true);
        $return = array_chunk($array, 2, false);
        $return['pages'] = round(($count / 2), 0, PHP_ROUND_HALF_UP);

        if (!empty($return)) {
            return $return;
        } else {
            return null;
        }
    }

    /**
     * Function to check if user exists
     * @param $email email of an user
     */
    public static function checkExistsUser($email, $password = null)
    {
        if (empty($email)) {
            return "ID Required";
        }
        $sql = " SELECT us.iduser, us.email
            FROM users as us
            WHERE us.email = ?";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $email);
        $read->execute();
        $result = $read->fetchAll(PDO::FETCH_OBJ);

        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Function to get a unique User
     * @params param to get token
     */
    public function getUser($params)
    {
        ClassLogin::checkToken($params['token']);

        $sql = " SELECT us.iduser, us.name, us.email, SUM(dm.amount_coffee) as drink_counter
        FROM users as us
        LEFT JOIN drink_movement as dm ON us.iduser = dm.iduser 
        WHERE us.iduser = ?";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $params['id']);
        $read->execute();
        $result = $read->fetch(PDO::FETCH_OBJ);


        if (!empty($result->iduser)) {
            return $result;
        } else {
            return "ID not found.";
        }
    }

    /**
     * Function to create a new User
     * @params array with data
     */
    public function newUser($params)
    {

        $check_exists_user = $this->checkExistsUser($params->email);

        if ($check_exists_user) {
            return $check_exists_user;
        }

        $sql = " INSERT INTO users (name, email, password) VALUES ( ?, ?, ?) ";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $params->name);
        $read->bindValue(2, $params->email);
        $read->bindValue(3, $params->password);

        if ($read->execute()) {
            return true;
        } else {
            print_r($read->errorInfo());
            return false;
        }
    }

    /**
     * Function to check user admin
     * @params iduser
     */
    private function checkAdmin($id)
    {
        if (empty($id)) {
            return "ID Required";
        }
        $sql = " SELECT type_user
        FROM users as us
        WHERE us.iduser = ?";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $id);
        $read->execute();
        $result = $read->fetch(PDO::FETCH_OBJ);
        $array = json_decode(json_encode($result), true);
        if (!empty($result) && $array['type_user'] == '1') {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Function to edit user
     * @params array with data
     * @headers array with token and id
     */
    public function editUser($params, $header)
    {
        ClassLogin::checkToken($header['token']);

        $check = $this->checkAdmin($header['id']);
        if (empty($check)) {
            return "You need to be Admin to edit.";
        }
        $sql = " UPDATE users SET name = ?, email = ?, password = ? WHERE iduser = ? ";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $params->name);
        $read->bindValue(2, $params->email);
        $read->bindValue(3, $params->password);
        $read->bindValue(4, $header['id']);

        if ($read->execute()) {
            return true;
        } else {
            print_r($read->errorInfo());
            return null;
        }
    }

    /**
     * Function to delete user
     * @params array with token and id
     */
    public function deleteUser($params)
    {
        ClassLogin::checkToken($params['token']);

        $check = $this->checkAdmin($params['id']);
        if (empty($check)) {
            return "You need to be Admin to edit.";
        }
        $sql = " DELETE FROM users WHERE iduser = ?";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $params['id']);

        if ($read->execute()) {
            return true;
        } else {
            print_r($read->errorInfo());
            return null;
        }
    }

    /**
     * Function to insert a new drink to an user
     * @params array with token and id
     * @amount_coffee int amount coffee
     */
    public function drinkUser($param, $amount_coffee)
    {
        ClassLogin::checkToken($param['token']);
        $sql = " INSERT INTO drink_movement (iduser, date_drink, amount_coffee) VALUES (?, ?, ?)";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $param['id']);
        $read->bindValue(2, date("Y-m-d"));
        $read->bindValue(3, $amount_coffee->drink);

        if ($read->execute()) {
            $result_sql = " SELECT us.iduser, us.name, us.email, SUM(dm.amount_coffee) as drink_counter
            FROM users as us
            LEFT JOIN drink_movement as dm ON us.iduser = dm.iduser 
            WHERE us.iduser = ?";

            $result_read = Model::getConn()->prepare($result_sql);
            $result_read->bindValue(1, $param['id']);
            $result_read->execute();
            $result = $result_read->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } else {
            print_r($read->errorInfo());
            return null;
        }
    }

    /**
     * Function rank users drink by days
     * @params array with token and id
     * @date array with interval dates
     */
    public function rankByDays($params, $date)
    {
        ClassLogin::checkToken($params['token']);
        $sql = " SELECT us.name, SUM(dm.amount_coffee) as amount_coffee 
        FROM users as us 
        LEFT JOIN drink_movement as dm ON us.iduser = dm.iduser 
        WHERE dm.date_drink >= ? 
        GROUP BY us.name 
        ORDER BY SUM(dm.amount_coffee) DESC";
        $last_days = date("Y-m-d", strtotime($date->begin_date . " -" . $date->last_x_days . " days"));

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $last_days);
        if ($read->execute()) {
            $result = $read->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } else {
            print_r($read->errorInfo());
            return null;
        }
    }

    /**
     * Function to rank a user who most drink
     * @date filter date
     * @params array with token and id
     */
    public function rankDrink($date, $params)
    {

        ClassLogin::checkToken($params['token']);

        $sql = " SELECT us.name, SUM(dm.amount_coffee) as amount_coffee 
        FROM users as us 
        LEFT JOIN drink_movement as dm ON us.iduser = dm.iduser 
        WHERE dm.date_drink >= ? 
		AND dm.date_drink <= ?
        GROUP BY us.name 
        ORDER BY SUM(dm.amount_coffee) DESC";

        $read = Model::getConn()->prepare($sql);
        $read->bindValue(1, $date->begin_date);
        $read->bindValue(2, $date->end_date);
        if ($read->execute()) {
            $result = $read->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } else {
            print_r($read->errorInfo());
            return null;
        }
    }

    /**
     * Function to get a history per day
     * @params array with token and id
     */
    public function historyPerDay($params)
    {

        ClassLogin::checkToken($params['token']);

        $sql = " SELECT SUM(dm.amount_coffee) as amount_coffee, dm.date_drink 
        FROM drink_movement as dm 
        WHERE dm.amount_coffee IS NOT NULL
        GROUP BY dm.date_drink
        ORDER BY dm.date_drink  ASC";

        $read = Model::getConn()->prepare($sql);
        if ($read->execute()) {
            $result = $read->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } else {
            print_r($read->errorInfo());
            return null;
        }
    }
}
