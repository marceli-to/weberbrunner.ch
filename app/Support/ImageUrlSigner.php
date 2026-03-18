<?php

namespace App\Support;

use League\Glide\Signatures\SignatureFactory;

class ImageUrlSigner
{
	public static function signUrl(string $path, array $params = []): string
	{
		$signature = SignatureFactory::create(config('app.key'));
		$params['s'] = $signature->generateSignature('/img/' . $path, $params);

		return '/img/' . $path . '?' . http_build_query($params);
	}

	public static function ogImageUrl(string $path): string
	{
		return url(static::signUrl($path, ['w' => 1200, 'h' => 630, 'fit' => 'crop', 'fm' => 'jpg']));
	}
}
