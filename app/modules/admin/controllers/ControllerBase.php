<?php
namespace Phalcon\Modules\Admin\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

/**
* 后台：公共控制器
*/
class ControllerBase extends Controller{

	/* 构造函数 */
	public function initialize(){
		$this->view->setVar('base_url',$this->url->get($this->dispatcher->getModuleName().'/'));
		$this->view->setVar('getUrl','');
	}

	/* 跳转 */
	function redirect($url=''){
		return $this->response->redirect($this->dispatcher->getModuleName().'/'.$url);
	}

	/* Page */
	public function getPage($config=array()){
		if(isset($config['data'])){
			$cname = isset($config['cname'])?$config['where']:$this->dispatcher->getControllerName();
			$limit = isset($config['limit'])?$config['limit']:15;
			$getUrl = isset($config['getUrl'])?$config['getUrl']:'';
			// Page
			$num = $this->request->getQuery('page', 'int');
			$page = empty($num)?1:$num;
			$paginator   = new PaginatorModel(array('data'=>$config['data'],'limit'=>$limit,'page'=>$page));
			$Page = $paginator->getPaginate();
			// Page Html
			$html = '';
			if(empty($page) || $page==1){
				$html .= '<span>首页</span>';
				$html .= '<span>上一页</span>';
			}else{
				$html .= '<a href="'.$this->url->get($cname).'?page=1'.$getUrl.'&search">首页</a>';
				$html .= '<a href="'.$this->url->get($cname).'?page='.$Page->before.$getUrl.'&search">上一页</a>';
			}
			if($Page->total_pages==0 || $page==$Page->last){
				$Page->current = $Page->total_pages?$Page->current:0;
				$html .= '<span>下一页</span>';
				$html .= '<span>末页</span>';
			}else{
				$html .= '<a href="'.$this->url->get($cname).'?page='.$Page->next.$getUrl.'&search">下一页</a>';
				$html .= '<a href="'.$this->url->get($cname).'?page='.$Page->last.$getUrl.'&search">末页</a>';
			}
			$html .= ' Page : '.$Page->current.'/'.$Page->total_pages;
			$Page->PageHtml = $html;
			return $Page;
		}else{return FALSE;}
	}
	// Page Where
	public function pageWhere(){
		$getUrl = '';
		$like = $this->request->getQuery();
		$page = isset($like['page'])?$like['page']:1;
		unset($like['_url']);
		unset($like['page']);
		foreach($like as $key=>$val){if($val==''){unset($like[$key]);}else{$getUrl .= '&'.$key.'='.$val;}}
		unset($like['search']);
		$this->view->setVar('getUrl','?search&page='.$page.$getUrl);
		return array('getUrl'=>$getUrl,'data'=>$like);
	}

}
