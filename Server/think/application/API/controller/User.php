<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/11/2
 * Time: 上午10:48
 */

namespace app\API\controller;

use app\API\model\CommonResponseModel;
use app\API\model\ProfileModel;
use app\API\model\UserModel;
use think\Response;
use think\response\Json;

class User
{
    /**
     * @return Response|Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function login()
    {
        $userName = $_REQUEST["user_name"];
        $password = $_REQUEST["password"];//客户端应该传密码的md5值

        if ($userName && $password) {
            $user = UserModel::get(['identifier' => $userName
                , 'credential' => password_hash($password, PASSWORD_DEFAULT)
                , 'identity_type' => 'username']);

            if ($user) {
                $loginDate = date('Y-m-d H:i:s');
                $loginTimeStamp = strtotime($loginDate);
                $user->token = md5($loginTimeStamp);//时间戳的MD5值
                $user->loginTime = $loginDate;

                $user->save();

                $jsonResponse = Json::create(new CommonResponseModel(0, $user->token));
                return $jsonResponse;
            }
        }

        return Response::create("", "", 403);//返回403,访问被拒绝
    }

    /**
     * @param $userName
     * @param $password 接收客户端MD5加密后传过来的密码,加密后存储到数据库
     * @param $nickName
     * @param $mail
     * @return Json
     */
    public function register()
    {
        $userName = $_REQUEST["user_name"];
        $password = $_REQUEST["password"];
        $nickName = $_REQUEST["nick_name"];
        $mail = $_REQUEST["mail"];

        if($userName && $password){
            $userModel = new UserModel;
            $userModel->identifier = $userName;
            $userModel->identityType = "username";
            $userModel->credential = password_hash($password, PASSWORD_DEFAULT);
            $userModel->registerTime = date('Y-m-d H:i:s');

            if ($userModel->save()) {

                $userProfileModel = new ProfileModel;
                $userProfileModel->userId = $userModel->id;
                $userProfileModel->nickName = $nickName;

                if ($userProfileModel->save()) {
                    return json(new CommonResponseModel(0));
                }
                else{
                    UserModel::destroy($userModel->id);
                }
            }
        }


        return Json::create(new CommonResponseModel(1, "", "创建用户失败"), "", 400);
    }

    public function books($userId){
        
    }
}