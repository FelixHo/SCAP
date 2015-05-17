<?php
namespace Home\Model;

use Think\Model;

/**
 * 活动参与人员
 */
class ActivityMemberModel extends Model{

    /**
     * 添加一名成员
     * @param $data
     * @param $aid
     * @return mixed
     */
    public function insertMember($data){
        return $this->add($data);
    }

    /**
     * 通过AID获取某个活动的参与名单
     */
    public function getActivityMembersByAid($aid,$field='',$order='dateline desc'){
        $field = $field ? : '*';
        $where['aid'] = $aid;
        return $this->where($where)->field($field)->order($order)->select();
    }

    /**
     * 检测某人是否参加某个活动
     * @param $uid
     * @param $aid
     * @return bool|mixed
     */
    public function checkActivityMemberWithAidNUid($aid,$uid){
        $where['aid'] = $aid;
        $where['uid'] = $uid;
        $is_exist = $this->where($where)->find();
        $is_exist = $is_exist ? true : false;
        return $is_exist ;
    }

    /**
     * 获取某个活动报名的人数
     */
    public function getMemberNumByAid($aid){
        $where['aid'] = $aid;
        return $this->where($where)->count();
    }
    
    /**
     * 获取某个用户所参加或所发起的活动
     * @author JunhaoHo
     */
    public function getAidsByUid($uid){
    	$where['uid'] = $uid;
    	$rs = $this->where($where)->field('aid')->select();
    	foreach ($rs as $r){
    		$aids[]=$r['aid'];
    	}
    	return  $aids;
    }
    
    /**
     * 删除某个活动的某个报名用户
     * @param unknown $aid
     * @param unknown $uid
     * @return boolean
     * @author JunhaoHo
     */
    public function deleteMemerByAidNUid($aid,$uid){
    	$where['aid'] = $aid ; 
    	$where['uid'] = $uid ;
    	return $this->where($where)->delete();
    }
    
	/**
	 * 删除某个活动的所有用户
	 * @param  $aid
	 * @return boolean
	 * @author JunhaoHo
	 */
    public function delAllMembersByAid($aid){
    	$where['aid'] = $aid;
    	$r = $this->where($where)->delete();
    	return $r !==false;
    }
}
