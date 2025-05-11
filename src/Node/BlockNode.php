<?php

declare(strict_types=1);

namespace DH\Adf\Node;

use InvalidArgumentException;

abstract class BlockNode extends Node
{
    protected array $allowedContentTypes = [];
    protected array $content = [];

    public function jsonSerialize(): array
    {
        $result = parent::jsonSerialize();
        $result['content'] = $this->content;

        return $result;
    }

    /**
     * @param array $data ADF-Array
     * @param BlockNode|null $parent Parent-Node, set to 'null' if none
     * @param bool $skipUndefined Set to 'false' to throw exception for undefined nodes (default: 'true')
     * @return self
     */
    public static function load(array $data, ?self $parent = null, bool $skipUndefined = true): self
    {
        self::checkNodeData(static::class, $data);

        $class = Node::NODE_MAPPING[$data['type']];
        $node = new $class($parent);
        \assert($node instanceof self);

        // set attributes if defined
        if (\array_key_exists('attrs', $data)) {
            foreach ($data['attrs'] as $key => $value) {
                $node->{$key} = $value;
            }
        }

        // set content if defined
        if (\array_key_exists('content', $data)) {
            foreach ($data['content'] as $nodeData) {
                if (!self::checkNodeType($nodeData['type'], $skipUndefined))
                    continue;

                $class = Node::NODE_MAPPING[$nodeData['type']];
                $child = $class::load($nodeData, $node);

                $node->append($child);
            }
        }

        return $node;
    }

    /**
     * Check if node type is valid
     * @param string $nodeType
     * @param bool $skipException
     * @return bool True if valid node type
     */
    public static function checkNodeType(string $nodeType, bool $skipException): bool
    {
        if(!\array_key_exists($nodeType, Node::NODE_MAPPING)) {
            if (!$skipException) {
                throw new InvalidArgumentException(sprintf('Invalid node type "%s"', $nodeType));
            }
            return false;
        }
        return true;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    protected function append(Node $node): void
    {
        foreach ($this->allowedContentTypes as $type) {
            if ($node instanceof $type) {
                $this->content[] = $node;

                return;
            }
        }

        throw new InvalidArgumentException(sprintf('Invalid content type "%s" for block node "%s".', $node->type, $this->type));
    }
}
