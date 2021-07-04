<?php

namespace app\studio\service;

class UserExtensionTypeService extends BaseService
{
    public function GetAll()
    {
        return $this->_dbContext->UserExtensionTypes->select()->toArray();
    }
}