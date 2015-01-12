<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

    const STATUS_OK       = 0;   //正常
    const STATUS_DISABLED = 1;   //禁用

	const NEWBEE            = 0; //普通用户
    const FREEMAN_INREVIEW  = 1; //自由人审核中
    const FREEMAN           = 2; //自由人
    const ROLE_ADMIN        = 99; //后台管理用户


    protected $softDelete = true;
    public  $timestamps   = true;
    protected $guarded = array();

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * 全部用户格式化API格式
     *
     * @return array
     */
    public function formatToApi() {
        return array(
            'id'                    => $this->id,
            'user_group'            => $this->role,
            'nickname'              => $this->nickname,  // 昵称
            'mobile_phone'          => $this->mobile,  // 手机号
            'gender'                => $this->gender,  // 性别
            "avatar"                => array(
                                        "width"     => 0,
                                        "height"    => 0,
                                        "thumb"     => $this->avatar(),                                                // 缩略图
                                        "large"     => $this->avatar()
                                    ),  // 头像
            "signature"             => $this->signature, // 个人签名
            "brief"                 => $this->brief,     // 个人简介
            "comment_goodrate"      => $this->comment_goodrate, //
            "comment_count"         => $this->comment_count, // 共xx个评价
            "current_service_price" => $this->price,
            "location"              => json_decode($this->location), // 位置
            "my_certification"      => $this->certification($this->id),
            "joined_at"             => apiTime($this->created_at),
        );
    }


    /**
     * 简单用户格式化API格式
     *
     * @return array
     */
    public function simpleFormatToApi() {
        return array(
            'id'                    => $this->id,
            'user_group'            => $this->role,
            'nickname'              => $this->nickname,  // 昵称
            'mobile_phone'          => $this->mobile,  // 手机号
            'gender'                => $this->gender,  // 性别
            "avatar"                => array(
                                        "width"     => 0,
                                        "height"    => 0,
                                        "thumb"     => $this->avatar(),                                                // 缩略图
                                        "large"     => $this->avatar()
                                    ),  // 头像
            "comment_goodrate"      => $this->comment_goodrate, //
            "comment_count"         => $this->comment_count, // 共xx个评价
            "current_service_price" => $this->price,
            "location"              => json_decode($this->location), // 位置
            "joined_at"             => apiTime($this->created_at),
        );
    }


    /**
     * 获取用户信息
     *
     * @return object
     */
    public static function userinfo($id, $key='nickname'){
        if(!$id && $key != 'avatar'){
            return '无此用户';
        }

        $user = User::find($id);
        if(empty($user) && $key != 'avatar'){
            return '无此用户';
        }
        if($key == 'avatar'){
            if(!$id){
                return 'http://' . $_SERVER['HTTP_HOST'].'/uploadFile/avatar/avatar.png';
            }
            if(empty($user)){
                return 'http://' . $_SERVER['HTTP_HOST'].'/uploadFile/avatar/empty.png';
            }
            return $user->avatar();
        }
        return $user->$key;
    }



    // /**
    //  *  twoLevel = false 获取邀请我的人的id 一级
    //  *  twoLevel = true  获取邀请邀请我的人的id 二级
    //  * @return object
    //  */
    // public static function getInvite($user_id, $twoLevel=false){

    //     $invite_uid = self::userinfo($user_id, 'invite_uid');

    //     if($twoLevel == false){
    //         return $invite_uid;
    //     }

    //     $invite_two_uid = self::userinfo($invite_uid, 'invite_uid');

    //     return $invite_two_uid;
    // }

    /**
     * 状态转换为html
     *
     * @return string
     */
    public function roleToHtml()
    {
        switch ($this->role) {
            case self::NEWBEE:
                $text = '普通用户';
                break;
            case self::FREEMAN_INREVIEW:
                $text = '自由人审核中';
                break;
            case self::FREEMAN:
                $text = '自由人';
                break;
        }

        return "<span>$text</span>";
    }

    /**
     * 状态转换为html
     *
     * @return string
     */
    public function statusToHtml()
    {
        switch ($this->status) {
            case self::STATUS_OK:
                $class = 'badge-success';
                $text = '正常';
                break;
            default:
                $class = 'badge-danger';
                $text = '已禁用';
                break;
        }

        return "<span class=\"badge $class\">$text</span>";
    }

    /**
     * 是否锁定
     *
     * @return boolean
     */
    public function locked()
    {
        return $this->status == self::STATUS_DISABLED;
    }


    /**
     * 用户相关的设备
     *
     * @return object
     */
    public function client()
    {
        return $this->hasOne('Client')->withTrashed();
    }

    /**
     * 获取用户头像
     *
     * @return string/array
     */
    public function avatar()
    {
        $doname = 'http://' . $_SERVER['HTTP_HOST'];
        if (!empty($this->avatar)) {
            $avatar = $doname.'/uploadFile/avatar'.$this->avatar;
        }
        else {
            $avatar = $doname.'/uploadFile/avatar/empty.png';
        }
        return $avatar;
    }

    /**
     * 我的认证
     *
     * @return object
     */
    public function certification($user_id)
    {
        // echo $user_id;exit;
        $sql = "SELECT `M`.id, `C`.id, `C`.name
                     FROM `o2omobile_my_certify` AS `M`
                     INNER JOIN `o2omobile_certify` AS `C`
                     WHERE `M`.certify_id = `C`.id AND `M`.user_id = $user_id";

        $my_certify = DB::table('my_certify')->where('user_id', $user_id)->get();
        $my_certifys = array();
        if (!$my_certify) {
            return $my_certifys;
        }
        foreach ($my_certify as $key => $value) {
            $certify = DB::table('certify')->where('id', $value->certify_id)->get();
            if (!$certify) {
                continue;
            }
            $my_certifys[$key]['id'] = $value->id;
            $my_certifys[$key]['certification'] = $certify[0];
        }
        return $my_certifys;
    }

    /**
     * 所属权限组(后台账号用)
     *
     * @return Object
     */
    public function permissionGroup()
    {
        return $this->belongsTo('PermissionGroup', 'group_id');
    }

	/**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

}
