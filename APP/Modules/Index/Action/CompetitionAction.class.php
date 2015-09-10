<?php 

Class CompetitionAction extends Action {

	Public function index(){
		$field = array('id', 'title', 'author', 'summary', 'content', 'time');
		$where = array('del' => 0,);
		$this->competition = M('competition')->order('id DESC')->where($where)->field($field)->select();
		$this->display();
	}
}
 ?>