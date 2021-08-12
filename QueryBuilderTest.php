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
        
        // $pdo->query('CREATE TABLE products (
        // id INTEGER CONSTRAINT products_pk primary key autoincrement,
        // name TEXT,
        // address TEXT,
        // city TEXT)');
        //     for ($i = 1; $i <= 10; $i++) {
        //         $pdo->exec("INSERT INTO products (name, address, city) VALUES ('Product $i', 'Addresse $i', 'Ville $i');");
        //     }
        return $pdo;
    }

    public function testSimpleQuery()
    {
        $q1 = $this->getBuilder()->from("users")->__toString();
        $q2 = $this->getBuilder()->from("users", "u")->select('user_name')->__toString();
        $q3 = $this
        ->getBuilder()
        ->from("users", "u")
        ->select('user_name')
        ->where('user_name = :x OR user_name = :y', 'user_name = :Z')->__toString();
        $q4 = $this
        ->getBuilder()
        ->from("users", "u")
        ->select('user_name')
        ->where('user_name = :x OR user_name = :y')
        ->where('user_name = :Z')->__toString();


        $this->assertEquals("SELECT * FROM users", $q1);
        $this->assertEquals("SELECT user_name FROM users as u", $q2);
        $this->assertEquals("SELECT user_name FROM users as u WHERE (user_name = :x OR user_name = :y) AND (user_name = :Z)", $q3);
        $this->assertEquals("SELECT user_name FROM users as u WHERE (user_name = :x OR user_name = :y) AND (user_name = :Z)", $q4);
        
    }

    // public function testOrderBy()
    // {
    //     $q = $this->getBuilder()->from("users", "u")->orderBy("id", "DESC")->toSQL();
    //     $this->assertEquals("SELECT * FROM users u ORDER BY id DESC", $q);
    // }

    // public function testMultipleOrderBy()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->orderBy("id", "ezaearz")
    //         ->orderBy("name", "DESC")
    //         ->toSQL();
    //     $this->assertEquals("SELECT * FROM users ORDER BY id, name DESC", $q);
    // }

    // public function testLimit()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->limit(10)
    //         ->orderBy("id", "DESC")
    //         ->toSQL();
    //     $this->assertEquals("SELECT * FROM users ORDER BY id DESC LIMIT 10", $q);
    // }

    // public function testOffset()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->limit(10)
    //         ->offset(3)
    //         ->orderBy("id", "DESC")
    //         ->toSQL();
    //     $this->assertEquals("SELECT * FROM users ORDER BY id DESC LIMIT 10 OFFSET 3", $q);
    // }

    // public function testPage()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->limit(10)
    //         ->page(3)
    //         ->orderBy("id", "DESC")
    //         ->toSQL();
    //     $this->assertEquals("SELECT * FROM users ORDER BY id DESC LIMIT 10 OFFSET 20", $q);
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->limit(10)
    //         ->page(1)
    //         ->orderBy("id", "DESC")
    //         ->toSQL();
    //     $this->assertEquals("SELECT * FROM users ORDER BY id DESC LIMIT 10 OFFSET 0", $q);
    // }

    // public function testCondition()
    // {
    //     $q = $this->getBuilder()
    //         ->from("users")
    //         ->where("id > :id")
    //         ->setParam("id", 3)
    //         ->limit(10)
    //         ->orderBy("id", "DESC")
    //         ->toSQL();
    //     $this->assertEquals("SELECT * FROM users WHERE id > :id ORDER BY id DESC LIMIT 10", $q);
    // }

     public function testSelect()
    {
        $q = $this->getBuilder()
            ->select("id", "name", "product")
            ->from("users");
        $this->assertEquals("SELECT id, name, product FROM users", $q->__toString());
    }

    public function testSelectMultiple()
    {
        $q = $this->getBuilder()
            ->select("id", "name")
            ->from("users");
        $this->assertEquals("SELECT id, name FROM users", $q->__toString());
    }

    // public function testSelectAsArray()
    // {
    //     $q = $this->getBuilder()
    //         ->select(["id", "name", "product"])
    //         ->from("users");
    //     $this->assertEquals("SELECT id, name, product FROM users", $q->toSQL());
    // }

    public function testFetch()
    {
        $user =(new QueryBuilder($this->getPDO()))
            ->from("utilisateur")
            ->where("idUser = :id")
            ->params(["id"  => 70])
            ->count();
            
        $this->assertEquals(0, $user);
    }

    // public function testFetchWithInvalidRow()
    // {
    //     $city = $this->getBuilder()
    //         ->from("products")
    //         ->where("name = :name")
    //         ->setParam("name", "azezaeeazazzaez")
    //         ->fetch($this->getPDO(), "city");
    //     $this->assertNull($city);
    // }
    // public function testCount()
    // {
    //     $query = $this->getBuilder()
    //         ->from("products")
    //         ->where("name IN (:name1, :name2)")
    //         ->setParam("name1", "Product 1")
    //         ->setParam("name2", "Product 2");
    //     $this->assertEquals(2, $query->count($this->getPDO()));
    // }

    // /**
    //  * L'appel a count ne doit pas modifier les champs de la première requête
    //  */
    // public function testBugCount()
    // {
    //     $q = $this->getBuilder()->from("products");
    //     $this->assertEquals(10, $q->count($this->getPDO()));
    //     $this->assertEquals("SELECT * FROM products", $q->toSQL());
    // }

}