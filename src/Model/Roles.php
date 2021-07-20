<?php 

namespace Sigfw\Model;

use Sigfw\Service\Model;
use Sigfw\Service\Logger;
use Sigfw\Service\Utils;
use Sigfw\Model\UsersRoles;
use Sigfw\Model\Users;
use PDO;
use PDOException;

class Roles extends Model
{
    public function __construct()
    {
        parent::__construct("roles");
    }


    public function get_users($role_id)
    {
        $con = $this->verification_connection();
        if(!$con) return null;
        
        $role_id = Utils::clean_string($role_id);
        
        $sql = "SELECT `users`.* FROM `roles`
                JOIN `users_roles` ON `users_roles`.`role_id` = `roles`.`id`
                JOIN `users` ON `users`.`id` = `users_roles`.`user_id`
                WHERE `roles`.`id` = :role_id";

        try
        {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':role_id', $role_id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to get data: " . $e->getMessage());
            return null;
        }
    }
}