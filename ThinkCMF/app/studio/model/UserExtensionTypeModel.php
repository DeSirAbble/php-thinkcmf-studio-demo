<?php

namespace app\studio\model;

use Exception;

class UserExtensionTypeModel extends BaseModel
{
    protected $table = "cmf_final_studio_user_extension_type";

    private string $_userExtensionTypeName;

    public function GetUserExtensionTypeName()
    {
        return $this->_userExtensionTypeName;
    }

    public function SetUserExtensionTypeName(string $userExtensionTypeName)
    {
        $this->_userExtensionTypeName = $userExtensionTypeName;
    }


    // public function __construct(int $id = 0)
    // {
    //     parent::__construct();

    //     if (0 != $id)
    //     {
    //         $data = $this->find($id)->toArray();

    //         if (0 == count($data)) 
    //         {
    //             throw new Exception("查询的 UserExtensionType 数据不存在");
    //         }

    //         $this->SetId($data[$this->pk]);
    //         $this->SetCreateTime($data[$this->createTime]);
    //         $this->SetUpdateeTime($data[$this->updateTime]);
    //         $this->SetDeleteTime($data[$this->deleteTime]);
    //         $this->_userExtensionTypeName = $data["user_extension_type_name"];
    //     }
    // }

}