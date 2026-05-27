<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Works extends Component
{
	#[Url]
	public string $query = '';

	#[Url]
	public array $types = [];

	#[Url]
	public array $status = [];

	#[Url]
	public array $locations = [];

	#[Url(keep: true)]
	public int $seed = 0;

	public function mount(): void
	{
		if ($this->seed === 0) {
			$this->seed = random_int(1, PHP_INT_MAX);
		}
	}

	public function clearSearch(): void
	{
		$this->query = '';
	}

	public function toggleType(string $type): void
	{
		$this->types = $this->toggleArrayValue($this->types, $type);
	}

	public function toggleStatus(string $status): void
	{
		$this->status = $this->toggleArrayValue($this->status, $status);
	}

	public function toggleLocation(string $location): void
	{
		$this->locations = $this->toggleArrayValue($this->locations, $location);
	}

	public function clearFilters(): void
	{
		$this->query = '';
		$this->types = [];
		$this->status = [];
		$this->locations = [];
	}

	#[Computed]
	public function hasActiveFilters(): bool
	{
		return !empty($this->query)
			|| !empty($this->types)
			|| !empty($this->status)
			|| !empty($this->locations);
	}

	#[Computed]
	public function availableTypes(): array
	{
		return Category::whereHas('projects', fn ($q) => $q->published())
			->pluck('title', 'slug')
			->toArray();
	}

	#[Computed]
	public function availableStatus(): array
	{
		return Status::whereHas('projects', fn ($q) => $q->published())
			->pluck('title', 'slug')
			->toArray();
	}

	#[Computed]
	public function availableLocations(): array
	{
		return [
			'zuerich' => 'Zürich',
			'berlin' => 'Berlin',
		];
	}

	public function render()
	{
		$projects = $this->getProjects();

		return view('livewire.works', [
			'projects' => $projects,
			'columns2' => $this->splitIntoColumns($projects, 2),
			'columns3' => $this->splitIntoColumns($projects, 3),
			'columns4' => $this->splitIntoColumns($projects, 4),
			'resultCount' => $projects->count(),
		]);
	}

	protected function getProjects(): Collection
	{
		return $this->buildQuery()
			->get()
			->map(function (Project $project) {
				return [
					'title' => $project->full_title,
					'slug' => $project->slug,
					'media' => $project->media->first(),
				];
			});
	}

	protected function buildQuery(): Builder
	{
		$query = Project::published()
			->with(['media' => fn ($q) => $q->where('is_teaser', true)]);

		$this->applySearch($query);
		$this->applyFilters($query);

		if (empty($this->query)) {
			$query->orderByRaw('RAND(?)', [$this->seed]);
		}

		return $query;
	}

	protected function applySearch(Builder $query): void
	{
		if (empty($this->query)) {
			return;
		}

		$likeTerm = '%' . trim($this->query) . '%';

		$query->where(fn ($q) => $q
			->where('title', 'LIKE', $likeTerm)
			->orWhere('city', 'LIKE', $likeTerm)
			->orWhere('description', 'LIKE', $likeTerm)
		);

		$query->orderByRaw("CASE WHEN title LIKE ? THEN 0 ELSE 1 END", [$likeTerm])
			->orderBy('created_at', 'desc');
	}

	protected function applyFilters(Builder $query): void
	{
		if (!empty($this->types)) {
			$query->whereHas('categories', fn ($q) => $q->whereIn('slug', $this->types));
		}

		if (!empty($this->status)) {
			$query->whereHas('statuses', fn ($q) => $q->whereIn('slug', $this->status));
		}

		if (!empty($this->locations)) {
			$query->whereHas('location', fn ($q) => $q->whereIn('slug', $this->locations));
		}

	}

	protected function splitIntoColumns(Collection $items, int $count): array
	{
		$columns = array_fill(0, $count, []);

		foreach ($items->values() as $index => $item) {
			$columns[$index % $count][] = $item;
		}

		return $columns;
	}

	protected function toggleArrayValue(array $array, string $value): array
	{
		if (in_array($value, $array)) {
			return array_values(array_diff($array, [$value]));
		}

		$array[] = $value;
		return $array;
	}
}
