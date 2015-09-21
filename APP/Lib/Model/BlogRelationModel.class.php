<?php 

/**
 * 多对多关联模型
 */
Class BlogRelationModel extends RelationModel{

	Protected $tableName = 'blog';

	Protected $_link = array(
		'attr' => array(
			'mapping_type' => MANY_TO_MANY,
			'mapping_name' => 'attr',
			'foreign_key' => 'bid',
			'relation_foreign_key' => 'aid',
			'relation_table' => 'fr_blog_attr',
			),
		//HAS_MANY一对多HAS_ONE一对一
		'cate' => array(
			'mapping_type' => BELONGS_TO,
			'foreign_key' => 'cid',
			//只读取的部分，比如name
			'mapping_fields' => 'name',
			//改名字
			'as_fields' => 'name:cate',
			),
		'user' => array(
			'mapping_type' => BELONGS_TO,
			'foreign_key' => 'cid',
			//只读取的部分，比如name
			'mapping_fields' => 'username',
			//改名字
			'as_fields' => 'username',
			)
		);

	/**
	 * type 0为未删除列表 1为删除列表
	 * bloglock 0为公开，1为私密
	 * author 0所有 其余为用户私有列表
	 * way 0为公众列表10置顶+10精华+普通	-1普通 1置顶 2精华 3心得 4总结 5其他
	 */
	Public function getBlogsList ($type = 0,$bloglock = -1,$author = 0,$way = -2){
		//用于控制读取主表中的字段,在调用时field($field)是读取其中的字段,field($field,true)是读取除$field以外的字段
		$where = array('del' => $type);

		$field = array('del', 'content');
		if ($author) {
			$where['author'] = $author;
		}
		if ($bloglock == 0) {
			$where['bloglock'] = $bloglock;
		}
		if ($bloglock == 1) {
			$where['bloglock'] = $bloglock;
		}
		
		//如果只想关联其中的某一个，将relation中true修改为对应的名字，->where($where)->field($field)
		$data = $this->order('id DESC')->field($field,true)->where($where)->relation(true)->select();
		if ($way == -2) {
			return $data;
		}
		if ($way == 1) {
			$new = array();
			foreach ($data as $v) {
				if ($v['attr'][0]["name"] == '置顶') {
					$new[] = $v;
				}
			}
			return $new;
		}
		if ($way == 2) {
			$new = array();
			foreach ($data as $v) {
				if ($v['attr'][0]["name"] == '精华' || $v['attr'][1]["name"] == '精华') {
					$new[] = $v;
				}
			}
			return $new;
		}
		if ($way == -1) {
			$new = array();
			foreach ($data as $v) {
				if ($v['attr'][0]["name"] != '精华' && $v['attr'][1]["name"] != '精华' &&$v['attr'][0]["name"] != '置顶') {
					$new[] = $v;
				}
			}
			return $new;
		}
		if ($way == 0) {
			$top = array();
			$essence = array();
			$other = array();
			foreach ($data as $v) {

				if ($v['attr'][0]["name"] == '置顶') {
					if (count($top) < 10) {
						$top[] = $v;
					}
					else {
						if ($v['attr'][1]["name"] == '精华') {
							if (count($essence) < 10) {
								$essence[] = $v;
							}else{
								$other[] = $v;
							}
						}
					}
				}
				else if ($v['attr'][0]["name"] == '精华') {
					if (count($essence) < 10) {
						$essence[] = $v;
					}else{
						$other[] = $v;
					}
				}
				else{
					$other[] = $v;
				}
			}
			$final = array_merge($top , $essence , $other);
			return $final;
		}

	}
}
 ?>