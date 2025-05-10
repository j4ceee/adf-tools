<?php

declare(strict_types=1);

namespace DH\Adf\Exporter\Html\Inline;

use DH\Adf\Exporter\Html\HtmlExporter;
use DH\Adf\Node\Inline\InlineCard;

class InlineCardExporter extends HtmlExporter
{
    public function __construct(InlineCard $node)
    {
        parent::__construct($node);
        $this->tags = ['<a class="adf-inline-card" href="%s">', '</a>'];
    }

    public function export(): string
    {
        \assert($this->node instanceof InlineCard);

        return sprintf($this->tags[0], $this->node->getUrl()).$this->node->getUrl().$this->tags[1];
    }
}
