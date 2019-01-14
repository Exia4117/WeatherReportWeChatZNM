<?php
namespace app\api\controller;

use think\Controller;

class City extends Controller
{
	public function read()
	{
		$county_name = input('county_name');
		$model = model('City');
		$data = $model->getCity($county_name);
		if ($data) {
			$code = 200;
		} else {
			$code = 404;
		}

		$weather_code = $data[0]['weather_code'];

		$daynum = 3;
		$address = "http://wthrcdn.etouch.cn/WeatherApi?citykey=".$weather_code;

		$url = "http://wthrcdn.etouch.cn/weather_mini?city=".$county_name;
 //XML标签配置
		$xmlTag = array(
			'city',
			'updatetime',
			'shidu',
			'wendu',
			'pm25',
			'quality',
			'fengxiang',
			'high',
			'low',
			'type'
		);
		$contents = gzdecode(file_get_contents($address));

		$arr = array();
		foreach($xmlTag as $x) {
			preg_match_all("/<".$x.">.*<\/".$x.">/", $contents, $temp);
			$arr[] = $temp[0];
		}
 //去除XML标签并组装数据
		$data = array();
		foreach($arr as $key => $value) {
			foreach($value as $k => $v) {
				$a = explode($xmlTag[$key].'>', $v);
				$v = substr($a[1], 0, strlen($a[1])-2);
				$data[$k][$xmlTag[$key]] = $v;
			}
		}
	//	print_r($data);
 return json($data[0]);
	}
}
