<?php
declare (strict_types=1);

namespace app\common\basic;

use app\BaseController;

class BasicAdminApiController extends BaseController
{

    protected $middleware = [
        'jwt'=>['except' => ['login']],
        'log',
        'auth'=> ['except' => ['login']],
    ];
    public function __construct(){
        parent::__construct(app());
    }
}
