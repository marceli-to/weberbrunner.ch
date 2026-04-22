<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
	public function up(): void
	{
		DB::transaction(function () {
			$this->copyBlocks('project_blocks', 'App\Models\Project', 'project_id', false);
			$this->copyBlocks('publication_blocks', 'App\Models\Publication', 'publication_id', true);

			$this->copyBlockLinks('project_block_links', 'project_blocks', 'project_block_id');
			$this->copyBlockLinks('publication_block_links', 'publication_blocks', 'publication_block_id');

			$this->rewriteMedia('App\Models\ProjectBlock', 'project_blocks');
			$this->rewriteMedia('App\Models\PublicationBlock', 'publication_blocks');

			$this->assertParity();
		});
	}

	public function down(): void
	{
		DB::transaction(function () {
			DB::table('media')
				->whereIn('id', function ($query) {
					$query->select('media.id')
						->from('media')
						->join('blocks', function ($join) {
							$join->on('media.mediable_id', '=', 'blocks.id')
								->where('media.mediable_type', 'App\Models\Block');
						});
				})
				->where('mediable_type', 'App\Models\Block')
				->update(['mediable_type' => DB::raw("CASE WHEN (SELECT blockable_type FROM blocks WHERE blocks.id = media.mediable_id) = 'App\\\\Models\\\\Project' THEN 'App\\\\Models\\\\ProjectBlock' ELSE 'App\\\\Models\\\\PublicationBlock' END")]);

			DB::table('block_links')->delete();
			DB::table('blocks')->delete();
		});
	}

	private function copyBlocks(string $sourceTable, string $blockableType, string $parentColumn, bool $hasUrl): void
	{
		$rows = DB::table($sourceTable)->orderBy('id')->get();

		foreach ($rows as $row) {
			DB::table('blocks')->insert([
				'uuid' => $row->uuid,
				'blockable_type' => $blockableType,
				'blockable_id' => $row->{$parentColumn},
				'type' => $row->type,
				'title' => $row->title,
				'content' => $row->content,
				'url' => $hasUrl ? $row->url : null,
				'sort_order' => $row->sort_order,
				'created_at' => $row->created_at,
				'updated_at' => $row->updated_at,
			]);
		}
	}

	private function copyBlockLinks(string $sourceTable, string $sourceBlockTable, string $sourceBlockFk): void
	{
		$rows = DB::table($sourceTable)
			->join($sourceBlockTable, "{$sourceTable}.{$sourceBlockFk}", '=', "{$sourceBlockTable}.id")
			->join('blocks', 'blocks.uuid', '=', "{$sourceBlockTable}.uuid")
			->select(
				"{$sourceTable}.uuid",
				'blocks.id as new_block_id',
				"{$sourceTable}.title",
				"{$sourceTable}.url",
				"{$sourceTable}.link_type",
				"{$sourceTable}.linked_project_id",
				"{$sourceTable}.sort_order",
				"{$sourceTable}.publish",
				"{$sourceTable}.created_at",
				"{$sourceTable}.updated_at",
			)
			->orderBy("{$sourceTable}.id")
			->get();

		foreach ($rows as $row) {
			DB::table('block_links')->insert([
				'uuid' => $row->uuid,
				'block_id' => $row->new_block_id,
				'title' => $row->title,
				'url' => $row->url,
				'link_type' => $row->link_type,
				'linked_project_id' => $row->linked_project_id,
				'sort_order' => $row->sort_order,
				'publish' => $row->publish,
				'created_at' => $row->created_at,
				'updated_at' => $row->updated_at,
			]);
		}
	}

	private function rewriteMedia(string $oldType, string $sourceBlockTable): void
	{
		$rows = DB::table('media')
			->join($sourceBlockTable, function ($join) use ($sourceBlockTable) {
				$join->on('media.mediable_id', '=', "{$sourceBlockTable}.id");
			})
			->join('blocks', 'blocks.uuid', '=', "{$sourceBlockTable}.uuid")
			->where('media.mediable_type', $oldType)
			->select('media.id as media_id', 'blocks.id as new_block_id')
			->get();

		foreach ($rows as $row) {
			DB::table('media')
				->where('id', $row->media_id)
				->update([
					'mediable_type' => 'App\Models\Block',
					'mediable_id' => $row->new_block_id,
				]);
		}
	}

	private function assertParity(): void
	{
		$projectBlocks = DB::table('project_blocks')->count();
		$publicationBlocks = DB::table('publication_blocks')->count();
		$blocks = DB::table('blocks')->count();

		if ($blocks !== $projectBlocks + $publicationBlocks) {
			throw new \RuntimeException("Block count mismatch: blocks={$blocks}, expected=" . ($projectBlocks + $publicationBlocks));
		}

		$projectBlockLinks = DB::table('project_block_links')->count();
		$publicationBlockLinks = DB::table('publication_block_links')->count();
		$blockLinks = DB::table('block_links')->count();

		if ($blockLinks !== $projectBlockLinks + $publicationBlockLinks) {
			throw new \RuntimeException("Block link count mismatch: block_links={$blockLinks}, expected=" . ($projectBlockLinks + $publicationBlockLinks));
		}

		$orphanedMedia = DB::table('media')
			->whereIn('mediable_type', ['App\Models\ProjectBlock', 'App\Models\PublicationBlock'])
			->count();

		if ($orphanedMedia > 0) {
			throw new \RuntimeException("Orphaned media still references legacy block types: {$orphanedMedia} rows");
		}
	}
};
