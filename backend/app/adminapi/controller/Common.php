<?php
namespace app\adminapi\controller;

use app\common\basic\BasicAdminApiController;
use app\common\service\PublicService;

class Common extends BasicAdminApiController
{
    public function upload()
    {
        try{
            $res = (new publicService())->upload(input('type','image'));
            return success($res);
        }catch(\Exception $e){
            return_error($e->getMessage(),0);
        }
    }
}