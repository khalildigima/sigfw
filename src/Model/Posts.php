<?php 

namespace Sigfw\Model;

use Sigfw\Service\Model;

class Posts extends Model
{
    public function __construct()
    {
        parent::__construct("posts");
    }
}