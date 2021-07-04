<?php

namespace app\studio\model;


class StudioMemberModel extends BaseModel
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
    protected $table = "cmf_final_studio_member";

    private int $_studioId;
    private int $_userId;

    public function GetStudioId()
    {
        return $this->_studioId;
    }

    public function SetStudioId(int $studioId)
    {
        $this->_studioId = $studioId;
    }

    public function GetUserId()
    {
        return $this->_userId;
    }

    public function SetUserId(int $userId)
    {
        $this->_userId = $userId;
    }

    // public function __construct(int $studioId = 0)
    // {
    //     parent::__construct();

    //     if (0 != $studioId)
    //     {
    //         $this->where("studio_id", $studioId)->select()->toArray();
    //     }
    // }

}