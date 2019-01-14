<?php
namespace app\weixin\controller;
use think\Controller;

class Ajax extends Controller
{
   public function read(){
    if(!empty($_POST['name'])){
      $value = array("status"=>"1","msg"=>"保存成功");
       echo json_encode($value);
    }
    elseif(!empty($_POST['latitude'])){
     $latitude=$_POST['latitude'];
     $longitude=$_POST['longitude'];

     $data = $this->location($latitude,$longitude);
     $city=$data->result->addressComponent->city;


     $city1=str_replace("市","",$city);
     $weather_info = $this->get_weather($city1);

     $update_time = $weather_info->updatetime;
     $shidu = $weather_info->shidu;
     $wendu = $weather_info->wendu;
     $fx = $weather_info->fengxiang;
     $type = $weather_info->type;
     $high = $weather_info->high;
     $high1 = str_replace("高温 ","",$high);
     $low = $weather_info->low;
     $low1 = str_replace("低温 ","",$low);
     $value = array("status"=>"1","city"=>"$city","update_time"=>"$update_time","shidu"=>"$shidu",
                  "wendu"=>"$wendu","high1"=>"$high1","low1"=>"$low1","fx"=>"$fx","type"=>"$type",);
     echo json_encode($value);  
    }  
    else {
     $value = array("status"=>"0","msg"=>"保存失败");
     echo json_encode($value);
   }
}

 private function location($latitude,$longitude){
   if(!empty($latitude)){
     $result_v1=json_decode(file_get_contents("https://api.map.baidu.com/geoconv/v1/?coords=".$longitude.",".$latitude."&output=json&pois=1&ak=wITvbGMEOmAUTsBNkKILLx4jACLissmL"));
     $baidulat = $result_v1->result[0]->y;
     $baidulong = $result_v1->result[0]->x;
     $result_v2=json_decode(file_get_contents("http://api.map.baidu.com/geocoder/v2/?location=".$baidulat.",".$baidulong."&output=json&pois=1&ak=wITvbGMEOmAUTsBNkKILLx4jACLissmL"));
     return $result_v2;
   } else {
     return null;
   }
  }
  
  private function get_weather($city){
    if(!empty($city)){
      $url_citycode = 'http://154.8.210.161/api/city/read/county_name/'.$city;
      $weather_info = json_decode(file_get_contents($url_citycode));
      return $weather_info;
    }else{
     return null;
   }
 }
}
