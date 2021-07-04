<?php

namespace app\studio\service;

use app\studio\model\StudioMemberModel;

class StudioMemberService extends BaseService
{
    public function GetMemberByStudioId(int $studioId)
    {
        return $this->_dbContext->StudioMembers->where("studio_id",$studioId)->select()->toArray();
    }

    public function AddMember(int $studioId, int $userId)
    {
        return $this->_dbContext->StudioMembers->save([
            "studio_id" => $studioId,
            "user_id" => $userId
        ]);
    }

    public function DeleteMember(int $studioId, int $userId)
    {
        $data = $this->_dbContext->StudioMembers->where("studio_id", $studioId)->where("user_id", $userId)->select()->toArray();
        return StudioMemberModel::destroy($data[0]["id"]);
    }

    public function GetByStudioIdAndUserId(int $studioId, int $userId)
    {
        $data = $this->_dbContext->StudioMembers->where("studio_id", $studioId)->where("user_id", $userId)->select();

        if (0 == count($data))
        {
            return null;
        }

        return $data;
    }

    public function GetIsFull(int $studioId)
    {
        $studio = $this->_dbContext->Studios->find($studioId);
        $maxNumber = $studio["max_number"];
        $currentNumber = $this->_dbContext->StudioMembers->where("studio_id", $studioId)->count();

        if ($maxNumber == $currentNumber)
        {
            return true;
        }
        return false;
    }

    public function GetStudioIdByUserId(int $userId)
    {
        $data = $this->_dbContext->StudioMembers->where("user_id",$userId)->select()->toArray();

        if(0 == $data[0]["studio_id"])
        {
            return 0;
        }

        return $data[0]["studio_id"];
    }

}