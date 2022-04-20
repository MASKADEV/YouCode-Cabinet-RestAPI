<?php 
require_once 'src/security/jwt_handler.php';

class UsersController extends JwtController{
	
	public function __construct()
	{
	}

	public function index() {
		echo 'index users';
	}

	public function signin()
	{
				if($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					require_once('src/models/authentication.php');
					require_once('src/config/Header.php');
					if(isset($_POST['id'])) {
						$auth =  new Authentication();
						$result = $auth->signin($_POST['id']);
						if($result){
							require_once('src/security/jwt_handler.php');
							$jwt = new JwtController();
							$token = $jwt->authorization();
							echo Authentication::message('Logedin!', $token, $result ,false);
						}else {
							echo Authentication::message('User not exist please try to sign up first', null, null, true);
						}
					}else {
						echo Authentication::message('Empty InputField!', null, null, true);
					}
				}
	}

	public function signup() {

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			require_once('src/models/authentication.php');
			require_once('src/config/Header.php');
			if(isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['password'])){
				// echo $_POST['full_name'] . $_POST['email'] . $_POST['password']; 
					if(!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
					$auth =  new Authentication();
					$id = uniqid('gfg');
					$result = $auth->signup($id, $_POST['full_name'], $_POST['email'], $_POST['password']);
					if($result) {
						$jwt = new JwtController();
						$token = $jwt->authorization();
						echo Authentication::message('User has been Added!', $token, $id ,false);
					}else {
						echo Authentication::message('User Already exist', null, null, false);
					}
				}else {
						echo Authentication::message('Empty InputField!', null, null, true);
				}
			}else {
				echo Authentication::message('Error 404', null, null, true);
			}
		}else {
			echo Authentication::message('Internal server issue please contact the support Team',null, null, true);
		}

	}

	public function addBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!empty($_POST['user_id']) && !empty($_POST['booking_date']) && !empty($_POST['time']) && !empty($_POST['service_type']) && !empty($_POST['price'])){
				$db = new Database();
				$result = $db->insert('booking', ['user_id', 'booking_date', 'time', 'service_type', 'price'], [$_POST['user_id'], $_POST['booking_date'], $_POST['time'], $_POST['service_type'], $_POST['price']]);
				if($result) {
					echo Database::message('thank you for your booking', false);
				}else {
					echo Database::message('failed Booking!', true);
				}
			}else {
				echo Database::message('please fill the whole form', true);
			}
		}else {
			echo Database::message('Internal server issue please contact the support Team', true);
		}
	}

	public function updateBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!empty($_POST['booking_date']) && !empty($_POST['time']) && !empty($_POST['service_type']) && !empty($_POST['price'])){
				$db = new Database();
				$params = explode('/', $_GET['p']);
				$result = $db->update('booking', $params[2], $_POST['booking_date'], $_POST['time'], $_POST['service_type'], $_POST['price']);
				if($result) {
					echo Database::message('update has been Done!', false);
				}else {
					echo Database::message('failed Update!', true);
				}
			}else {
				echo Database::message('please fill the whole form', true);
			}
		}else {
			echo Database::message('Internal server issue please contact the support Team', true);
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
