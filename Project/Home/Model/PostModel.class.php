<?php
namespace Home\Model;
use Think\Model;
/**
 * 回复模型
 */
class PostModel extends Model{
	
	/**
	 * 获取某个活动的所有回复
	 * @author JunhaoHo
	 */
	public function getPostsByAid($aid,$field ='',$order='dateline desc'){
		$where['aid']  = $aid ;
		$field         = $field  ?  : "*" ;
		return $this->where($where)->field($field)->order($order)->select();
	}
	
	/**
	 * 插入一条回复
	 * @param unknown $post
	 * @return Ambigous <\Think\mixed, boolean, string, unknown>
	 * @author JunhaoHo
	 */
	public function addPost($post){
		return $this->add($post);
	}

    /**
     * 返回随机图片 若无返回 null
     * @param $aid 活动aid
     * @param string $field
     * @return mixed
     */
    public function getRandomPicByAid($aid){
        $where['hasimg'] = array('eq',1);
        $where['aid']    = array('eq',$aid);
        $rs = $this->where($where)->field('img')->select();
        shuffle($rs);
        return $rs[0]['img'];
    }
}