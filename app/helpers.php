<?php
// 示例函数
function etype() {
  $earray =[
    '中通快递' => "中通快递",
    '圆通快递' => "圆通快递",
    '韵达快递' => "韵达快递"
  ];
    return $earray;
}

function getQtypes(){
  $redis = app()->make('redis.connection');
  $data = $redis->get('qtypes');
  if(!$data){
    $qtypes = app()->make('App\Models\Qtype');
    $data = $qtypes->all()->toJson();
    $redis->setex('qtypes',7200,$data);
    $data = $redis->get('qtypes');
  }
  return json_decode($data,true);
}

//问题类型转化数组
function qdataArry(){
  $datas = getQtypes();
  $rdata = [];
  foreach ($datas as $key => $data) {
    // code...
    $rdata[$data['id']] = $data['name'];
  }
  return $rdata;
}

function getQdata($id){
  $datas = getQtypes();
  $result = [];
  foreach ($datas as $key => $data) {
    if($data['id']==$id){
      $result = $data;
      break;
    }
  }
  return $result;
}

function getQid($name){
  $datas = getQtypes();
  $result = [];
  foreach ($datas as $key => $data) {
    if($data['name']==$name){
      $result = $data;
      break;
    }
  }
  return $result;
}
//按上下班时间生成超时时间
function getDeadline($seconds){
  $nexttime = time(); //当前时间
  $endtime = strtotime("18:00:00",$nexttime); //今天下班时间
  $starttime = strtotime("8:10:00",$nexttime); //今天上班时间

  if($nexttime<$endtime&&$nexttime>=$starttime){   //上班时间
    $a = $endtime-$nexttime;  //剩余时间
	  //$nexttime =$starttime;
	  if($a<$seconds){
		  $nexttime =$starttime;
	  }
  }else if($nexttime>=$endtime){
    $nexttime = strtotime("+1 days",$starttime); //明天上班时间
    $endtime = strtotime("+1 days",$endtime);  //明天下班时间
    $a = $endtime-$nexttime; //剩余时间
  }else{
    $a = $endtime-$starttime;
    $nexttime =$starttime;
  }
  while($a<$seconds){
      $seconds=$seconds-$a;
      $nexttime = strtotime("+1 days", $nexttime);
      $endtime = strtotime("+1 days",$endtime);
      $a = 3600*10;
    }
  return $nexttime+$seconds;
}

function getUUID(){
  return UUID::generate()->string;
}

function taskstatus($time,$isok){
  $w0 = "待处理";
  $w1 = "已接单";
  $w2 = "已完结";
  $d = "(已超时)";
  $str = "";
  switch ($isok) {
    case 0:
      if($time<time()){
        $str = $w0.$d;
      }else{
        $str = $w0;
      }
      // code...
      break;
    case 1:
      if($time<time()){
        $str = $w1.$d;
      }else{
        $str = $w1;
      }
      // code...
      break;
    case 2:
        $str = "超时处理";
      // code...
      break;
    case 3:
        $str = $w2;
      // code...
      break;
  }
  return $str;
}

//获取当前时
function taskcount($user_uuid){
  $tasks = app()->make('App\Models\Task');
  $tasks = $tasks->where('user_uuid',$user_uuid)->get();
  $data['0'] = $tasks->where('isok','0')->count();
  $data['1'] = $tasks->where('isok','1')->count();
  $data['2'] = $tasks->where('isok','>','1')->count();
  $data['all'] = $tasks->count();
  return $data;
}

function wdlist(){
  $wds = app()->make('App\Models\Store')->where('status',0)->get();
  return $wds;
}

function elists(){
  $es = app()->make('App\Models\Elist')->get();
  return $es;
}
 
function storedatas($status = 0){
  if($status){
    $stores = app()->make('App\Models\Store')->get();
  }else{
    $stores = wdlist();
  }
  $row = [];
  foreach ($stores as $key => $e) {
    # code...
    $row[$e->name] = $e->name;
  }
  return $row;
}

function edatas(){
  $es = elists();
  $row = [];
  foreach ($es as $key => $e) {
    # code...
    $row[$e->name] = $e->name;
  }
  return $row;
}