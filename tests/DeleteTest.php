<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class DeleteTest
 */
class DeleteTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function deleteALeafNode()
    {
        $response = $this->delete('/node/4');

        $response->assertResponseStatus(200);
        $this->notSeeInDatabase('node', [
            'name' => 'Data'
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Jean-Luc Picard',
            'level' => 0,
            'lft'   => 1,
            'rgt'   => 8
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'William Riker',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 7
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 2,
            'lft'   => 5,
            'rgt'   => 6
        ]);
    }

    /**
     * @test
     */
    public function deleteANodeAndItsChildren()
    {
        $response = $this->delete('/node/3');

        $response->assertResponseStatus(200);
        $this->notSeeInDatabase('node', [
            'name' => 'William Riker'
        ]);
        $this->notSeeInDatabase('node', [
            'name' => 'Geordi La Forge'
        ]);
        $this->notSeeInDatabase('node', [
            'name' => 'Data'
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Jean-Luc Picard',
            'level' => 0,
            'lft'   => 1,
            'rgt'   => 4
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 3
        ]);
    }

    /**
     * @test
     */
    public function deleteNodeWithoutDeletingTheChildren()
    {
        $response = $this->delete('/node/3/save-children');

        $response->assertResponseStatus(200);
        $this->notSeeInDatabase('node', [
            'name' => 'William Riker'
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Jean-Luc Picard',
            'level' => 0,
            'lft'   => 1,
            'rgt'   => 8
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 3
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Data',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 5
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 1,
            'lft'   => 6,
            'rgt'   => 7
        ]);
    }
}