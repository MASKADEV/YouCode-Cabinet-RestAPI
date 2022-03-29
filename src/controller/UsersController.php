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

	public function signup() {
		if($this->gettoken()) {
			try {
				$this->verification($this->gettoken());
				if($_SERVER['REQUEST_METHOD'] == 'POST')
				{
					require_once('src/models/authentication.php');
					require_once('src/config/Header.php');
					if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){
							if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
							$auth =  new Authentication();
							$result = $auth->signup($_POST['name'], $_POST['email'], $_POST['password']);
							if($result) {
								echo Authentication::message('User has been Added!', false);
							}else {
								echo Authentication::message('User Already exist', false);
							}
						}else {
								echo Authentication::message('Empty InputField!', true);
						}
					}else {
						echo Authentication::message('Error 404', true);
					}
				}else {
					echo Authentication::message('Internal server issue please contact the support Team', true);
				}

			} catch (\Throwable $th) {
				print_r(json_encode("unauthorized_token"));
			}
		}else {
			echo 'no token available here is a new toekn for you:' . '<br>';
			echo $this->authorization();

		}

	}

	public function addBooking() {
		require_once('src/config/Header.php');
		require_once('src/models/connection.php');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!empty($_POST['user_id']) && !empty($_POST['booking_date']) && !empty($_POST['services_type']) && !empty($_POST['price'])){
				$db = new Database();
				$result = $db->insert('booking', ['user_id', 'booking_date', 'services_type', 'price'], [$_POST['user_id'], $_POST['booking_date'], $_POST['services_type'], $_POST['price']]);
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