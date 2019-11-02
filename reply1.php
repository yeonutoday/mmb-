<?
include "env.php";
include "option_data.php";
include "config_data.php";
include "mtype_plugin/extend_lib.php";
include "mtype_plugin/db_admin.php";
include "KDM_skin_data.php";
include "KDM_fontcol_data.php";
include "KDM_tb_data.php";

header ("Pragma: no-cache");

if(!is_writable("$datafo")) {
 	die("◈ $datafo 폴더 기록불가 상태.<br />퍼미션을 777로 변경하시기 바랍니다.<br /><br />");
}
if(!file_exists($dbindex)){
  die("MMB $BBS_VERSION 신규 설치를 확인합니다. 관리자 로그인 뒤, 환경설정을 먼저 끝마쳐 주세요.");
}
if(!is_writable($dbindex)) {
  die("◈ $dbfile 파일 이 없거나 퍼미션이 666이 아닙니다.  FTP로 확인하시기 바랍니다.<br /><br />");
}

//비공개 게시판 모드
if($mem_login=='on'){
  if($memberlogin == $cfg_member_passwd);
  else{
    gourl("./admin.php?member=1");
    exit;
  }
}


if($ckadminpasswd == $cfg_admin_passwd && $ckadminpasswd !="")
{
	$isAdmin = 1;
}


/*
if($reple_mode=='on'){
  gourl("./admin.php");
  exit;
}//리플 권한제어 모드
*/

if(($ckadminpasswd != $cfg_admin_passwd || $ckadminpasswd =="") && $reple_mode=='on'){
  gourl("./admin.php");
  exit;
}//리플 권한제어 모드

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

function showmsg($msg)
{
	echo "</head>\n";
	echo "<body bgcolor=\"#FFFFFF\" text=\"#333333\">\n";
	echo $msg."\n";
	echo "</body><html>";
}


$ckname = stripslashes($ckname);
$emowidth = $cfg_emolist*72; //사용하시는 이모티콘의 가로 사이즈가 클 경우 곱셈 값을 올리세요.
$dbnum = $num%100;
$dbfile = "$datafo/$dbnum.dat";

$fp = fopen("$dbfile","r");
//dbfile 선정

