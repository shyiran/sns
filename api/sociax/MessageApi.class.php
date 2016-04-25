<?php
/**
 * 
 * @author jason
 *
 */
class MessageApi extends Api {

    /**
     * 获取socket地址
     *
     * @return void
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function getSocketAddress()
    {
        return model('Xdata')->get('admin_Application:socket');
    }

    /**
     * 获取用户信息
     *
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function getUserInfo()
    {
        $uid = intval($_REQUEST['uid']);

        if (!$uid) {
            $this->error(array(
                'status' => '-1',
                'msg'    => '没有传入UID'
            ));
        } elseif (!($user = model('User')->getUserInfo($uid))) {
            $this->error(array(
                'status' => '-2',
                'msg'    => '用户不存在'
            ));
        }
        return array(
            'status'=> '1',
            'uname' => $user['uname'],
            'avatar'=> $user['avatar_small'],
            'intro' => $user['intro']
        );
    }

    /**
     * 获取用户头像
     *
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function getUserFace()
    {
        list($uid, $method, $size) = array($_REQUEST['uid'], $_REQUEST['method'], $_REQUEST['size']);
        list($uid, $method, $size) = array(intval($uid), t($method), t($size));
        $uid    or $uid    = $this->mid;
        $method or $method = 'stream'; // stream, url, redirect
        $size   or $size   = 'big';    // original, big, middle, small

        if (!in_array($method, array('stream', 'url', 'redirect'))) {
            $this->error(array(
                'status' => 0,
                'msg'    => '获取模式错误'
            ));
        } elseif (!in_array($size, array('original', 'big', 'middle', 'small'))) {
            $this->array(array(
                'status' => 0,
                'msg'    => '头像尺寸错误'
            ));
        } elseif (!$uid) {
            $this->error(array(
                'status' => 0,
                'msg'    => '不存在用户'
            ));
        } elseif (!($user = model('User')->getUserInfo($uid))) {
            $this->error(array(
                'status' => 0,
                'msg'    => '该用户不存在'
            ));
        }

        $size = 'avatar_' . $size;
        $face = $user[$size];

        if ($method == 'stream') {
            ob_end_clean();
            header('Content-type: image/jpg');
            echo file_get_contents($face);
            exit;
        } elseif ($method == 'redirect') {
            ob_end_clean();
            header('Location:' . $face);
            exit;
        }

        return array(
            'status' => 1,
            'url'    => $face
        );
    }

    /**
     * 获取附件信息
     *
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function getAttach()
    {
        list($hash, $method) = array($_REQUEST['hash'], $_REQUEST['method']);
        list($hash, $method) = array($hash, t($method));

        $method or  $method = 'stream'; // stream, url, redirect

        // # 解密成ID
        $hash = @desdecrypt($hash, C('SECURE_CODE'));

        if (!$hash) {
            $this->error(array(
                'status' => '-1',
                'msg'    => '没有传递需要获取的附件ID'
            ));
        } elseif (!in_array($method, array('stream', 'url', 'redirect'))) {
           $this->error(array(
                'status' => '-2',
                'msg'    => '没有正确的传递获取模式'
            ));
        } elseif (!($attach = model('Attach')->getAttachById(intval($hash)))) {
            $this->error(array(
                'status' => '-3',
                'msg'    => '没有这个附件'
            ));
        } elseif ($method == 'stream') {
            ob_end_clean();
            header('Content-type:' . $attach['type']);
            echo file_get_contents(getAttachUrl($attach['save_path'] . $attach['save_name']));
            exit;
        } elseif ($method == 'redirect') {
            ob_end_clean();
            header('Location:' . getAttachUrl($attach['save_path'] . $attach['save_name']));
            exit;
        }

        return array(
            'status' => '1',
            'url'    => getAttachUrl($attach['save_path'] . $attach['save_name']),
            'msg'    => '获取成功'
        );
    }

    /**
     * 上传图片
     *
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function uploadImage()
    {
        return $this->uploadFile('image', 'message_image', 'gif', 'jpg', 'png', 'jpeg', 'bmp');
    }

    /**
     * 上传语音
     *
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function uploadVoice()
    {
        return $this->uploadFile('file', 'message_voice', 'mp3', 'ogg', 'wav');
    }

    /**
     * 上传位置图片
     *
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function uploadLocationImage()
    {
        return $this->uploadFile('image', 'message_location', 'gif', 'jpg', 'png', 'jpeg', 'bmp');
    }

    /**
     * 上传文件
     *
     * @param string $uploadType 上传文件的类型
     * @param string $attachType 保存文件的类型
     * @param string [$param, $param ...] 限制文件上传的类型
     * @return array
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    protected function uploadFile($uploadType, $attachType)
    {
        $ext = func_get_args();
        array_shift($ext);
        array_shift($ext);

        $option = array(
            'attach_type' => $attachType
        );
        count($ext) and $option['allow_exts'] = implode(',', $ext);

        $info = model('Attach')->upload(array(
            'upload_type' => $uploadType
        ), $option);

        // # 判断是否有上传
        if (count($info['info']) <= 0) {
            $this->error(array(
                'status' => '-1',
                'msg'    => '没有上传的文件'
            ));

        // # 判断是否上传成功
        } elseif ($info['status'] == false) {
            $this->error(array(
                'status' => '0',
                'msg'    => $info['info']
            ));
        }

        $data = array();
        foreach ($info['info'] as $value) {
            $value = desencrypt($value['attach_id'], C('SECURE_CODE'));
            array_push($data, $value);
        }

        return array(
            'status' => '1',
            'list'   => $data
        );
    }

    public function unreadcount() {
        $count = model ( 'UserData' )->setUid ( $GLOBALS ['ts'] ['mid'] )->getUserData ();
        return array (
            'comment' => ( string ) intval ( $count ['unread_comment'] ),
            'atme' => ( string ) intval ( $count ['unread_atme'] ),
            'digg' => ( string ) intval ( $count ['unread_digg'] ),
            'follower' => ( string ) intval ( $count ['new_folower_count'] ) ,
            'weiba' => ( string ) intval ( $count ['new_folower_count'] )
        );
    }

    // 上传图片 --using
    public function upload_image() {
        $d ['attach_type'] = 'message_image';
        $d ['upload_type'] = 'image';
        //return $_FILES;
        $info = model ( 'Attach' )->upload ( $d, $d );
        //return $info;
        if (! getSubByKey ( $info ['info'], 'attach_id' )) {
            return false;
        }
        foreach ( $info ['info'] as $k => $v ) {
            $data [$k] ['attach_id'] = $v ['attach_id'];
            $data [$k] ['image_url'] = getImageUrl ( $v ['save_path'] . $v ['save_name'] );
            $data [$k] ['image_width'] = $v ['width'];
            $data [$k] ['image_height'] = $v ['height'];
        }
        // $data['forApi'] = 1;
        return $data;
    }

    // 上传声音 --using
    public function upload_voice() {
        $d ['attach_type'] = 'message_voice';
        $d ['upload_type'] = 'file';
        $info = model ( 'Attach' )->upload ( $d, $d );
        // return $info;
        if (! getSubByKey ( $info ['info'], 'attach_id' )) {
            return false;
        }
        $cloud = model ( 'CloudAttach' );
        $data ['attach_id'] = $info ['info'] [0] ['attach_id'];
        if ($cloud->isOpen ()) {
            $data ['voice_url'] = $cloud->getFileUrl ( $info ['info'] [0] ['save_path'] . $info ['info'] [0] ['save_name'] );
        } else {
            $data ['voice_url'] = UPLOAD_URL . '/' . $info ['info'] [0] ['save_path'] . $info ['info'] [0] ['save_name'];
        }
        // $data['forApi'] = 1;
        return $data;
    }
    
    /**
     * 获取未读聊天数据
     */
    public function get_unread(){
        $new_list = D('message_member')->where('new>0 and member_uid='.$this->mid)->field('list_id,new')->findAll();
        $message_new = 0;
        $list = array();
        foreach ($new_list as $k => $v) {
            $list_detail = D('message_list')->where(array('list_id'=>$v['list_id']))->field('type,last_message')->find();
            if(in_array($list_detail['type'], array(1,2))){
                $d['list_id'] = $v['list_id'];
                $d['new'] = $v['new'];
                //最后一条消息
                $last_message = unserialize($list_detail['last_message']);
                if(intval($last_message['msg_id'])){    //去message_content表中取
                    $content = $this->get_msg(intval($last_message['msg_id']));
                    $d['last_message'] = $content[0]['content'];
                }else{  //直接显示
                    $d['last_message'] = getUserName($last_message['from_uid']).':'.$last_message['content'];
                }
                $list[] = $d;
                unset($d);
            }
        }
        return $list;
    }

