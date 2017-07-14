<?php
namespace app\data\controller\v3;

use think\Controller;
use think\Request;
use think\Db;

class Init
{

    protected $redis;

    public function __construct()
    {
        set_time_limit(1200);
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1');
    }

    public function __destruct()
    {
        $this->redis->close();
    }
    
    public function add($count){
        for ($i=0;$i<$count;$i++){
            try {
                $this->user();
            } catch (\Exception $e) {
            }
        }
    }

    private function user()
    {
        $key = 'user_key';
        
        $id = $this->redis->incr($key);
        $arr = [];
        $arr['uni_account'] = substr(md5(microtime(true)), rand(4, 7), rand(6, 10)) . '_' . $id;
        ;
        $arr['password'] = sha1($arr['uni_account']);
        $arr['solt'] = rand(10000, 99999);
        if (rand(1, 2) > 1) {
            $arr['regtime'] = time() + (rand(1, 86400 * 3000));
        } else {
            $arr['regtime'] = time() - (rand(1, 86400 * 3000));
        }
        $arr['type'] = rand(1, 9);
        $arr['phone'] = '13' . rand(10000, 99999) . rand(10000, 9999);
        $arr['mobile'] = rand(100000000, 1000000000);
        $arr['email'] = '13' . rand(10000, 99999) . rand(10000, 9999) . $id . '@qq.com';
        $arr['sex'] = rand(0, 2);
        $arr['subscribe'] = rand(0, 1);
        $arr['nickname'] = md5('nickname' . $id);
        $arr['city'] = '';
        $arr['country'] = '';
        $arr['province'] = '';
        $arr['language'] = '';
        $arr['headimgurl'] = '';
        $arr['subscribe_time'] = 0;
        $arr['unionid'] = '';
        $arr['remark'] = '';
        $arr['groupid'] = '';
        $arr['tabid_list'] = '';
        
        $user_id = Db::table('sys_user')->insertGetId($arr);
        $this->bind_car($user_id);
        
    }

    private function bind_car($user_id)
    {
        $way_user_bind_car = array(
                'user_id' => $user_id,
                'status' => '1',
                'verify_time' => rand(0,1),
                'create_time' => \request()->time() + (200 - rand(0,300))*rand(1,86400) ,
                'car_number' => '吉'.$user_id,
                'car_color' => rand(40,50),
                'username' => md5('username' . $user_id),
                'identity_card' => '',
                'phone' => '13' . rand(10000, 99999) . rand(10000, 9999),
                'car_type_id' => rand(1,10),
                'engine_six' => md5($user_id),
                'brand' => date('YmdHi')
        );
        Db::table('way_user_bind_car')->insertGetId($way_user_bind_car);
    }
}
