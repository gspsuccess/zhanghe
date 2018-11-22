<?php
/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/9/29
 * Time: 16:13
 */

namespace app\api\controller\v1;

use app\api\model\Project as ProjectModel;

class Project extends Base
{
    public function getProjects()
    {
        $projects = ProjectModel::select();

        return $projects;
    }

    public function getProject()
    {
        $map = input('param.');
        unset($map['version']);

        $result = ProjectModel::getOne($map);

        return $result;
    }
}