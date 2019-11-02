<?
include "env.php";
include "lib.php";
include "config_data.php";
include "option_data.php";
include "mtype_plugin/extend_lib.php";
include "mtype_plugin/db_admin.php";
include "KDM_skin_data.php";
include "KDM_fontcol_data.php";
include "KDM_tb_data.php";

header ("Pragma: no-cache");

$ad_ico = "<img src='$ad_icon' border='0' onerror=\"this.style.display='none';\">";
$maxleng_w = strlen($max_width);
$maxleng_h = strlen($max_height);
$emowidth = $cfg_emolist*72; //사용하시는 이모티콘의 가로 사이즈가 클 경우 곱셈 값을 올리세요.

//비공개 게시판 모드
if($mem_login=='on')
	{
		if($memberlogin == $cfg_member_passwd);
		else
			{
			gourl("./admin.php?member=1");
			exit;
			}
	}

if($memberpasswd === $cfg_member_passwd)
{
  setcookie ("memberlogin",$memberpasswd,0);
  $isMember = 1;
}
else $isMember = 0;
	// 관리자 패스워드쿠키가 있으면서 관리자암호와 같으면 관리자모드임
if($ckadminpasswd == $cfg_admin_passwd && $ckadminpasswd !="")
{
	$isAdmin = 1;
}

if($cfg_admin_passwd=="")
{
	print "관리자 패스워드가 설정되어있지 않거나 'env.php' 파일이 읽어지지 않았습니다.";
	exit();
}

print "<!--MM BToolBBS Version $BBS_VERSION-->\n";

if(file_exists($dbindex)){
//------M타입 페이지바 계산

  $res=readlock(); //env. 현재 쓰기중이 아닌 상태에서만 read.
  if($res != 1)
  {
	  print"락 해제에 실패하였습니다.";
  	exit();
  }

  if($pagebar_type=="on"){
    $temp_to = 0;
    $fp = fopen ("$dbindex", "r");
    if($fp){
      while(!feof($fp)) {
        $buffer = trim(fgets($fp, 4096));
        if ($buffer!=""&&(!($temp_to%$cfg_pic_per_page) || $temp_to==0)) { $page_arr[]=$buffer; }
        $temp_to++;
      }
      $total = $page_arr[0];
      fclose($fp);
    }// 언제든지삭제될수있음
  
    $num = intval($num);
    if($num==0)$num=$total;
    $topnum = 0;
  
    $page_bar = mmb_page_bar($num, $cfg_bar_per_page, $page_arr);
  }
//------mmb1 페이지바 계산
  else{
    $fp = fopen ("$dbindex", "r");
    if($fp){
      $buffer = chop(fgets($fp, 4096));
      $total = $buffer;
      fclose($fp);
    }// 언제든지삭제될수있음

    $num = intval($num);
    if($num==0)$num=$total;
    $topnum = 0;
  }
}
?>

<html>
<head>

<STYLE>
BODY { scrollbar-3dlight-color:666666;
scrollbar-arrow-color:666666;
scrollbar-track-color:#000000;
scrollbar-darkshadow-color:666666;
scrollbar-face-color:#000000;
scrollbar-highlight-color:#000000;
scrollbar-shadow-color:#000000}
</STYLE>

<title><?=$browser_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel=StyleSheet HREF=style.css type=text/css title=style>

<!-- 모바일 출력 추가 -->
<link rel="stylesheet" media="handheld, screen and (max-device-width: 800px)" type="text/css" href="mobile800under.css"  /> 
<link rel="stylesheet" media="only screen and (min-device-width : 320px) and (max-device-width: 480px)" type="text/css" href="mobile800under.css"  />
<!-- ▲가로 800px 이하의 기기에서는 그림 td 너비를 100%로 하여 그림이 가운데 정렬되도록 한다 -->

<link rel="stylesheet" media="handheld, screen and (max-device-width: 800px)" type="text/css" href="mobile.css"  />
<link rel="stylesheet" media="only screen and (min-device-width : 320px) and (max-device-width: 480px)" type="text/css" href="mobile.css"  />
<link rel="stylesheet" media="only screen and (-webkit-device-pixel-ratio: 0.75)" type="text/css" href="mobile.css"  />
<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio:1.5)" type="text/css" href="mobile.css"/>
<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="mobile.css"/>
<meta name="HandheldFriendly" content="True" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
<!-- 모바일 출력 추가 끝 -->

