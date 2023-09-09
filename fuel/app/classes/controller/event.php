<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Str;

class Controller_Event extends Controller_Rest
{
	protected $format = 'json';

	public function action_list()
	{
		$query = Model_Event::query();
		$query->order_by('created_at', 'desc');
		$query->order_by('start_at', 'desc');
		$results = $query->get();


		$data = [];
		foreach ($results as $item) {
			$data[] = $this->formattedEvent($item);
		}

		$response["events"] = $data;
		$this->response($response);
	}

	public function action_single($event_id)
	{
		$query = Model_Event::query();
		$query->where('event_id', $event_id);
		$result = $query->get_one();

		if ($result == null) Printer::error('resource_invalide', 'Aucune ressource trouvé pour ce ID', 00);

		$data["event"] = $this->formattedEvent($result);
		$this->response($data);
	}

	public function action_join($event_id)
	{
		$user = AccessToken::check();
		$bodyRequest = Api::getBody();

		$event = Model_Event::query()->where('event_id', $event_id)->get_one();
		$participant = Model_Event_Participant::query()
			->where('event_id', $event->id)
			->where('user_id', $user->id)
			->get_one();
		if ($participant == null) {
			$participant = Model_Event_Participant::forge();
			$participant->event_participant_id = Str::random('uuid');
			$participant->event_id = $event->id;
			$participant->user_id = $user->id;
			$participant->status = 1;
			$participant->save();

			$event->count_participants += 1;
			$event->save();
		}

		$data["participant"]['id'] = $participant->event_participant_id;
		$data["participant"]['created_at'] = date('c', $participant->created_at);
		$data["participant"]['event']['id'] = $event->event_id;
		$data["participant"]['event']['title'] = $event->title;
		$this->response($data);
	}

	private function formattedEvent($event)
	{
		$participants = [];
		foreach ($event->participants as $item) {
			$participants[] = $this->formattedParticipant($item);
		}

		$row['id'] = $event->event_id;
		$row['title'] = $event->title;
		$row['description'] = $event->description;
		$row['organisator_name'] = $event->organisator_name;
		$row['count_participants'] = (int)$event->count_participants;
		$row['venue_place'] = $event->venue_place;
		$row['venue_address'] = $event->venue_address;
		$row['is_live'] = (bool)$event->is_live;
		$row['is_free'] = (bool)$event->is_free;
		$row['start_at'] = date('c', $event->start_at);
		$row['finish_at'] = date('c', $event->finish_at);
		$row['created_at'] = date('c', $event->created_at);
		$row['paticipants'] = $participants;

		return $row;
	}

	private function formattedParticipant($participant)
	{
		$user = $participant->user;

		$row['id'] = $user->user_id;
		$row['participation_id'] = $participant->event_participant_id;
		$row['name'] = $user->name;
		$row['first_name'] = $user->first_name;
		$row['last_name'] = $user->last_name;
		$row['photo_url'] = $user->photo_url;

		return $row;
	}
}
