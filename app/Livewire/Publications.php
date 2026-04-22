<?php

namespace App\Livewire;

use App\Models\Publication;
use Illuminate\Support\Collection;
use Livewire\Component;

class Publications extends Component
{
	public function render()
	{
		$publications = $this->getPublications();

		return view('livewire.publications', [
			'publications' => $publications,
			'columns2' => $this->splitIntoColumns($publications, 2),
			'columns3' => $this->splitIntoColumns($publications, 3),
			'columns4' => $this->splitIntoColumns($publications, 4),
		]);
	}

	protected function getPublications(): Collection
	{
		return Publication::published()
			->with(['teaser'])
			->orderBy('sort_order')
			->get()
			->map(function (Publication $publication) {
				return [
					'uuid' => $publication->uuid,
					'title' => $publication->title,
					'slug' => $publication->slug,
					'media' => $publication->teaser->first(),
				];
			});
	}

	protected function splitIntoColumns(Collection $items, int $count): array
	{
		$columns = array_fill(0, $count, []);

		foreach ($items->values() as $index => $item) {
			$columns[$index % $count][] = $item;
		}

		return $columns;
	}
}
