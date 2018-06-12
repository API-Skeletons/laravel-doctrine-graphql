<?php

namespace ZFTest\Doctrine\GraphQL\GraphQL;

use ZFTest\Doctrine\GraphQL\AbstractTest;
use GraphQL\GraphQL;
use Db\Entity;

class UserTest extends AbstractTest
{
    public function testUserEntity()
    {
        $schema = $this->getSchema();

        $query = "{ user { id name } }";

        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $this->assertEquals(5, sizeof($output['data']['user']));
    }

    public function testUserFilterId()
    {
        $schema = $this->getSchema();

        $query = "{ user (filter: { id:1 }) { id name } }";

        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $this->assertEquals(1, sizeof($output['data']['user']));
    }

    public function testUserAddress()
    {
        $schema = $this->getSchema();

        $query = "{ user (filter: { id:1 }) { id name address { id address } } }";

        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $this->assertEquals('address1', $output['data']['user'][0]['address']['address']);
    }

    public function testPasswordFilter()
    {
        $schema = $this->getSchema();

        $query = "{ user (filter: { id:1 }) { password } }";

        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $this->assertEquals(
            'Cannot query field "password" on type "DbTest_Entity_User".',
            $output['errors'][0]['message']
        );
    }

    public function testUserArtistManyToMany()
    {
        $schema = $this->getSchema();

        $query = "{ user( filter: { id:1 } ) { id artist { id } } }";

        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $this->assertEquals(5, sizeof($output['data']['user'][0]['artist']));
    }

    public function testFetchCriteriaForRelation()
    {
        $schema = $this->getSchema();

        $query = "{ user( filter: { id:1 } ) { id artist { id performance ( filter: { id: 3 } ) { id } } } }";

        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $this->assertEquals(5, sizeof($output['data']['user'][0]['artist']));
    }
}