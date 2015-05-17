<?php
namespace Home\Model;
use Think\Model;
class RobotChatLogModel extends Model{
	
	public function addChatLog($ask,$answer){
		$data['ask'] = $ask;
		$data['answer'] = $answer;
		$data['dateline'] = time();
		$this->add($data);		
	}
}