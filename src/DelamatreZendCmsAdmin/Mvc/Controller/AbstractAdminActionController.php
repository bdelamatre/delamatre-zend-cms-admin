<?php

namespace DelamatreZendCmsAdmin\Mvc\Controller;

use DelamatreZendCmsAdmin\Form\Element\UserType;

class AbstractAdminActionController extends \DelamatreZendCms\Mvc\Controller\AbstractActionController{

    public $requiredGroups = array(UserType::TYPE_ADMIN,
                                    UserType::TYPE_SUPERADMIN);

}