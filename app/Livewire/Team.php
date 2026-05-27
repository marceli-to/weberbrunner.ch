<?php

namespace App\Livewire;

use App\Models\TeamMember;
use Livewire\Attributes\Url;
use Livewire\Component;

class Team extends Component
{
	#[Url]
	public $location = 'all';

	public function setFilter($filter)
	{
		$this->location = $filter;
	}

	public function render()
	{
		$query = TeamMember::published()
			->with(['location', 'image', 'bios'])
			->orderBy('name')
			->orderBy('firstname');

		if ($this->location !== 'all') {
			$query->whereHas('location', fn ($q) => $q->where('slug', $this->location));
		}

		return view('livewire.team', ['members' => $query->get()]);
	}
}
