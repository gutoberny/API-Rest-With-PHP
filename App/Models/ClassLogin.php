<?php

use App\Core\Model;

require_once('User.php');

class ClassLogin
{
    private $key = 'gberny_mosyle';

    /**
     * Function do login
     * @params array with email and password
     */
    public function newLogin($params)
    {

        $check_login = User::checkExistsUser($params->email, $params->password);

        if ($check_login) {
            $email = $check_login[0]->email;
            if ($email != $params->email) {
                return "User not found.";
            }
        }
        $sql_login = " SELECT iduser FROM users WHERE email = ? AND password = ?";

        $read = Model::getConn()->prepare($sql_login);
        $read->bindValue(1, $params->email);
        $read->bindValue(2, $params->password);
        $read->execute();
        $result = $read->fetch(PDO::FETCH_OBJ);

        if (empty($result->iduser)) {
            return "User or Password Invalid.";
        } else {

            $sql_return = " SELECT us.iduser, us.name, us.email, SUM(dm.amount_coffee) as drink_counter
                FROM users as us
                LEFT JOIN drink_movement as dm ON us.iduser = dm.iduser 
                WHERE us.iduser =  ?";

            $read = Model::getConn()->prepare($sql_return);
            $read->bindValue(1, $result->iduser);
            $read->execute();
            $result = $read->fetch(PDO::FETCH_OBJ);


            if (!empty($result)) {
                $token = $this->generateToken($result->name, $result->iduser);
                return [
                    "token" => $token,
                    "return" => $result
                ];
            } else {
                return null;
            }
        }
    }

    /**
     * Function support generate token
     * @data data
     */
    static function base64ErlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Function to generate Token
     * @name name user
     * @id id user
     */
    protected function generateToken($name, $id)
    {
        //Header Token
        $header = $this->base64ErlEncode('{"alg": "HS256", "typ":"JWT"}');

        //Payload - Content
        $payload = $this->base64ErlEncode('{"sub": "' . md5(time()) . '", "name": "' . $name . '","iduser": "' . $id . '","iat": ' . time() . '}');

        //Sign
        $sign = $this->base64ErlEncode(hash_hmac("sha256", $header . '.' . $payload, $this->key, true));

        $token = $header . "." . $payload . "." . $sign;

        return $token;
    }

    /**
     * Function to validate Token
     * @name name user
     * @id id user
     */
    static function checkToken($token)
    {
        $class = new ClassLogin();
        $parts = explode('.', $token);

        $sign = $class->base64ErlEncode(
            hash_hmac('sha256', $parts[0] . "." . $parts[1], $class->key, true)
        );

        if ($sign == $parts[2]) {

            $payload = json_decode(base64_decode($parts[1]));

            return $payload;
        } else {
            return "Access denied.";
        }
    }
}