    /**
     * 获取与某人聊天list_id（没聊过则创建）
     * @param integer $uid 对方uid
     * @return array status + list_id
     */
    public function get_listid_by_uid(){
        $uid = intval($this->data['uid']);
        if($uid > $this->mid){
            $data['min_max'] = $this->mid.'_'.$uid;
        }else if($uid < $this->mid){
            $data['min_max'] = $uid.'_'.$this->mid;
        }else{
            return array('status'=>0,'msg'=>'参数错误');
        }
        $data['type'] = 1;
        $list_id = intval(D('message_list')->where($data)->getField('list_id'));
        if(!$list_id){  //还没有，创建
            $data['from_uid'] = $this->mid;
            $data['member_num'] = 2;
            $data['mtime'] = time();
            if($list_id = D('message_list')->add($data)){
                $res = M()->execute("INSERT into ".C('DB_PREFIX')."message_member (list_id,member_uid,new,message_num,ctime,list_ctime) values (".$list_id.",".$data['from_uid'].",0,0,".$data['mtime'].",".$data['mtime']."),(".$list_id.",".$uid.",0,0,".$data['mtime'].",".$data['mtime'].")");
                if(!$res){
                    D('message_list')->where(array('list_id'=>$list_id))->delete();
                    return array('status'=>0,'msg'=>'对话创建失败');
                }
            }else{
                return array('status'=>0,'msg'=>'对话创建失败');
            }
        }
        return array('status'=>1,'list_id'=>$list_id);
    }

