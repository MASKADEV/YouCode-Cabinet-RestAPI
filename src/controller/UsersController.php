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
						$auth =  new Authentication();
						$user_id = json_decode(file_get_contents("php://input"));
						$result = $auth->signin($user_id->user_id);
						if($result){
							require_once('src/security/jwt_handler.php');
							$jwt = new JwtController();
							$token = $jwt->authorization();
							echo Authentication::message('Logedin!', $token, $result ,false);
						}else {
							echo Authentication::message('User not exist please try to sign up first', null, null, true);
						}
				}
	}

	public function signup() {

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			require_once('src/models/authentication.php');
			require_once('src/config/Header.php');
			$email = json_decode(file_get_contents("php://input"));
			$full_name = json_decode(file_get_contents("php://input"));
			$password = json_decode(file_get_contents("php://input"));
			$password_confirmation = json_decode(file_get_contents("php://input"));
			if(!empty($email) && !empty($full_name) && !empty($password)){
				
				if($password->password == $password_confirmation->password_confirmation) {
					$auth =  new Authentication();
					$id = uniqid('gfg');

					$result = $auth->signup($id, $full_name->full_name, $email->email, $password->password);
					if($result) {
						$jwt = new JwtController();
						$token = $jwt->authorization();
						echo Authentication::message('User has been Added!', $token, $id ,false);
					}else {
						echo Authentication::message('User Already exist', null, null, false);
					}
				}else {
					echo Authentication::message('password not the same', null, null, false);
				}

			}else {
				echo Authentication::message('Empty InputField!', null, null, true);
			}

		}else {
			echo Authentication::message('Internal server issue please contact the support Team',null, null, true);
		}

	}

	public function fetchBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		$user_id = json_decode(file_get_contents("php://input"));
		$db = new Database();
		$result = $db->fetchBooking($user_id->user_id);
		echo json_encode($result);
	}

	public function addBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$user_id = json_decode(file_get_contents("php://input"));
			$booking_date = json_decode(file_get_contents("php://input"));
			$time = json_decode(file_get_contents("php://input"));
			$service_type = json_decode(file_get_contents("php://input"));
			$price = json_decode(file_get_contents("php://input"));
				$db = new Database();
				$result = $db->insert('booking', ['user_id', 'booking_date', 'time', 'service_type', 'price'], [$user_id->user_id, $booking_date->booking_date, $time->time, $service_type->service_type, $price->price]);
				if($result) {
					echo Database::message('thank you for your booking', false);
				}else {
					echo Database::message('failed Booking!', true);
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

	public function checkBookedTime()
	{
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		$booking_date = json_decode(file_get_contents("php://input"));
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$db = new Database();
			$result = $db->checkBookedTime($booking_date->booking_date);
			if($result){
				echo $result;
			}else {
				echo Database::message('Operation Failed!', true);
			}
		}else {
			echo Database::message('Internal server issue please contact the support Team', true);
		}
	}

	public function editBooking() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = json_decode(file_get_contents("php://input"));
			$booking_date = json_decode(file_get_contents("php://input"));
			$time = json_decode(file_get_contents("php://input"));
			$service_type = json_decode(file_get_contents("php://input"));
			$price = json_decode(file_get_contents("php://input"));
			$db = new Database();
			$result = $db->update('booking', $id->id, $booking_date->booking_date, $time->time, $service_type->service_type, $price->price);
			if($result) {
				echo json_encode($result);
			}else {
				echo Database::message('update Failed!', true);
			}
		}else {
			echo Database::message('Internal server issue please contact the support Team', true);
		}
	}
}
