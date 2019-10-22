<!doctype html>
<?php 
header("Content-Type: text/html; charset=utf-8");
require_once("connMysql.php");

if(isset($_GET["action"])&&($_GET["action"]=="hits")){	
	$query_hits = "UPDATE `album` SET `al_hits`=`al_hits`+1 WHERE `album_id`=".$_GET["id"];
	mysqli_query($link,$query_hits);
	header("Location: albumshow2a.php?id=".$_GET["id"]);
}
//預設每頁筆數
$pageRow_records = 16;
//預設頁數
$num_pages = 1;
$con=true;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}

//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
//$query_RecAlbum = "SELECT `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`,`album`.`album_section`,`album`.`album_phone`,`album`.`album_address`,`album`.`album_traffic` , `albumphoto`.`ap_picurl`, count( `albumphoto`.`ap_id` ) AS `albumNum` FROM `album` LEFT JOIN `albumphoto` ON `album`.`album_id` = `albumphoto`.`album_id` GROUP BY `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc` ORDER BY `album_date` DESC";
$query_RecAlbum = "SELECT `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`,`album`.`album_section`,`album`.`album_phone`,`album`.`album_address`,`album`.`album_traffic` , `albumphoto`.`ap_picurl` ,`album`.`al_hits`,`album`.`al_dis`, count( `albumphoto`.`ap_id` ) AS `albumNum` FROM `album` , `albumphoto` where `album`.`al_dis` =1 AND `album`.`album_id` = `albumphoto`.`album_id` GROUP BY `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc` ORDER BY `album_section` DESC";

//if($flag==1)
//$query_RecAlbum ="SELECT `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`,`album`.`album_section`,`album`.`album_phone`,`album`.`album_address`,`album`.`album_traffic` , `albumphoto`.`ap_picurl` FROM `album` ,`albumphoto` WHERE (`album`.`album_id` = `albumphoto`.`album_id`) AND `album`.`album_section`=".$_GET["alsec"];
//$query_RecAlbum = "SELECT `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`,`album`.`album_section`,`album`.`album_phone`,`album`.`album_address`,`album`.`album_traffic` , `albumphoto`.`ap_picurl`, count( `albumphoto`.`ap_id` ) AS `albumNum` FROM `album` LEFT JOIN `albumphoto` ON `album`.`album_id` = `albumphoto`.`album_id` AND `album`.`album_section`=".$_GET["alsec"]. "GROUP BY `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`";
	//$query_RecAlbum = "SELECT `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`,`album`.`album_section`,`album`.`album_phone`,`album`.`album_address`,`album`.`album_traffic` , `albumphoto`.`ap_picurl`, count( `albumphoto`.`ap_id` ) AS `albumNum` FROM `album` LEFT JOIN `albumphoto` ON `album`.`album_id` = `albumphoto`.`album_id` AND `album_section`=" . $_GET["alsec"];
	//$query_RecAlbum = "SELECT `album`.`album_id` , `album`.`album_date` , `album`.`album_location` , `album`.`album_title` , `album`.`album_desc`,`album`.`album_section`,`album`.`album_phone`,`album`.`album_address`,`album`.`album_traffic` , `albumphoto`.`ap_picurl`  FROM `album` ,`albumphoto` where `album_section`=" . $_GET["alsec"]. " AND `album`.`album_id` = `albumphoto`.`album_id`";
	//$flag==0;
//

//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecAlbum = $query_RecAlbum." LIMIT ".$startRow_records.", ".$pageRow_records;
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecAlbum 中
$RecAlbum = mysqli_query($link,$query_limit_RecAlbum);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecAlbum 中
$all_RecAlbum = mysqli_query($link,$query_RecAlbum);
//計算總筆數
$total_records = mysqli_num_rows($all_RecAlbum);
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>twaaphp</title>
	<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">    
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    .dropdown-toggle{
    font-size:16px;
    }
    .bg{
	background-image:url(ss6.png);
	//background-position:left top,right top;
	//background-repeat:repeat,repeat;
	
	}
	.heading{
		text-align:center;
		color:white;
		font-size:20px;
	}
	.small{}
	.stp{}
	
   .imgStep1 {
	border-style: none;
	
	padding-right: 0px;
	//background-color: #448aca///blue	
   }
   .imgStep2 {
	border-style: none;
	padding-left: 20px;
	
	//background-color: #448aca///blue	
   }
   .normalDiv2 {
	font-family: "微軟正黑體";
	font-size: 20pt;
	font-weight: bold;
	clear: both;
	color: white;
	
}
   #toTop {
	display: none;
	text-decoration: none;
	position: fixed;
	bottom: 14px;
	right: 3%;
	overflow: hidden;
	width: 40px;
	height: 40px;
	background: url("./img/to-top1.png") no-repeat ;
  }
  td{
	  overlflow:hidden;
  }
  tr td img{
	border:1px solid #000000;  
  }
  </style>
