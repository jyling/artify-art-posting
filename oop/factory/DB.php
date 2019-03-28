<?php
class DB
{
    private static $conn = null;
    private $_pdo,
    $_query,
    $_err = false,
    $_output,
    $_count = 0;
    private function __construct()
    {
        try {
            $Sets       = new Settings();
            $host       = $Sets->get('sql>host');
            $usr        = $Sets->get('sql>dbusr');
            $dbname     = $Sets->get('sql>dbnm');
            $pass       = $Sets->get('sql>pwd');
            $this->_pdo = new PDO("mysql:host=$host;dbname=$dbname", $usr, $pass);
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }
    public static function Run()
    {

        if (!isset(self::$conn)) { // self is like super in java
            self::$conn = new DB();
        }
        return self::$conn;
    }
    private function conditionGenerator($terms)
    {
        if (isset($terms['condition'],
            $terms['condition']['target'],
            $terms['condition']['value'],
            $terms['condition']['operator'])) {
            $condition = "WHERE " .
                $terms['condition']['target'] .
                " " . $terms['condition']['operator'] .
                " " . $terms['condition']['value'];
            return $condition;
        }
        return '';
    }
    public function getAll($table)
    {
        $sql = "SELECT * FROM $table";
        if (!$this->query($sql)->error()) {
            return $this->_count;
        }
        return false;
    }
    public function getLimited($table, $page, $terms = array(), $order = array())
    {
        if (count($terms) >= 1) {
            $limit     = $terms['limit'];
            $offset    = (--$page * $limit);
            $condition = $this->conditionGenerator($terms);
            $orders    = '';
            foreach ($order as $key => $value) {
                $orders = "ORDER BY `$key` $value";
            }
            $sql = "SELECT * FROM $table $condition $orders LIMIT $limit OFFSET $offset";
            // die($sql);
            if (!$this->query($sql)->error()) {
                return $this;
            }
        }
        return false;
    }
    public function multiConditionGen($condition = array())
    {
        $counter = 0;
        $output  = '';
        foreach ($condition as $key => $x) {
            if ($counter <= 0) {
                $output .= "`$key` = ?";
            } else {
                $output .= " AND `$key` = ?";
            }
            $counter += 1;
        }
        return $output;

    }

    public function multiCon($condition, $table, $terms = array())
    {
        $sql = "$condition FROM `$table` WHERE " . $this->multiConditionGen($terms);
        // die($sql);
        if (!$this->query($sql, $terms)->error()) {
            return $this;
        }
    }
    public function doThis($condition, $table, $terms = array())
    {
        if (count($terms) === 3) {
            $WHITELIST = array('=', '<', '>', '>=', '<=','LIKE');
            $allowed   = $WHITELIST;

            $term     = $terms[0];
            $operator = $terms[1];
            $val      = $terms[2];

            if (in_array($operator, $allowed)) {
                $sql = "$condition FROM `$table` WHERE `$term` $operator ?";
                // print_r($sql.'<br>');
                if (!$this->query($sql, array($val))->error()) {
                    return $this;
                }
            }
        } else {
            $sql = "$condition FROM `$table`";
            if (!$this->query($sql)->error()) {
                return $this;
            }
        }

    }

    public function haystackFind($terms = array(),$table = '',$limit = '',$page = '') { 
        $additionalCondition = '';
        if ($limit != '') {
            $offset    = (--$page * $limit);
            $additionalCondition = "LIMIT $limit OFFSET $offset";
        }

        $output = '';
        $val = array();
        foreach ($terms as $key => $value) {
            $output .= " LOWER(`$key`) LIKE ? OR";
            $val[] = "%$value%";
        }
        $output = substr($output,0,strlen($output) - 2);
        $sql = "SELECT * FROM $table WHERE $output $additionalCondition";
        // echo $sql . ' ' . var_dump($val) . "<br>";
        if (!$this->query($sql, $val)->error()) {
            return $this;
        }
        
    }

    public function jointTable($inputs = array(), $conditions = array(), $additionalCondition = '')
    {
        $select   = array();
        $where    = array();
        $operator = '';
        foreach ($inputs as $table => $contents) {
            foreach ($contents as $key => $value) {
                $select[] = "$table.$value";
            }
        }
        foreach ($conditions as $operate => $values) {
            $operator = $operate;
            foreach ($values as $table => $value) {
                $where[] = "$table.$value";
            }
        }
        if (Count($where) > 0) {
            $where = ' WHERE ' . implode(" $operator ", $where);
        } else {
            $where = '';
        }
        $sql = "SELECT " . implode(", ", $select) . " FROM " . implode(", ", array_keys($inputs)) . $where . ' ' . $additionalCondition;
        // die($sql);
        if (!$this->query($sql)->error()) {
            return $this;
        }
    }

    public function getWithCol($table, $get = array(), $condition = array())
    {
        return $this->doThis('SELECT ' . implode(',', $get), $table, $condition);

    }
    public function get($table, $condition = array())
    {
        return $this->doThis('SELECT *', $table, $condition);
    }
    public function sortResult($identifier, $reverse = true)
    {
        if (!$reverse) {
            usort($this->_output, function ($a, $b) use ($identifier) {
                return strcmp($a->{$identifier}, $b->{$identifier});
            });
        } else {
            usort($this->_output, function ($a, $b) use ($identifier) {
                return strcmp($b->{$identifier}, $a->{$identifier});
            });
        }
    }
    public function getFirst($table, $condition = array())
    {
        return $this->doThis('SELECT *', $table, $condition)[0];
    }
    public function remove($table, $condition = array())
    {
        return $this->doThis('DELETE', $table, $condition);
    }
    public function getResult()
    {
        return $this->_output;
    }
    public function insert($table, $data = array())
    {
        $this->_err = false;
        if (count($data)) {
            $keys    = array_keys($data);
            $values  = null;
            $counter = 1;

            $values = implode(',', array_fill(0, count($data), "?"));

            $sql = "INSERT INTO `$table` (`" . implode('`,`', $keys) . "`) VALUES ($values)";
            // echo $sql;
            if (!$this->query($sql, $data)->error()) {
                return true;
            }
        }
        return false;
    }

    public function update($table, $id = array(), $data = array())
    {
        $this->_err = false;
        if (count($data)) {
            $values  = null;
            $counter = 1;
            //
            foreach ($data as $key => $val) {
                $values .= "`$key` = ?";
                if ($counter < count($data)) {
                    $values .= ', ';
                }
                $counter++;
            }
            $colname = '';
            foreach ($id as $key => $val) {
                $colname = $key;
                $id      = $val;
            }
            $sql = "UPDATE `$table` SET $values WHERE `$colname` = $id";
            if (!$this->query($sql, $data)->error()) {
                return true;
            }
        }
        return false;
    }
    public function query($sql, $param = array())
    {

        $this->_err = false;
        // echo $sql . "<br>";
        if ($this->_query = $this->_pdo->prepare($sql)) {
            if (count($param)) {
                $counter = 1;
                foreach ($param as $key) {
                    $this->_query->bindValue($counter, $key);
                    $counter++;
                }
            }
            if ($this->_query->execute()) {
                $this->_output = $this->_query->fetchALL(PDO::FETCH_OBJ);
                $this->_count  = $this->_query->rowCount();
            } else {
                $this->_err = true;
            }
        }
        return $this;
    }
    public function error()
    {
        return $this->_err;
    }
    public function getCount()
    {
        return $this->_count;
    }
}