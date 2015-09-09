<?php 

Class NoticeAction extends Action{

	Public function index(){
		/**
		 * 预留翻页功能
		 * where time > time1 and time <time2;
		 */
		$field = array('id', 'title', 'content', 'time');
		$where = array('del' => 0,);
		$this->notice = M('notice')->order('id DESC')->where($where)->field($field)->select();
		$this->display();
	}
}
 ?>