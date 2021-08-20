<?php
namespace App\JordiFramework\Test\Datatabase;

use App\JordiFramework\Datatabase\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class QueryBuilderTest extends TestCase
{

    public function getBuilder(): QueryBuilder
    {
        return new QueryBuilder();
    }

    public function getPDO(): \PDO
    {
        $pdo =  new \PDO('mysql:host=localhost;dbname=diaspofinance','root','',array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION));        ;
    
        return $pdo;
    }

    

    // public function testSimpleQuery()
    // {
    
    //     $q1 = $this->getBuilder()->from("users")->__toString();
    //     $q2 = $this->getBuilder()->from("users", "u")->select('user_name')->__toString();
    //     $q3 = $this
    //     ->getBuilder()
    //     ->from("users", "u")
    //     ->select('user_name')
    //     ->where('user_name = :x OR user_name = :y', 'user_name = :Z')->__toString();

    //     $q4 = $this
    //     ->getBuilder()
    //     ->from("users", "u")
    //     ->select('user_name')
    //     ->where('user_name = :x OR user_name = :y')
    //     ->where('user_name = :Z')->__toString();


    //     $this->assertEquals("SELECT * FROM users", $q1);
    //     $this->assertEquals("SELECT user_name FROM users u", $q2);
    //     $this->assertEquals("SELECT user_name FROM users u WHERE (user_name = :x OR user_name = :y) AND (user_name = :Z)", $q3);
    //     $this->assertEquals("SELECT user_name FROM users u WHERE (user_name = :x OR user_name = :y) AND (user_name = :Z)", $q4);
        
    // }

    // public function testOrderBy()
    // {
    //     $q = $this->getBuilder()->from("users", "u")->orderBy("id", "DESC")->__toString();
    //     $this->assertEquals("SELECT * FROM users u ORDER BY id DESC", $q);
    // }

    // public function testMultipleOrderBy()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->orderBy("name", "DESC")
    //         ->__toString();
    //     $this->assertEquals("SELECT * FROM users ORDER BY name DESC", $q);
    
    // }

    // public function testLimit()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->limit(10)
    //        ->__toString();
    //     $this->assertEquals("SELECT * FROM users LIMIT 10", $q);
    // }

    //  public function testSelect()
    // {
    //     $q = $this->getBuilder()
    //         ->select("id", "name", "product")
    //         ->from("users");
    //     $this->assertEquals("SELECT id, name, product FROM users", $q->__toString());
    // }

    // public function testSelectMultiple()
    // {
    //     $q = $this->getBuilder()
    //         ->select("id", "name")
    //         ->from("users");
    //     $this->assertEquals("SELECT id, name FROM users", $q->__toString());
    // }

    // public function testSelectAsArray()
    // {
    //     $q = $this->getBuilder()
    //         ->select(["id", "name", "product"])
    //         ->from("users");
    //     $this->assertEquals("SELECT id, name, product FROM users", $q->toSQL());
    // }

    // public function testFetch()
    // {
    //     $user =(new QueryBuilder($this->getPDO()))
    //         ->from("utilisateur")
    //         ->where("idUser = :id")
    //         ->params(["id"  => 70])
    //         ->count();
            
    //     $this->assertEquals(0, $user);
    // }

    //Test join
    public function testJoin()
    {
        $user =(new QueryBuilder($this->getPDO()))
            ->from("user")
            ->join('contacts', 'user.id', '=', 'contact.user_id')
            ->join('product', 'contact.p_id', '=', 'product.id')
          
            ->__toString();
            
        $this->assertEquals("SELECT * FROM user  INNER JOIN contacts ON user.id = contact.user_id", $user);

        echo  $user;
    }

}