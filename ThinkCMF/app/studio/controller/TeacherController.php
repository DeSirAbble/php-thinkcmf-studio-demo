<?php

namespace app\studio\controller;

use app\studio\service\StudioApplicationExitService;
use app\studio\service\StudioApplicationJoinService;
use app\studio\service\StudioMemberService;
use app\studio\service\StudioService;
use app\studio\service\UserExtensionService;
use app\studio\service\UserExtensionTypeService;
use app\studio\service\UserService;
use cmf\controller\HomeBaseController;
use Exception;
use think\Validate;

class TeacherController extends HomeBaseController
{
    private StudioService $_studioService;
    private StudioMemberService $_studioMemberService;
    private StudioApplicationJoinService $_studioApplicationJoinService;
    private StudioApplicationExitService $_studioApplicationExitService;
    private UserService $_userService;
    private UserExtensionService $_userExtensionService;
    private UserExtensionTypeService $_userExtensionTypeService;

    public function __construct()
    {
        parent::__construct();

        $this->_studioService = new StudioService();
        $this->_studioMemberService = new StudioMemberService();
        $this->_studioApplicationJoinService = new StudioApplicationJoinService();
        $this->_studioApplicationExitService = new StudioApplicationExitService();
        $this->_userService = new UserService();
        $this->_userExtensionService = new UserExtensionService();
        $this->_userExtensionTypeService = new UserExtensionTypeService();
    }

    // public function __construct(StudioService $studioService,StudioMemberService $studioMemberService,StudioApplicationJoinService $studioApplicationJoinService,StudioApplicationExitService $studioApplicationExitService,UserService $userService,UserExtensionService $userExtensionService,UserExtensionTypeService $userExtensionTypeService)
    // {
    //     parent::__construct();

    //     $this->_studioService =  $studioService;
    //     $this->_studioMemberService = $studioMemberService;
    //     $this->_studioApplicationJoinService = $studioApplicationJoinService;
    //     $this->_studioApplicationExitService = $studioApplicationExitService;
    //     $this->_userService = $userService;
    //     $this->_userExtensionService = $userExtensionService;
    //     $this->_userExtensionTypeService = $userExtensionTypeService;
    // }

    public function Index()
    {
        return $this->fetch();
    }    
    
    private function ValidateUser()
    {
        try
        {
            $userType = $this->_userExtensionService->GetUserType(cmf_get_current_user_id());
            if ("Teacher" != $userType)
            {
                throw new Exception("??????????????????");
            }
        }
        catch(Exception $e)
        {
            return false;
        }

        return true;
    }

    public function Create()
    {
        return $this->fetch();
    }

    public function DoCreate()
    {
        $request = $this->request;
        
        if (false == $request->isPost()) 
        {
            return json("??? POST ??????");
        }

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        //????????????
        $params = [
            "title" => $request->post("title"),
            "description" => $request->post("description"),
        ];

        //????????????
        $rules = [
            "title" => "require|min:6|max:32",
            "description" => "require",
        ];

        //????????????
        $messge = [
            "title.require" => "???????????????????????????",
            "title.min" => "???????????????????????????????????????",
            "title.max" => "?????????????????????????????????????????????",
            "description.require" => "???????????????????????????",
        ];

        $validate = new Validate($rules, $messge);

        if (false == $validate->check($params)) 
        {
            return json($validate->getError());
        }

        $this->_studioService->AddStudio($params);

        return json("ok");
    }

    public function DoCheckStudioTitle()
    {
        $request=$this->request;

        if (false == $request->isPost())
        {
            return json("??? POST ??????");
        }

        //???title?????????hash_key??????????????????
        $title = $request->post("title");
        $hashKey = base_convert(substr(md5($title),0,8), 16, 10);
        $studioList = $this->_studioService->GetStudioByHashKey($hashKey);

        //??????????????????
        for ($i = 0; $i < count($studioList); $i++)
        {
            if ($title == $studioList[$i]["title"])
            {
                return json("????????????");
            }
        }
        
        return json("ok");
    }

    public function GetMyStudio()
    {
        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        //?????????????????????????????????Id???????????????
        $userId = cmf_get_current_user_id();
        $studioList = $this->_studioService->GetStudioByUserId($userId);

        return json($studioList);
    }

