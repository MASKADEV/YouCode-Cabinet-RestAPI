<?php 




class Authentication {
    public function  __construct () {
    }

    public function signup($name, $email, $password) {
        require_once('connection.php');
        $db = new Database(); 
        if(empty($db->selectOne('users','email',$email))){
        $result = $db->insert('users', ['id', 'full_name', 'email', 'password'], [uniqid('gfg'),$name, $email, $password]);
        return ($result);
        }else {
            return false;
        }
    }

    public function signin($id) {
        require_once('connection.php');
        $db = new Database();
        $result = $db->checkUserExist($id);
        return $result;
    }

    public static function message($content, $status) {
	    return json_encode(array('message' => $content, 'error' => $status));
	}
}