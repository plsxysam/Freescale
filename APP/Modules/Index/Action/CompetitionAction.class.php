<?php 

Class CompetitionAction extends Action {

	Public function index(){
		$field = array('id', 'title', 'author', 'summary', 'content', 'time');
		$where = array('del' => 0,);
		$this->competition = M('competition')->order('id DESC')->where($where)->field($field)->select();
		$this->display();
	}

	Public function competition(){
		$id = I('id');
		$field = array( 'title', 'author', 'content', 'time');
		$where = array('del' => 0,'id' => $id);
		$this->msg = M('competition')->order('id DESC')->where($where)->field($field)->select();
		$this->display();
	}
}
 ?>