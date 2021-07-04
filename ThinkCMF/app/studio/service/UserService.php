<?php

namespace app\studio\service;

use api\user\model\UserModel;

class UserService extends BaseService
{
    public function GetAllUser()
    {
        return $this->_dbContext->Users->select();
    }

    public function GetUserById(int $id)
    {
        return $this->_dbContext->Users->find($id);
    }

    public function GetCompleteUserById(int $id)
    {
        $user = new UserModel($id);

        return $user;
    }

    public function GetUserByIdArray(array $idArray)
    {
        return $this->_dbContext->Users->select($idArray)->toArray();
    }

}