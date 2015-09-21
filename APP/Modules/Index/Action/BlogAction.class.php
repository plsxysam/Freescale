<?php 

Class BlogAction extends Action{
	Public function index($type = 0){
		$this->blog = D('BlogRelation')->getBlogsList(0,-1,$_SESSION['uid'],0);
		$this->display();
	}

	Public function blog(){
		$this->display();
	}

	Public function bloglog(){
		$msg = array();
		$id = I('id');
		$field = array('id','title', 'author', 'summary', 'content', 'time');
		$where = array('id' => $id);
		$msg = M('blog')->where($where)->field($field)->find($where);

		$fieldblog = array('id','title', 'author');
		$whereblog = array('author' => $msg['author'], 'del' => 0);
		$bloglist = M('blog')->order('id DESC')->where($whereblog)->field($fieldblog)->limit('0,5')->select();

		$fieldtrace = array('id','content', 'author');
		$wheretrace = array('author' => $msg['author'], 'del' => 0);
		$tracelist = M('trace')->order('id DESC')->where($wheretrace)->field($fieldtrace)->limit('0,3')->select();
		
		$this->tracelist = $tracelist;
		$this->bloglist = $bloglist;
		$this->msg = $msg;
		$this->display();
	}

	Public function bloguser($id){
		$name=M('user')->select($id);
		
		$this->$name['username'];
	}
}
 ?>