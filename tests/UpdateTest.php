<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class UpdateTest
 */
class UpdateTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function updateAName()
    {
        $response = $this->patch('/node/3', ['name' => 'Number One']);
        $response->seeJson([
            'id'    => 3,
            'name'  => 'Number One',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 9
        ]);
        $this->seeInDatabase('node', [
            'name' => 'Number One'
        ]);
    }

    /**
     * @test
     */
    public function moveNodeToLeftNodeOfGivenParent()
    {
        $response = $this->patch('/node/3', ['parent' => 1]);
        $response->seeJson([
            'name'  => 'William Riker',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 7
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'William Riker',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 7
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Data',
            'level' => 2,
            'lft'   => 3,
            'rgt'   => 4
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 2,
            'lft'   => 5,
            'rgt'   => 6
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 1,
            'lft'   => 8,
            'rgt'   => 9
        ]);
    }

    /**
     * @test
     */
    public function moveNodeToRightOfGivenLeftSibling()
    {
        $response = $this->patch('/node/4', ['leftSibling' => 2]);
        $response->seeJson([
            'name'  => 'Data',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 5
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'William Riker',
            'level' => 1,
            'lft'   => 6,
            'rgt'   => 9
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Data',
            'level' => 1,
            'lft'   => 4,
            'rgt'   => 5
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Geordi La Forge',
            'level' => 2,
            'lft'   => 7,
            'rgt'   => 8
        ]);
        $this->seeInDatabase('node', [
            'name'  => 'Deanna Troi',
            'level' => 1,
            'lft'   => 2,
            'rgt'   => 3
        ]);
    }
}