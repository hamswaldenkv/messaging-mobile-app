<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Str;

class Controller_Profile extends Controller_Rest
{
	protected $format = 'json';

	public function action_devices()
	{
		$user = AccessToken::check();
		$bodyRequest = Api::getBody();

		try {
			$device = Model_User_Device::query()
				->where('user_id', $user->id)
				->where('unique_id', $bodyRequest->uniqueId)
				->where('platform', $bodyRequest->platform)
				->get_one();
			if ($device == null) {
				$device = Model_User_Device::forge();
				$device->user_id = $user->id;
				$device->user_device_id = Str::random('uuid');
				$device->name = $bodyRequest->deviceName;
				$device->unique_id = $bodyRequest->uniqueId;
				$device->device_id = $bodyRequest->deviceId;
				$device->brand = $bodyRequest->deviceBrand;
				$device->model = $bodyRequest->deviceModel;
				$device->platform = $bodyRequest->platform;
				$device->os_version = $bodyRequest->osVersion;
				$device->status = 1;
			}
			$device->firebase_token = $bodyRequest->firebaseToken;
			$device->save();

			$data["device"]['id'] = $device->user_device_id;
			$data["device"]['created'] = date('c', $device->created_at);
			$this->response($data);
		} catch (\Exception $th) {
			Printer::error('exception_occured', 'Erreur survenue', 00);
		}
	}

	public function action_delete()
	{
		$data["subnav"] = array('delete' => 'active');
		$this->response($data);
	}

	public function action_threads()
	{
		$data["subnav"] = array('threads' => 'active');
		$this->response($data);
	}
}
