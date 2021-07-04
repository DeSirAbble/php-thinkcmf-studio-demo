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
use think\facade\Validate;

class AdminController extends HomeBaseController
{

    private StudioService $_studioService;
    private StudioMemberService $_studioMemberService;
    private StudioApplicationJoinService $_studioApplicationJoinService;
    private StudioApplicationExitService $_studioApplicationExitService;
    private UserService $_userService;
    private UserExtensionService $_userExtensionService;
    private UserExtensionTypeService $_userExtensionTypeService;
    // public function __construct()
    // {
    //     parent::__construct();

    //     $this->_studioService = new StudioService();
    //     $this->_studioMemberService = new StudioMemberService();
    //     $this->_studioApplicationJoinService = new StudioApplicationJoinService();
    //     $this->_studioApplicationExitService = new StudioApplicationExitService();
    //     $this->_userService = new UserService();
    //     $this->_userExtensionService = new UserExtensionService();
    //     $this->_userExtensionTypeService = new UserExtensionTypeService();
    // }

    public function __construct(StudioService $studioService,StudioMemberService $studioMemberService,StudioApplicationJoinService $studioApplicationJoinService,StudioApplicationExitService $studioApplicationExitService,UserService $userService,UserExtensionService $userExtensionService,UserExtensionTypeService $userExtensionTypeService)
    {
        parent::__construct();

        $this->_studioService =  $studioService;
        $this->_studioMemberService = $studioMemberService;
        $this->_studioApplicationJoinService = $studioApplicationJoinService;
        $this->_studioApplicationExitService = $studioApplicationExitService;
        $this->_userService = $userService;
        $this->_userExtensionService = $userExtensionService;
        $this->_userExtensionTypeService = $userExtensionTypeService;
    }

    private function ValidateUser()
    {
        try
        {
            $userType = $this->_userExtensionService->GetUserType(cmf_get_current_user_id());
            if ("Admin" != $userType)
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

    public function Index()
    {
        return $this->fetch();
    }

    public function GetAllStudio()
    {
        if(false == $this->ValidateUser())
        {
            return json("用户验证失败");
        }

        $data = $this->_studioService->GetAllStudio();

        return json($data);
    }

    public function SetMaxNumber()
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
        $maxNumber = $params["maxNumber"];

        if(false == $this->_studioService->SetStudioMaxNumber($studioId, $maxNumber))
        {
            return json("fail");
        }

        return json("完成");
    }



}