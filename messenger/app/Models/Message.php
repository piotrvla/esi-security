<?php
namespace App\Models;
class Message{
    
    public static function getMessages($token, $user){
        $user2 = \DB::select('select id from users where token = ?',[$token]);

        $friends = \DB::select('select user1, user2 from friends join users on user2=users.id where users.login = ? and user1 = ?',[$user,$user2[0]->id]);
        if(count($friends)!=0)
            return \DB::select('select sndr.login,rciver.login,content,date from messages join users sndr on messages.sender=sndr.id join 
            users rciver on rciver.id=messages.receiver join friends fr on (fr.user1=sndr.id and fr.user2=rciver.id) where (sndr.token = ? and rciver.login = ?) or (sndr.login = ? and rciver.token = ?) order by date desc',[$token,$user,$user,$token]);
        else
        return null;
    }

    public static function postMessage($token,$receiver,$content){
        $user = \DB::select('select id from users where token = ?',[$token]);
        $friends = \DB::select('select user1, user2 from friends join users on user2=users.id where users.login = ? and user1 = ?',[$receiver,$user[0]->id]);
        $date = date('d-m-y h:i:s');
        if(count($friends)!=0)
            return $result = \DB::insert("INSERT INTO `messages`(sender,receiver,content,date) VALUES(?,?,?,?)",[$user[0]->id, $receiver,$content,$date]);
        else 
            throw new Exception('not friends');
    }
}
?>