<?php
namespace Home\Model;
use Think\Model;

/**
 * 用户类
 */
class MemberModel extends Model{

    /**
     * 通过用户名查找用户
     * @param $username
     * @param array $where
     * @param string $field
     */
    public function getUsersByUsername($usernames,$where=array(),$field=''){
        $field = $field ? : '*';
        if(is_array($usernames)){
            $where['username'] = array('in',$usernames);
        }else{
            $where['username'] = array('eq',$usernames);
        }
        return $this->where($where)->field($field)->select();
    }

    /**
     * 跟进uid获取用户
     * @param $uid
     */
    public function getUserByUid($uid,$field='*'){
        $where['uid'] = $uid;
        return $this->where($where)->field($field)->find();
    }

    /**
     * 获取所有用户
     * @return mixed
     */
	public function getAllMembers(){
		return $this->select();
	}

    /**
     * 添加一个用户
     * @param $data
     * @return mixed
     */
	public function addUser($data){
		return $this->add($data);
	}

    /**
     * 获取单个用户的头像文件名
     * @param $uid
     * @return mixed
     */
    public function getAvatarByUid($uid){
        $where['uid'] = $uid;
        $rs =  $this->where($where)->field('avatar')->find();
        return $rs['avatar'];
    }

    /**
     * 批量获取多个用户的头像文件名
     * @param array $uids
     * @return array(uid1=>avatar1,uid2=>avatar2,...)
     */
    public function getAvatarsByUids($uids = array()){
        $where['uid'] = array('in',$uids);
        $rs = $this->where($where)->field('uid,avatar')->select();
        foreach ($rs as $user) {
            $avatars[$user['uid']] = $user['avatar'];
        }
        return $avatars;
    }

    /**
     * 更新用户数据
     * @param $uid
     * @param $data
     * @return bool
     */
    public function updateMemberByUid($uid,$data){
        $where['uid'] = $uid;
        return $this->where($where)->save($data);
    }
}