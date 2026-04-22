<?php

namespace App\Http\Controllers\Api;

use App\Actions\Block\DeleteAction as DeleteBlockAction;
use App\Actions\Block\ReorderAction as ReorderBlockAction;
use App\Actions\Block\StoreAction as StoreBlockAction;
use App\Actions\Block\UpdateAction as UpdateBlockAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Block\ReorderBlockRequest;
use App\Http\Requests\Block\StoreBlockRequest;
use App\Http\Requests\Block\UpdateBlockRequest;
use App\Http\Resources\BlockResource;
use App\Models\Block;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BlockController extends Controller
{
	public function index(Request $request, Model $parent)
	{
		$this->authorize('view', $parent);

		return BlockResource::collection(
			$parent->blocks()->with(['media', 'links.linkedProject'])->orderBy('sort_order')->get()
		);
	}

	public function store(StoreBlockRequest $request, Model $parent)
	{
		$block = (new StoreBlockAction)->execute($parent, $request->validated());

		return new BlockResource($block->load(['media', 'links.linkedProject']));
	}

	public function update(UpdateBlockRequest $request, Model $parent, Block $block)
	{
		$block = (new UpdateBlockAction)->execute($block, $request->validated());

		return new BlockResource($block->load(['media', 'links.linkedProject']));
	}

	public function destroy(Request $request, Model $parent, Block $block)
	{
		$this->authorize('update', $parent);

		(new DeleteBlockAction)->execute($block);

		return response()->json(null, 204);
	}

	public function reorder(ReorderBlockRequest $request, Model $parent)
	{
		(new ReorderBlockAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
