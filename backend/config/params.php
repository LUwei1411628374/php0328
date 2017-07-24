<?php
return [
    'adminEmail' => 'admin@example.com',
    'qiniu'=> [
            'accessKey'=>'3cNnYXHp2g1IugRr1mKdFDlooU5BS_CMPmwisrqi',
            'secretKey'=>'v_JY8Yp3Dp3bVJurDs40dU67IJMNe-C78o4C-t3D',
            'domain'=>'http://otbtv88wb.bkt.clouddn.com/',
            'bucket'=>'yii2shop',
            'area'=>flyok666\qiniu\Qiniu::AREA_HUADONG
        ],
    /*'upload' => [
        'class' => 'kucha\ueditor\UEditorAction',
        'config' => [
            "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
            "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
            "imageRoot" => Yii::getAlias("@webroot"),
        ]
    ]*/


];