<script>  
  var Pstart = 8;

function plusnum ()
  {
  Pstart ++;
  if (Pstart > 30) Pstart = 0;
  }

function showit()  //onload="showit();"
{
  var imgaPoint = "";

   for (i=0; i<9; i++)
    {
    plusnum ();
    imgaPoint = "IMGA" + i;
    document.images[imgaPoint].src = "img/imgg" + Pstart + ".JPG";
    }
    //plusnum ();

    TimerID = setTimeout ("showit()",3000);
}
  var i=0,pic=8, big=0;
  var go=true;
  $(function(){
        var timer;    // 計時器變數
        var i=0,j=0,gt;
        // 設定縮圖的按下事件處理函式
        $('.small').click(function(){
			
             clearTimeout(timer);    // 清除計時器
             var cur=$('.small')[i];
			 $(cur).fadeOut(800);
			 i=i+1;
			 if(i>8){i=0;j=(j+1)%9;}
			 pic ++;
             if (pic > 30) pic = 0
			 $(cur).attr('src', "img/imgg"+pic+".JPG").fadeIn(2000);
			 if(go==true){
               timer=setTimeout(function(){
                $('.small')[i].click()
               }, 4000); } 
        }); // $('.small').click() 敘述的結尾 
        $('.stp').mouseover(function(){
             if(go==false) go=true; else go=false;
        }); // $('.small').click() 敘述的結尾 
		 
        //一開始先選第一張縮圖
        $('.small')[i].click();
		
		$('').bind({
	     mouseover:function(){if(big==0){
	      $(this).animate({
				width: '+=6px',
				height: '+=6px',
			}, 
			700, 
			function() {
			  $(this).animate({
				width: '-=6px',
				height: '-=6px',
			  }, 
			  300, 
			  function() {
			  
			  });
			}); }}
	  		
     });
		
      });
</script>
</head>

<body  >

<div class="container-fluid">
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12">
  <!-- 相片輪播元件 -->
  <div id="photoCarousel" class="carousel slide " data-ride="carousel" style="height:60%">
  <!-- Indicators 瀏覽控制 --> 
  <ol class="carousel-indicators" style="margin-top:20px">
    <li data-target="#photoCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#photoCarousel" data-slide-to="1"></li>
    <li data-target="#photoCarousel" data-slide-to="2"></li>
	<li data-target="#photoCarousel" data-slide-to="3"></li>
    <li data-target="#photoCarousel" data-slide-to="4"></li>
  </ol>
  
  <!-- 建立相片清單 -->
  <div class="carousel-inner">
    <div class="item  active">
      <img src="photos3/car1.jpg">
      <div class="carousel-caption">
	    <div class="normalDiv2">台灣旅遊導覽網</div>  
	  </div>
    </div>
    <div class="item">
      <img src="photos3/car2.jpg">
      <div class="carousel-caption">
	    <div class="normalDiv2">台灣旅遊導覽網</div>  
	  </div>	  
    </div>
    <div class="item">
      <img src="photos3/car3.jpg">
      <div class="carousel-caption">
	    <div class="normalDiv2">台灣旅遊導覽網</div>  
	  </div>	  
    </div>
	<div class="item">
      <img src="photos3/car4.jpg">
	  <div class="carousel-caption">
	    <div class="normalDiv2">台灣旅遊導覽網</div>  
	  </div>
    </div>
	<div class="item">
      <img src="photos3/car5.jpg">
	  <div class="carousel-caption">
	    <div class="normalDiv2">台灣旅遊導覽網</div>  
	  </div>
    </div>
  </div>
  <!-- 上一張/下一張控制 --> 
  <a class="left carousel-control" href="#photoCarousel" data-slide="prev">
  <span class="glyphicon glyphicon-chevron-left"></span></a>
  <a class="right carousel-control" href="#photoCarousel" data-slide="next">
  <span class="glyphicon glyphicon-chevron-right"></span></a>
  </div>
 </div>
 
