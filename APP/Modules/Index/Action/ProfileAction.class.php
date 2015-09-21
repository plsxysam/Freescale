<?php 

Class ProfileAction extends Action{

	Public function index(){
		$this->display();
	}

	Public function trace(){
		$this->trace = D('TraceRelation')->getTracesList(0,-1,$_SESSION['uid']);
		$this->display();
	}

	Public function toAddTrace(){
		$data = array(
			'author' => $_SESSION['uid'],
			'content' => $_POST['content'],
			'cid' => I('share','','htmlspecialchars'),
			'tracelock' => (int)$_POST['tracelock'],
			'time' => time(),
			);
		if ($bid = M('trace')->add($data)) {
			$this->success('添加成功', U(GROUP_NAME . '/Profile/trace'));
		} else {
			$this->error('添加失败');
		}
	}

	//删除到回收站/还原
	Public function toTrach(){
		$url = $_GET['act'];
		$type = (int) $_GET['type'];
		$msg = $type ? '删除' : '还原';
		$update = array(
			'id' =>(int) $_GET['id'],
			'del' => $type
			);
		//BUG
		//M('blog')->where(array('id' = > $_GET['id']))->setField('del', 1)
		if(M('blog')->save($update)){
			$this->success($msg . '成功', U(GROUP_NAME . '/Profile/'. $url));
		} else {
			$this->error($msg . '失败');
		}
	}

	//回收站
	Public function tracetrach(){
		$this->trace = D('TraceRelation')->getTracesList(1,-1,$_SESSION['uid']);
		$this->display('trace');
	}

	//回收站
	Public function trach(){
		$this->blog = D('BlogRelation')->getBlogsList(1,-1,$_SESSION['uid']);
		$this->newcount = M('blog')->where(array('del' => 1,'author' =>$_SESSION['uid'],'cid' => 1))->count();
		$this->display('blog');
	}

	//彻底删除
	Public function deleteBlog(){
		$id = (int) $_GET['id'];
		//D('BlogRelation')->relation('attr')->delete($id);
		if(M('blog')->delete($id)){
			M('blog_attr')->where(array('bid' => $id))->delete();
			$this->success('删除成功', U(GROUP_NAME . '/Profile/trach'));
		} else {
			$this->error('删除失败');
		}
	}

	//彻底删除
	Public function deleteTrace(){
		$id = (int) $_GET['id'];
		//D('BlogRelation')->relation('attr')->delete($id);
		if(M('trace')->delete($id)){
			$this->success('删除成功', U(GROUP_NAME . '/Profile/tracetrach'));
		} else {
			$this->error('删除失败');
		}
	}

	Public function blog(){
		$this->blog = D('BlogRelation')->getBlogsList(0,-1,$_SESSION['uid']);
		$this->newcount = M('blog')->where(array('del' => 0,'author' =>$_SESSION['uid'],'cid' => 1))->count();
		$this->display();
	}

	Public function bloglog(){
		$id = I('id');
		$field = array('id','title', 'author', 'summary', 'content', 'time');
		$where = array('id' => $id);
		$this->msg = M('blog')->where($where)->field($field)->find($where);
		$this->display();
	}

	Public function addblog(){
		//博文所属分类
		import('Class.Category', APP_PATH);
		$this->cate = M('cate')->order('sort')->select();
		// $cate = Category::unlimitedForLevel($cate);

		//博文属性
		$this->attr = M('attr')->select();
		$this->display();
	}

	//添加博文表单处理
	Public function toAddBlog () {
		$data = array(
			'title' => I('title','','htmlspecialchars'),
			'author' => $_SESSION['uid'],
			'summary' => I('summary','','htmlspecialchars'),
			'content' => $_POST['content'],
			'cid' => I('cid','','htmlspecialchars'),
			'bloglock' => (int)$_POST['bloglock'],
			'time' => time(),
			);
		if ($bid = M('blog')->add($data)) {
			
			if (isset($_POST['aid'])) {
				$sql = 'INSERT INTO `' . C('DB_PREFIX') . 'blog_attr` (bid, aid) VALUES';
				foreach ($_POST['aid'] as $v) {
					$sql .= '(' . $bid . ',' . $v . '),';
				}
				$sql = rtrim($sql, ',');
				M('blog_attr')->query($sql);
			}
			$this->success('添加成功', U(GROUP_NAME . '/Profile/blog'));
		} else {
			$this->error('添加失败');
		}
	} 

	//编辑器图片上传处理
	Public function upload(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';

		if($upload->upload('./Uploads/')){
			$info = $upload->getUploadFileInfo();
			// import('ORG.Util.Image');
			// Image::water('./Uploads/' . $info[0]['savename'], './Data/fff.png');
			// import('Class.Image', APP_PATH);
			// Image::water('./Uploads/' . $info[0]['savename'], './Data/fff.png');

			echo json_encode(array(
				'url' => $info[0]['savename'],
				'title' => htmlspecialchars($POST['pictitle'], ENT_QUOTES),
				'original' => $info[0]['name'],
				'state' => 'SUCCESS'
				));
		} else {
			echo json_encode(array(
				'state' =>$upload->getErrorMsg()
				));
			
		}
	}
}
 ?>