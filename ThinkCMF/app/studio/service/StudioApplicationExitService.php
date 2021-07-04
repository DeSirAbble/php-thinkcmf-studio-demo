<?php

namespace app\studio\service;

class StudioApplicationExitService extends BaseService
{
    public function SetResult(int $studioId, int $userId, string $result)
    {
        $data = $this->_dbContext->StudioApplicationExits->select();

        if ("1" == $result)
        {
            $this->_dbContext->StudioApplicationExits->where("studio_id", $studioId)
                ->where("user_id", $userId)
                ->where("result",0)->update(["result" => 1]);

            $this->_dbContext->StudioMembers->where("studio_id", $studioId)->where("user_id", $userId)->delete();
        }
        else
        {
            $this->_dbContext->StudioApplicationExits->where("studio_id", $studioId)
                ->where("user_id", $userId)
                ->where("result",0)->update(["result" => 2]);
        }
    }

    public function AddAppliction(int $studioId, int $userId,string $reason)
    {
        $data = $this->_dbContext->StudioApplicationExits->where("studio_id", $studioId)->where("user_id", $userId)->select();
        if (0 == count($data))
        {
            return $this->_dbContext->StudioApplicationExits->save([
                "studio_id"=>$studioId,
                "user_id"=>$userId,
                "reason"=>$reason
            ]);
        }
        return false;
    }

    public function GetApplicationByStudioIdArray(array $studioIdArray)
    {
        $data = [];

        $studioList = $this->_dbContext->Studios->select($studioIdArray)->toArray();
        $studioApplicationExitList = $this->_dbContext->StudioApplicationExits->where("result", 0)->select()->toArray();
        $userList = $this->_dbContext->Users->select()->toArray();
        $userExtensionList = $this->_dbContext->UserExtensions->select()->toArray();
        $userExtensionTypeList = $this->_dbContext->UserExtensionTypes->select()->toArray();

        for ($i = 0; $i < count($studioApplicationExitList); $i++)
        {
            $data[$i]["studioId"] = $studioApplicationExitList[$i]["studio_id"];
            $data[$i]["title"] = "NULL";
            for ($j = 0; $j < count($studioList); $j++)
            {
                if ($data[$i]["studioId"] == $studioList[$j]["id"])
                {
                    $data[$i]["title"] = $studioList[$j]["title"];
                    break;
                }
            }

            $data[$i]["userId"] = $studioApplicationExitList[$i]["user_id"];
            $data[$i]["username"] = "NULL";
            for ($j = 0; $j < count($userList); $j++)
            {
                if ($data[$i]["userId"] == $userList[$j]["id"])
                {
                    $data[$i]["username"] = $userList[$j]["user_nickname"];
                    break;
                }
            }

            $data[$i]["userType"] = "NULL";
            for ($j = 0; $j < count($userExtensionList); $j++)
            {
                if ($data[$i]["userId"] == $userExtensionList[$j]["id"])
                {
                    for ($k = 0; $k < count($userExtensionTypeList); $k++)
                    {
                        if ($userExtensionList[$j]["user_extension_type_id"] == $userExtensionTypeList[$k]["id"])
                        {
                            $data[$i]["userType"]=$userExtensionTypeList[$k]["user_extension_type_name"];
                            break;
                        }
                    }
                    break;
                }
            }


            $data[$i]["reason"] = $studioApplicationExitList[$i]["reason"];
        }

        return $data;
    }
}