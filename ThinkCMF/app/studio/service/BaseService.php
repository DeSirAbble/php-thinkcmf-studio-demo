<?php
 
namespace app\studio\service;

use app\studio\data\DbContext;

class BaseService
{
    protected DbContext $_dbContext;

    public function __construct()
    {
        $this->_dbContext = new DbContext();
    }

}