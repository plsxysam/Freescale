<?php 

/**
 * 多对多关联模型
 */
Class TraceRelationModel extends RelationModel{

	Protected $tableName = 'trace';

	Public function getTracesList ($type = 0,$bloglock = -1,$author = 0){
		//用于控制读取主表中的字段,在调用时field($field)是读取其中的字段,field($field,true)是读取除$field以外的字段
		$where = array('del' => $type);

		$field = array('del', 'title');
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
		return $this->order('id DESC')->field($field,true)->where($where)->relation(true)->select();
		
	}
}
 ?>