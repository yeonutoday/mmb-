<?
include "env.php";
include "config_data.php";
include "option_data.php";
include "mtype_plugin/extend_lib.php";
include "mtype_plugin/db_admin.php";
include "KDM_skin_data.php";
include "KDM_fontcol_data.php";
include "KDM_tb_data.php";

header ("Pragma: no-cache");

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

$pagcnt = $logcnt = $findnum = 0;
$prev_pgnum = $next_pgnum = 0;

$num = intval($num);

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

?>

<html>
<head>
<title><?=$browser_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel=StyleSheet HREF=style.css type=text/css title=style>
<!-- lightbox ST -->
<script src="lightbox/js/jquery-1.7.2.min.js"></script>
<script src="lightbox/js/lightbox.js"></script>
<link href="lightbox/css/lightbox.css" rel="stylesheet" />
<!-- lightbox ED -->	
<? include ("style_Hatti.php"); ?><!-- 테마색 삽입 -->		
</head>

<body background="<?=$bgurl?>" bgcolor="<?=$bgcol?>" text="<?=$b_fo?>" link="<?=$li_fo?>" vlink="<?=$vi_fo?>" alink="<?=$ac_fo?>">

<div align = "center">
<? print "SEARCH : <b>$keyw</b>"; ?><br>
<a href = "./index.php">◀◀◀</a>
</p>

</div>

<?
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

function gourl($url)
{
	echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
	echo"</head></html>";
}