</div>
</div>


<div class="container-fluid" style="margin-top:-20px" >    
<!-- 巡覽列, navbar-default 表示白底黑字樣式 -->
<nav class="navbar navbar-inverse">
  <!-- 選單表頭區塊 -->
  <div class="navbar-header">
    <a class=" navbar-brand heading" href="./phpalbum/loginB.php" style="color:yellow">網站管理</a>
	<a class=" navbar-brand heading" href="./phpalbum/index1a.php" style="color:red">熱門景點</a>
    <!-- 按鈕 -->
    <button type="button" class="navbar-toggle" 
            data-toggle="collapse" data-target="#mynav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
  </div>
  <!-- 選單 -->
  <div id="mynav" class="navbar-collapse collapse">
    <ul class="navbar-nav nav">
	  <li style="margin-left:-8px"><a class="dropdown-toggle heading" href="./aa/index2.html">景點影片</a></li>
      <li style="margin-left:-8px;"><a href="#" class="dropdown-toggle heading" data-toggle="dropdown">  <!-- 選單項目 4：下拉子選單 -->
            <span class="glyphicon glyphicon-info-sign"></span>北部地區<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li><a href="phpalbum/index2a.php?alsec=基隆" title="基隆旅遊">基隆</a></li>
           <li><a href="phpalbum/index2a.php?alsec=台北" title="台北旅遊">台北</a></li>
           <li><a href="phpalbum/index2a.php?alsec=新竹" title="新竹旅遊">新竹</a></li>
           <li><a href="phpalbum/index2a.php?alsec=桃園" title="桃園旅遊">桃園</a></li>
          </ul>
      </li>
	  <li style="margin-left:-8px"><a href="#" class="dropdown-toggle heading" data-toggle="dropdown">  <!-- 選單項目 4：下拉子選單 -->
            <span class="glyphicon glyphicon-info-sign"></span>中部地區<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li><a href="phpalbum/index2a.php?alsec=苗栗" title="苗栗旅遊">苗栗</a></li>
           <li><a href="phpalbum/index2a.php?alsec=台中" title="台中旅遊">台中</a></li>
           <li><a href="phpalbum/index2a.php?alsec=彰化" title="彰化旅遊">彰化</a></li>
           <li><a href="phpalbum/index2a.php?alsec=南投" title="南投旅遊">南投</a></li>
           <li><a href="phpalbum/index2a.php?alsec=雲林" title="雲林旅遊">雲林</a></li>
          </ul>
      </li>
	  <li style="margin-left:-8px"><a href="#" class="dropdown-toggle heading" data-toggle="dropdown">  <!-- 選單項目 4：下拉子選單 -->
            <span class="glyphicon glyphicon-info-sign"></span>南部地區<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li><a href="phpalbum/index2a.php?alsec=嘉義" title="嘉義旅遊">嘉義</a></li>
           <li><a href="phpalbum/index2a.php?alsec=台南" title="台南旅遊">台南</a></li>
           <li><a href="phpalbum/index2a.php?alsec=高雄" title="高雄旅遊">高雄</a></li>
           <li><a href="phpalbum/index2a.php?alsec=屏東" title="屏東旅遊">屏東</a></li>
          </ul>
      </li>
	  <li style="margin-left:-8px"><a href="#" class="dropdown-toggle heading" data-toggle="dropdown">  <!-- 選單項目 4：下拉子選單 -->
            <span class="glyphicon glyphicon-info-sign"></span>東部地區<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li><a href="phpalbum/index2a.php?alsec=宜蘭" title="宜蘭旅遊">宜蘭</a></li>
           <li><a href="phpalbum/index2a.php?alsec=台東" title="台東旅遊">台東</a></li>
           <li><a href="phpalbum/index2a.php?alsec=花蓮" title="花蓮旅遊">花蓮</a></li>
          </ul>
      </li>
	  <li style="margin-left:-8px"><a href="#" class="dropdown-toggle heading" data-toggle="dropdown">  <!-- 選單項目 4：下拉子選單 -->
            <span class="glyphicon glyphicon-info-sign"></span>離島地區<b class="caret"></b></a>
          <ul class="dropdown-menu">
           <li><a href="phpalbum/index2a.php?alsec=澎湖" title="澎湖旅遊">澎湖</a></li>
           <li><a href="phpalbum/index2a.php?alsec=金門" title="金門旅遊">金門</a></li>
           <li><a href="phpalbum/index2a.php?alsec=馬祖" title="馬祖旅遊">馬祖</a></li>
          </ul>
      </li>
    </ul>
	<form class="navbar-form navbar-right" id="form1" name="form1" method="get" action="phpalbum/index5a.php" >
            <div class="form-group has-feedback">
              <input  type="search" class="form-control" placeholder="關鍵字查詢" name="alde" id="alde" ondblclick="this.value=''">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
            <button type="submit" class="btn btn-default" >搜尋</button>
    </form>
  </div>
