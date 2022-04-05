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
			$id = json_decode(file_get_contents("php://input"));
			$auth =  new Authentication();
			$result = $auth->signin($id->id);
			if($result){
				echo json_encode($result);
			}else {
				echo Authentication::message('User not exist please try to sign up first', true);
			}
		}
	}

	public function signup() {
				if($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					$full_name = json_decode(file_get_contents("php://input"));
  					$email = json_decode(file_get_contents("php://input")); 
  					$password = json_decode(file_get_contents("php://input")); 
					require_once('src/models/authentication.php');
					require_once('src/config/Header.php');
					$auth =  new Authentication();
					$result = $auth->signup($full_name->full_name, $email->email, $password->password);
					if($result) {
					echo Authentication::message('User has been Added!', false);
					}else {
						echo Authentication::message('User Already exist', false);
					}
				}else {
					echo Authentication::message('Internal server issue please contact the support Team', true);
				}

	}

	public function addBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$user_id = json_decode(file_get_contents("php://input"));
			$booking_date = json_decode(file_get_contents("php://input"));
			$booking_time = json_decode(file_get_contents("php://input"));
			$services_type = json_decode(file_get_contents("php://input"));
			$price = json_decode(file_get_contents("php://input"));
				$db = new Database();
				$result = $db->insert('booking', ['user_id', 'booking_date', 'booking_time' ,'services_type', 'price'], [$user_id->user_id, $booking_date->booking_date, $booking_time->booking_time ,$services_type->services_type, $price->price]);
				if($result) {
					echo Database::message('Thank you for your booking', false);
				}else {
					echo Database::message('failed Booking!', true);
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
