<?php


namespace Pf\Core;


class Dao
{


    private $connection;
    private $driver = '';
    private $table = '';
    private $field = '';
    private $where = '';
    private $order = '';
    private $offset = 0;
    private $limit = 0;
    private $parameter = [];
    private $statement = '';
    private $row_list = [[]];
    private $row = [];
    private $debug = FALSE;
    private $sql = '';
    private $index = '';


    public function __construct($table_name)
    {

        $table = C('table' . '.' . $table_name);
        $this->connection = C('database.' . $table['database']);
        $this->driver = $this->connection['driver'];
        $this->table = $table['table'];

    }
    public function driver($driver)
    {

        $this->driver = $driver;
        return $this;

    }
    public function connection($connection)
    {

        $this->connection = $connection;
        return $this;

    }
    public function field($field)
    {

        $this->field = $field;
        return $this;

    }
    public function table($table)
    {

        $this->table = $table;
        return $this;

    }
    public function index($index)
    {

        $this->index = $index;
        return $this;

    }
    public function debug($debug)
    {

        $this->debug = $debug;
        return $this;

    }
    public function addList($row_list)
    {

        $this->rowList($row_list);
        $this->buildInsertList();
        return $this->execute('addList');

    }
    public function add($row)
    {

        $this->row($row);
        $this->buildInsert();
        return $this->execute('add');

    }
    public function del($where = '', $parameter = [])
    {

        $this->where($where);
        $this->parameter($parameter);
        $this->buildDelete();
        return $this->execute('del');

    }
    public function set($row = [], $where = '', $parameter = [])
    {

        $this->row($row);
        $this->where($where);
        $this->parameter($parameter);
        $this->buildUpdate();
        return $this->execute('set');

    }
    public function getList($where = '', $parameter = [], $order = '', $offset = 0, $limit = 0)
    {

        $this->where($where);
        $this->parameter($parameter);
        $this->order($order);
        $this->offset($offset);
        $this->limit($limit);
        $this->buildSelectList();
        return $this->execute('getList');

    }
    public function get($where = '', $parameter = [])
    {

        $this->where($where);
        $this->parameter($parameter);
        $this->buildSelect();
        return $this->execute('get');

    }
    public function sql($sql, $parameter = [])
    {

        $this->statement($sql);
        $this->parameter($parameter);
        return $this->execute('sql');

    }
    public function count($where = '', $parameter = [])
    {

        $this->where($where);
        $this->parameter($parameter);
        $this->buildCount();
        return $this->execute('count');

    }
    public function exists($where = '', $parameter = [])
    {

        $this->where($where);
        $this->parameter($parameter);
        $this->buildExists();
        return $this->execute('exists');

    }
    public function beginTransaction()
    {

        $this->execute('beginTransaction');

    }
    public function commit()
    {

        $this->execute('commit');

    }
    public function rollBack()
    {

        $this->execute('rollBack');

    }


