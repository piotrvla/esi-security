<?php
namespace App\Models;

class Connection{
    public static function auth($login,$pswd){
        $checkPswd = \DB::select('select password from users where login = ?',[$login]);
        if (password_verify($pswd, $checkPswd[0]->password)) {
            $p = new OAuthProvider();
            $token = $p->generateToken(8);
            $user = \DB::select('select id from users where login = ?',[$login]);
            \DB::update('UPDATE users SET token = ? where id = ?', [$token,$user[0]->id]);

        } else {
            // à voir ce que jdois t'envoyer
        }
    }
    public static function deconnect($token){
        $user = \DB::select('select id from users where token = ?',[$token]);
        \DB::update('UPDATE users SET token = NULL where id = ?'[$user[0]->id]);
    }
    public static function newUser($login, $pwd){
        /**
         * cout algorithmique
         */
        $options = [
            'cost' => 11,
        ];
        $hash = password_hash($pwd, PASSWORD_BCRYPT, $options); // pcq le salt précisé explicitement est ignoré :))
        $result = \DB::insert("INSERT INTO `users`(login,password) VALUES(?,?)",[$login, $hash]);

    }

}