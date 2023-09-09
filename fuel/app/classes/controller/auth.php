<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\DB;
use Fuel\Core\Str;

class Controller_Auth extends Controller_Rest
{
	protected $format = 'json';

	public function action_login()
	{

		try {
			$bodyRequest = Api::getBody();
			$emailAddress = $bodyRequest->emailAddress;
			$userPassword = $bodyRequest->userPassword;

			$user = Model_User::query()->where('username', $emailAddress)->get_one();
			if ($user == null) throw new \Exception("Error Processing Request");

			if ($user->password != Credentials::hash_password($userPassword)) {
				Printer::error('password_invalid', 'Le mot de passe est invalide', 00);
			}


			$session = $this->outputSession($user);
			$this->response($session);
		} catch (\Exception $th) {
			DB::rollback_transaction();

			$response['error']['code'] = 00;
			$response['error']['message'] = $th->getMessage();
			$response['error']['type'] = 'exception_occured';
			$this->response($response);
		}
	}

	public function action_register()
	{

		try {
			$bodyRequest = Api::getBody();
			$firstName = $bodyRequest->firstName;
			$lastName = isset($bodyRequest->lastName) ? $bodyRequest->lastName : null;
			$emailAddress = $bodyRequest->emailAddress;
			$userPassword = $bodyRequest->userPassword;

			$checkExisting = Model_User::query()->where('username', $emailAddress)->get_one();
			if ($checkExisting != null) throw new \Exception("Error Processing Request");

			$user = Model_User::forge();
			$user->user_id = Str::random('uuid');
			$user->username = $emailAddress;
			$user->password = Credentials::hash_password($userPassword);
			$user->email_validated = true;
			$user->email_address = $emailAddress;
			$user->name = $firstName;
			$user->first_name = $firstName;
			$user->account_state = 1;
			$user->status = 1;
			$user->country_code = 'CD';

			DB::start_transaction();
			$user->save();

			$session = $this->outputSession($user);
			DB::commit_transaction();
			$this->response($session);
		} catch (\Exception $th) {
			DB::rollback_transaction();

			$response['error']['code'] = 00;
			$response['error']['message'] = $th->getMessage();
			$response['error']['type'] = 'exception_occured';
			$this->response($response);
		}
	}

	private function outputSession($user)
	{
		$tokenId = Str::random('alnum', 64);
		$expiresAt = time() + 7200;

		$accessToken = Model_Access_Token::query()
			->where('user_id', $user->id)
			->where('expires_at', '>', time())
			->where('status', 1)
			->get_one();
		if ($accessToken == null) {
			$accessToken = Model_Access_Token::forge();
			$accessToken->access_token_id = Str::random('uuid');
			$accessToken->user_id  = $user->id;
			$accessToken->access_token = $tokenId;
			$accessToken->expires_at = $expiresAt;
			$accessToken->type = 'Bearer';
			$accessToken->scope = 'messages,event_participation';
			$accessToken->status = 1;
			$accessToken->save();
		}

		$data['access_token'] = $accessToken->access_token;
		$data['type'] = $accessToken->type;
		$data['expires'] = $accessToken->expires_at;
		$data['associated_user']['id'] = $user->user_id;
		$data['associated_user']['name'] = $user->name;
		$data['associated_user']['username'] = $user->name;
		$data['associated_user']['email_address'] = $user->email_address;
		$data['associated_user']['first_name'] = $user->first_name;
		$data['associated_user']['last_name'] = $user->last_name;
		$data['associated_user']['photo_url'] = $user->photo_url;

		return $data;
	}
}