<!-- lightbox ST -->
<script src="lightbox/js/jquery-1.7.2.min.js"></script>
<script src="lightbox/js/lightbox.js"></script>
<link href="lightbox/css/lightbox.css" rel="stylesheet" />
<!-- lightbox ED -->			

<? include ("style_Hatti.php"); ?><!-- 테마색 삽입 -->

</head>

<script type="text/javascript" src="js/js_input.js" charset='utf-8'></script>
<script type="text/javascript" src="./js/FileButton.js"></script>
<script type="text/javascript">
var myFileButton = new FileButton("imageswap", "imagesrc");
window.onload = function () {
    //myFileButton.run();
}
</script>
<script language="javascript">
function selectok(tooltype){
  formdraw.choose.value = tooltype;
  formdraw.submit();
}
</script>
<body background="<?=$bgurl?>" bgcolor="<?=$bgcol?>" text="<?=$b_fo?>" link="<?=$li_fo?>" vlink="<?=$vi_fo?>" alink="<?=$ac_fo?>">

<!---------전체 테이블 생성-->
<table id=cellMMB cellpadding="0" cellspacing="0" align="center" valign="top" bgcolor='<?=$alltb_bgc?>'>
<? if($upteble =='on'){ // 디폴트로 꺼져 있길래 걍 켰다  ?>
<? }  else { //상단메뉴형 테이블일 경우?>

<!---------↑↑↑↑↑얘가 전체테이블 아래는 상단테이블소스-->
	<tr><td bgcolor="<?=$toptb_bgc?>" align="center" valign="bottom" style="border:<?=$menutb_bor?> solid <?=$menutb_borc?>;">

<!----↓↓↓공지~동영상 업로드 부분은 아래의 소스를 수정함으로서 따로 모양을 낼 수 있습니다. 현재는 메뉴td와 같은 속성 적용중 :)-->

		<table width=100% border=0 cellpadding=0 cellspacing=0>
		<tr><td valign=top>
		
			<? notice($notice); ?>
		
		</td>
		<td align=right valign=top>
		
		<? if($isAdmin==0) // 방문자에게는 번잡한 로딩 화면을 가린다  
		print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<span style=color:$kd_morecol;>▲</span>': '<span style=color:$kd_morecol;>▼</span>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol;'>▼</span></a><div style=\"display: none;\">\n";
		?>
		
			<? if($img_mode=="off" || $isAdmin==1) { ?>

				<form name="form1" method=post action="upload.php" enctype="multipart/form-data" style="margin:0; padding:0;">
					<? if($upimg=="on") { ?>
						<input type="text" name="filevalue" style="width:<?=$menu_width?>px; height: 15px; font-size:9px; color:<?=$beu_te_fontcol?>; border:none;" readonly ><!--//그림 위에 파일 위치 보여주는 텍스트박스입니당 :)-->
						<script type="text/javascript">
							myFileButton.write('<input type="file" name="userfile" imageswap="true" imagesrc="image/upl.jpg" onmousedown="this.parentNode.style.backgroundPosition=\'2px 2px\';" onmouseup="this.parentNode.style.backgroundPosition=\'0px 0px\';" onchange="this.form.filevalue.value=this.value;"/>');
						</script>
						<input type='submit' name='submit' value="UPLOAD" class=buttonMMB>
						<?
						if($isAdmin==1) print "<input type='password' name='passwd' value='$ckadminpasswd' style='display:none;' >";
						else print "<input type='password' name='passwd' size='2' style='font-size:7pt; color:$beu_te_fontcol; background:transparent; border:none; border-bottom:$beu_te_bordercol 1px solid; text-align:center; height: 15px;' value='$ckpass'>";
					 } else {?>
						<input type='file' size='2' name='userfile' style=" font-size:10px; color:<?=$beu_te_fontcol?>; border-style:none; text-align:center; background-color:<?=$beu_te_bgcol?>;">
						<?
						if($isAdmin==1) print "<input type='password' name='passwd' value='$ckadminpasswd' style='display:none;' >";
						else print "<input type='password' name='passwd' size='2' style='font-size:7pt; color:$beu_te_fontcol; background:transparent; border:none; border-bottom:$beu_te_bordercol 1px solid; height: 15px;' value='$ckpass'>";
						?>
						<input type='submit' name='submit' value="UPLOAD" class=buttonMMB>
					<? } ?> 
						<br>
							<input type='checkbox' name='loadFold' >FOLD
							<input type='checkbox' name='loadAdmin' >ⓐ
							<input type='checkbox' name='loadMember' >ⓜ
							<input type='checkbox' name='loadWidth' >↔
							<input type='checkbox' name='loadWidthWide' >⇔
							<input type='checkbox' name='loadHeight' >↕
					</form>
				<? if($isAdmin==1){ ?>
					<form name="dong" method=post action="mupload.php" enctype="multipart/form-data" style="margin:0; padding:0;">
						<input type="text" name="mov" style="width:140px; height:15px; color:<?=$beu_te_fontcol?>; font-size:10px; border:none; border-bottom:1px solid #ddd; background: transparent;">
						<?
						if($isAdmin==1) print "<input type='password' name='passwd' value='$ckadminpasswd' style='display:none;' >";
						else print "<input type='password' name='passwd' size='4' style='font-size:7pt; color:$beu_te_fontcol; BORDER-RIGHT:none; BORDER-LEFT:none; BORDER-TOP:none; BORDER-BOTTOM:$beu_te_bordercol 1px solid; height: 15px;' value='$ckpass'>&nbsp;";
						?>
						<input type='submit' name='submit' value="TAG" class=buttonMMB><input type='checkbox' name='loadFold' >FOLD
					</form>
					<form name="search" method="get" action="./search.php" style="margin:0; padding:0;">
							<input type="text" name="keyw" size="8" style="color:#888; background-color:transparent; border:0px; border-bottom:1px solid #ccc;"><input type="submit" name="Submit3" value="SEARCH" class="buttonMMB">
					</form>
				<? } ?>
			
			<? 
			} else print "\n" // 그림작성을 관리자전용으로 해뒀을 시, 접힌 부분 안에 빈칸만을 출력한다
			?> 

			<? if($isAdmin==0) // 방문자에게는 번잡한 로딩 화면을 가린다 
			print "</div>\n";
			?>
			
			<? if ($isAdmin==0) {
				echo "<form class=login name='admin' method='post' action='admin.php' style='margin:0; padding:0;'>
				LOGIN
				<input type='password' name='adminpasswd'  size='2'  style='color:333333; background-color:$toptb_bgc; border-width:1pt; border-color:#333333; border-style:none; border-bottom-style:solid;'>
				 <input type='submit' name='Submit' value='확인' style='font-size:8pt; color:333333; background-color:fff7e7; border-width:1pt; border-color:#333333; border-style:solid; display:none;'>
				</form>";
				}
			?>		
				
	</td></tr>
	<tr><td colspan=2 align=center>
	
		<div style='border:4px solid transparent;'></div> <!-- 코멘트들 사이에 흰 줄 넣기 -->
		<ul class=MMB>					
			<li class=menuMMB><a href = "./index.php">REFRESH</a> |</li>
			<? print "<li class=menuMMB><a href = 'recent.php'>RECENT$cfg_recent</a> |</li>"; ?>
		<?
			if ($isAdmin==1) {
				print "<li class=menuMMB><a href = 'admin_config.php'>OPT</a> |&nbsp;</li>";
				print "<li class=menuMMB><a href = 'kd_config.php'>KD</a> |&nbsp;</li>";
				print "<li class=menuMMB><a href = 'admin.php?logout=on'>LOGOUT</a> |&nbsp;</li>";
				}
		else { }
		?>

			<li class=menuMMBPage><? print "$page_bar"; ?></li>	
		</ul>

	</td>
	</tr></table>
	<br>
	
