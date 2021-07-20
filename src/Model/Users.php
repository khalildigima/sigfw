<?php 

namespace Sigfw\Model;

use Sigfw\Service\Model;
use Sigfw\Service\Logger;
use Sigfw\Model\UsersRoles;
use Sigfw\Model\Roles;
use PDO;
use PDOException;

class Users extends Model
{
    public function __construct()
    {
        parent::__construct("users");
    }

    public function get_role($user_id)
    {
        $model = new UsersRoles();
        return $model->get_user_role($user_id);
    }
}