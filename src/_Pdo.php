<?php

namespace Junyang7\PhpCommon;

class _Pdo
{

    public function machine($machine)
    {

        $this->machine = $machine;
        return $this;

    }

    public function sql($sql)
    {

        $this->sql = $sql;
        return $this;

    }

    public function parameter($parameter)
    {

        $this->parameter = $parameter;
        return $this;

    }

    public function master($master)
    {

        $this->master = $master;
        return $this;

    }

    public function tablePrefix($table_prefix)
    {

        $this->table_prefix = $table_prefix;
        return $this;

    }

    public function tableSuffix($table_suffix)
    {

        $this->table_suffix = $table_suffix;
        return $this;

    }

    public function table($table)
    {

        $this->table = $table;
        return $this;

    }

    public function where($where)
    {

        $this->where = $where;
        return $this;

    }

    public function field($field)
    {

        $this->field = $field;
        return $this;

    }

    public function index($index)
    {

        $this->index = $index;
        return $this;

    }

    public function offset($offset)
    {

        $this->offset = (int)$offset;
        return $this;

    }

    public function limit($limit)
    {

        $this->limit = $limit;
        return $this;

    }

    public function order($order)
    {

        $this->order = $order;
        return $this;

    }

    public function group($group)
    {

        $this->group = $group;
        return $this;

    }

    public function rowList($row_list)
    {

        $this->row_list = $row_list;
        return $this;

    }

    public function fetchStyle($fetch_style)
    {

        $this->fetch_style = $fetch_style;
        return $this;

    }

    public function fetchMethod($fetch_method)
    {

        $this->fetch_method = $fetch_method;
        return $this;

    }

    public function tx($tx)
    {

        $this->tx = $tx;
        return $this;

    }

    public function beginTransaction()
    {

        $this->master(true);
        $this->transaction(true);
        $this->sql("beginTransaction");
        $this->do();
        $this->tx($this);

    }

    public function commit()
    {

        $this->getTx()->getPdo()->commit();
        return $this;

    }

    public function rollBack()
    {

        $this->getTx()->getPdo()->rollBack();
        return $this;

    }

    public function addList()
    {

        $this->master(true);
        $this->buildAddList();
        return $this->do(function () {
            return (int)$this->getInstance()->getPdo()->lastInsertId();
        });

    }

    public function del()
    {

        $this->master(true);
        $this->buildDel();
        return $this->do(function () {
            return (int)$this->getInstance()->prepared->rowCount();
        });

    }

    public function set()
    {

        $this->master(true);
        $this->buildSet();
        return $this->do(function () {
            return (int)$this->getInstance()->prepared->rowCount();
        });

    }

    public function getList()
    {

        $this->buildGetList();
        return $this->do(function () {
            return $this->getInstance()->prepared->fetchAll($this->getFetchStyle()) ?? [];
        });

    }

    public function get()
    {

        $this->limit(1);
        $this->buildGetList();
        return $this->do(function () {
            return $this->getInstance()->prepared->fetch($this->getFetchStyle()) ?? [];
        });

    }

    public function count()
    {

        $this->buildCount();
        return $this->do(function () {
            return (int)$this->getInstance()->prepared->fetch()["c"] ?? 0;
        });

    }

    public function query()
    {

        return $this->do(function () {
            return $this->getInstance()->prepared->{$this->getFetchMethod()}($this->getFetchStyle()) ?? [];
        });

    }

    public function execute()
    {

        $this->master(true);
        return $this->do(function () {
            if ("insert" == substr(strtolower($this->getSql()), 0, 6)) {
                return (int)$this->getInstance()->getPdo()->lastInsertId();
            }
            return (int)$this->getInstance()->prepared->rowCount();
        });


    }

    private $machine = [];
    private $sql = "";
    private $parameter = [];
    private $master = false;
    private $table_prefix = "";
    private $table_suffix = "";
    private $table = "";
    private $where = "";
    private $field = "";
    private $index = "";
    private $offset = 0;
    private $limit = 0;
    private $order = "";
    private $group = "";
    private $row_list = [];
    private $fetch_style = \PDO::FETCH_ASSOC;
    private $fetch_method = "fetchAll";
    private $transaction = false;
    private $prepared = null;
    private $uk = "";
    private static $pdo_map = [];
    private $tx = null;

    private function getMachine()
    {

        return $this->machine;

    }

    private function getSql()
    {

        return $this->sql;

    }

    private function getParameter()
    {

        return $this->parameter;

    }

    private function getMaster()
    {

        return $this->master;

    }

    private function getTablePrefix()
    {

        return $this->table_prefix;

    }

    private function getTableSuffix()
    {

        return $this->table_suffix;

    }

    private function getTable()
    {

        return $this->getTablePrefix() . $this->table . $this->getTableSuffix();

    }

    private function getWhere()
    {

        return empty($this->where) ? "" : " WHERE " . $this->where;

    }

