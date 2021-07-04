<?php

namespace app\studio\data;

use api\user\model\UserModel;
use app\studio\model\StudioApplicationExitModel;
use app\studio\model\StudioApplicationJoinModel;
use app\studio\model\StudioMemberModel;
use app\studio\model\StudioModel;
use app\studio\model\UserExtensionModel;
use app\studio\model\UserExtensionTypeModel;

class DbContext
{
    public StudioModel $Studios;
    public StudioMemberModel $StudioMembers;
    public StudioApplicationJoinModel $StudioApplicationJoins;
    public StudioApplicationExitModel $StudioApplicationExits;
    public UserModel $Users;
    public UserExtensionModel $UserExtensions;
    public UserExtensionTypeModel $UserExtensionTypes;

    public function __construct()
    {
        $this->Studios = new StudioModel();
        $this->StudioMembers = new StudioMemberModel();
        $this->StudioApplicationJoins = new StudioApplicationJoinModel();
        $this->StudioApplicationExits = new StudioApplicationExitModel();
        $this->Users = new UserModel();
        $this->UserExtensions = new UserExtensionModel();
        $this->UserExtensionTypes = new UserExtensionTypeModel();
    }

}