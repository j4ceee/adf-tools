<?php

declare(strict_types=1);

namespace DH\Adf\Node\Inline;

use DH\Adf\Node\InlineNode;

/**
 * @see https://developer.atlassian.com/cloud/jira/platform/apis/document/nodes/emoji
 */
class Emoji extends InlineNode
{
    protected string $type = 'emoji';
    private string $shortName;
    private ?string $id;
    private ?string $text;

    public function __construct(string $shortName, ?string $id = null, ?string $text = null)
    {
        $this->shortName = $shortName;
        $this->id = $id;
        $this->text = $text;
    }

    public static function load(array $data): self
    {
        self::checkNodeData(static::class, $data, ['attrs']);
        self::checkRequiredKeys(['shortName'], $data['attrs']);

        return new self(
            /* removes at both ends: colons (:), spaces, tab (\t), newline (\n), carriage returns (\r),
               null byte (\0), vertical tabs (\x0B) */
            preg_replace('/^[:\s\0\x0B]+|[:\s\0\x0B]+$/', '', $data['attrs']['shortName']),
            $data['attrs']['id'] ?? null,
            $data['attrs']['text'] ?? null
        );
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    protected function attrs(): array
    {
        $attrs = parent::attrs();
        $attrs['shortName'] = sprintf(':%s:', $this->shortName);

        if (null !== $this->id) {
            $attrs['id'] = $this->id;
        }
        if (null !== $this->text) {
            $attrs['text'] = $this->text;
        }

        return $attrs;
    }
}
