<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Glide\ServerFactory;
use League\Glide\Server;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;

class ImageController extends Controller
{
	protected Server $server;

	public function __construct()
	{
		$this->server = ServerFactory::create([
			'source' => storage_path('app/public'),
			'cache' => storage_path('app/.glide-cache'),
			'driver' => 'imagick',
		]);
	}

	public function show(Request $request, string $path): Response
	{
		try {
			SignatureFactory::create(config('app.key'))
				->validateRequest('/img/' . $path, $request->all());
		} catch (SignatureException $e) {
			abort(403, 'Invalid image signature.');
		}

		$params = $request->all();
		unset($params['s']);

		$cachedPath = $this->server->makeImage($path, $params);
		$cache = $this->server->getCache();
		$imageContent = $cache->read($cachedPath);
		$mimeType = $cache->mimeType($cachedPath);

		return response($imageContent, 200)
			->header('Content-Type', $mimeType)
			->header('Cache-Control', 'public, max-age=31536000, immutable')
			->header('Expires', now()->addYear()->toRfc7231String());
	}

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
