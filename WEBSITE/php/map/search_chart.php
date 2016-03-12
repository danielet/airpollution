<?php
	include ("../include.php");
	
  $start = $_POST[start_time];
	$end = $_POST[end_time];
	$kind = $_POST[pollution];
	$session_id = $_POST[session_id];
	$criteria = $_POST[creteria]; 
	

	
	
   $query = "select board_id from session where session_id = '$session_id'";
   $list = mysql_query($query);
   $arr = mysql_fetch_array($list);
   $board_id = $arr['board_id'];
   //시간 재정의
   if($criteria != "secondly"){
      $start = substr($start, 0,10)." 00:00:00";
      $end = substr($end, 0,10)." 23:59:59";
   }
   else
   {
      $start = substr($start, 0,17)."00";
      $end = substr($end, 0,17)."50";
   }
   
   //get device_id
   $query = "select fixed from sensorboard where board_id = '$board_id'";
   $list = mysql_query($query);
   $info = mysql_fetch_array($list);
   if($info['fixed'] != 0)
   {
      $query = "select file_name,path from file_info where session_id in (select session_id from sensorboard where board_id = $board_id);";
      $list2 = mysql_query($query);
      while($info2 = mysql_fetch_array($list2))
      {
         $arr = array($info2['file_name'], $info2['path']);
      
         
         
         //여기까지 해당 날짜에 해당하는 파일 이름, 경로를 뽑아오는 과정
         
         //색인 테이블에 csv파일의 정보를 입력.
         //1. 압축풀기
         //2. 파일읽기
         //3. DB테이블에 파일내용 insert
         //4. 파일지우기
         //5. DB테이블에서 날짜로 데이터 검색, 검색기준(년,월,일,...)
         //6. 차트로 만들기
         
         //1,2,3
         $totalname;
         $csv_filename;
         
         $name = $arr[0];
         $path = $arr[1];
            
         $totalname = $path.$name;
         /*
         $zip = new ZipArchive;
         if($zip->open($totalname)==TRUE)
         {
            $zip->extractTo($path);
            $zip->close();
         }
         
         $csv_filename = substr($totalname,0,strlen($totalname)-4).".csv";
          * */
         $csv_filename = $totalname;
         $filer = fopen($csv_filename, "r") or exit("Unable to open file!");
         
         while( !feof($filer) ) {
             $string = fgets($filer);
			 $arr2 = explode(',',$string);
             
             //echo $session_id." ".$arr2[0]." ".$arr2[1]." ".$arr2[2]." ".$arr2[3]." ".$arr2[4]." ".$arr2[5]." ".$arr2[6];
            
            $query = "select data_id from `index` where session_id = '".$session_id."' and time = '".substr($arr2[0],1,strlen($arr2[0])-2)."'";
            $list = mysql_query($query);
            
            
            if(!$r = mysql_fetch_array($list)){
               //$query = "insert into `index` (`session_id`,`time`,`co`,`so2`,`no2`,`o3`,`lat`,`lng`) values ('4','2016-02-11 11:11:11','1','2','3','4','5','6');";
               $query = "insert into `index` (`session_id`,`time`,`co`,`so2`,`no2`,`o3`,`pm2d5`,`temp`,`lat`,`lng`) values ('".$session_id."','".substr($arr2[0],1,strlen($arr2[0])-2)."','".substr($arr2[1],1,strlen($arr2[1])-2)."','".substr($arr2[2],1,strlen($arr2[2])-2)."','".substr($arr2[3],1,strlen($arr2[3])-2)."','".substr($arr2[4],1,strlen($arr2[4])-2)."','".substr($arr2[5],1,strlen($arr2[5])-1)."','".substr($arr2[6],1,strlen($arr2[6])-2)."','".substr($arr2[7],1,strlen($arr2[7])-2)."','".substr($arr2[8],1,strlen($arr2[8])-2)."');";
               mysql_query($query);
            }
      
         }
         fclose($filer);
         $count++;
         
         //4
         /*
          unlink($csv_filename); // csv file delecte
         $android_csv_filename = substr($totalname,0,strlen($totalname)-4)."a.csv";
         unlink($android_csv_filename);
         */
      }
      
      
      //5
      $sub = array();
      $interval = 1;
      // 기준에 따른 서브스트링 구간
      switch ($criteria) {
         //하루 권장
         case 'secondly':
            $sub[0] = 18;
            $sub[1] = 2;
            $sub[2] = 'second';
            $interval = 10;
            break;
         //5일 권장
         case 'minutely':
            $sub[0] = 15;
            $sub[1] = 2;
            $sub[2] = 'minute';
            break;
         //1년 권장
         case 'hourly':
            $sub[0] = 12;
            $sub[1] = 2;
            $sub[2] = 'hour';
            break;
         // 25년 권장
         case 'daily':
            $sub[0] = 9;
            $sub[1] = 2;
            $sub[2] = 'day';
            break;
         // 800년 이상
         case 'monthly':
            $sub[0] = 6;
            $sub[1] = 2;
            $sub[2] = 'month';
            break;
         case 'yearly':
            $sub[0] = 1;
            $sub[1] = 4;
            $sub[2] = 'year';
            break;
      }
      $lim = $sub[0]+$sub[1]-1;
      
      if($kind != "all"){
         $query = "select substr(t1.time,1,'$lim') time, round(avg($kind),1) $kind from `index` right outer join (select  dt.time
                   from    (
                                 select   '$start' + INTERVAL (('$interval' * a.a) + ('$interval' * 10 * b.a) + ('$interval' * 100 * c.a)+('$interval' * 1000 *d.a)) $sub[2] as time
                                 from     (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                                           cross join  (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                                           cross join  (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
                                           cross join  (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as d
                                           ) dt
                                 where dt.time between '$start' and '$end') t1 on substr(index.time,1,'$lim') = substr(t1.time,1,'$lim')
                   where session_id = '$session_id' or session_id is null group by substr(time,1,'$lim') order by t1.time";
      }
      else
      {
         
      }
      $list = mysql_query($query);
   
       
       
      //6
      $rows = array();
       $table = array();
      
      $table['cols'] = array(
   
           array('label' => 'Time', 'type' => 'string'),
           array('label' => $kind, 'type' => 'number'),
   
       );
      
      while($r = mysql_fetch_array($list))
      {
         $temp = array();
   
             // the following line will be used to slice the Pie chart
   
             $temp[] = array('v' => $r['time']); 
   
             // Values of each slice
   
             $temp[] = array('v' =>  $r[$kind]); 
           
             $rows[] = array('c' => $temp);
      }
      $table['rows'] = $rows;
      echo json_encode($table);
   }
   else
   {
      $rows = array();
       $table = array();
      
      $table['cols'] = array(
   
           array('label' => 'Time', 'type' => 'string'),
           array('label' => 'This sensor is not fixed', 'type' => 'number'),
   
       );
       $table['rows'] = $rows;
      echo json_encode($table);
   }

?>










