<?php

declare(strict_types=1);

if (php_sapi_name() !== 'cli') {
    exit(__FILE__." must be run in CLI\n");
}

include __DIR__.'/vendor/autoload.php';

// Normalize codepoints for Twemoji
$emoji = array_map(
	function ($emoji) {
		if (strpos($emoji, "\u{200D}") === false) {
			$emoji = str_replace("\u{FE0F}", '', $emoji);
		}

		return $emoji;
	},
	array_filter(
		Spatie\Emoji\Emoji::all(),
		fn($emoji) => !in_array($emoji, [\Spatie\Emoji\Emoji::CHARACTER_TRADE_MARK, \Spatie\Emoji\Emoji::CHARACTER_COPYRIGHT, \Spatie\Emoji\Emoji::CHARACTER_REGISTERED])
	)
);

// Work on bytes, output a PCRE regexp made of ASCII characters
$builder = new s9e\RegexpBuilder\Builder([
    'input'  => 'Bytes',
    'output' => 'PHP',
]);
$regexp = $builder->build($emoji);
file_put_contents(__DIR__.'/src/emoji_bytes.regexp', $regexp);
