<?php

namespace app\studio\model;

use Exception;

class StudioModel extends BaseModel
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
    protected $name = "studio";
    protected $table = "cmf_final_studio";

    private string $_title;
    private string $_description;
    private int $_maxNumber;
    private array $_memberList;
    private int $_userId;

    public function GetTitle()
    {
        return $this->_title;
    }

    public function SetTitle(string $title)
    {
        $this->_title = $title;
    }

    public function GetDescription()
    {
        return $this->_description;
    }

    public function SetDescription(string $description)
    {
        $this->_description = $description;
    }

    public function GetMaxNumber()
    {
        return $this->_maxNumber;
    }

    public function SetMaxNumber(int $maxNumber)
    {
        $this->_maxNumber = $maxNumber;
    }

    public function GetMemberList()
    {
        return $this->_memberList;
    }

    public function SetMemberList(array $memberList)
    {
        $this->_memberList = $memberList;
    }

    public function GetUserId()
    {
        return $this->_userId;
    }

    public function SetUserId(int $userId)
    {
        $this->_userId = $userId;
    }

    // public function __construct(int $id = 0)
    // {
    //     parent::__construct();

    //     if (0 != $id)
    //     {
    //         $data = $this->find($id)->toArray();

    //         if (0 == count($data)) 
    //         {
    //             throw new Exception("查询的 Studio 数据不存在");
    //         }

    //         $this->SetId($data[$this->pk]);
    //         $this->SetCreateTime($data[$this->createTime]);
    //         $this->SetUpdateeTime($data[$this->updateTime]);
    //         $this->SetDeleteTime($data[$this->deleteTime]);
    //         $this->_memberList = new StudioMemberModel($id);
    //     }
    // }
}
