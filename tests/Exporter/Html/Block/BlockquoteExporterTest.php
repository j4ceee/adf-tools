<?php

declare(strict_types=1);

namespace DH\Adf\Tests\Exporter\Html\Block;

use DH\Adf\Exporter\Html\Block\BlockquoteExporter;
use DH\Adf\Node\Block\Blockquote;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @small
 */
final class BlockquoteExporterTest extends TestCase
{
    public function testEmptyBlockquote(): void
    {
        $node = new Blockquote();
        $exporter = new BlockquoteExporter($node);

        self::assertSame('<blockquote></blockquote>', $exporter->export());
    }

    public function testBlockquoteWithText(): void
    {
        $node = (new Blockquote())
            ->paragraph()
            ->text('This is a text inside a paragraph wrapped into a blockquote.')
            ->end()
        ;
        $exporter = new BlockquoteExporter($node);

        self::assertSame('<blockquote><p>This is a text inside a paragraph wrapped into a blockquote.</p></blockquote>', $exporter->export());
    }

    public function testBlockquoteWithEmoji(): void
    {
        $node = (new Blockquote())
            ->paragraph()
            ->emoji('poop', '1f4a9', '💩')
            ->end()
        ;
        $exporter = new BlockquoteExporter($node);

        self::assertSame('<blockquote><p>💩</p></blockquote>', $exporter->export());
    }
}
