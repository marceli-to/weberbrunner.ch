<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Masterdata;
use App\Models\MasterdataGroup;
use App\Models\MasterdataProject;
use App\Models\Project;
use Illuminate\Console\Command;

class HideMasterdata extends Command
{
	protected $signature = 'app:hide-masterdata
		{--location= : Location slug (e.g. "zuerich"). If omitted, applies to all projects.}
		{--project= : Single project (uuid or slug). Overrides --location when given.}
		{--titles=* : Masterdata title(s). Repeat the flag or pass a comma-separated list.}
		{--groups=* : Masterdata group title(s). All entries in the group are hidden. Repeat or comma-separate.}
		{--force : Actually update rows (default is dry-run)}';

	protected $description = 'Set publish=false on masterdata_project pivot rows for the given titles and/or groups, optionally scoped to a location or single project';

	public function handle(): int
	{
		$titles = $this->resolveList('titles');
		$groups = $this->resolveList('groups');

		if (empty($titles) && empty($groups)) {
			$this->error('Provide at least one --titles or --groups value.');
			return self::FAILURE;
		}

		$masterdata = collect();

		if (! empty($titles)) {
			$byTitle = Masterdata::whereIn('title', $titles)->get();
			$missing = array_diff($titles, $byTitle->pluck('title')->all());
			foreach ($missing as $title) {
				$this->warn("No masterdata entry matches title: {$title}");
			}
			$masterdata = $masterdata->merge($byTitle);
		}

		if (! empty($groups)) {
			$groupRecords = MasterdataGroup::whereIn('title', $groups)->get();
			$missingGroups = array_diff($groups, $groupRecords->pluck('title')->all());
			foreach ($missingGroups as $group) {
				$this->warn("No masterdata group matches title: {$group}");
			}
			$byGroup = Masterdata::whereIn('masterdata_group_id', $groupRecords->pluck('id'))->get();
			$masterdata = $masterdata->merge($byGroup);
		}

		$masterdata = $masterdata->unique('id')->values();

		if ($masterdata->isEmpty()) {
			return self::FAILURE;
		}

		$query = MasterdataProject::query()
			->whereIn('masterdata_id', $masterdata->pluck('id'))
			->where('publish', true);

		$scopeLabel = 'all locations';

		if ($projectRef = $this->option('project')) {
			$project = Project::where('uuid', $projectRef)->orWhere('slug', $projectRef)->first();

			if (! $project) {
				$this->error("Project not found: {$projectRef}");
				return self::FAILURE;
			}

			$query->where('project_id', $project->id);
			$scopeLabel = "project \"{$project->title}\" (id={$project->id})";
		} elseif ($slug = $this->option('location')) {
			$location = Location::where('slug', $slug)->first();

			if (! $location) {
				$this->error("Location not found: {$slug}");
				return self::FAILURE;
			}

			$projectIds = $location->projects()->pluck('id');
			$query->whereIn('project_id', $projectIds);
			$scopeLabel = "location \"{$location->title}\" ({$projectIds->count()} project(s))";
		}

		$isDryRun = ! $this->option('force');
		$rows = $query->get();

		$this->line('');
		$this->line(($isDryRun ? '[dry-run] ' : '') . "Hiding " . $masterdata->count() . " masterdata entry/entries for {$scopeLabel}");
		$this->line('Entries: ' . implode(', ', $masterdata->pluck('title')->all()));
		$this->line('');

		if ($rows->isEmpty()) {
			$this->info('No pivot rows to update.');
			return self::SUCCESS;
		}

		$titleById = $masterdata->keyBy('id');
		$projects = Project::whereIn('id', $rows->pluck('project_id')->unique())->orderBy('title')->get()->keyBy('id');
		$rowsByProject = $rows->groupBy('project_id');

		foreach ($rowsByProject as $projectId => $projectRows) {
			$this->line(($isDryRun ? '[dry-run] ' : '') . "project_id={$projectId}  hides=" . $projectRows->count());
		}

		if (! $isDryRun) {
			$query->update(['publish' => false]);
		}

		$logPath = $this->writeLog($projects, $rowsByProject, $titleById, $isDryRun, $scopeLabel);

		$count = $rows->count();
		$this->line('');
		$this->info($isDryRun
			? "{$count} pivot row(s) would be hidden. Run with --force to apply."
			: "Hid {$count} pivot row(s)."
		);
		$this->line("Log written to: {$logPath}");

		return self::SUCCESS;
	}

	private function writeLog($projects, $rowsByProject, $titleById, bool $isDryRun, string $scopeLabel): string
	{
		$timestamp = now()->format('Y-m-d_His');
		$suffix = $isDryRun ? '_dryrun' : '';
		$path = storage_path("logs/hide-masterdata_{$timestamp}{$suffix}.log");

		$lines = [];
		$lines[] = ($isDryRun ? '[DRY-RUN] ' : '') . "hide-masterdata — " . now()->toDateTimeString();
		$lines[] = "Scope: {$scopeLabel}";
		$lines[] = str_repeat('=', 60);
		$lines[] = '';

		foreach ($rowsByProject as $projectId => $projectRows) {
			$project = $projects->get($projectId);
			$title = $project ? $project->full_title : "(unknown project id={$projectId})";
			$lines[] = $title;
			foreach ($projectRows as $row) {
				$lines[] = '- ' . $titleById[$row->masterdata_id]->title;
			}
			$lines[] = '';
		}

		file_put_contents($path, implode(PHP_EOL, $lines));

		return $path;
	}

	private function resolveList(string $option): array
	{
		$raw = (array) $this->option($option);
		$expanded = [];

		foreach ($raw as $value) {
			foreach (explode(',', (string) $value) as $piece) {
				$piece = trim($piece);
				if ($piece !== '') {
					$expanded[] = $piece;
				}
			}
		}

		return array_values(array_unique($expanded));
	}
}
