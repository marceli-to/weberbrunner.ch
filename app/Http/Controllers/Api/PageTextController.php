<?php

namespace App\Http\Controllers\Api;

use App\Actions\PageText\FindAction as FindPageTextAction;
use App\Actions\PageText\UpdateAction as UpdatePageTextAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageText\UpdatePageTextRequest;
use App\Http\Resources\PageTextResource;
use App\Models\PageText;

class PageTextController extends Controller
{
	public function show(string $page)
	{
		$this->authorize('viewAny', PageText::class);

		$pageText = (new FindPageTextAction)->execute($page);

		return new PageTextResource($pageText);
	}

	public function update(UpdatePageTextRequest $request, string $page)
	{
		$pageText = (new FindPageTextAction)->execute($page);

		(new UpdatePageTextAction)->execute($pageText, $request->validated());

		return new PageTextResource($pageText);
	}
}
