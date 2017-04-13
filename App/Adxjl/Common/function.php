<?php 
    function  nodeMerge($node,$access=null,$pid=0){
        $arr=array();
        foreach ($node as $k => $v) {
            if (is_array($access)) {
                $v['access']=in_array($v['id'], $access)? 1:0;
            }
            if ($v['pid']==$pid) {
               $v['child']=nodeMerge($node,$access,$v['id']);
               $arr[]=$v;
            }
        }
        return $arr;
    }


?>