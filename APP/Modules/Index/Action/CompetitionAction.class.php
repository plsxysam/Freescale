<?php 

Class CompetitionAction extends Action {

	Public function index(){
		$field = array('id', 'title', 'author', 'summary', 'imgname', 'content', 'time');
		$where = array('del' => 0,);
		$this->competition = M('competition')->order('id DESC')->where($where)->field($field)->select();
		$this->display();
	}

	Public function competition(){
		$id = I('id');
		$field = array('id','title', 'author', 'imgname', 'content', 'time');
		$where = array('del' => 0, 'id' => $id);
		$this->msg = M('competition')->where($where)->field($field)->find($where);
		$this->display();
	}
}
 ?>