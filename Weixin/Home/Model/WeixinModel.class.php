<?php
namespace Home\Model;
use Think\Model;
class WeixinModel extends Model{
    public function addurl($url){
        $str = $this->add($url);
        return $str;
    }
}
?>