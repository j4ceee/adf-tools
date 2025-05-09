<?php

declare(strict_types=1);

namespace DH\Adf\Tests\Node\Inline;

use DH\Adf\Node\Inline\Emoji;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @small
 */
final class EmojiTest extends TestCase
{
    public function testEmoji(): void
    {
        $block = new Emoji('smile');
        $doc = $block->toJson();

        self::assertJsonStringEqualsJsonString($doc, json_encode([
            'type' => 'emoji',
            'attrs' => [
                'shortName' => ':smile:',
            ],
        ]));
    }

    public function testLoadComplexEmoji(): void
    {
        $array = [
            'type' => 'emoji',
            'attrs' => [
                'shortName' => "\t\r\n:\r\0\x0Bnr:test:rn\x0B\0\r:\n\t:",
                'id' => 'e0b07a13-4d07-4596-a42c-16d562065ceb',
                'text' => ':test:',
            ],
        ];

        $emoji = Emoji::load($array);
        $emoji = $emoji->toJson();


        $emojiExpect = new Emoji('nr:test:rn', 'e0b07a13-4d07-4596-a42c-16d562065ceb', ':test:');
        $emojiExpect = $emojiExpect->toJson();

        self::assertJsonStringEqualsJsonString($emojiExpect, $emoji);
    }
}
