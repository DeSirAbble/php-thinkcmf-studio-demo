<?php

namespace app\studio\model;

use Exception;

class StudioApplicationExitModel extends BaseModel
{
    protected $table = "cmf_final_studio_application_exit";

    private int $_studioId;
    private int $_userId;
    private string $_reason;
    private string $_result;

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

    public function GetReason()
    {
        return $this->_reason;
    }

    public function SetReason(string $reason)
    {
        $this->_reason = $reason;
    }

    public function GetReasult()
    {
        return $this->_result;
    }

    public function SetReaslut(string $reasult)
    {
        $this->_result = $reasult;
    }

    /**
     * 构造器
     * 不带参数用于查询
     * 带参数用于创建对象
     *
     * @param array $data 从数据库查询出来的数据
     */
    // public function __construct(array $data = null)
    // {
    //     parent::__construct();

    //     if (null != $data) 
    //     {
    //         $this->SetId($data[$this->pk]);
    //         $this->SetCreateTime($data[$this->createTime]);
    //         $this->SetUpdateeTime($data[$this->updateTime]);
    //         $this->SetDeleteTime($data[$this->deleteTime]);
    //         $this->_studioId = $data["studio_id"];
    //         $this->_userId = $data["user_id"];
    //         $this->_reason = $data["reason"];
    //         $this->_result = $data["result"];
    //     }
    // }
    
}