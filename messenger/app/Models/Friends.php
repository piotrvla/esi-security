<?php
namespace App\Models;

class Friends{

    public static function getFriends($token){
        $user = \DB::select('select id from users where token = ?',[$token]);
        if(count($user)==0){
            throw new Exception('no token found');
        }
        $friends = \DB::select('select users.login from friends join users on friends.user2=users.id where friends.user1=?',[$user[0]->id]);
        return $friends;
    }
    public static function addFriend($login, $token){
        $user = \DB::select("select id from users where token =?",[$token]);
        error_log(count($user));
        if(count($user)==0){
            
            throw new Exception('no token found');
        }
        $friendsId = \DB::select('select id from users where login = ?',[$login]);
        if(count($friendsId)==0){
            throw new Exception('no friend found');
        }
        $result = \DB::insert("INSERT INTO `friends`(user1,user2) VALUES(?,?)",[$user[0]->id, $friendsId[0]->id]);
    }
    public static function delFriend($token,$login){
        $user = \DB::select('select id from users where token = ?',[$token]);
        if(count($user)==0){
            throw new Exception('no token found');
        }
        $friendsId = \DB::select('select id from users where login = ?',[$login]);
        if(count($friendsId)==0){
            throw new Exception('no friend found');
        }
        \DB::delete('DELETE FROM friends WHERE user1 = ?',[$user[0]->id,$friendsId[0]->id]);
    }
    public static function getOnlineFriends($token){
        $user = \DB::select('select id from users where token = ?',[$token]);
        if(count($user)==0){
            throw new Exception('no token found');
        }
        return \DB::select('select * from friends f JOIN users u ON f.user2=u.id where f.user1=? and u.token IS NOT NULL;',[$user[0]->id]);
    }
    public static function getPendingInvitations($token){
        $user = \DB::select('select id from users where token = ?',[$token]);
        if(count($user)==0){
            throw new Exception('no token found');
        }
        return \DB::select('SELECT u.login from friends f JOIN users u ON u.id=f.user1 where f.user2=? AND ? not in (SELECT user1 from friends where user2=f.user1);' ,[$user[0]->id, $user[0]->id]);
    }

}