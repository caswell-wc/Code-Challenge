<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Node
 *
 * @package App
 */
class Node extends Model
{
    protected $table      = 'node';
    public    $timestamps = false;

    protected $guarded = ['id'];

    public $children = [];

    /**
     * Load all descendants of this node into the children property
     *
     * @return $this
     */
    public function loadChildren()
    {
        $this->children = $this->getChildren();
        foreach ($this->children as $child) {
            $child->loadChildren();
        }
        return $this;
    }

    /**
     * Get the direct descendants of this node
     *
     * @return mixed
     */
    public function getChildren()
    {
        return Node::where('lft', '>', $this->lft)
                   ->where('rgt', '<', $this->rgt)
                   ->where('level', $this->level + 1)
                   ->orderBy('lft')
                   ->get();
    }

    /**
     * Format this node and it's children into an array that can be json encoded
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $jsonArray = parent::jsonSerialize();
        foreach ($this->children as $child) {
            $jsonArray['children'][] = $child->jsonSerialize();
        }
        return $jsonArray;
    }

    /**
     * Move this node to a new location.
     *
     * @param int    $moveToId The id of the reference node of where to move the node to
     * @param string $type     The type of reference node that was given. This can be parent if moving the node to be
     *                         the left child of the given $moveToId or sibling if moving the node to be a right
     *                         sibling of $moveToId
     */
    public function move($moveToId, $type)
    {
        $this->loadChildren();
        $width = $this->rgt - $this->lft + 1;

        Node::where('lft', '>', $this->rgt)->decrement('lft', $width);
        Node::where('rgt', '>', $this->rgt)->decrement('rgt', $width);
        $moveToNode = Node::findOrFail($moveToId);

        $moveToStartPoint = $moveToNode->rgt;
        $moveToLevel      = $moveToNode->level;
        if ($type == 'parent') {
            $moveToStartPoint = $moveToNode->lft;
            $moveToLevel      = $moveToNode->level + 1;
        }

        Node::where('lft', '>', $moveToStartPoint)->increment('lft', $width);
        Node::where('rgt', '>', $moveToStartPoint)->increment('rgt', $width);

        $this->realignSubtree($this, $moveToStartPoint + 1, $moveToLevel);
    }

    /**
     * This is a recursive function that will reset the left, right, and level values of the nodes in the tree based on
     * the leftValue and level given when calling the function initially.
     *
     * @param Node $node
     * @param int  $leftValue The left value for the first node in the tree
     * @param int  $level     The level of the first node in the tree
     *
     * @return int
     */
    private function realignSubtree($node, $leftValue, $level)
    {
        $node->level = $level;
        $node->lft   = $leftValue;
        if (!empty($node->children)) {
            foreach ($node->children as $child) {
                $leftValue = $this->realignSubtree($child, ++$leftValue, $level + 1);
            }
            $node->rgt = ++$leftValue;
        } else {
            $node->rgt = ++$leftValue;
        }

        $node->save();

        return $leftValue;
    }
}