</td></tr></table>
<?
}
//상단메뉴 닫음


//--------------------비툴테이블 시작

if(!file_exists($dbindex)){
  die("MMB $BBS_VERSION 신규 설치를 확인합니다. 관리자 로그인 뒤, 환경설정을 먼저 끝마쳐 주세요.");
}

$intbl = 0; // 테이블이 열려있는지 여부
$cp = fopen("option_list.php", "r");
while(!feof($cp)) {
  $first_arg = trim(fgets($cp, 4096));
  $second_arg = trim(fgets($cp, 4096));
  $option_list[$first_arg] = $second_arg;
}
fclose($cp);

reset($option_list);
while($option_onff = each($option_list)){
  ${"img_".$option_onff["key"]} = $$option_onff["key"];
}

//--------dbindex에서 pixcnt 추출

$fp = fopen("$dbindex","r");
if($fp)

$page_count = 0;
$dbbrk=0;
$cnt=0;

while(!feof($fp))
{
  $buffer = fgets($fp, 4096);
  $lognum[$cnt++] = $buffer= chop($buffer);
  
  if($num==0) $num=$buffer;
  if($topnum==0)$topnum=$buffer;
	if($buffer > $num){
    $cnt--;
    continue;
  } // 아직 번호에 도달하지못하면 스킵

  $page_count++;
  if($page_count > $cfg_pic_per_page) break;
}
fclose($fp);


