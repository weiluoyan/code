<?php
function binSearch1($arr, $target){  
    $height = count($arr)-1;  
    $low = 0;  

    while($low <= $height){  
        $mid = floor(($low+$height)/2);//获取中间数

        //两值相等，返回 
        if($arr[$mid] == $target){  
            return $mid; 

            //元素比目标大，查找左部 
        } elseif ($arr[$mid] < $target){
            $low = $mid + 1;  

            //元素比目标小，查找右部
        } elseif ($arr[$mid] > $target){  
            $height = $mid - 1;  
        }  
    }  
    return "查找失败";  
}

$arr =  array(6,3,8,2,9,1);
sort($arr);
var_dump(binSearch1($arr, 6));
die;
function bubble_sort($arr){
    $count = count($arr);
    if ( $count == 0 ){
        return false; 
    }

    for($i = 0; $i < $count; $i++){
        for($j = 0; $j < $count-1-$i; $j++){
            if ($arr[$j] > $arr[$j+1]){
                $temp = $arr[$j];
                $arr[$j] = $arr[$j+1];
                $arr[$j+1] = $temp;
            }
        }
    }
    return $arr;
}

$arr =  array(6,3,8,2,9,1);
$new_arr = bubble_sort($arr);
//var_dump($new_arr);


function quick_sort($list){
    $len = count($list);
    if ($len <= 1){
        return $list;
    }

    $pivotValue = $list[0];
    $left = array();
    $right = array();

    for($i = 1; $i< $len; $i++){
        if ($list[$i] < $pivotValue){
            $left[] = $list[$i];
        }else{
            $right[] = $list[$i];
        }
    }
    $left = quick_sort($left);
    $right = quick_sort($right);
    var_dump($left);
    var_dump($right);
    return array_merge($left, array($pivotValue), $right);
}

var_dump(quick_sort($arr));

function binSearch($arr, $target){
    $height = count($arr) - 1;
    $low = 0;

    while($low <= $height){
        $mid = floor(($low+$height)/2);

        //两值相等，返回
        if ($arr[$mid] == $target){
            return $mid; 
            
            //元素比目标大，查找左部
        }elseif ($arr[$mid] < $target){
            $low = $mid + 1;
        }elseif($arr[$mid] > $target){
            $height = $mid - 1; 
        }

    }


}



















