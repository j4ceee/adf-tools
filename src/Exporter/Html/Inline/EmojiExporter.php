<?php

declare(strict_types=1);

namespace DH\Adf\Exporter\Html\Inline;

use DH\Adf\Exporter\Html\HtmlExporter;
use DH\Adf\Node\Inline\Emoji;

class EmojiExporter extends HtmlExporter
{
    public function export(): string
    {
        \assert($this->node instanceof Emoji);

        $text = $this->node->getText();
        if (preg_match(":[a-zA-Z]+:", $text)) {
            return "";
        } else {
            return $this->node->getText();
        }
    }
}
