<?php

namespace app\studio\model;

use Exception;

class UserExtensionModel extends BaseModel
{
    protected $table = "cmf_final_studio_user_extension";

    private UserExtensionTypeModel $_userExtensionType;

    public function GetUserExtensionType()
    {
        return $this->_userExtensionType;
    }

    public function SetUserExtensionType(UserExtensionTypeModel $userExtensionType)
    {
        $this->_userExtensionType = $userExtensionType;
    }

    // public function __construct()
    // {
    //     parent::__construct();
    //     if (0 != $userId)
    //     {
    //         $data = $this->where("user_id", $userId)->find()->toArray();

    //         if (0 == count($data))
    //         {
    //             throw new Exception("查询的 UserExtension 数据不存在");
    //         }


    //         $this->SetId($data[$this->pk]);
    //         $this->SetCreateTime($data[$this->createTime]);
    //         $this->SetUpdateeTime($data[$this->updateTime]);
    //         $this->SetDeleteTime($data[$this->deleteTime]);
    //         $this->_userExtensionType = new UserExtensionTypeModel($data["user_extension_type_id"]);
    //     }
    // }


}