</nav>
</div> 
     <div class="container-fluid">
	  <h3>景點數量: <?php echo $total_records;?></h3>
       <?php while($con==true){ ?>
        <div class="row">
		<?php for($i=1;$i<=4;$i++) 
		  if($row_RecAlbum=mysqli_fetch_assoc($RecAlbum)){?>
		   <div class="col-xs-12 col-sm-6 col-md-3">
		    <div class="thumbnail" style="background:#000000; color:white;">
		      <img  style="width:100%; height:100%" class="img-responsive" src="phpalbum/photos/<?php echo $row_RecAlbum["ap_picurl"];?>" title="<?php echo $row_RecAlbum["album_title"];?>"/>
		      <div class="caption">
		        <h4 style="color:white;"><?php echo $row_RecAlbum["album_title"];?></h4>
		        <h5 style="color:white;"></a>點擊數:<?php echo $row_RecAlbum["al_hits"];?></h5>
		        <p><a href="phpalbum/albumshow2a.php?action=hits&id=<?php echo $row_RecAlbum["album_id"];?>" class="btn btn-primary">詳細內容</a></p>
		      </div>
		    </div>
		   </div>
		<?php }else{ $con=false; break;} ?>
		</div>  
       <?php }?>
	 </div>
	 <div class="container-fluid  table-responsive text-center">
       <table class="table">	    
        <tr>
		  <td>
		    <ul class="pagination pagination-sm">
              <li><a href="?page=<?php if($num_pages >1) echo $num_pages-1; else echo $num_pages; ?>"><span>&laquo;</span></a></li>
              <li <?php if($num_pages ==1) echo "class=\"active\"";?>><a href="?page=1">1</a></li>
		      <?php for($i=2;$i<=$total_pages;$i++){?>
               <li <?php if($num_pages ==$i) echo "class=\"active\"";?>><a href="?page=<?php echo $i;?>"><?php echo $i;?></a> </li>
              <?php }?>
              <li><a href="?page=<?php if($num_pages < $total_pages) echo $num_pages+1;else echo $num_pages;?>"><span>&raquo;</span></a></li>
             </ul>
		  </td>    
		</tr>
       </table>     
           
	 </div> 
	<!--
     <div class="container table-responsive">
	  <table class="table">	    
        <tr>
		  <td>
		   <p class="text-center">
             <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
               <a href="?page=1">|&lt;</a> <a href="?page=<?php echo $num_pages-1;?>">&lt;&lt;</a>
             <?php }else{?>
               |&lt; &lt;&lt;
             <?php }?>
		     <?php
  	          for($i=1;$i<=$total_pages;$i++){
  	  	       if($i==$num_pages){
  	  	  	     echo $i." ";
  	  	       }else{
  	  	         echo "<a href=\"?page=$i\">$i</a> ";
  	  	       }
  	          }
  	        ?>
            <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
              <a href="?page=<?php echo $num_pages+1;?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages;?>">&gt;|</a>
            <?php }else{?>
              &gt;&gt; &gt;|
            <?php }?>
		   </p>
		  </td>
		</tr>		
	  </table>
	</div>
   -->
  <!--回頂部動畫開始-->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>

<script type="text/javascript">
		$(document).ready(function() {
		$().UItoTop({ easingType: 'easeOutQuart' });
});
</script>
<a href="#"><span id="toTop"></span></a>
<!--回頂部動畫結束--> 
</body>

</html>