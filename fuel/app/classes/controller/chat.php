<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\DB;
use Fuel\Core\Str;

class Controller_Chat extends Controller_Rest
{
	protected $format = 'json';

	public function action_delete()
	{
		$data["subnav"] = array('delete' => 'active');
		$this->response($data);
	}

	public function action_threads()
	{
		$user = AccessToken::check();

		$query = Model_Chat_Thread::query();
		$query->where(DB::expr("FIND_IN_SET({$user->id}, user_ids)"), '>', 0);
		$chats = $query->get();

		$data = [];
		foreach ($chats as $item) {
			$user_ids = explode(',', $item->user_ids);
			$title = 'Chat #' . $item->id;
			if ($item->kind == 'group') $title = $item->event->title;
			if ($item->kind == 'private') {

				$target_users = [];
				$target_users_names = [];
				foreach ($user_ids as $user_id) {
					if ((int)$user_id !== $user->id) $target_users[] = $user_id;
				}

				$users = Model_User::query()->where('id', 'in', $target_users)->get();
				foreach ($users as $usr) $target_users_names[] = $usr->name;
				$title = implode(', ', $target_users_names);
			}

			$row['id'] = $item->chat_thread_id;
			$row['created'] = date('c', $item->created_at);
			$row['updated'] = Utils::IsNullOrEmptyString($item->updated_at) ? date('c', $item->created_at) : date('c', $item->updated_at);
			$row['kind'] = $item->kind;
			$row['user_ids'] = $user_ids;
			$row['status'] = (int)$item->status;
			$row['title'] = $title;
			$row['description'] = $item->last_message;
			$row['metafield'] = json_decode($item->metafield);
			$row['photo_url'] = null;

			$data[] = $row;
		}

		$response["chat_threads"] = $data;
		$this->response($response);
	}

	public function action_thread()
	{
		$user = AccessToken::check();

		$bodyRequest = Api::getBody();
		$kind = $bodyRequest->kind;
		$event_id = isset($bodyRequest->event_id) ? $bodyRequest->event_id : null;
		$user_id = isset($bodyRequest->user_id) ? $bodyRequest->user_id : null;


		try {
			$event = Model_Event::query()->where('event_id', $event_id)->get_one();
			$user_2 = Model_User::query()->where('user_id', $user_id)->get_one();


			$query = Model_Chat_Thread::query();
			$query->where('kind', $kind);
			if ($kind == 'group') $query->where('event_id', $event->id);
			if ($kind == 'private') {
				$query->where_open();
				$query->where('user_ids', implode(',', [$user->id, $user_2->id]));
				$query->or_where('user_ids', implode(',', [$user_2->id, $user->id]));
				$query->where_close();
			}
			$thread = $query->get_one();

			if ($thread == null) {
				$user_ids = [];

				if ($kind == 'group') {
					$participants = Model_Event_Participant::query()
						->where('event_id', $event->id)
						->get();
					foreach ($participants as $part) $user_ids[] = $part->user_id;
				} else {
					$user_ids[] = $user->id;
					$user_ids[] = $user_2->id;
				}

				$thread = Model_Chat_Thread::forge();
				$thread->chat_thread_id = Str::random('uuid');
				$thread->creator_user_id = $user->id;
				$thread->status = 1;
				$thread->kind = $kind;
				$thread->last_message = 'Chat crée';
				$thread->user_ids = implode(',', $user_ids);
				$thread->metafield = json_encode([]);
				if ($kind == 'group') $thread->event_id = !is_null($event) ? $event->id : null;
			} else {
				$user_ids = explode(',', $thread->user_ids);
				if (!in_array($user->id, $user_ids)) $user_ids[] = $user->id;

				$thread->user_ids = implode(',', $user_ids);
			}
			$thread->save();

			$data['chat_thread']['id'] = $thread->chat_thread_id;
			$data['chat_thread']['last_message'] = $thread->last_message;
			$data['chat_thread']['created'] = date('c', $thread->created_at);
			$data['chat_thread']['updated'] = Utils::IsNullOrEmptyString($thread->updated_at) ? date('c', $thread->created_at) : date('c', $thread->updated_at);
			$this->response($data);
		} catch (\Exception $th) {
			Printer::printResult($th, 'array');
		}
	}
}
