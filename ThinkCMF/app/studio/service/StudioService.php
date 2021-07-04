<?php

namespace app\studio\service;

use app\studio\model\StudioModel;
use Exception;

class StudioService extends BaseService
{
    public function GetAllStudio()
    {
        $studioList = $this->_dbContext->Studios->select()->toArray();
        $userList = $this->_dbContext->Users->select()->toArray();

        $data = [];
        for ($i = 0; $i < count($studioList); $i++)
        {
            $data[$i]["studioId"] = $studioList[$i]["id"];
            $data[$i]["title"] = $studioList[$i]["title"];
            $data[$i]["description"] = $studioList[$i]["description"];
            $data[$i]["maxNumber"] = $studioList[$i]["max_number"];
            $data[$i]["username"] = "NULL";
            for ($j = 0; $j < count($userList); $j++)
            {
                if ($studioList[$i]["user_id"] == $userList[$j]["id"])
                {
                    $data[$i]["username"] = $userList[$j]["user_nickname"];
                    break;
                }
            }
            $data[$i]["applicationNumber"] = $this->_dbContext->StudioApplicationJoins->where("studio_id",$data[$i]["studioId"])->count();
            $data[$i]["number"] = $this->_dbContext->StudioMembers->where("studio_id",$data[$i]["studioId"])->count();
        }

        return $data;
    }

    public function GetStudioById(int $id)
    {
        return $this->_dbContext->Studios->find($id);
    }

    public function GetCompleteStudioById(int $id)
    {
        $data = [];

        $studio = $this->_dbContext->Studios->find($id);

        $data["studioId"] = $studio["id"];
        $data["title"] = $studio["title"];
        $data["description"] = $studio["description"];
        $data["maxNumber"] = $studio["max_number"];
        $data["applicationNumber"] = $this->_dbContext->StudioApplicationJoins->where("studio_id",$data["studioId"])->count();
        $data["number"] = $this->_dbContext->StudioMembers->where("studio_id",$data["studioId"])->count();

        return $data;
    }

    public function GetStudioByIdArray(array $idArray)
    {
        return $this->_dbContext->Studios->select($idArray)->toArray();
    }

    public function GetStudioByHashKey(int $hashKey)
    {
        return $this->_dbContext->Studios->where("hash_key",$hashKey)->select()->toArray();
    }

    public function AddStudio($data = [])
    {
        try
        {
            $title = $data["title"];
            $hashKey = base_convert(substr(md5($title),0,8), 16, 10);

            $this->_dbContext->Studios->save([
                "title" => $title,
                "description" => $data["description"],
                "hash_key" => $hashKey,
                "user_id" => cmf_get_current_user_id()
            ]);
        }
        catch(Exception $e)
        {
            return false;
        }

        return true;
    }

    public function DeleteStudioById(int $id)
    {
        return StudioModel::destroy($id);
    }

    public function SetStudioMaxNumber(int $id, int $maxNumber)
    {
        try
        {
            $data = $this->_dbContext->Studios->where("id", $id)->update(["max_number" => $maxNumber]);
        }
        catch(Exception $e)
        {
            return false;
        }

        return true;
    }

    public function GetStudioByUserId(int $userId)
    {
        $studioList = $this->_dbContext->Studios->where("user_id", $userId)->select()->toArray();

        $data = [];
        for ($i = 0; $i < count($studioList); $i++)
        {
            $data[$i]["studioId"] = $studioList[$i]["id"];
            $data[$i]["title"] = $studioList[$i]["title"];
            $data[$i]["description"] = $studioList[$i]["description"];
            $data[$i]["maxNumber"] = $studioList[$i]["max_number"];
            
            $data[$i]["applicationNumber"] = $this->_dbContext->StudioApplicationJoins->where("studio_id",$data[$i]["studioId"])->count();
            $data[$i]["number"] = $this->_dbContext->StudioMembers->where("studio_id",$data[$i]["studioId"])->count();
        }

        return $data;
    }

    public function GetStudioByTitle(string $title)
    {
        $hashKey = base_convert(substr(md5($title),0,8), 16, 10);
        $studioList = $this->_dbContext->Studios->where("hash_key", $hashKey)->select()->toArray();
        $studioId = 0;

        for ($i = 0; $i < count($studioList); $i++)
        {
            if($title == $studioList[$i]["title"])
            {
                $studioId = $studioList[$i]["id"];
            }
        }

        return $this->_dbContext->Studios->find($studioId);
    }
}