//--------dbdata 출력 시작

for($cnt=0;$cnt<$cfg_pic_per_page;$cnt++){

  $dbnum = $lognum[$cnt]%100;
  $dbfile = "$datafo/$dbnum.dat";
  //dbfile 선택

  if(!file_exists($dbfile)){  $dbbrk=1; continue;  }
  $fp = fopen("$dbfile","r");
  while(!feof($fp))
  {
	  $buffer = fgets($fp, 4096);
  	$buffer = chop($buffer);

	  if(substr($buffer,0,1)==">") // 라인의 제일 앞에 '>'가 있으면 그림임
  	{
	  	if($intbl==1)
  		{
			$intbl = 0;
		}
  		$buffer = substr($buffer,1);
	  	$data = explode("|", $buffer);
		  list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
  		// 그림번호, 파일명, 암호화된패스워드, 등록시간, 호스트네임, IP

   		if($picno!=$lognum[$cnt])
		{
   		  continue; //lognum가 dbfile의 picno 와 다르면 같을때까지 스킵
   		}
    	if(!file_exists("$picfo/$picfn"))continue; // 그림이 없으면 스킵

    	// 작업시간을 시분초 단위로 변환
  		$strjtime = sprintf("%d시간 %d분 %d초",$sec/3600,($sec/60)%60,$sec%60);
	  	if($sec<3600)$strjtime = sprintf("%d분 %d초",($sec/60)%60,$sec%60);
		if($sec<60)$strjtime = sprintf("%d초",$sec%60);
  		if($sec<=0)$strjtime = "알 수 없음";
	  	$vhchoice = @GetImageSize("$picfo/$picfn");

		//--------------------------그림테이블

if($isAdmin==1)
				{
				echo("
				<div align=right>
					<form name='mdel' method='post' action='multidel.php'>
					<input type='submit' name='Submit' value='delete' style=' font-family:Tahoma; font-size:8; color:$beu_bt_fontcol; background-color:eeeeee; border-width:1; border-style:solid; border-color:666666;' >
				</div>
					");
				}
	
print "<TABLE id=cellMMB width=100% bgcolor='$logtb_bgc' style='border:$logtb_bor solid $logtb_borc;' CELLSPACING='0' CELLPADDING='0' align=center>
		<tr><TD valign=top>"; // 그림과 코멘트 테이블 스타트
		
	if(!$mov){ // !$mov에서 좁은 로그일 경우, 넓은 로그일 경우, 그리고 !$mov가 아닌 텍스트로그일 경우 세 가지 경우를 상정한다
	
		if($vhchoice[0] > $max_width_comment) { // 넓은 로그일 경우
		print "<div class=numMMB style='background:$pic_number;'>
		<a href='delete.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>&nbsp;#$picno</span></a>
		</div>";	
		print "<div class=picTd";
		
		if($vhchoice[0] > $max_width_comment) { // 넓은 로그일 경우, 모바일대응으로 교체하면서 picTd가 float:left라서 로그가 왼쪽으로 쓸려가는 현상이 생김. 그걸 막기 위함
		print "style='width:100% !important;";	
		}
		
		print ">"; // 그림칸 내용을 통째로 묶는다 
		print "<div class='wideLog'>";	// 넓은 로그일 시 안쪽에 div를 별도로 마련한다
		}
		
		else { // 좁은 로그일 경우
		print "<div class=numMMB style='background:$pic_number;'>
		<a href='delete.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>&nbsp;#$picno</span></a>
		</div>";	
		print "<div class=picTd>"; // 그림칸 내용을 통째로 묶는다 
		
		}
	}
  	
	else { // 텍스트로그일 경우
		print "<div class=numMMB style='background:$pic_number;'>
		<a href='delete.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>&nbsp;#$picno</span></a>
		</div>
		"; // 번호표를 그림칸에 넣어서 코멘트td 쪽의 혼란을 줄인다 + 그림 있을 시와는 달리, 
		print "<div class=picTdText>"; // $mov가 유효할 때도 else 붙여서 출력해야 텍스트로그일 때 번호표가 안 없어진다... 그림칸 내용을 통째로 묶어본다 

	}
		reset($option_list);
		$crt = "&#13;";
		$optcnt=0;
		while($option_onff = each($option_list)) {
			if ($optcnt>2) break;
			$optcnt++;
			$option_key = explode("_", $option_onff["key"]);
			$alt = ($$option_onff["key"]=="on") ? $alt.$option_onff["value"]." : ".${$option_key[1]} : $alt;
			if($optcnt<3)    $alt = ($$option_onff["key"]=="on") ? $alt.$crt : $alt;
		}

	
		include("mov.php"); //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// mov.php 별도삽입
	
			
		// 여기부터 그림삭제 체크버튼
		print "<div align=left>";
		if($restrict_del != "on"){
			print "";
	  	if($isAdmin!=1) print "";
		  else  print "<input type='checkbox' name='delpic[]' value='$picno'>\n";
		}
		print "</div>";


		reset($option_list);
		$crt = "&#13;";
		$optcnt=0;
		while($option_onff = each($option_list)) {
			if ($optcnt>2) break;
			$optcnt++;
			$option_key = explode("_", $option_onff["key"]);
			$alt = ($$option_onff["key"]=="on") ? $alt.$option_onff["value"]." : ".${$option_key[1]} : $alt;
			if($optcnt<3)    $alt = ($$option_onff["key"]=="on") ? $alt.$crt : $alt;
		}
		// 여기까지 그림삭제 체크버튼 
		
		print "</div>"; // 그림 div 닫음
		
		// 이 아래부터 코멘트
		if(!$mov){
			// 만일 그림의 가로크기가 지정 크기 이상이면 코멘트를 그림 밑으로 표시한다.
			if($vhchoice[0] < $max_width_comment) print "<table border=0><td class='forMobile commentTd'>";	 // 좁은로그일 시
			else print "<table width=100% border=0><td class='forMobile commentTdWide'>"; // 넓은로그일 시
		}
		else print "<table width=100% border=0><td class='forMobile commentTdText'>"; // 텍스트로그일 시
		global $mov2;
		$mov2 = $mov;
  		$intbl = 1;
  	}
	  else //글임
  	{
	  	if($intbl!=1)continue;
		  $data = explode("|", $buffer);
  		list($autname,$comment,$rtime,$ip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $data;
  		// 작성자명,글내용,등록시간,IP,패스워드
  		if($comment=="")continue;
		

		if($kd_col == 'on'){
			if($kd_replt == 'on') print "<div style='background:$repl_bgcol; padding:0 0 0 5px; border-left: 2px solid $replt_text;'><font style='color:$replt_text; font-size:$ad_namesize;'><b>RE: </b></font><font style='color:$comm_ad_namecol; font-size:$ad_namesize;'>";//답글 테이블의 좌우 여백을 없애고 싶으면 margin의 20을 0으로 해주세요:)
			else print "<div style='background:$comm_adbgcol; margin:0;'><font style='color:$comm_ad_namecol; font-size:$ad_namesize;'>";
		}
		else {
			if($kd_replt == 'on') print "<div style='background:$repl_bgcol; padding:0 0 0 5px; border-left: 2px solid #555;'><font style='color:$replt_text; font-size:$ad_namesize;'><b>RE: </b></font><font style='color:$comm_cu_namecol; font-size:$cu_namesize;'>";//답글 테이블의 좌우 여백을 없애고 싶으면 margin의 20을 0으로 해주세요:)
			else print"<div style='background:$comm_cuscol; padding-left:$cu_textpadding;'><font style='color:$comm_cu_namecol; font-size:$cu_namesize;'>";
		}
		$autname = emote_ev($autname, $emote_table);
		print "<b>$autname</b>&nbsp;</font>";
		
  		$comment = str_replace("%7C","|",$comment);
	  	//$comment = del_html($comment);
		$comment = autolink($comment);
  		$comment = emote_ev($comment, $emote_table);

	  	$altdate = date("Y년 m월 d일 H시 i분 s초",$rtime);

		if($kd_col == 'on'){ 
			print "<font title='$altdate'>";
			print "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;font-size:8px;'>";
			print date("ymd*H:i",$rtime)."&nbsp;</span></font>\n";
		} else {
			print "<font title='$altdate'>";
			print "<span style='color:$comm_cu_datecol; font-family:tahoma; font-size:8px;'>";
			print date("ymd*H:i",$rtime)."&nbsp;&nbsp;</span></font>";
		}

		$autname=urlencode($autname);//유니코드 해결

		if($restrict_del == "on" && $isAdmin !="1"){ print "<br>";}
		else{
			if($kdreply_mode != "on" || $isAdmin =="1"){if ($kd_replt != 'on'){
				echo "<a href=\"reply1.php?num=$picno&name=$autname&time=$rtime\">";
				if($kd_col == 'on') print "<span style='color:$comm_ad_datecol; font-size:8px;'>RE </span></a>";
				else print "<span style='color:$comm_cu_fontcol; font-size:8px;'>RE </span></a>";
				}
			}
			else {
			print "";	
			}
		
		echo "<a href=\"mod.php?num=$picno&name=$autname&time=$rtime\">";
		if($kd_col == 'on') {
			if($isAdmin==1) print "<span style='color:$comm_ad_datecol; font-size:8px;'>M </span></a>";
			else print "\n";
		}
		else print "<span style='color:$comm_cu_fontcol; font-size:8px;'>M </span></a>";
		if($isAdmin!=1){
			echo "<a href=\"delete.php?num=$picno&name=$autname&time=$rtime\">";
			if($kd_col == 'on') print "</a><br>\n";
			else print "<span style='color:$comm_cu_fontcol; font-size:11px;'>× </span></a><br>\n";
		}
		else print "<input type='checkbox' name='delreply[]' value='$picno|$ip$rtime'><br>\n";
		}
		if($kd_memo) print "<font style='font-size:11px; color:$kd_memocol;'><b>memo. </b>$kd_memo</font><br>";

		if($kd_s == 'on'){
			if($isAdmin==1)	{ 
				print "<span style='color:$kd_seccol;'><b>HIDDEN MESSAGE</b>↘</span><br><span style='color:#888;'>";
				if($kd_m =='on') print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<span style=color:$kd_morecol;>$close_text</span>': '<span style=color:$kd_morecol;>$open_text</span>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style=color:$kd_morecol;>$open_text</span></a><div style=\"display: none;\">\n";
				
				print "<span style='line-height:16px !important;'>$comment</span>";
			
			if($kd_m =='on') print "</div>";
			}
			else print "<span style='color:$kd_seccol;'><b>$secret_text</b><br></span>";
			
		}

		else{
			if($kd_m =='on'){
			print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<span style=color:$kd_morecol;>$close_text</span>': '<span style=color:$kd_morecol;>$open_text</span>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style=color:$kd_morecol;>$open_text</span></a><div style=\"display: none;\">\n";
			}
			if($kd_col == 'on') {
				if($kd_replt == 'on') print "<span style='color:$reply_text;'>";
				else print "<span style='color:$comm_ad_fontcol;'>";
			}
			else print "<span style='color:$comm_cu_fontcol;'>";
			print "<span style='line-height:16px !important;'>$comment</span>\n";
			print "</span>";
			
		if($kd_m =='on') 	print "</div>";

		
		if($option_ip == "on" && $kd_replt !='on') print "<div align=right style=font-family:Tahoma;font-size:7pt;color:$kd_ipcol;>$ip</div>\n";

			}


		

		print "</div>";

		print "<div style='border:4px solid transparent;'></div>"; // 코멘트들 사이에 투명줄 넣기 

  	}

$num2 = $num;
$num2 = $picno; }

if($isAdmin==1)
{
echo "</form>\n";
}

	

if(!$mov2){
if($vhchoice[0] < $max_width_comment) print "<div style='margin:0 0 10px 0; float:left;' class='forMobile commentWrite'>";//코멘트 밑에 write1삽입부 div, 좁은 로그
else print "<div style='margin:0px;' class='forMobile commentWrite'>";//코멘트 밑에 write1삽입부 div, 넓은 로그용
}
else print "<div style='margin:5px 0 10px 0;' class='forMobile commentWrite'>";//코멘트 밑에 write1삽입부 div, 텍스트로그용

if($reple_mode=="off" || $isAdmin==1) {

if($reply_close =='on') print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'▲': '▼';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'>▼</span></a><div style=\"display: none;\">\n";
include ("write1.php");
if($reply_close =='on') print "</div>";

}

print "</div>"; // write1 삽입부 div 닫음
print "</td></table>"; // 코멘트 table 닫음

print "</td></tr></table>\n"; // 전체 table 닫음

print "<br><br>\n";

}
//------------------요기서 비툴 테이블 끝남


if($dbbrk==0 && $fp) fclose($fp);
else "echo $dbfile 이 서버에 없습니다.<br>\n";
print "<script type='text/javascript'>js_input_checkboxs_skin_all(null,true);</script>";
//메인 테이블 끝남

print "<table width='100%'><tr><td>";
  echo "<div align = 'center'>";
  if($topnum>$num)
  {
	  $prev=$num+$cfg_pic_per_page;
  	if($prev>$topnum)$prev=$topnum;
	  echo " <a href=\"./index.php?num=$prev\">◀</a>&nbsp&nbsp&nbsp│&nbsp&nbsp;";
  }
  if($num>$cfg_pic_per_page)
  {
	  $next=$num-$cfg_pic_per_page;
  	echo " <a href=\"./index.php?num=$next\">▶</a> ";
  }
  echo "</div>";
//페이지 바 표시

print "</td></tr></table></td></tr></table>";//페이지바 끝내고 열었던 테이블 모두 닫음

?>

<div style="width:100%;margin:0 auto; margin-top:40px;text-align:center; padding:0px; clear:both; font-size:9px;">
MMB &copy;Madoka / &copy;tomCat / &copy;Bandi / SKIN &copy;kodama / Lightbox and Resizing Edition by <a href=http://888t.365managed.net/ target=_blank>Hatti</a>
</div>


</body>
</html>
<?




function member_login()
{
echo "
<form name='member' method='post'>
  <table width='200' border='0' cellspacing='0' cellpadding='0' align='center' style='margin-top:5px'>
  <tr>
    <td height='24' bgcolor='$pic_bgcol' style='border:0px solid $menu_bordercol;'>
        <div align='center' style='font-size:11px;'><img src='image/locked.gif' style='padding-bottom:5px;'><br><b>MEMBERS ONLY</b></div>
    </td>
  </tr>
  <tr>
    <td  bgcolor='$comm_bgcol' style='border:0 solid $menu_bordercol;' valign='middle' align='center'>
      <div align='center'>
            <input type='password' name='memberpasswd' style='width:100px; background:transparent; border:none; border-bottom:1px solid #aaa;'>&nbsp;
           	<input type='submit' name='submit2' value='OK' style='font-family:tahoma; font-size:7pt; font-weight:bold; color:#fff; border:1px solid #666; background-color:#666;'>
	  </div>
    </td>
  </tr>
</table>
</form>";
}

function del_html($str)
{
	$str = str_replace( ">", "&gt;",$str );
	$str = str_replace( "<", "&lt;",$str );
	$str = str_replace( "\"", "&quot;",$str );
	$str = str_replace( "&lt;br&gt;","<br>",$str); //br은되게함
	return $str;
}

function autolink($str)
{
	// URL 치환
	$homepage_pattern = "/([^\"\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"]+)/";
	$str = preg_replace($homepage_pattern,"\\1<a href=\\2://\\3 target=_blank>\\2://\\3</a>", " ".$str);
	return $str;
}

function alt($msg='') {
  echo "<script language='javascript'>";
  if($msg) echo 'alert("'.$msg.'");';
  echo "location.reload();</script>\n";
}

function gourl($url)
{
	echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
	echo"</head></html>";
}
?>