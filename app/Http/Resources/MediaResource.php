<?php

namespace App\Http\Resources;

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'file' => $this->file,
			'original_name' => $this->original_name,
			'mime_type' => $this->mime_type,
			'size' => $this->size,
			'alt' => $this->alt,
			'caption' => $this->caption,
			'credits' => $this->credits,
			'width' => $this->width,
			'height' => $this->height,
			'orientation' => $this->orientation,
			'is_image' => $this->is_image,
			'is_teaser' => $this->is_teaser,
			'is_og' => $this->is_og,
			'is_download' => $this->is_download,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'thumbnail_url' => $this->is_image ? ImageController::signUrl($this->file, ['w' => 200, 'h' => 200, 'fit' => 'crop']) : null,
			'preview_url' => $this->is_image ? ImageController::signUrl($this->file, ['w' => 800, 'fit' => 'max']) : null,
			'file_url' => !$this->is_image ? asset('storage/' . $this->file) : null,
			'download_url' => asset('storage/' . $this->file),
		];
	}
}