function result($fp)
{ global $isAdmin;
  global $option_ip; 
  global $kd_memocol;  global $kd_seccol;  global $kd_morecol;
  global $kd_ipcol;
  global $secret_text; global $open_text; global $close_text;
	


  $buffer = fgets($fp, 4096);
  $buffer = chop($buffer);

  while(substr($buffer,0,1)!=">" && $buffer != ""){    // 답글 출력
    $reply = explode("|", $buffer);
    list($autname,$comment,$rtime,$repip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $reply;
    // 작성자명,글내용,이멜,홈주소,등록시간,IP,패스워드

    if($comment=="")continue;
    print "<b>$autname</b>&nbsp;</font>";

    $comment = str_replace("%7C","|",$comment);
//    $comment = del_html($comment);
    $comment = autolink($comment);
	print "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;font-size:8px;'>";
	print date("ymd＊H:i",$rtime)."&nbsp;</span><br>\n";

	if($kd_memo) print "<font style='font-size:11px; color:$kd_memocol;'><b>memo. </b>$kd_memo</font><br>";
		if($kd_s == 'on'){
			if($isAdmin==1)	{ 
				print "<span style='color:$kd_seccol;'><b>HIDDEN MESSAGE</b>↘</span><br><span style='color:#888;'>";
				if($kd_m =='on') print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<span style=color:$kd_morecol;>$close_text</span>': '<span style=color:$kd_morecol;>$open_text</span>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style=color:$kd_morecol;>$open_text</span></a><div style=\"display: none;\">\n";

				print "<span style='line-height:16px !important;'>$comment</span></span>";
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
			
			if($kd_m =='on') print "</div>\n";
			
		if($option_ip == "on" && $kd_replt !='on') print "<div align=right style=font-family:Tahoma;font-size:7pt;color:$kd_ipcol;>$repip</div>\n";
		}

		print "</div>"; // 코멘트 div 닫기
		
		print "<div style='border:4px solid transparent;'></div>"; // 코멘트들 사이에 투명줄 넣기 
		
    $buffer = fgets($fp, 4096);
  }

  if(!feof($fp)){
    $back = strlen($buffer);
    fseek ($fp,-$back,SEEK_CUR);
  }
  return $fp;
}

// 관리자 패스워드쿠키가 있으면서 관리자암호와 같으면 관리자모드임
if($ckadminpasswd == $cfg_admin_passwd && $ckadminpasswd !="")
{
	$isAdmin = 1;
}

$fp = fopen("$dbindex","r");
$buffer = chop(fgets($fp, 4096));
fclose($fp);
$total=intval($buffer);
if($num==0) $num=$total;

while($pagcnt<$cfg_pic_per_page && $num > 0){
  $dbnum = $num%100;
  $dbfile = "$datafo/$dbnum.dat";

  if(!file_exists($dbfile)) continue;
  $fp = fopen("$dbfile","r");
  while(!feof($fp)){
	  $buffer = trim(fgets($fp, 4096));
   	if(substr($buffer,0,1)==">"){  // 라인의 제일 앞에 '>'가 있으면 그림임
  		$buffer = substr($buffer,1);
	  	$data = explode("|", $buffer);
  		list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
	  	// 그림번호, 파일명, 작업시간(초), 암호화된패스워드, 툴버젼, 등록시간, 호스트네임, IP

     	if(!file_exists("data/$picfn") && $isAdmin!=1)  continue; //그림이 없고 일반이면 스킵
      $fpsav = ftell($fp);
      $intbl = 0;    
    }

  	else{  //글임
      if($intbl==1) continue;
      
      $pos = strpos($buffer, $keyw);  //search
      if ($pos !== FALSE)  // === 필수!
      {
        if($picno != $num){
        $intbl = 1;
        continue;
        }
      
        if($pagcnt++ == 0) { $prev = $picno;}
                
        $intbl = 1;
        $findnum=$picno;

        $reply = explode("|", $buffer);
        list($autname,$comment,$rtime,$repip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $reply;
        if(strcmp($ip,$repip)==0 && $autname==$keyw) $logcnt++;
        //검색어가 이름이면 ip 대조로 본인그림인지까지 확인.

     	  if($pagcnt==1)	$fsttime=$rtime;
       	else $lsttime=$rtime; //현재 페이지 기간 계산

        // 작업시간을 시분초 단위로 변환
        $strjtime = sprintf("%d시간 %d분 %d초",$sec/3600,($sec/60)%60,$sec%60);
        if($sec<3600)$strjtime = sprintf("%d분 %d초",($sec/60)%60,$sec%60);
        if($sec<60)$strjtime = sprintf("%d초",$sec%60);
        if($sec<=0)$strjtime = "알 수 없음";

       	if(!file_exists("data/$picfn") && $isAdmin!=1)  continue; //그림이 없고 일반이면 스킵
       	else  $vhchoice = @GetImageSize("data/$picfn");

		print "<table width=$alltb_w border=0 cellpadding=0 cellspacing=0 align=center><td>"; // 아래 테이블 전체를 감싸는 table 작성

		print "<div class=numMMB style='background:$pic_number;'>
		<a href='index.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>&nbsp;#</span></a>";
		print "<a href='delete.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>$picno</span></a>";
		print "</div>";		
		print "<TABLE id=cellMMB bgcolor='$logtb_bgc' style='border:$logtb_bor solid $logtb_borc;' width='100%' CELLSPACING='0' CELLPADDING='0' align=center>";
		print "<tr><TD align='center' valign='top' width=5% BGCOLOR='$pictb_bgc' rowspan='2' style='padding:12px 8px 12px 12px;'>\n"; // recent.php, search.php에서 그림이 무조건 왼쪽에 위치하면서 갖는 패딩 
		
       	if(!file_exists("data/$picfn")) echo "<center>Log<br>delete</center>"; // 관리자면 스킵않고 표시
     	  else{
          $alt = "";
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



        }

        print "</td><TD BORDER=1 CELLSPACING=0 CELLPADDING=0 valign='top' width=100% style='background-color:$comtb_bgc; padding: 10px 10px 10px 0;'>\n"; // 코멘트창 패딩
    	  // 만일 그림의 가로크기가 지정 크기 이상이면 리플을 그림 밑으로 표시한다.
        $intbl = 1;
        //여기까지 그림 표시
        fseek ($fp,$fpsav);
        result($fp);
     	}
    }
    
    if($intbl==1){
      print "</td></tr></table>";
	  print "</td></table><br>\n\n";
      $intbl = 0;
    }
  }
  $num--;
}



$next = $findnum-1;

fclose($fp);
$fpsav = 0;

if($intbl==1)
{
	print "</td></tr></table>"; // cellMMB 닫기
	print "</td></table><br>"; // 전체 감싼 테이블 닫기
  $intbl = 0;
}

//next, prev 버튼
echo "\n<center><TABLE border=0><TR><TD>\n";
if($total>$prev)
{
  echo "<form name='search' method='get' action=./search.php>
  <input type=hidden name='keyw' value=$keyw>
  <input type=hidden name='num' value=$prev>
  <input type=submit name='bprev' value='prev'></form>";
}

echo "</TD><TD>\n";

if($num > 0)
{
  echo "<form name='search' method='get' action=./search.php>
  <input type=hidden name='keyw' value=$keyw>
  <input type=hidden name='num' value=$next>
  <input type=submit name='bnext' value='next'>
  </form>";
}
echo "</TD></TR></TABLE></center>\n";
?>

</body>
</html>
<?
function member_login()
{
echo "
<form name='member' method='post'>
  <table width='200' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td height='24' bgcolor='$pic_bgcol' style='border:0 solid $menu_bordercol;'>
        <div align='center'><img src='image/locked.gif' style='padding-bottom:5px;'></div>
    </td>
  </tr>
  <tr>
    <td bgcolor='$comm_bgcol' style='border:0 solid $menu_bordercol;' valign='middle' align='center'>
      <div align='center'>
            <input type='password' name='memberpasswd' style='width:100px; background:transparent; border:none; border-bottom:1px solid #aaa;'>&nbsp;
           	<input type='submit' name='submit2' value='OK' style='font-family:tahoma; font-size:7pt; font-weight:bold; color:#fff; border:1px solid #666; background-color:#666;'>
     </div>
	</td>
  </tr>
</table>
</form>";
}?>
