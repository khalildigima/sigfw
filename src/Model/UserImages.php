<?php 

namespace Sigfw\Model;

use Sigfw\Service\Model;
use Sigfw\Service\Logger;
use Sigfw\Model\Users;
use PDO;
use PDOException;

class UserImages extends Model
{
    public function __construct()
    {
        parent::__construct("user_images");
    }

    public function get_images($user_id)
    {
        return $this->get_all_by("user_id", $user_id);
    }
	
	public function get_user($user_id)
	{
		$model = new Users();
		return $model->get_by_id($user_id);
	}
}