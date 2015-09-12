<?php 

Class CompetitionAction extends CommonAction{

	//比赛信息列表
	Public function index(){
		$field = array('id', 'title', 'content', 'time');
		$where = array('del' => 0);
		$this->competition = M('competition')->where($where)->field($field)->select();
		$this->display();
	}

	//删除到回收站/还原
	Public function toTrach(){
		$type = (int) $_GET['type'];
		$msg = $type ? '删除' : '还原';
		$update = array(
			'id' =>(int) $_GET['id'],
			'del' => $type
			);
		//M('blog')->where(array('id' = > $_GET['id']))->setField('del', 1)
		if(M('competition')->save($update)){
			$this->success($msg . '成功', U(GROUP_NAME . '/Competition/index'));
		} else {
			$this->error($msg . '失败');
		}
	}

	//公告回收站
	Public function trach(){
		$field = array('id', 'title', 'content', 'time');
		$where = array('del' => 1);
		$this->competition = M('competition')->where($where)->field($field)->select();
		$this->display('index');
	}

	//彻底删除公告
	Public function delete(){
		$id = (int) $_GET['id'];
		//D('BlogRelation')->relation('attr')->delete($id);
		if(M('competition')->delete($id)){
			$this->success('删除成功', U(GROUP_NAME . '/Competition/trach'));
		} else {
			$this->error('删除失败');
		}
	}

	//添加公告
	Public function competition(){
		$this->display();
	}

	//添加博文表单处理
	Public function addCompetition () {
		//不安全了可以用I方法
		$data = array(
			'title' => I('title'),
			'author' => I('author'),
			'summary' => I('summary'),
			'content' => I('content','','htmlspecialchars'),
			'content' => $_POST['content'],
			'time' => time(),
			);
		if ($bid = M('competition')->add($data)) {
			
			$this->success('添加成功', U(GROUP_NAME . '/Competition/index'));
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
			import('Class.Image', APP_PATH);
			Image::water('./Uploads/' . $info[0]['savename'], './Data/fff.png');

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