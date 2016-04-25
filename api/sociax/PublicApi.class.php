<?php
/**
 * 公开api接口
 *
 * @package ThinkSNS\Api\Public
 * @author Medz Seven <lovevipdsw@vip.qq.com>
 **/
class PublicApi extends Api
{

	/**
	 * 获取application幻灯数据
	 *
	 * @return array
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function getSlideShow()
	{
		$list = D('application_slide')->field('`title`, `image`, `type`, `data`')->select();

		foreach ($list as $key => $value) {
			$value['image'] = getImageUrlByAttachId($value['image']);
			$list[$key]     = $value;
		}

		return $list;
	}

	/**
	 * 获取关于我们HTML信息
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function showAbout()
	{
		ob_end_clean();
		ob_start();
		header('Content-Type:text/html;charset=utf-8');
		echo '<!DOCTYPE html>',
			 '<html lang="zh">',
			 	'<head><title>关于我们</title></head>',
			 	'<body>',
			 	json_decode(json_encode(model('Xdata')->get('admin_Application:about')), false)->about,
			 	'</body>',
			 '</html>';
		ob_end_flush();
		exit;
	}

} // END class PublicApi extends Api