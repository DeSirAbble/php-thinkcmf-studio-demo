<?php

namespace app\studio\service;

use app\studio\model\UserExtensionModel;

class UserExtensionService extends BaseService
{
    public function GetUserType(int $userId)
    {
        $userType = "";

        $userExtension = $this->_dbContext->UserExtensions->where("user_id", $userId)->find();
        $userTypeId = $userExtension["user_extension_type_id"];

        $userExtensionType = $this->_dbContext->UserExtensionTypes->find($userTypeId);
        $userType = $userExtensionType["user_extension_type_name"];

        return $userType;
    }

    public function GetUserByIdArray(array $idArray)
    {
        return $this->_dbContext->UserExtensions->select($idArray)->toArray();
    }
}
