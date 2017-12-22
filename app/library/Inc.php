<?php

namespace Phalcon\Library;

class Inc{

	/* URL */
	public function BaseUrl($url=''){
		$base_url = $_SERVER['SERVER_PORT']=='443'?'https://':'http://';
		$base_url .= $_SERVER['HTTP_HOST'].'/'.$url;
		return $base_url;
	}

	/* Key */
	public function getKey($str){
		return md5($str.'e33e907621123d2bf01b7f580f316ade');
	}
	public function getKeyArr($parameter=''){
		ksort($parameter);
		reset($parameter);
		$parameter['sign'] = 'e33e907621123d2bf01b7f580f316ade';
		return md5(http_build_query($parameter));
	}

	/* 关键字高亮 */
	public function keyHH($str='', $phrase, $tag_open = '<span style="color:#FF6600">', $tag_close = '</span>'){
		if ($str == ''){return FALSE;}
		if ($phrase != ''){return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);}
		return $str;
	}

}