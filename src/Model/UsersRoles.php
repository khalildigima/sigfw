<?php 

namespace Sigfw\Model;

use Sigfw\Service\Model;
use Sigfw\Service\Logger;
use Sigfw\Model\Users;
use Sigfw\Model\Roles;
use PDO;
use PDOException;

class UsersRoles extends Model
{
    public function __construct()
    {
        parent::__construct("users_roles");
    }

    public function get_user_role($user_id)
    {
        $row = $this->get_by("user_id", $user_id);
        if(!$row) return null;
        $model = new Roles();
        return $model->get_by("id", $row["role_id"]);   
    }
}