    /**
     * 创建群聊
     * @param string $members 邀请的成员uids(多个uid之间用逗号隔开)
     * @return array
     */
    public function create_group_chat() {
        $from_uid = intval ( $this->mid );
        $title = t($this->data ['title']);
        $members = t($this->data ['members']);
        $members .= ',' . $from_uid;
        $members = explode ( ',', $members );
        $members = array_map ( 'intval', $members );
        $members = array_unique ( array_filter ( $members ) );
        $member_num = count ( $members );
        if (! $from_uid || ! $member_num) {
            return array('status'=>0,'msg'=>'参数错误');
        }
        $data ['type'] = 2;
        $data ['from_uid'] = $from_uid;
        $data ['title'] = $title;
        $data ['member_num'] = $member_num;
        asort($members);
        $data ['min_max'] = implode('_', $members);
        $data ['mtime'] = time();
        $list_id = D ( 'message_list' )->add ( $data );
        if ($list_id) {
            $ctime = time ();
            $sql = "INSERT into " . C ( 'DB_PREFIX' ) . "message_member (list_id,member_uid,new,message_num,ctime,list_ctime) values ";
            foreach ( $members as $v ) {
                $uname = getUserName($v);
                if($v==$this->mid){
                    $new = 0;
                }else{
                    $new = 1;
                    $unames[] = $uname;
                    $uid[] = $v;
                }
                $total_uname[] = $uname;
                $sql .= "($list_id,$v,$new,1,$ctime,$ctime),";
            }
            $sql = rtrim ( $sql, ',' );
            $res = D ()->execute ( $sql );
            if($res){
                //发出第一条消息
                $content['list_id'] = $list_id;
                $content['from_uid'] = $this->mid;
                $content['type'] = 'notify';
                $content['content'] = 'group_create-'.$this->mid.'-'.implode(',', $uid);
                $content['mtime'] = $data ['mtime'];
                $msg_id = D('message_content')->add($content);
                if(!$title){
                    $title = implode(',', $total_uname);
                }
                $last_message['from_uid'] = $this->mid;
                $last_message['msg_id'] = $msg_id;
                //更新对话最后一条消息
                D('message_list')->where(array('list_id'=>$list_id))->setField('last_message',serialize($last_message));
                $offline_content = getUserName($this->mid).'邀请 '.implode(',', $unames).' 加入了群聊';
                return array ('status'=>1,'list_id'=>$list_id,'msg_id'=>$msg_id,'title'=>$title,'content'=>$offline_content);
            }else{
                D('message_list')->where(array('list_id'=>$list_id))->delete();
                return array ('status'=>0,'msg'=>'创建群聊失败');
            }
        } else {
            return array ('status'=>0,'msg'=>'创建群聊失败');
        }
    }

    private function get_group_title($list_id){
        $title = D('message_list')->where(array('list_id'=>$list_id))->getField('title');
        if(!$title){
            $members = D('message_member')->where(array('list_id'=>$list_id))->field('member_uid')->findAll();
            foreach ($members as $k => $v) {
                $unames[] = getUserName($v['member_uid']);
            }
            $title = implode(',', $unames);
        }
        return $title;
    }

    /**
     * 邀请他人加入
     * @param integer $list_id 
     * @param string $members 邀请的成员uids(多个uid之间用逗号隔开)
     * @return array
     */
    public function group_add_user(){
        $list_id = intval($this->data['list_id']);
        $uids = array_unique(array_filter(explode(',', $this->data['members'])));
        $joined_uids = getSubByKey(D('message_member')->where(array('list_id'=>$list_id))->field('member_uid')->findAll(),'member_uid');
        $ctime = time();
        $sql = "INSERT into " . C ( 'DB_PREFIX' ) . "message_member (list_id,member_uid,ctime,list_ctime) values ";
        foreach ($uids as $k => $v) {
            if(in_array($v, $joined_uids)){
                continue;
            }else{
                $uid[] = $v;
                $sql .= "($list_id,$v,$ctime,$ctime),";
                $unames[] = getUserName($v);
            }
        }
        if(count($uid)>0){
            $sql = rtrim ( $sql, ',' );
            $res = D ()->execute ( $sql );
            if($res){
                //修改人数和min_max
                $total_uids = array_unique(array_merge($uid,$joined_uids));
                asort($total_uids);
                $data ['min_max'] = implode('_', $total_uids);
                $data ['member_num'] = count($total_uids);
                D('message_list')->where(array('list_id'=>$list_id))->save($data);
                //发出一条新消息
                $content['list_id'] = $list_id;
                $content['from_uid'] = $this->mid;
                $content['type'] = 'notify';
                $content['content'] = 'group_add_user-'.$this->mid.'-'.implode(',', $uid);
                $content['mtime'] = $ctime;
                $msg_id = D('message_content')->add($content);
                if($msg_id){
                    //新消息和所有消息更新
                    $save['new'] = array('exp','new+1');
                    $save['message_num'] = array('exp','message_num+1');
                    D('message_member')->where(array('list_id'=>$list_id))->save($save);
                    D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->setDec('new');
                    //更新时间和对话最后一条消息
                    $list['mtime'] = $ctime;
                    $last_message['from_uid'] = $this->mid;
                    $last_message['msg_id'] = $msg_id;
                    $list['last_message'] = serialize($last_message);
                    D('message_list')->where(array('list_id'=>$list_id))->save($list);
                }
                $my_uname = getUserName($this->mid);
                $offline_content = $my_uname.'邀请您加入了群聊'.'|'.$my_uname.'邀请 '.implode(',', $unames).' 加入了群聊';
                $to_uid = implode(',', array_diff($total_uids, array($this->mid))).'|'.implode(',', $uid);
                return array ('status'=>1,'list_id'=>$list_id,'msg_id'=>$msg_id,'title'=>$this->get_group_title($list_id),'content'=>$offline_content,'to_uid'=>$to_uid);
            }else{
                return array ('status'=>0,'msg'=>'邀请失败-2');
            }
        }else{  
            return array ('status'=>0,'msg'=>'邀请失败-1');
        }
    }

