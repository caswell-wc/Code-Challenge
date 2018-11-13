<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class ReadTest
 */
class ReadTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function showTree()
    {
        $response = $this->get('/node/1');
        $response->assertResponseStatus(200);
        $response->seeJson([
            'id'       => 1,
            'name'     => 'Jean-Luc Picard',
            'level'    => 0,
            'lft'      => 1,
            'rgt'      => 10,
            'children' => [
                [
                    'id'    => 2,
                    'name'  => 'Deanna Troi',
                    'level' => 1,
                    'lft'   => 2,
                    'rgt'   => 3
                ],
                [
                    'id'       => 3,
                    'name'     => 'William Riker',
                    'level'    => 1,
                    'lft'      => 4,
                    'rgt'      => 9,
                    'children' => [
                        [
                            'id'    => 4,
                            'name'  => 'Data',
                            'level' => 2,
                            'lft'   => 5,
                            'rgt'   => 6
                        ],
                        [
                            'id'    => 5,
                            'name'  => 'Geordi La Forge',
                            'level' => 2,
                            'lft'   => 7,
                            'rgt'   => 8
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function showSubTree()
    {
        $response = $this->get('/node/3');
        $response->assertResponseStatus(200);
        $response->seeJson([
            'id'       => 3,
            'name'     => 'William Riker',
            'level'    => 1,
            'lft'      => 4,
            'rgt'      => 9,
            'children' => [
                [
                    'id'    => 4,
                    'name'  => 'Data',
                    'level' => 2,
                    'lft'   => 5,
                    'rgt'   => 6
                ],
                [
                    'id'    => 5,
                    'name'  => 'Geordi La Forge',
                    'level' => 2,
                    'lft'   => 7,
                    'rgt'   => 8
                ]
            ]
        ]);
    }
}
