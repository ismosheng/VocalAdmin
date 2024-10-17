<?php
namespace app\common\service;

class PublicService 
{
    public function upload($type)
    {
        $files = request()->file();
        try {
            $rule = [
                'image'=>'fileSize:10240|fileExt:jpg|image:200,200,jpg,png',
                'video'=>'fileSize:10240|fileExt:mp4',
                'audio'=>'fileSize:10240|fileExt:mp3',
                'file'=>'fileSize:10240|fileExt:doc,docx,xls,xlsx,ppt,pptx,pdf,txt,zip,rar',
            ];
            validate([$type=>$rule[$type]])->check($files);
            $savename = [];
            foreach($files as $file) {
                $savename[] = fullUrl('/storage/'.\think\facade\Filesystem::disk('public')->putFile( 'upload/'.$type, $file));
            }
            return $savename;
        } catch (\think\exception\ValidateException $e) {
            return_error($e->getMessage());
        }
    }
}