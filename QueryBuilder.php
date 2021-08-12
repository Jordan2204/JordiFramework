<?php 
    namespace App\JordiFramework\Datatabase;

     /*

    This file contain the query constructor made by Jordan ANAFACK

    */

    class QueryBuilder{

        private $select = [];

        private $from = [];

        private $where = [];

        private $group = [];

        private $order;

        private $limit;

        private $join = [];

        private $pdo;

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

        public function params(array $params): self
        {
            $this->params = $params;

            return $this;
        }
        public function select(string ...$fields): self
        {
            $this->select = array_merge($this->select, $fields);

            return $this;
        }

        public function where(string ...$conditions): self
        {
            $this->where = array_merge($this->where, $conditions);

            return $this;
        }

        public function count():int
        {
            $this->select("COUNT('id')");
            
            return $this->execute()->fetchColumn();

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

            //WHERE
            if (!empty($this->where)) {
                $part[] = 'WHERE';
                $part[] = '('.join(') AND (', $this->where).')';
                
            }

            return join(' ', $part);

        }
        
        private function builderFrom(): string
        {
            $from = [];
            foreach($this->from as $key => $value){
                if(is_string($key)){
                    $from[] = "$value as $key";
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

    }
?>