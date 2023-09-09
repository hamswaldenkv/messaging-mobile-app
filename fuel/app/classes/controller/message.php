<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
use Fuel\Core\Str;
use Firebase\Firebase;
use Firebase\Push;
use Fuel\Core\Uri;

class Controller_Message extends Controller_Rest
{
	protected $format = 'json';

	public function action_delete()
	{
		$data["subnav"] = array('delete' => 'active');
		$this->response($data);
	}

	public function action_filter()
	{
		$user = AccessToken::check();
		$chat_thread_id = Input::get('thread_id', null);
		$rows_limit = Input::get('rows_limit', 100);

		$query = Model_Chat_Message::query();
		$query->related('chat_thread');
		$query->where('chat_thread.chat_thread_id', $chat_thread_id);
		$query->rows_limit($rows_limit);
		$query->order_by('t0.id');
		$messages = $query->get();

		$data = [];
		foreach ($messages as $item) {
			$user = $item->user;
			$metafield = json_decode($item->metafield);

			$row['id'] = $item->chat_message_id;
			$row['created'] = date('c', $item->created_at);
			$row['kind'] = $item->kind;
			$row['content'] = $item->content;
			$row['status'] = (int)$item->status;
			$row['user_id'] = $user->user_id;
			$row['user_name'] = $user->name;
			$row['user_photo_url'] = $user->photo_url;

			foreach ($metafield as $key => $value) $row[$key] = $value;

			$data[] = $row;
		}

		$response["messages"] = $data;
		$this->response($response);
	}

	public function action_send()
	{
		$user = AccessToken::check();

		$bodyRequest = Api::getBody();
		$chat_thread_id = $bodyRequest->thread_id;
		$content = isset($bodyRequest->content) ? $bodyRequest->content : null;
		$kind = isset($bodyRequest->kind) ? $bodyRequest->kind : 'text';
		$photo_url = isset($bodyRequest->photo_url) ? $bodyRequest->photo_url : null;
		$file_url = isset($bodyRequest->file_url) ? $bodyRequest->file_url : null;
		$web_url = isset($bodyRequest->web_url) ? $bodyRequest->web_url : null;


		try {
			if (!Utils::IsNullOrEmptyString($photo_url)) {
				$kind = 'photo';
				if (Utils::IsNullOrEmptyString($content)) $content = 'Photo envoyée';
			} else {
				if (Utils::IsNullOrEmptyString($content)) throw new \Exception("Error Processing Request");
			}
			$thread = Model_Chat_Thread::query()->where('chat_thread_id', $chat_thread_id)->get_one();


			$message = Model_Chat_Message::forge();
			$message->chat_message_id = Str::random('uuid');
			$message->chat_thread_id = $thread->id;
			$message->user_id = $user->id;
			$message->status = 1;
			$message->kind = $kind;
			$message->content = trim($content);
			$message->metafield = json_encode([
				'photo_url' 	=> $photo_url,
				'file_url' 		=> $file_url,
				'web_url' 		=> $web_url
			]);
			$message->save();

			$thread->last_message = $content;
			$thread->save();

			$this->sendNotification($message);


			$data['message']['id'] = $message->chat_message_id;
			$data['message']['chat_thread_id'] = $thread->chat_thread_id;
			$data['message']['content'] = $message->content;
			$data['message']['created'] = date('c', $message->created_at);
			$this->response($data);
		} catch (\Exception $th) {
			$message = 'Error occured :';
			$message .= "[" . $th->getLine() . "] " . $th->getMessage();
			Printer::error('exception_occured', $message, 00);
		}
	}


	public function action_notification($messageId)
	{
		try {
			$bodyRequest = Api::getBody(true);
			$message = Model_Chat_Message::query()->where('chat_message_id', $messageId)->get_one();

			$thread = $message->chat_thread;
			$metafield = json_decode($message->metafield);

			$icon_url = null;
			$icon_url = isset($metafield->photo_url) ? $metafield->photo_url : null;
			$photo_url = isset($metafield->photo_url) ? $metafield->photo_url : null;
			$deliveryReceipts = [];

			$payload = [];
			$payload['event'] = 'onIncomingMessage';
			$payload['data'] = ['chat_thread_id' => $thread->chat_thread_id];

			$push = new Push;
			$push->setTitle("Nouveau message");
			$push->setMessage($message->content);
			$push->setVibrate(1);
			$push->setSound(1);
			$push->setPayload($payload);
			if ($icon_url != null) $push->setIcon($icon_url);
			if ($photo_url != null) $push->setImage($photo_url);
			$notification = $push->getNotification();
			$notificationPush = $push->getPush();

			$user_ids = explode(',', $thread->user_ids);
			$users_filter = [];
			foreach ($user_ids as $user_id) {
				if ($user_id != $message->user_id) $users_filter[] = $user_id;
			}

			if (empty($users_filter)) throw new Exception("Empty recipients", 1);

			$devices  = Model_User_Device::query()->where('user_id', 'in', $users_filter)->get();
			foreach ($devices as $device) {

				try {
					$firebaseContext = new Firebase();
					$deliveryReceipts[] = $firebaseContext->send($device->firebase_token, $notification, $notificationPush);
				} catch (\Exception $th) {
					//throw $th;
				}
			}

			Printer::printResult([
				'notification' 		=> $notification,
				'notificationPush' 	=> $notificationPush,
				'deliveryReceipts'	=> $deliveryReceipts
			]);
		} catch (\Exception $th) {
			$message = 'Error occured :';
			$message .= "[" . $th->getLine() . "] " . $th->getMessage();
			Printer::error('exception_occured', $message, 00);
		}
	}

	private function sendNotification($message)
	{
		$bodyRequest['type'] = 'push';
		$bodyRequest['chat_message_id'] = $message->chat_message_id;
		$url = Uri::base(false) . 'message/notification/' . $message->chat_message_id;

		Api::push($url, json_encode($bodyRequest));
	}
}
