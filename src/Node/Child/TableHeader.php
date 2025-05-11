<?php

declare(strict_types=1);

namespace DH\Adf\Node\Child;

use DH\Adf\Node\BlockNode;
use DH\Adf\Node\Node;
use JsonSerializable;

/**
 * @see https://developer.atlassian.com/cloud/jira/platform/apis/document/nodes/table_header
 */
class TableHeader extends TableCell implements JsonSerializable
{
    protected string $type = 'tableHeader';

    public static function load(array $data, ?BlockNode $parent = null, bool $skipUndefined = true): self
    {
        self::checkNodeData(static::class, $data);

        $node = new self(
            $data['attrs']['background'] ?? null,
            $data['attrs']['colspan'] ?? null,
            $data['attrs']['rowspan'] ?? null,
            $data['attrs']['colwidth'] ?? null,
            $parent
        );

        // set content if defined
        if (\array_key_exists('content', $data)) {
            foreach ($data['content'] as $nodeData) {
                if (!self::checkNodeType($data['type'], $skipUndefined))
                    continue;

                $class = Node::NODE_MAPPING[$nodeData['type']];
                $child = $class::load($nodeData, $node);

                $node->append($child);
            }
        }

        return $node;
    }
}
