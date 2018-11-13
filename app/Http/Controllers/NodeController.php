<?php

namespace App\Http\Controllers;


use App\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class NodeController
 *
 * @package App\Http\Controllers
 */
class NodeController extends Controller
{

    /**
     * Get the given node and all descendants and return the json to represent them
     *
     * @param int $id
     *
     * @return mixed
     */
    public function show($id)
    {
        $node = Node::findOrFail($id)->loadChildren();
        return $node;
    }

    /**
     * Create a new node with the given request. The request should have a name and the id for the reference node to
     * determine where to put the node. If the id is passed in a variable called parent, it will be set as the left
     * most child of that parent. If the id is passed in a variable called left, it will be inserted to the right of
     * that node.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        if (!empty($request->left)) {
            $leftSibling = Node::findOrFail($request->left);
            Node::where('rgt', '>', $leftSibling->rgt)->increment('rgt', 2);
            Node::where('lft', '>', $leftSibling->rgt)->increment('lft', 2);

            return Node::create([
                'name'  => $request->name,
                'lft'   => $leftSibling->rgt + 1,
                'rgt'   => $leftSibling->rgt + 2,
                'level' => $leftSibling->level
            ]);
        } elseif (!empty($request->parent)) {
            $parent = Node::findOrFail($request->parent);
            Node::where('rgt', '>', $parent->lft)->increment('rgt', 2);
            Node::where('lft', '>', $parent->lft)->increment('lft', 2);

            return Node::create([
                'name'  => $request->name,
                'lft'   => $parent->lft + 1,
                'rgt'   => $parent->lft + 2,
                'level' => $parent->level + 1
            ]);

        } else {
            Node::increment('level');
            Node::increment('lft', 1);
            Node::increment('rgt', 1);

            return Node::create([
                'name'  => $request->name,
                'lft'   => 1,
                'rgt'   => Node::max('rgt') + 1,
                'level' => 0
            ]);
        }
    }

    /**
     * Remove the node for the given id along with all of its descendants
     *
     * @param $id
     */
    public function delete($id)
    {
        $nodeToDelete = Node::findOrFail($id);
        $width        = $nodeToDelete->rgt - $nodeToDelete->lft + 1;
        Node::whereBetween('lft', [$nodeToDelete->lft, $nodeToDelete->rgt])->delete();
        Node::where('rgt', '>', $nodeToDelete->rgt)->decrement('rgt', $width);
        Node::where('lft', '>', $nodeToDelete->rgt)->decrement('lft', $width);
    }


    /**
     * Remove only the node for the given id. It's children will get connected to it's parent node.
     *
     * @param $id
     */
    public function deleteSaveChildren($id)
    {
        $nodeToDelete = Node::findOrFail($id);
        $nodeToDelete->delete();

        /**
         * I chose this query because it is more efficient and only runs one update. If that violates your raw query rule
         * This can also be done with:
         * Node::whereBetween('lft', [$nodeToDelete->lft, $nodeToDelete->rgt])->decrement('rgt');
         * Node::whereBetween('lft', [$nodeToDelete->lft, $nodeToDelete->rgt])->decrement('lft');
         * Node::whereBetween('lft', [$nodeToDelete->lft, $nodeToDelete->rgt])->decrement('level');
         */
        Node::whereBetween('lft', [$nodeToDelete->lft, $nodeToDelete->rgt])
            ->decrement('rgt', 1, [
                'lft'   => DB::raw('lft - 1'),
                'level' => DB::raw('level - 1')
            ]);
        Node::where('rgt', '>', $nodeToDelete->rgt)->decrement('rgt', 2);
        Node::where('lft', '>', $nodeToDelete->rgt)->decrement('lft', 2);

    }

    /**
     * Update the given node with the information from the request. If name is given the name will be updated. If a
     * parent is given, the node will be moved to the left most child of the given parent. If a leftSibling is given,
     * the node will be moved to be the next sibling to the right of the given node.
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $nodeToUpdate = Node::findOrFail($id);
        if ($request->has('name')) {
            $nodeToUpdate->name = $request->name;
            $nodeToUpdate->save();
        }
        if ($request->has('parent')) {
            $nodeToUpdate->move($request->parent, 'parent');
        } elseif ($request->has('leftSibling')) {
            $nodeToUpdate->move($request->leftSibling, 'sibling');
        }

        return $nodeToUpdate;
    }
}