    /**
     * 移出他人
     * @param integer $list_id 
     * @param string $members 移出的成员uids(多个uid之间用逗号隔开)
     * @return array
     */
    public function group_remove_user(){
        $list_id = intval($this->data['list_id']);
        $from_uid = D('message_list')->where(array('list_id'=>$list_id))->getField('from_uid');
        if($from_uid != $this->mid){
            return array('status'=>0,'msg'=>'您没有权限');
        }
        $uids = array_unique(array_filter(explode(',', $this->data['members'])));
        if(count($uids)<=0){
            return array('status'=>0,'msg'=>'请选择成员');
        }
        if(in_array($this->mid, $uids)){
            return array('status'=>0,'msg'=>'不能移出自己');
        }
        foreach ($uids as $k => $v) {
            $unames[] = getUserName($v);
        }
        $res = D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>array('in',$uids)))->delete();
        if($res){
            //修改人数和min_max
            $total_uids = array_unique(getSubByKey(D('message_member')->where(array('list_id'=>$list_id))->field('member_uid')->findAll(),'member_uid'));
            asort($total_uids);
            $data ['min_max'] = implode('_', $total_uids);
            $data ['member_num'] = count($total_uids);
            D('message_list')->where(array('list_id'=>$list_id))->save($data);
            //发出一条新消息
            $content['list_id'] = $list_id;
            $content['from_uid'] = $this->mid;
            $content['type'] = 'notify';
            $content['content'] = 'group_remove_user-'.$this->mid.'-'.implode(',', $uids);
            $content['mtime'] = time();
            $msg_id = D('message_content')->add($content);
            if($msg_id){
                //新消息和所有消息更新
                $save['new'] = array('exp','new+1');
                $save['message_num'] = array('exp','message_num+1');
                D('message_member')->where(array('list_id'=>$list_id))->save($save);
                D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->setDec('new');
                //更新时间和对话最后一条消息
                $list['mtime'] = time();
                $last_message['from_uid'] = $this->mid;
                $last_message['msg_id'] = $msg_id;
                $list['last_message'] = serialize($last_message);
                D('message_list')->where(array('list_id'=>$list_id))->save($list);
            }
            $my_uname = getUserName($this->mid);
            $offline_content = $my_uname.'把您移出了群聊'.'|'.$my_uname.'把 '.implode(',', $unames).' 移出了群聊';
            $to_uid = implode(',', array_diff(array_merge($total_uids,$uids),array($this->mid)));
            return array ('status'=>1,'list_id'=>$list_id,'msg_id'=>$msg_id,'title'=>$this->get_group_title($list_id),'content'=>$offline_content,'to_uid'=>$to_uid);
        }else{
            return array('status'=>0,'msg'=>'移出失败');
        }
    }

    /**
     * 主动退出
     * @param integer $list_id 
     * @return array
     */
    public function group_out(){
        $list_id = intval($this->data['list_id']);
        if(!D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->count()){
            return array('status'=>0,'msg'=>'您不是该群聊成员');
        }
        $res = D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->delete();
        if($res){
            //修改人数和min_max
            $total_uids = array_unique(getSubByKey(D('message_member')->where(array('list_id'=>$list_id))->field('member_uid')->findAll(),'member_uid'));
            asort($total_uids);
            $data ['min_max'] = implode('_', $total_uids);
            $data ['member_num'] = count($total_uids);
            D('message_list')->where(array('list_id'=>$list_id))->save($data);
            //发出一条新消息
            $content['list_id'] = $list_id;
            $content['from_uid'] = $this->mid;
            $content['type'] = 'notify';
            $content['content'] = 'group_out-'.$this->mid;
            // $content['content'] = getUserName($this->mid).' 退出了群聊';
            $content['mtime'] = time();
            $msg_id = D('message_content')->add($content);
            if($msg_id){
                //新消息和所有消息更新
                $save['new'] = array('exp','new+1');
                $save['message_num'] = array('exp','message_num+1');
                D('message_member')->where(array('list_id'=>$list_id))->save($save);
                //更新对话最后一条消息
                $list['mtime'] = $content['mtime'];
                $last_message['from_uid'] = $this->mid;
                $last_message['msg_id'] = $msg_id;
                $list['last_message'] = serialize($last_message);
                D('message_list')->where(array('list_id'=>$list_id))->save($list);
            }
            $my_uname = getUserName($this->mid);
            $offline_content = $my_uname.'退出了群聊';
            $to_uid = implode(',', array_merge(array_diff($total_uids, array($this->mid)),$uids)).'|'.implode(',', $uids);
            return array ('status'=>1,'list_id'=>$list_id,'msg_id'=>$msg_id,'title'=>$this->get_group_title($list_id),'content'=>$offline_content,'to_uid'=>implode(',', $total_uids));
        }else{
            return array('status'=>0,'msg'=>'退出失败');
        }
    }

    /**
     * 更改群聊标题
     * @param integer $list_id 
     * @param string $title 新标题
     * @return array
     */
    public function group_change_title(){
        $list_id = intval($this->data['list_id']);
        $title = t($this->data['title']);
        $detail = D('message_list')->where(array('list_id'=>$list_id))->field('title,from_uid')->find();
        if($title != $detail['title']){
            $res = D('message_list')->where(array('list_id'=>$list_id))->setField('title',$title);
            if($res !== false){
                //发出一条新消息
                $content['list_id'] = $list_id;
                $content['from_uid'] = $this->mid;
                $content['type'] = 'notify';
                $content['content'] = 'group_change_title-'.$this->mid.'-'.$title;
                $content['mtime'] = time();
                $msg_id = D('message_content')->add($content);
                if($msg_id){
                    //新消息和所有消息更新
                    $save['new'] = array('exp','new+1');
                    $save['message_num'] = array('exp','message_num+1');
                    D('message_member')->where(array('list_id'=>$list_id))->save($save);
                    D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->setDec('new');
                    //更新对话最后一条消息
                    $list['mtime'] = $content['mtime'];
                    $last_message['from_uid'] = $this->mid;
                    $last_message['msg_id'] = $msg_id;
                    $list['last_message'] = serialize($last_message);
                    D('message_list')->where(array('list_id'=>$list_id))->save($list);
                }
                $total_uids = array_diff(array_unique(getSubByKey(D('message_member')->where(array('list_id'=>$list_id))->field('member_uid')->findAll(),'member_uid')), array($this->mid));
                $offline_content = getUserName($this->mid).'修改了群聊名称“'.$title.'”';
                return array ('status'=>1,'list_id'=>$list_id,'msg_id'=>$msg_id,'title'=>$this->get_group_title($list_id),'content'=>$offline_content,'to_uid'=>implode(',', $total_uids));
            }else{
                return array('status'=>0,'msg'=>'修改失败');
            }
        }else{
            return array('status'=>0,'msg'=>'名称相同');
        }

    }

    /**
     * 获取聊天
     * @param integer $list_id 
     * @return array()
     */
    public function get_chat(){
        $list_id = intval($this->data['list_id']);
        // if(!D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->count()){
        //     return array('status'=>0,'msg'=>'您不是成员');
        // }
        $chat_detail = D('message_list')->where(array('list_id'=>$list_id))->field('list_id,type,from_uid,title,min_max,mtime,last_message')->find();
        $chat['list_id'] = $chat_detail['list_id'];
        $chat['min_max'] = $chat_detail['min_max'];
        $chat['time'] = $chat_detail['mtime'];
        if($chat_detail['type'] == 1){
            $chat['room_type'] = 'chat';
        }else if($chat_detail['type'] == 2){
            $chat['room_type'] = 'group';
        }else{
            return array('status'=>0,'msg'=>'类型错误');
        }
        $chat['from_uid'] = $chat_detail['from_uid'];
        if($chat_detail['title']){
            $chat['from_uname'] = $chat_detail['title'];
        }else{
            $need_deal_title = 1;
        }
        $uids = array_unique(array_filter(explode('_', $chat_detail['min_max'])));
        $chat['is_member'] = (string) in_array($this->mid, $uids)?1:0;
        $chat['from_avatar'] = '';
        foreach ($uids as $k => $v) {
            if($k<=8){  //聊天头像图标
                $avatars = model ( 'Avatar' )->init($v)->getUserAvatar();
                if($chat_detail['type']==2){
                    $chat['from_avatar'] .= $avatars['avatar_big'].',';
                }else{
                    if($v != $this->mid){
                        $chat['from_avatar'] = $avatars['avatar_big'].',';
                    }
                }
            }
            if($k<=5 && $need_deal_title){  //聊天名称
                if($chat_detail['type'] == 2){
                    $uname[] = getUserName($v);
                }else{
                    if($v != $this->mid){
                        $uname[] = getUserName($v);
                    }
                }
            }
        }
        $chat['from_avatar'] = substr($chat['from_avatar'],0,strlen($chat['from_avatar'])-1);
        if($need_deal_title){
            $chat['from_uname'] = implode(',', array_filter($uname)); 
        }
        //最后一条消息
        $last_message = unserialize($chat_detail['last_message']);
        if(intval($last_message['msg_id'])){    //去message_content表中取
            $content = $this->get_msg(intval($last_message['msg_id']));
            $chat['last_message'] = $content[0]['content'];
        }else{  //直接显示
            $chat['last_message'] = getUserName($chat_detail['from_uid']).':'.$last_message['content'];
        }
        $chat['from_uname'] = getUserName($chat_detail['from_uid']);
        // $chat['from_uid'] = 
        return $chat;
    }

    /**
     * 获取聊天对话
     * @param integer $list_id 
     * @param integer $msg_id 消息id
     * @param integer $from_msg_id 客户端本地存储的该问题最大的msg_id
     */
    public function get_msg($msg_id){
        if($msg_id){
            $chat_list  = D('message_content')->where(array('message_id'=>$msg_id))->field('message_id,list_id,from_uid,type as msgtype,content,mtime as time,attach_ids')->findAll();
        }else{
            $list_id = intval($this->data['list_id']);
            $type = D('message_list')->where(array('list_id'=>$list_id))->getField('type');
            if(in_array($type, array(1,2))){
                $room_type = $type==1?'chat':'group';
            }else{
                return array();
            }
            $map['list_id'] = $list_id;
            // $msg_id = intval($this->data['msg_id']);
            $from_msg_id = intval($this->data['from_msg_id']);
            $member_detail = D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->field('ctime')->find();
            if($member_detail){ //是成员
                $new = $member_detail['new'];

                // $join_time = intval($member_detail['ctime']);
                // $is_member = $join_time?1:0;
                // $map['mtime'] = array('egt',$join_time);
                // if($msg_id && $from_msg_id){
                //     $map['message_id'] = array(array('gt',$from_msg_id),array('elt',$msg_id),'and');
                // }else if($msg_id && !$from_msg_id){
                //     $map['message_id'] = array('elt',$msg_id);
                // }else if(!$msg_id && $from_msg_id){
                //     $map['message_id'] = array('gt',$from_msg_id);
                // }
                if($from_msg_id){
                    $map['message_id'] = array('lt',$from_msg_id);
                }
                $map['is_del'] = 0;
                $chat_list  = D('message_content')->where($map)->order('message_id DESC')->field('message_id,list_id,from_uid,type as msgtype,content,mtime as time,attach_ids')->limit(10)->findAll();
                $chat_list = array_reverse($chat_list);
            }else{  //已被移除
                $map['message_id'] = $msg_id;
                $chat_list  = D('message_content')->where($map)->order('message_id DESC')->field('message_id,list_id,from_uid,type as msgtype,content,mtime as time,attach_ids')->limit(10)->findAll();
                if($chat_list[0]['msgtype'] != 'notify'){   //只能读到类型为notify的消息
                    return array();
                }
            }
        }
        
        // dump(D()->getLastSql());
        // dump($chat_list);
        if(count($chat_list)>0){
            foreach($chat_list as $r_d_k => $r_d_v) {
                $chat_list[$r_d_k]['is_member'] = (string)$is_member;
                $chat_list[$r_d_k]['room_type'] = $room_type;
                $chat_list[$r_d_k]['from_uname'] = getUserName($r_d_v['from_uid']);
                $avatars = model ( 'Avatar' )->init($r_d_v['from_uid'])->getUserAvatar();
                $chat_list[$r_d_k]['from_avatar'] = $avatars['avatar_big'];
                $att_arr = explode('----', $r_d_v['attach_ids']);
                $attachIds = unserialize($att_arr[0]);
                if(in_array($r_d_v['msgtype'], array('voice','position','image'))){
                    foreach ($attachIds as $attachId) {
                        $attachInfo = model('Attach')->getAttachById($attachId);
                        switch ($r_d_v['msgtype']) {
                            case 'voice':
                                $cloud = model ( 'CloudAttach' );
                                if ($cloud->isOpen ()) {
                                    $chat_list[$r_d_k]['voice_url'] = $cloud->getFileUrl ( $attachInfo['save_path'] . $attachInfo['save_name'] );
                                } else {
                                    $chat_list[$r_d_k]['voice_url'] = UPLOAD_URL . '/' . $attachInfo['save_path'] . $attachInfo['save_name'];
                                }
                                $chat_list[$r_d_k]['length'] = $attachInfo['width'];
                                break;
                            case 'position':
                                $chat_list[$r_d_k]['poi_image'] = getImageUrl($attachInfo['save_path'].$attachInfo['save_name']);
                                $chat_list[$r_d_k]['poi_lat'] = $att_arr[1];
                                $chat_list[$r_d_k]['poi_lng'] = $att_arr[2];
                                $chat_list[$r_d_k]['poi_name'] = $att_arr[3];
                            default:    //image
                                $chat_list[$r_d_k]['image_url'] = getImageUrl($attachInfo['save_path'].$attachInfo['save_name']);
                                break;
                        }
                    }
                }else{
                    if($r_d_v['msgtype'] == 'card'){
                        $uid = intval($r_d_v['attach_ids']);
                        $chat_list[$r_d_k]['card_uid'] = t($uid);
                        $chat_list[$r_d_k]['card_avatar'] = model('Avatar')->getUserBigAvatar($uid);
                        $chat_list[$r_d_k]['card_uname'] = getUserName($uid);
                        $chat_list[$r_d_k]['card_intro'] = model('Stage')->getDetailName($uid,2);
                    }else if($r_d_v['msgtype'] == 'notify'){
                        $notify_arr = explode('-', $r_d_v['content']);
                        $notify_type = $notify_arr[0];
                        $controller_uid = $notify_arr[1];
                        $controller_uname = getUserName($controller_uid);
                        if($notify_arr[2] && $notify_type!='group_change_title'){
                            $controled_uids = array_unique(array_filter(explode(',', $notify_arr[2])));
                            foreach ($controled_uids as $k => $v) {
                                $controled_uname[] = getUserName($v);
                            }
                            $controled_unames = implode(',', $controled_uname);
                        }
                        switch ($notify_type) {
                            case 'group_create':
                                if($this->mid == $controller_uid){  //是操作者
                                    $chat_list[$r_d_k]['content'] = '您邀请 '.$controled_unames.' 加入了群聊';
                                }else{  //是被邀请对象
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'邀请 '.$controled_unames.' 加入了群聊';
                                }
                                break;
                            case 'group_add_user':
                                if($this->mid == $controller_uid){  //是操作者
                                    $chat_list[$r_d_k]['content'] = '您邀请 '.$controled_unames.' 加入了群聊';
                                }else if(in_array($this->mid, $controled_uids)){    //是被邀请对象
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'邀请您加入了群聊';
                                }else{  //其他成员
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'邀请 '.$controled_unames.' 加入了群聊';
                                }
                                break;
                            case 'group_remove_user':
                                if($this->mid == $controller_uid){  //是操作者
                                    $chat_list[$r_d_k]['content'] = '您把 '.$controled_unames.' 移出了群聊';
                                }else if(in_array($this->mid, $controled_uids)){    //是被邀请对象
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'把您移出了群聊';
                                }else{  //其他成员
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'把 '.$controled_unames.' 移出了群聊';
                                }
                                break;
                            case 'group_out':
                                if($this->mid == $controller_uid){  //是操作者
                                    $chat_list[$r_d_k]['content'] = '您退出了群聊';
                                }else{    //是被邀请对象
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'退出了群聊';
                                }
                                break;
                            case 'group_change_title':
                                if($this->mid == $controller_uid){  //是操作者
                                    $chat_list[$r_d_k]['content'] = '您修改了群聊名称:“'.$notify_arr[2].'”';
                                }else{    //是被邀请对象
                                    $chat_list[$r_d_k]['content'] = $controller_uname.'修改了群聊名称:“'.$notify_arr[2].'”';
                                }
                                break;
                        }
                        unset($controled_uname);
                    }
                }
                unset($chat_list[$r_d_k]['attach_ids']);
            }
        }else{
            $chat_list = array();
        }
        //新消息清空
        D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->setField('new',0);
        return $chat_list;
    }

    /**
     * 发送对话
     * @param integer $list_id 对话ID
     * @param string $content 内容(发送纯文本时用到)
     * @param integer $uid 用户ID(发送名片用到)
     * @param float $latitude 纬度(发送位置用到)
     * @param float $longitude 经度(发送位置用到)
     * @param string $location 地址名称(发送位置用到)
     * @param integer $length 语音时长(发送语音时用到) 
     * @param array $_FILES 发送图片或语音或位置时用到
     * @return array
     */
    public function send_message(){
        $list_id = intval($this->data['list_id']);
        if(!$list_id) return array('status'=>0,'msg'=>'参数错误');
        //判断是否可以发送
        if(!D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->count()){
            return array('status'=>0,'msg'=>'您不是群成员');
        }
        //隐私判断 --单聊时判断
        $list_detail = D('message_list')->where(array('list_id'=>$list_id))->field('type,min_max')->find();
        if($list_detail['type'] == 1){
            $member_arr = explode('_', $list_detail['min_max']);
            foreach ($member_arr as $ks => $vs) {
                if($vs != $this->mid){  //另一个人
                    $privacy =  model('UserPrivacy')->getPrivacy($this->mid,$vs);
                    if($privacy['message'] == 1){
                        return array('status'=>0,'msg'=>'根据对方的设置，您无法给对方发消息');
                    }
                }
            }
        }
        $message['list_id']  = $list_id;
        $message['from_uid'] = $this->mid;
        $message['mtime']    = time();
        $last_message['from_uid'] = $this->mid;
        if($_FILES){
            if(intval($this->data['length'])){  //语音
                $d ['attach_type'] = 'message_voice';
                $d ['upload_type'] = 'file';
                $message['type'] = 'voice';
                $message['content'] = $last_message['content'] = '[语音]';
            }else if($this->data['latitude'] && $this->data['longitude'] && $this->data['location']){   //位置
                $d ['attach_type'] = 'message_location';
                $d ['upload_type'] = 'image';
                $message['type'] = 'position';
                $message['content'] = $last_message['content'] = '[位置]';
            }else{  //图片
                $d ['attach_type'] = 'message_image';
                $d ['upload_type'] = 'image';
                $message['type'] = 'image';
                $message['content'] = $last_message['content'] = '[图片]';
            }
            $info = model ( 'Attach' )->upload ( $d, $d );
            if (! getSubByKey ( $info ['info'], 'attach_id' )) {
                return array('status'=>0,'msg'=>'上传失败');
            }
            if($message['type']=='voice'){
                $cloud = model ( 'CloudAttach' );
                if ($cloud->isOpen ()) {
                    $$data['voice_url'] = $cloud->getFileUrl ( $info['info'][0]['save_path'] . $info['info'][0]['save_name'] );
                } else {
                    $data['voice_url'] = UPLOAD_URL . '/' . $info['info'][0]['save_path'] . $info['info'][0]['save_name'];
                }
                $data['length'] = intval($this->data['length'])?intval($this->data['length']):1;
                model('Attach')->where(array('attach_id'=>$info['info'][0]['attach_id']))->setField('width',$data['length']);
                $message['attach_ids'] = serialize(array($info['info'][0]['attach_id']));
            }else if($message['type']=='position'){
                $data['image_url'] = getImageUrl ( $info['info'][0]['save_path'] . $info['info'][0]['save_name'] );
                $location = $this->data['latitude'] .'----'. $this->data['longitude'] .'----'. $this->data['location'];
                $message['attach_ids'] = serialize(array($info['info'][0]['attach_id'])) .'----'. $location;
            }else{
                $data['image_url'] = getImageUrl ( $info['info'][0]['save_path'] . $info['info'][0]['save_name'] );
                $message['attach_ids'] = serialize(array($info['info'][0]['attach_id']));
            }
        }else{
            if(intval($this->data['uid'])){ //名片
                $message['type'] = 'card';
                $message['attach_ids'] = intval($this->data['uid']);
                $message['content'] = $last_message['content'] = '[名片]';
            }else{  //纯文本
                $message['type'] = 'text';
                $message['content'] = $last_message['content'] = t($this->data['content']);
            }
        }

        if($msg_id = D('message_content')->data($message)->add()){    // 保存内容
            // 设置新消息
            D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>array('neq',$this->mid)))->setInc('new');
            // 添加消息总数
            D('message_member')->where(array('list_id'=>$list_id))->setInc('message_num'); 
            // 更新时间
            $m_data['list_ctime'] = time();
            D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>array('eq',$this->mid)))->save($m_data);
            // 更新最后一条消息
            $list['last_message'] = serialize($last_message);
            //$list['from_uid'] = $this->mid; // # 数据库设计问题
            D('message_list')->where(array('list_id'=>$list_id))->save($list);
            $data['to_uid'] = implode(',',getSubByKey(D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>array('neq',$this->mid)))->field('member_uid')->findAll(),'member_uid'));
            $data['list_id']  = t($list_id);
            $data['msgtype'] = $message['type'];
            $data['msg_id'] = t($msg_id); 
            $data['status'] = 1;
            $data['time'] = $message['mtime'];
            $data['from_uid'] = $this->mid;
            return $data;
        }else{
            return array('status'=>0);
        }
    }

    /**
     * 清空聊天记录
     * @param integer $list_id 对话ID
     */
    public function clear_message(){
        $list_id = intval($this->data['list_id']);
        if(!$list_id) return array('status'=>0,'msg'=>'参数错误');
        $save['ctime'] = time();
        $save['new'] = 0;
        $res = D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->save($save);
        if($res !== false){
            return array('status'=>1,'msg'=>'清空成功');
        }else{
            return array('status'=>0,'msg'=>'清空失败');
        }
    }

    /**
     * 未读消息清零
     * @param integer $list_id 对话ID
     */
    public function clear_unread(){
        $list_id = intval($this->data['list_id']);
        if(!$list_id) return array('status'=>0,'msg'=>'参数错误');
        $save['new'] = 0;
        $res = D('message_member')->where(array('list_id'=>$list_id,'member_uid'=>$this->mid))->save($save);
        if($res !== false){
            return array('status'=>1,'msg'=>'清零成功');
        }else{
            return array('status'=>0,'msg'=>'清零失败');
        }
    }

    /**
     * 获取群聊成员	--using
     *
     * @param integer $list_id
     *        	群聊ID
     * @return array 成员信息
     */
    public function get_list_member() {
        $list_id = intval ( $this->data ['list_id'] );
        $members = D ( 'message_member' )->where ( 'list_id=' . $list_id )->order ( 'ctime ASC' )->field ( 'member_uid' )->findAll ();
        if (! $members) {
            return $this->error('没有任何用户');
        }
        $user = array ();
        foreach ( $members as $k => $v ) {
            $user_info_whole = api ( 'User' )->getUserInfo ( $v ['member_uid'] );
            $user_info ['uid'] = $user_info_whole ['uid'];
            $user_info ['uname'] = $user_info_whole ['uname'];
            $user_info ['avatar'] = $user_info_whole ['avatar_middle'];
            $user [] = $user_info;
            unset ( $user_info, $user_info_whole );
        }
        return $user;
    }

    /**
     * 获取群聊信息 --using
     *
     * @param integer $list_id
     *        	群聊ID
     * @return array 成员、及群聊创建者的信息
     */
    public function get_list_info() {
        $list_id = intval ( $this->data ['list_id'] );
        $list_info = D ( 'message_list' )->field ( 'list_id,from_uid,type as room_type,title,member_num' )->where ( 'list_id=' . $list_id )->find ();
        if (! $list_info) {
            return $this->error('房间不存在');
        }
        // 加入成员列表
        $members = D ( 'message_member' )->where ( 'list_id=' . $list_id )->order ( 'ctime ASC' )->field ( 'member_uid' )->findAll ();
        if (! $members) {
            return $this->error('没有任何用户');
        }
        foreach ( $members as $k => $v ) {
            $user_info_whole = model ( 'User' )->getUserInfo ( $v ['member_uid'] );
            $user_info ['uid'] = $user_info_whole ['uid'];
            $user_info ['uname'] = $user_info_whole ['uname'];
            $user_info ['avatar'] = $user_info_whole ['avatar_middle'];
            $list_info ['memebrs'] [] = $user_info;
            unset ( $user_info, $user_info_whole );
        }
        // 格式化信息
        if ($list_info ['room_type'] == 1) {
            $list_info ['room_type'] = 'chat';
        } elseif ($list_info ['room_type'] == 2) {
            $list_info ['room_type'] = 'group';
        }
        $list_info ['status'] = 1;
        return $list_info;
    }

    /**
     * 判断是否有发私信的权限	--using
     *
     * @param
     *        	integer user_id 目标用户ID
     * @return array 状态+提示
     */
    public function can_send_message() {
        $uid = intval ( $this->user_id );
        if (! $uid){
            return $this->error('请选择用户');
        }
        $data = model('UserPrivacy')->getPrivacy($this->mid,$uid);
        if($data['message'] == 1){
            return $this->error('您没有权限给TA发私信');
        }
        return $this->success('可以发私信');
    }


    /**
     * 获取当前用户聊天列表  --using
     *
     * @param
     * 
     * @return array 
     */
    public function get_message_list(){
        $this->data['type']     = $this->data['type']   ? $this->data['type'] : array(1,2);
        $this->data['order']    = $this->data['order'] == 'ASC' ? '`list_ctime` ASC' : '`list_ctime` DESC';
        $message = model('Message')->getMessageListByUidForAPI($this->mid, $this->data['type']);
        $message = $this->__formatMessageList($message);
        foreach ($message as &$_l) {
            $_l['from_uid'] = $_l['last_message']['from_uid'];
            $_l['content']  = $_l['last_message']['content'];
            unset($_l['last_message']);
            unset($_l['to_user_info']);
        }
        return $message;
    }

    private function __formatMessageList($message) {
        foreach ($message as $k => $v) {
            $message[$k] = $this->__formatMessageDetail($v);
        }
        return $message;
    }

    private function __formatMessageDetail($message) {
        unset($message['deleted_by']);
        $fromUserInfo = model('User')->getUserInfo($message['from_uid']);
        $message['from_uname']  = $fromUserInfo['uname'];
        $message['from_face']   = $fromUserInfo['avatar_middle'];
        $message['timestmap']   = $message['mtime'];
        $message['ctime']       = date('Y-m-d H:i', $message['mtime']);
        $uids = explode('_', $message['min_max']);
        $message['with_uid'] = $uids[0] == $this->mid ? $uids[1] : $uids[0];
        $message['with_uid_userinfo'] = model('User')->getUserInfo($message['with_uid']);
        return $message;
    }
}