while(!feof($fp))
{
  if(!file_exists($dbfile)) break;

  $buffer = fgets($fp, 4096);
 	$buffer = chop($buffer);

	if(substr($buffer,0,1)==">"){ // 라인의 제일 앞에 '>'가 있으면 그림임
		$buffer = substr($buffer,1);
		$data = explode("|", $buffer);
		list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
		// 그림번호, ff파일명, 작업시간(초), 암호화된패스워드, 툴버젼, 등록시간, 호스트네임, IP
    if($picno==$num) { $nowpic=$picfn;}
  }
  else{ //글일때
    if($picno==$num){
      if(substr($buffer,0,1)!=">") // 라인의 제일 앞에 '>'가 있으면 그림임
      {
        $data = explode("|", $buffer);
        list($autname,$comment,$rtime,$ip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $data;
        if($comment=="")continue;
        // 작성자명,글내용,이멜,홈주소,등록시간,IP,패스워드
        
        // 수정할 리플 값 취득
        if($time==$rtime) { // mod.php에서 받은 작성 시간
        $mdata = $buffer;
        $mdata = explode("|", $buffer);
        list($mname,$mcom,$mtime,$mip,$mpasswd,$mkd_s,$mkd_m,$mkd_memo,$mkd_col,$mkd_replt) = $mdata;
        $mcom = str_replace("<br>","\n",$mcom);
        }
        // 여기까지
        // 비밀글 처리
        if($mkd_s == "on" && $isAdmin != "1") {
        showmsg("비밀글은 관리자만 답글을 달 수 있습니다.");
        exit();
        }
        
        
		if($kd_col == 'on')
			$old_name[] = "$ad_ico<font style='color:$comm_ad_namecol;'><b>$autname&nbsp;</b></font\n"; 
		else 
			$old_name[] = "<font style='color:$comm_cu_namecol;'><b>$autname</b></font>\n";

		print "</font>";
		
        $comment = str_replace("%7C","|",$comment);
        $comment = autolink($comment);
        if($wreply_emo == 'on') $comment = emote_ev($comment, $emote_table);
        else  $comment = emote_invi($comment, $emote_table);


		if($kd_col == 'on'){ 
			$old_comment = "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;font-size:8px;'>";
     	    $old_comment .= date("ymd*H:i",$rtime)."&nbsp;</span>\n<br>";
				}
					
			else {
				$old_comment = "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;font-size:8px;'>";
        $old_comment .= date("ymd*H:i",$rtime)."&nbsp;</span>\n<br>";

			}
		if($kd_s == 'on' && $isAdmin!=1 ){
		$old_comment .= "<span style='color:$kd_seccol;'>Secret</span>";
		}
		else if($kd_m =='on'){

		$old_comment .= "<a class=\"more\" onclick=\"this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'>$open_text</span></b></a><div style=\"display: none;\">$comment<br></div>";
		}

		else{	
		if($kd_s == 'on' && $isAdmin ==1 ) 
		$old_comment .= "<span style='color:$kd_seccol;'><b>HIDDEN MESSAGE</b>↘</span><br><span style='color:#888;'>$comment</span>";
		else	
		$old_comment .= $comment."\n<br>";
		if($kd_s == 'on') print "</span>";
		}


        if($option_ip == "on") {
          $old_comment .= "<div align=right style=font-family:Tahoma;font-size:7pt;>$ip</div>\n";
        }
        $old_comment .= "<br>\n";
        $old_comments[] = $old_comment;
        if($isAdmin==1) $old_comment .= "<br>".$admin_com."n";
      }
    }
    else continue;
  }
}
fclose($fp);

?>

<html>
<head>
<title>글작성하기</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel=StyleSheet HREF=./style.css type=text/css title=style>
<? include ("style_Hatti.php"); ?><!-- 테마색 삽입 -->
</head>
<script type="text/javascript" src="js/js_input.js" charset='utf-8'></script>
<body background="<?=$bgurl?>" bgcolor="<?=$bgcol?>" text="<?=$b_fo?>" link="<?=$li_fo?>" vlink="<?=$vi_fo?>" alink="<?=$ac_fo?>">

<h3 align="center"><a href="./index.php">◀◀◀</a></h3>
<form name="write" method="post" action="reply_proc.php">

<TABLE width=500 CELLSPACING='0' CELLPADDING='5' align='center'>

<tr><TD BORDER=1 bgcolor='<?=$comm_bgcol?>' CELLSPACING=0 CELLPADDING=0>
<?
if($mkd_col == 'on')	print "<font style='color:$comm_ad_namecol; font-size:$ad_namesize;'><b>";
else print "<font style='color:$comm_cu_namecol; font-size:$cu_namesize;'><b>";
print "$mname</b>&nbsp;</font>";

if($mkd_col == 'on'){ 
			print "<font size='1' title='$altdate'>";
			print "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;'>";
			print date("ymd*H:i",$mtime)."&nbsp;</span></font><br>";
		} else {
			print "<font size='1' title='$altdate'>";
			print "<span style='color:$comm_cu_datecol; font-family:tahoma;'>";
			print date("ymd*H:i",$mtime)."&nbsp;</span></font><br>";
		}
print "<font style='font-face:돋움체; color:$kd_memocol;'><b>memo.</b> $mkd_memo</font><br>";
if($mkd_s == 'on'){
	if($mkd_m =='on'){
				print "<a class=\"more\" onclick=\"this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'>$open_text</a><div style=\"display: none;\"></span>\n";
			}
			if($isAdmin==1)	print "<span style='color:$kd_seccol;'>$mcom</span>";
			if($mkd_m =='on') print "</div>";
			print "<br><br>\n";
		}

		else{
			if($mkd_m =='on'){
				print "<a class=\"more\" onclick=\"this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'>$open_text</a><div style=\"display: none;\"></span>\n";
			}
			if($mkd_col == 'on') {
				if($kd_replt == 'on') print "<font style='color:$reply_text;'>";
				else print "<font style='color:$comm_ad_fontcol;'>";
			}
			else print "<font style='color:$comm_cu_fontcol;'>";
			print "$mcom\n";
			print "</font>";
			
		if($mkd_m =='on') 	print "</div>";
		}
?>

</font>
</td></tr>

</font>
</table><br>


<table width='500' cellspacing='0' cellpadding='0' align='center'>
<tr><td>
<table width="100%" border="0"   bgcolor="<?=$co_w_tbcol?>">

<tr>

<td border = '0'>
<textarea name="comment" style=" color:<?=$co_w_txfontcol?>; background:<?=$co_w_textbox?>; border:1px solid #ddd; width:100%; height:80px;" ></textarea>
</td>

<td border='0' width="30" align=right valign=top>
<input type="submit" name="Submit" value="WRITE" style="width:50px; height:100%; font-size:7pt; font-weight:bold; color:#fff; border:1px solid <?=$co_w_submit?>; background-color:<?=$co_w_submit?>;">
          <input type="hidden" name="number" value="<?=$num?>">
          <input type="hidden" name="name" value="<?=$mname?>">
          <input type="hidden" name="time" value="<?=$mtime?>">
	  

          <? if($isAdmin != "1") echo "<input type=\"hidden\" name=\"f1\" value=\"$mf1\">"; ?>
          <input type="hidden" name="chk_w" value="whoareyou">

</td>
    </tr>
</table>
</td>
</tr>

        <td width=100% valign=bottom style='font-size:10px;' >

<? 
if($isAdmin==1)
{ 
print "<input type='text' name='name' size='3' value='$ckname' style='color:$co_w_txfontcol; border:none;'>";
print "<input type='checkbox' name='usecookie' value='on'  if($ckuse==\"on\")echo checked > 쿠키";
print "<input type='checkbox' name='kd_col' checked style='display:none'>";
}
else {print "<input type='text' name='name' size='3' value='$ckname' style='background:$co_w_textbox; color:$co_w_txfontcol; border:none;'><input type='password' name='passwd' size='2'  style='background:transparent; border:0px; border-bottom:1px solid #ccc; color:<?=$co_w_txfontcol?' value='$ckpass' >&nbsp;";
print "<input type='checkbox' name='usecookiepw' value='on' if($ckpass!=\"\")echo checked > 비번";
print "<input type='checkbox' name='usecookie' value='on'  if($ckuse==\"on\")echo checked > 쿠키";
}
?>

<input type="checkbox" name="kd_s" <? if($mkd_s == "on") echo "checked"; ?>> 비밀글
<input type="checkbox" name="kd_m" <? if($mkd_m == "on") echo "checked"; ?>> 접기
<input type="checkbox" name="kd_replt" checked style="display:none">
</font>
</td>
    </tr>

</table>

    <p>&nbsp;</p>

</form>
<script type='text/javascript'>js_input_checkboxs_skin_all(null,true);</script>
</body>
</html>