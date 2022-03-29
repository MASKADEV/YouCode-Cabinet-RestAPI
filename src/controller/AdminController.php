<?php 


class AdminController {
    public function index () {echo 'Admin Page';}

    public function signin()
	{
				if($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					require_once('src/models/authentication.php');
					require_once('src/config/Header.php');
					if(isset($_POST['email']) && isset($_POST['password'])) {
						$auth =  new Authentication();
						$result = $auth->signin($_POST['email'], $_POST['password']);
						if($result){
							echo json_encode($result);
						}else {
							echo Authentication::message('User not exist please try to sign up first', true);
						}
					}else {
						echo Authentication::message('Empty InputField!', true);
					}
				}
	}

	public function deleteBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'GET') {
			$params = explode('/', $_GET['p']);
			$db = new Database();
			$result = $db->delete('booking', $params[2]);
			if($result){
				echo Database::message('booking has been deleted', false);
			}else {
				echo Database::message('Operation Failed!', true);
			}
		}else {
			echo Database::message('Internal server issue please contact the support Team', true);
		}
	}
}