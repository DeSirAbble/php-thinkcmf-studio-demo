<?php

namespace app\studio\model;

use Exception;

class UserModel extends BaseModel
{
    /**
     * 模型名称
     * 
     * $table和$name两个属性都可以指定模型的数据表名
     * $table指定的是真实的数据表名
     * $name指定的是不带表前缀的数据表名,只要设置一个就可以了,如果两个同时设置,以$table设置的为准
     * 
     * @var string
     */
    protected $name = "user";
    protected $table = "cmf_user";

    private string $_username;
    private UserExtensionModel $_userExtension;

    public function GetUsername()
    {
        return $this->_username;
    }

    public function SetUsername(string $username)
    {
        $this->_username = $username;
    }

    public function GetUserExtension()
    {
        return $this->_userExtension;
    }

    public function SetUserExtension(UserExtensionModel $userExtension)
    {
        $this->_userExtension = $userExtension;
    }

    // public function __construct(int $id = 0)
    // {
    //     parent::__construct();

    //     if (0 != $id)
    //     {
    //         $data = $this->find($id)->toArray();

    //         if (0 == count($data)) 
    //         {
    //             throw new Exception("查询的 User 数据不存在");
    //         }

    //         $this->SetId($data[$this->pk]);
    //         $this->SetCreateTime($data[$this->createTime]);
    //         $this->SetUpdateeTime($data[$this->updateTime]);
    //         $this->SetDeleteTime($data[$this->deleteTime]);
    //         $this->_username($data["user_nickname"]);
    //         $this->_userExtension = new UserExtensionModel($this->GetId());
    //     }
    // }
}
