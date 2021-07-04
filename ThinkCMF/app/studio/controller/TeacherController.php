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
                throw new Exception("用户验证失败");
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
            return json("非 POST 请求");
        }

        if(false == $this->ValidateUser())
        {
            return json("用户验证失败");
        }

        //参数列表
        $params = [
            "title" => $request->post("title"),
            "description" => $request->post("description"),
        ];

        //验证规则
        $rules = [
            "title" => "require|min:6|max:32",
            "description" => "require",
        ];

        //提示信息
        $messge = [
            "title.require" => "工作室名称不能为空",
            "title.min" => "工作室名称不能小于六个字符",
            "title.max" => "工作室名称不能大于三十二个字符",
            "description.require" => "工作室简介不能为空",
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
            return json("非 POST 请求");
        }

        //将title转化为hash_key再查询数据库
        $title = $request->post("title");
        $hashKey = base_convert(substr(md5($title),0,8), 16, 10);
        $studioList = $this->_studioService->GetStudioByHashKey($hashKey);

        //处理哈希碰撞
        for ($i = 0; $i < count($studioList); $i++)
        {
            if ($title == $studioList[$i]["title"])
            {
                return json("名称重复");
            }
        }
        
        return json("ok");
    }

    public function GetMyStudio()
    {
        if(false == $this->ValidateUser())
        {
            return json("用户验证失败");
        }

        //通过当前登录的用户主键Id获取工作室
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
            return json("用户验证失败");
        }
        
        //通过工作室主键Id获取成员数据
        $studioId = $request->param("studioId");
        $memberList = $this->_studioMemberService->GetMemberByStudioId($studioId);

        //将成员数组拆成只包含用户Id的数组
        $userIdArray = [];
        for ($i = 0; $i < count($memberList); $i++)
        {
            $userIdArray[$i] = $memberList[$i]["user_id"];
        }

        //查询用户及其扩展数据
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
            return json("用户验证失败");
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
            return json("用户验证失败");
        }

        //通过 User 主键查询 Studio
        $userId = cmf_get_current_user_id();
        $studioList = $this->_studioService->GetStudioByUserId($userId);

        //查询所需的 StudioId 数组
        $studioIdArray = [];
        for ($i = 0; $i < count($studioList); $i++)
        {
            $studioIdArray[$i] = $studioList[$i]["studioId"];
        }
        
        //通过 StudioId 数组查询 加入申请
        $data = $this->_studioApplicationJoinService->GetApplicationByStudioIdArray($studioIdArray);

        return json($data);
    }

    public function DoApplicationJoin()
    {
        $request = $this->request;
        
        if (false == $request->isPost()) 
        {
            return json("非 POST 请求");
        }

        if(false == $this->ValidateUser())
        {
            return json("用户验证失败");
        }

        
        $params = $request->post("params");

        $studioId = $params["studioId"];
        $userId = $params["userId"];
        $result = $params["result"];
        $this->_studioApplicationJoinService->SetResult($studioId, $userId, $result);

        return json("完成");
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
            return json("用户验证失败");
        }

        //通过 User 主键查询 Studio
        $userId = cmf_get_current_user_id();
        $studioList = $this->_studioService->GetStudioByUserId($userId);

        //查询所需的 StudioId 数组
        $studioIdArray = [];
        for ($i = 0; $i < count($studioList); $i++)
        {
            $studioIdArray[$i] = $studioList[$i]["studioId"];
        }
        
        //通过 StudioId 数组查询 加入申请
        $data = $this->_studioApplicationExitService->GetApplicationByStudioIdArray($studioIdArray);

        return json($data);
    }

    public function DoApplicationExit()
    {
        $request = $this->request;
        
        if (false == $request->isPost()) 
        {
            return json("非 POST 请求");
        }

        if(false == $this->ValidateUser())
        {
            return json("用户验证失败");
        }

        $params = $request->post("params");

        $studioId = $params["studioId"];
        $userId = $params["userId"];
        $result = $params["result"];
        $this->_studioApplicationExitService->SetResult($studioId, $userId, $result);

        return json("ok");
    }

}