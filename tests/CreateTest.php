<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class CreateTest
 */
class CreateTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function addNodeWithSibling()
    {
        $response = $this->post('/node', [
            'name'   => 'Beverley Crusher',
            'left'   => 2,
            'parent' => ''
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson([
            'name'  => 'Beverley Crusher',
            'lft'   => 4,
            'rgt'   => 5,
            'level' => 1
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Jean-Luc Picard',
            'level' => 0,
            'lft'   => 1,
            'rgt'   => 12
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 3
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Beverley Crusher',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 5
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'William Riker',
            'level' => 1,
            'lft'   => 6,
            'rgt'   => 11
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Data',
            'level' => 2,
            'lft'   => 7,
            'rgt'   => 8
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 2,
            'lft'   => 9,
            'rgt'   => 10
        ]);

    }

    /**
     * @test
     */
    public function addNodeWithParent()
    {
        $response = $this->post('/node', [
            'name'   => 'Beverley Crusher',
            'left'   => '',
            'parent' => 1
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson([
            'name'  => 'Beverley Crusher',
            'lft'   => 2,
            'rgt'   => 3,
            'level' => 1
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Jean-Luc Picard',
            'level' => 0,
            'lft'   => 1,
            'rgt'   => 12
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Beverley Crusher',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 3
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 5
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'William Riker',
            'level' => 1,
            'lft'   => 6,
            'rgt'   => 11
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Data',
            'level' => 2,
            'lft'   => 7,
            'rgt'   => 8
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 2,
            'lft'   => 9,
            'rgt'   => 10
        ]);
    }

    /**
     * @test
     */
    public function addNewRootNode()
    {
        $response = $this->post('/node', [
            'name'   => 'James Kirk',
            'left'   => '',
            'parent' => ''
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson([
            'name'  => 'James Kirk',
            'lft'   => 1,
            'rgt'   => 12,
            'level' => 0
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'James Kirk',
            'level' => 0,
            'lft'   => 1,
            'rgt'   => 12
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Jean-Luc Picard',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 11
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 2,
            'lft'   => 3,
            'rgt'   => 4
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'William Riker',
            'level' => 2,
            'lft'   => 5,
            'rgt'   => 10
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Data',
            'level' => 3,
            'lft'   => 6,
            'rgt'   => 7
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 3,
            'lft'   => 8,
            'rgt'   => 9
        ]);
    }
}