<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
use Fuel\Core\Str;
use Fuel\Core\Uri;

class Controller_Uploads extends Controller_Rest
{
	protected $format = 'json';

	public function action_upload()
	{
		// $user = AccessToken::check();

		try {
			$file = Utils::uploadFile('useruploads/' . date('Ymd'));

			$data["asset"]['url'] = Uri::base(false) .'api/chat/'. $file->path;
			$data["asset"]['size'] = $file->size;
			$data["asset"]['file_name'] = $file->file_name;
			$data["asset"]['original_name'] = $file->original_name;
			$data["asset"]['extension'] = $file->extension;
			$this->response($data);
		} catch (\Exception $th) {
			Printer::error('exception_occured', 'Erreur survenue', 00);
		}
	}
}
