<?php

namespace app\studio\model;

use think\Model;

class BaseModel extends Model
{
    /**
     * 主键名
     *
     * @var string
     */
    protected $pk = "id";

    protected $createTime = "create_time";
    protected $updateTime = "update_time";
    protected $deleteTime = "delete_time";

    /**
     * 
     * 自动添加时间戳
     * 一旦配置开启的话，数据库会自动写入create_time和update_time两个字段的值
     * 
     * 手动选择数据库 
     * protected $createTime = "FieldName";
     * protected $updateTime = "FieldName";
     * 
     * 两个字段默认为整型（int），如果你的时间字段不是int类型的话，可以直接使用
     * protected $autoWriteTimestamp = 'datetime';
     * 
     */
    protected $autoWriteTimestamp = true;
   
    /**
     * 定义软删除字段的默认值
     *
     * @var integer
     */
    protected $defaultSoftDelete = 0;

    private int $_id;
    private int $_createTime;
    private int $_updateTime;
    private int $_deleteTime;

    public function GetId()
    {
        return $this->_id;
    }

    public function SetId(int $id)
    {
        $this->_id = $id;
    }

    public function GetCreateTime()
    {
        return $this->_createTime;
    }

    public function SetCreateTime(int $createTime)
    {
        $this->_createTime = $createTime;
    }

    public function GetUpdateTime()
    {
        return $this->_updateTime;
    }

    public function SetUpdateeTime(int $updateTime)
    {
        $this->_updateTime = $updateTime;
    }

    public function GetDeleteTime()
    {
        return $this->_deleteTime;
    }

    public function SetDeleteTime(int $deleteTime)
    {
        $this->_deleteTime = $deleteTime;
    }
}