    private function getField()
    {

        return empty($this->field) ? "*" : $this->field;

    }

    private function getIndex()
    {

        return empty($this->index) ? "" : " FORCE INDEX (" . $this->index . ")";

    }

    private function getOffset()
    {

        return $this->offset;

    }

    private function getLimit()
    {

        return $this->limit > 0 ? sprintf(" LIMIT %s,%s", $this->offset, $this->limit) : "";

    }

    private function getOrder()
    {

        return empty($this->order) ? "" : " ORDER BY " . $this->order;

    }

    private function getGroup()
    {

        return empty($this->group) ? "" : " GROUP BY " . $this->group;

    }

    private function getRowList()
    {

        return $this->row_list;

    }

    private function getFetchStyle()
    {

        return $this->fetch_style;

    }

    private function getFetchMethod()
    {

        return $this->fetch_method;

    }

    private function getTransaction()
    {

        return $this->transaction;

    }

    private function getTx()
    {

        return $this->tx;

    }

    private function getInstance()
    {

        return $this->getTx() ?? $this;

    }

    private function getPdo()
    {

        if (empty($this->uk)) {
            $this->uk = md5(serialize($this->machine));
        }
        if (!isset(self::$pdo_map[$this->uk])) {
            self::$pdo_map[$this->uk] = new \PDO(
                sprintf(
                    "%s:host=%s;port=%s;dbname=%s",
                    $this->machine["driver"],
                    $this->machine["host"],
                    $this->machine["port"],
                    $this->machine["database"]
                ),
                $this->machine["username"],
                $this->machine["password"],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $this->machine["charset"],
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_PERSISTENT => false,
                    \PDO::ATTR_STRINGIFY_FETCHES => true,
                ]
            );
        }
        return self::$pdo_map[$this->uk];

    }

    private function buildAddList()
    {

        $table = $this->getTable();
        $row_list = $this->getRowList();
        $field = implode(",", array_keys($row_list[0]));
        $template = implode(",", array_fill(0, count($row_list), "(" . implode(",", array_fill(0, count($row_list[0]), "?")) . ")"));
        $parameter = $this->getParameter();
        array_walk_recursive($row_list, function ($value) use (&$parameter) {
            $parameter[] = $value;
        });
        $this->sql(sprintf("INSERT INTO %s (%s) VALUES %s", $table, $field, $template));
        $this->parameter($parameter);
        return $this;

    }

    private function buildDel()
    {

        $table = $this->getTable();
        $where = $this->getWhere();
        $this->sql(sprintf("DELETE FROM %s%s", $table, $where));
        return $this;

    }

    private function buildSet()
    {

        $table = $this->getTable();
        $row_list = $this->getRowList();
        $template = implode(",", array_map(function ($v) {
            return $v . " = ?";
        }, array_keys($row_list[0])));
        $where = $this->getWhere();
        $parameter = array_values($row_list[0]);
        array_push($parameter, ...$this->parameter);
        $this->sql(sprintf("UPDATE %s SET %s%s", $table, $template, $where));
        $this->parameter($parameter);
        return $this;

    }

    private function buildGetList()
    {

        $field = $this->getField();
        $table = $this->getTable();
        $sql = sprintf("SELECT %s FROM %s", $field, $table);
        if (!empty($index = $this->getIndex())) {
            $sql .= $index;
        }
        if (!empty($where = $this->getWhere())) {
            $sql .= $where;
        }
        if (!empty($group = $this->getGroup())) {
            $sql .= $group;
        }
        if (!empty($order = $this->getOrder())) {
            $sql .= $order;
        }
        if (!empty($limit = $this->getLimit())) {
            $sql .= $limit;
        }
        $this->sql($sql);
        return $this;

    }

    private function buildCount()
    {

        $table = $this->getTable();
        $where = $this->getWhere();
        $this->sql(sprintf("SELECT COUNT(*) AS c FROM %s%s", $table, $where));
        return $this;

    }

    private function transaction($transaction)
    {

        $this->transaction = $transaction;

    }

    private function do($callback = null)
    {

        $i = 2;
        while ($i > 0) {
            $i--;
            try {
                if ("beginTransaction" == $this->getSql()) {
                    $this->getInstance()->getPdo()->beginTransaction();
                } else {
                    $this->prepared = $this->getInstance()->getPdo()->prepare($this->sql);
                    foreach ($this->parameter as $index => $value) {
                        $this->prepared->bindValue($index + 1, $value);
                    }
                    $this->prepared->execute();
                }
                return is_callable($callback) ? $callback() : null;
            } catch (\PDOException $exception) {
                if (!in_array($exception->errorInfo[1], [2006, 2013,]) || $this->getInstance()->transaction) {
                    throw $exception;
                }
                unset(self::$pdo_map[$this->uk]);
                self::$pdo_map[$this->uk] = null;
            }
        }
        throw $exception;

    }

}
