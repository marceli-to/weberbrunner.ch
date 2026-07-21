<?php

return [
	'max_upload_edge' => 6000,
	'max_upload_short_edge' => 1400,
	'upload_quality' => 90,
	'widths' => [480, 640, 768, 1024, 1280, 1440, 1600, 1920],
	'heights' => [220, 440, 640, 960, 1280],
	'slideshow_heights' => [0 => 220, 768 => 480, 1280 => 640],
	'formats' => ['avif', 'webp', 'jpg'],
	'fits' => ['crop', 'max'],
	'quality' => 90,
	'warm_memory_limit' => '512M',
];
