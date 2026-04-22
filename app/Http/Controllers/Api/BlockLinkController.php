<?php

namespace App\Http\Controllers\Api;

use App\Actions\BlockLink\DeleteAction as DeleteBlockLinkAction;
use App\Actions\BlockLink\ReorderAction as ReorderBlockLinkAction;
use App\Actions\BlockLink\StoreAction as StoreBlockLinkAction;
use App\Actions\BlockLink\ToggleAction as ToggleBlockLinkAction;
use App\Actions\BlockLink\UpdateAction as UpdateBlockLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlockLink\ReorderBlockLinkRequest;
use App\Http\Requests\BlockLink\StoreBlockLinkRequest;
use App\Http\Requests\BlockLink\UpdateBlockLinkRequest;
use App\Http\Resources\BlockLinkResource;
use App\Models\Block;
use App\Models\BlockLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BlockLinkController extends Controller
{
	public function store(StoreBlockLinkRequest $request, Model $parent, Block $block)
	{
		$link = (new StoreBlockLinkAction)->execute($block, $request->validated());

		return new BlockLinkResource($link->load('linkedProject'));
	}

	public function update(UpdateBlockLinkRequest $request, Model $parent, Block $block, BlockLink $link)
	{
		$link = (new UpdateBlockLinkAction)->execute($link, $request->validated());

		return new BlockLinkResource($link->load('linkedProject'));
	}

	public function destroy(Request $request, Model $parent, Block $block, BlockLink $link)
	{
		$this->authorize('update', $parent);

		(new DeleteBlockLinkAction)->execute($link);

		return response()->json(null, 204);
	}

	public function toggle(Request $request, Model $parent, Block $block, BlockLink $link)
	{
		$this->authorize('update', $parent);

		(new ToggleBlockLinkAction)->execute($link);

		return response()->json(null, 204);
	}

	public function reorder(ReorderBlockLinkRequest $request, Model $parent, Block $block)
	{
		(new ReorderBlockLinkAction)->execute($request->validated('items'));

		return response()->json(null, 204);
	}
}