    public function StudioDetail()
    {
        $request = $this->request;

        $studioId = $request->param("studioId");
        $studio = $this->_studioService->GetStudioById($studioId);

        $this->assign([
            "studioId" => $studioId,
            "title" => $studio["title"]
        ]);

        return $this->fetch();
    }

    public function GetStudioDetail()
    {
        $request = $this->request;

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }
        
        //?????????????????????Id??????????????????
        $studioId = $request->param("studioId");
        $memberList = $this->_studioMemberService->GetMemberByStudioId($studioId);

        //????????????????????????????????????Id?????????
        $userIdArray = [];
        for ($i = 0; $i < count($memberList); $i++)
        {
            $userIdArray[$i] = $memberList[$i]["user_id"];
        }

        //??????????????????????????????
        $userList = $this->_userService->GetUserByIdArray($userIdArray);
        $userExtensionList = $this->_userExtensionService->GetUserByIdArray($userIdArray);
        $userExtensionTypeList = $this->_userExtensionTypeService->GetAll();

        $data = [];
        for ($i = 0; $i < count($userList); $i++)
        {
            $data[$i]["userId"] = $userList[$i]["id"];
            $data[$i]["username"] = $userList[$i]["user_nickname"];
            for ($j = 0; $j < count($userExtensionTypeList); $j++)
            {
                if($userExtensionList[$i]["user_extension_type_id"] == $userExtensionTypeList[$j]["id"])
                {
                    $data[$i]["userType"] = $userExtensionTypeList[$j]["user_extension_type_name"];
                    break;
                }
            }
        }

        return json($data);
    }

    public function FireMember()
    {
        $request = $this->request;

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        $params = $request->post("params");

        $studioId = $params["studioId"];
        $userId = $params["userId"];
        if(false == $this->_studioMemberService->DeleteMember($studioId,$userId))
        {
            return json("fail");
        }

        return json("ok");
    }

    public function ApplicationJoin()
    {
        return $this->fetch();
    }

    public function GetApplicationJoin()
    {
        $request = $this->request;

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        //?????? User ???????????? Studio
        $userId = cmf_get_current_user_id();
        $studioList = $this->_studioService->GetStudioByUserId($userId);

        //??????????????? StudioId ??????
        $studioIdArray = [];
        for ($i = 0; $i < count($studioList); $i++)
        {
            $studioIdArray[$i] = $studioList[$i]["studioId"];
        }
        
        //?????? StudioId ???????????? ????????????
        $data = $this->_studioApplicationJoinService->GetApplicationByStudioIdArray($studioIdArray);

        return json($data);
    }

    public function DoApplicationJoin()
    {
        $request = $this->request;
        
        if (false == $request->isPost()) 
        {
            return json("??? POST ??????");
        }

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        
        $params = $request->post("params");

        $studioId = $params["studioId"];
        $userId = $params["userId"];
        $result = $params["result"];
        $this->_studioApplicationJoinService->SetResult($studioId, $userId, $result);

        return json("??????");
    }

    public function ApplicationExit()
    {
        return $this->fetch();
    }

    public function GetApplicationExit()
    {
        $request = $this->request;

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        //?????? User ???????????? Studio
        $userId = cmf_get_current_user_id();
        $studioList = $this->_studioService->GetStudioByUserId($userId);

        //??????????????? StudioId ??????
        $studioIdArray = [];
        for ($i = 0; $i < count($studioList); $i++)
        {
            $studioIdArray[$i] = $studioList[$i]["studioId"];
        }
        
        //?????? StudioId ???????????? ????????????
        $data = $this->_studioApplicationExitService->GetApplicationByStudioIdArray($studioIdArray);

        return json($data);
    }

    public function DoApplicationExit()
    {
        $request = $this->request;
        
        if (false == $request->isPost()) 
        {
            return json("??? POST ??????");
        }

        if(false == $this->ValidateUser())
        {
            return json("??????????????????");
        }

        $params = $request->post("params");

        $studioId = $params["studioId"];
        $userId = $params["userId"];
        $result = $params["result"];
        $this->_studioApplicationExitService->SetResult($studioId, $userId, $result);

        return json("ok");
    }

}