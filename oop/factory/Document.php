<?php

class Document
{
    public function insert($table, $params = array())
    {
        DB::Run()->insert($table, $params);
    }

    public function get($table, $params = array())
    {
        return DB::run()->get($table, $params)->getResult;
    }
    public function has($table, $params)
    {
        $output        = new StdClass();
        $output->exist = (DB::run()->get($table, $params)->getCount() > 0) ? true : false;
        if (isset(DB::run()->get($table, $params)->getResult()[0])) {
            $output->content = DB::run()->get($table, $params)->getResult()[0];
        }
        return $output;
    }
}