    private function statement($statement)
    {

        $this->statement = $statement;
        return $this;

    }
    private function parameter($parameter)
    {

        $this->parameter = $parameter;
        return $this;

    }
    private function where($where)
    {

        $this->where = $where;
        return $this;

    }
    private function rowList($row_list)
    {

        $this->row_list = $row_list;
        return $this;

    }
    private function row($row)
    {

        $this->row = $row;
        return $this;

    }
    private function order($order)
    {

        $this->order = $order;
        return $this;

    }
    private function offset($offset)
    {

        $this->offset = $offset;
        return $this;

    }
    private function limit($limit)
    {

        $this->limit = $limit;
        return $this;

    }
    private function getWhere()
    {

        return empty($this->where) ? '' : ' WHERE ' . $this->where;

    }
    private function getOrder()
    {

        return empty($this->order) ? '' : ' ORDER BY ' . $this->order;

    }
    private function getOffset()
    {

        return $this->offset;

    }
    private function getLimit()
    {

        return $this->limit;

    }
    private function getIndex()
    {

        return empty($this->index) ? '' : ' FORCE INDEX (' . $this->index . ')';

    }
    private function getDriver()
    {

        return $this->driver;

    }
    private function getConnection()
    {

        return $this->connection;

    }
    private function getField()
    {

        return empty($this->field) ? '*' : $this->field;

    }
    private function getTable()
    {

        return '`' . trim($this->table, "\0\t\n\x0B\r `") . '`';

    }
    private function getParameter()
    {

        return $this->parameter;

    }
    private function getRowList()
    {

        return $this->row_list;

    }
    private function getRow()
    {

        return $this->row;

    }
    private function getDebug()
    {

        return $this->debug;

    }
    private function getSql()
    {

        return $this->sql;

    }
    private function buildInsertList()
    {

        $statement = 'INSERT INTO %s (%s) VALUES %s;';
        $table = $this->getTable();
        $field_list_string = implode(', ', array_keys($this->row_list[0]));
        $row_list = [];
        foreach($this->row_list as $row)
        {
            $value_list = [];
            foreach($row as $value)
            {
                $value_list[] = '?';
            }
            $row_list[] = '(' . implode(', ', $value_list) . ')';
        }
        $value_list_string = implode(', ', $row_list);
        $this->statement = sprintf($statement, $table, $field_list_string, $value_list_string);
        foreach($this->row_list as $row)
        {
            foreach($row as $value)
            {
                $this->parameter[] = $value;
            }
        }

    }
    private function buildInsert()
    {

        $statement = 'INSERT INTO %s (%s) VALUES (%s);';
        $table = $this->getTable();
        $field_list_string = implode(', ', array_keys($this->row));
        $value_list = [];
        foreach($this->row as $value)
        {
            $value_list[] = '?';
        }
        $value_list_string = implode(', ', $value_list);
        $this->statement = sprintf($statement, $table, $field_list_string, $value_list_string);
        $this->parameter = array_values($this->row);

    }
    private function buildDelete()
    {

        $statement = 'DELETE FROM %s%s;';
        $table = $this->getTable();
        $where = $this->getWhere();
        $this->statement = sprintf($statement, $table, $where);

    }
    private function buildUpdate()
    {

        $statement = 'UPDATE %s SET %s%s;';
        $table = $this->getTable();
        $set_list = [];
        foreach($this->row as $field => $value)
        {
            $set_list[] = $field . ' = ?';
        }
        $set_list_string = implode(', ', $set_list);
        $where = $this->getWhere();
        $this->statement = sprintf($statement, $table, $set_list_string, $where);
        $this->parameter = array_merge(array_values($this->row), $this->parameter);

    }
    private function buildSelectList()
    {

        $statement = 'SELECT %s FROM %s';
        $field_list_string = $this->getField();
        $table = $this->getTable();
        $index = $this->getIndex();
        if(!empty($index))
        {
            $statement .= $index;
        }
        $where = $this->getWhere();
        if(!empty($where))
        {
            $statement .= $where;
        }
        $order = $this->getOrder();
        if(!empty($order))
        {
            $statement .= $order;
        }
        $offset = $this->getOffset();
        $limit = $this->getLimit();
        if($limit > 0)
        {
            $statement .= ' LIMIT ' . $offset . ',' . $limit;
        }
        $this->statement = sprintf($statement, $field_list_string, $table);

    }
    private function buildSelect()
    {

        $statement = 'SELECT %s FROM %s';
        $field_list_string = $this->getField();
        $table = $this->getTable();
        $index = $this->getIndex();
        if(!empty($index))
        {
            $statement .= $index;
        }
        $where = $this->getWhere();
        if(!empty($where))
        {
            $statement .= $where;
        }
        $order = $this->getOrder();
        if(!empty($order))
        {
            $statement .= $order;
        }
        $offset = $this->getOffset();
        $limit = $this->getLimit();
        if($limit > 0)
        {
            $statement .= ' LIMIT ' . $offset . ',' . $limit;
        }
        $this->statement = sprintf($statement, $field_list_string, $table);

    }
    private function buildCount()
    {

        $statement = 'SELECT COUNT(*) AS `count` FROM %s%s;';
        $table = $this->getTable();
        $where = $this->getWhere();
        $this->statement = sprintf($statement, $table, $where);

    }
    private function buildExists()
    {

        $statement = 'SELECT COUNT(*) AS `count` FROM %s%s;';
        $table = $this->getTable();
        $where = $this->getWhere();
        $this->statement = sprintf($statement, $table, $where);

    }
    private function init()
    {

        $this->field = '';
        $this->where = '';
        $this->order = '';
        $this->offset = 0;
        $this->limit = 0;
        $this->parameter = [];
        $this->statement = '';
        $this->row_list = [[]];
        $this->row = [];
        $this->debug = false;
        $this->sql = '';
        $this->index = '';
        $this->explain = false;

    }
    private function execute($cmd)
    {

        $pdo = Pdo::getInstance($this->getConnection());
        return $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);

        switch($cmd)
        {
            case 'beginTransaction':
                return $pdo->beginTransaction();
            case 'commit':
                return $pdo->commit();
            case 'rollBack':
                return $pdo->rollBack();
        }

        $statement = $pdo->prepare($this->statement);

        foreach($this->parameter as $index => $value)
        {
            $statement->bindValue($index + 1, $value);
        }

        $statement->execute();
        $this->init();

        switch($cmd)
        {
            case 'addList':
            case 'add':
                return $pdo->lastInsertId();
            case 'del':
            case 'set':
                return $statement->rowCount();
            case 'getList':
                return $statement->fetchAll(\PDO::FETCH_ASSOC);
            case 'get':
                $res = $statement->fetch(\PDO::FETCH_ASSOC);
                if($res === FALSE)
                {
                    return [];
                }
                return $res;
            case 'count':
                return $statement->fetch(\PDO::FETCH_ASSOC)['count'];
            case 'exists':
                return $statement->fetch(\PDO::FETCH_ASSOC)['count'] > 0;
            default:
                return NULL;
        }

    }


}
