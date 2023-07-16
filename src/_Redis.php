<?php

namespace Junyang7\PhpCommon;

class _Redis
{

    public function machine($machine)
    {

        $this->machine = $machine;
        return $this;

    }

    public function cmd($cmd)
    {

        $this->cmd = $cmd;
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

    public function query()
    {

        return $this->do();

    }

    public function execute()
    {

        $this->master(true);
        return $this->do();

    }

    private $machine = [];
    private $cmd = "";
    private $parameter = [];
    private $master = false;
    private $uk = "";
    private static $redis_map = [];

    private function getMachine()
    {

        return $this->machine;

    }

    private function getCmd()
    {

        return $this->cmd;

    }

    private function getParameter()
    {

        return $this->parameter;

    }

    private function getMaster()
    {

        return $this->master;

    }

    private function getInstance()
    {

        return $this;

    }

    private function getRedis()
    {

        if (empty($this->uk)) {
            $this->uk = md5(serialize($this->machine));
        }
        if (!isset(self::$redis_map[$this->uk])) {
            self::$redis_map[$this->uk] = new \Redis();
            if (false === self::$redis_map[$this->uk]->connect($this->machine["host"], $this->machine["port"], $this->machine["timeout"], $this->machine["persistent_id"], $this->machine["retry_interval"], $this->machine["read_timeout"])) {
                throw new \Exception("Redis连接失败");
            }
            if (!empty($this->machine["password"])) {
                if (false === self::$redis_map[$this->uk]->auth($this->machine["password"])) {
                    throw new \Exception("Redis认证失败");
                }
            }
        }
        return self::$redis_map[$this->uk];

    }

    private function do()
    {

        $i = 2;
        while ($i > 0) {
            $i--;
            try {
                return $this->getInstance()->getRedis()->rawCommand($this->cmd, ...$this->parameter);
            } catch (\Exception $exception) {
                try {
                    $this->getInstance()->getRedis()->ping();
                } catch (\Exception $exception) {
                    unset(self::$redis_map[$this->uk]);
                    self::$redis_map[$this->uk] = null;
                }
            }
        }
        throw $exception;

    }

}
