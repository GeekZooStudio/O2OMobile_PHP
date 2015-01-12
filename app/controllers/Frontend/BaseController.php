<?php namespace Frontend;
use \DB;
use \Auth;
use \View;
use \User;
use \Input;
use \Config;
use \Request;
use \History;
use \Message;
use \Response;
use \Redirect;
use \Validator;

class BaseController extends \Controller {
    const JSONS_ERROR = 1;  //json错误
    // 错误码

    public function __construct() {
        if (Auth::check()) {
            //如果是后台管理员账号，则跳转到登录页面
            if (Auth::user()->role == User::ROLE_ADMIN) {
                header('Location:'.url('/auth/login'));
                exit;
            }
            
            View::share('sessUser', Auth::user());
            View::share('is_login', 1);
        }
        else {
            View::share('is_login', 0);
        }
    }

    /**
     * 返回json格式
     *
     * @param array  $data
     * @return string
     */
    public static function jsons(array $data, $errno=0, $errmsg='')
    {
        $output['errno'] = $errno;
        $output['errmsg'] = $errmsg;
        $output['result'] = $data;
        return Response::json($output);
    }

    /**
     * 返回json格式-错误
     *
     * @param array  $data
     * @return string
     */
    public static function jsons_error($errno=1, $errmsg='')
    {
        return self::jsons(array(), $errno, $errmsg);
    }


    /**
     * 验证输入
     *
     * @param array $rule 
     *
     * @return response
     */
    public function validateInput($rules)
    {
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return self::error(self::STATUS_BAD_REQUEST, strip_tags(join('、', $validator->messages()->all())));
        } else {
            return '';
        }
    }
    /**
     * 每次操作更新订单历史记录
     *
     * @return 
     */
    public function setHistory($order_status, $order_id, $user_id, $note = '')
    {

        $historyids = DB::table('history')->where('order_id', $order_id)->where('order_status', $order_status)->get();
        if(!empty($historyids)){
            return Redirect::back()->withMessage('不能重复操作');
        }
        $history = new History;
        $history->order_id           = $order_id;
        $history->user_id            = $user_id;
        $history->order_status       = $order_status;
        $history->note               = $note;
        // print_r($history);exit;
        $history->save();
        
    }


    /**
     * 每次评论更新用户的被评论数和被评论级别
     *
     * @return 
     */
    public function updateRank($user_id, $rank)
    {

        $comments = DB::table('comments')->where('o_user', $user_id)->sum('rank');

        $users = User::find($user_id);

        $new = round((($comments + $rank) / ($users->comment_count + 1) / 5), 2);

        DB::table('users')
            ->where('id', $user_id)
            ->update(array('comment_count' => $users->comment_count + 1, 'comment_goodrate' => $new));
        
    }


    /**
     * 每次做完操作后存到message 再读出来推送
     *
     * @return 
     */
    public function createMessage($messages, $type = 0)
    {
        $message = new Message;
        $message->user_id     = $messages['user_id'];
        $message->content     = $messages['content'];
        $message->type        = $messages['type'];
        $message->order_id    = $messages['order_id'];
        $message->is_readed   = $messages['is_readed'];

        $message->save();
        
        $client = DB::table('client')->where('user_id', $messages['user_id'])->first();
        // print_r($message->id);exit;        

    }

}