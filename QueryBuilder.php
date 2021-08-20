<?php 
    namespace App\JordiFramework\Datatabase;

     /*

    This file contain the query constructor made by Jordan ANAFACK

    */

    class QueryBuilder{

        //ok
        private $select = [];

        //ok
        private $from = [];

        //ok
        private $where = [];

        private $group = [];

        //ok
        private $orderBy = [];

        //ok
        private $limit;

        //ok
        private $join;

        //ok
        private $pdo;
        
        //ok
        private $params = [];

        public function __construct(?\PDO $pdo=null)
        {
            $this->pdo = $pdo;
        }

        public function from(string $tables, ?string $alias=null): self
        {
            if ($alias) {
                $this->from[$alias] = $tables;
            }else{
                $this->from[] = $tables;
            }
         
            return $this;
        }

        public function join(string $table, string $column1, string $sign, string $column2): self
        {
           $this->join =  $this->join." INNER JOIN $table ON $column1 $sign $column2";
         
            return $this;
        }

        public function select(string ...$fields): self
        {
            $this->select = array_merge($this->select, $fields);

            return $this;
        }

        public function count():int
        {
            $this->select("COUNT('*')");
            
            return $this->execute()->fetchColumn();

        }

        public function where(string ...$conditions): self
        {
            $this->where = array_merge($this->where, $conditions);

            return $this;
        }

        public function orderBy(string $column, ?string $value=null , ?string $table=null): self
        {
            if (!is_null($column) & is_null($table)) {
                $this->orderBy = array_merge($this->orderBy, [$column.' '. $value]);

            }else if(!is_null($column) & is_null($table)){
                $this->orderBy = array_merge($this->orderBy, [$column.' ASC']);
            }
            if (!is_null($column) & !is_null($table)) {
                $this->orderBy = array_merge($this->orderBy, [$table.$column.' '. $value]);

            }else if(!is_null($column) & !is_null($table)){
                $this->orderBy =array_merge($this->orderBy, [$table.$column.' ASC']);
            }
 
            return $this;
        }


        public function limit($limit): self
        {
            $this->limit = $limit;

            return $this;
        }

        public function params(array $params): self
        {
            $this->params = $params;

            return $this;
        }

       
        public function __toString()
        {
            //SELECT FROM
            $part = ['SELECT'];
            if (!empty($this->select)) {
                $part[] = join(', ', $this->select);
            }else{
                $part[] = "*";
            }
            $part[] = 'FROM';
            $part[] = $this->builderFrom();

            //JOIN
            if (!empty($this->join)) {
                $part[] = $this->join;
            }

            //WHERE
            if (!empty($this->where)) {
                $part[] = 'WHERE';
                $part[] = '('.join(') AND (', $this->where).')';
                
            }

              //ORDERBY
              if (!empty($this->orderBy)) {
                $part[] = 'ORDER BY';
                $part[] = join(', ', $this->orderBy);;       
            }

            //LIMIT
            if (!empty($this->limit)) {
                $part[] = 'LIMIT';
                $part[] = $this->limit;
                
            }

            $string = join(' ', $part);


            return $string;
        }
        
        private function builderFrom(): string
        {
            $from = [];
            foreach($this->from as $key => $value){
                if(is_string($key)){
                    $from[] = "$value $key";
                }else{
                    $from[] = "$value";
                }
            }

            return join(',', $from);
        }

        private function execute(){
            $query = $this->__toString();

            if($this->params){
                $statement = $this->pdo->prepare($query);
                $statement->execute($this->params);
                return $statement;
            }
            
            return $this->pdo->query($query);
        }

        public function first(){
           return $this->execute()->fetch();
        }

        public function get(){
            return $this->execute()->fetchAll();
         }

    }
?>