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
 	die("�� $datafo ���� ��ϺҰ� ����.<br />�۹̼��� 777�� �����Ͻñ� �ٶ��ϴ�.<br /><br />");
}
if(!file_exists($dbindex)){
  die("MMB $BBS_VERSION �ű� ��ġ�� Ȯ���մϴ�. ������ �α��� ��, ȯ�漳���� ���� ������ �ּ���.");
}
if(!is_writable($dbindex)) {
  die("�� $dbfile ���� �� ���ų� �۹̼��� 666�� �ƴմϴ�.  FTP�� Ȯ���Ͻñ� �ٶ��ϴ�.<br /><br />");
}

//����� �Խ��� ���
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
}//���� �������� ���
*/

if(($ckadminpasswd != $cfg_admin_passwd || $ckadminpasswd =="") && $reple_mode=='on'){
  gourl("./admin.php");
  exit;
}//���� �������� ���

function del_html($str)
{
	$str = str_replace( ">", "&gt;",$str );
	$str = str_replace( "<", "&lt;",$str );
	$str = str_replace( "\"", "&quot;",$str );
	$str = str_replace( "&lt;br&gt;","<br>",$str); //br���ǰ���
	return $str;
}

function autolink($str)
{
	// URL ġȯ
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
	echo "<body bgcolor=\"#FFFFFF\" text=\"#333333\" link=\"#ffffff\">\n";
	echo $msg."\n";
	echo "</body><html>";
}


$ckname = stripslashes($ckname);
$emowidth = $cfg_emolist*72; //����Ͻô� �̸�Ƽ���� ���� ����� Ŭ ��� ���� ���� �ø�����.
$dbnum = $num%100;
$dbfile = "$datafo/$dbnum.dat";

$fp = fopen("$dbfile","r");
//dbfile ����

while(!feof($fp))
{
  if(!file_exists($dbfile)) break;

  $buffer = fgets($fp, 4096);
 	$buffer = chop($buffer);

	if(substr($buffer,0,1)==">"){ // ������ ���� �տ� '>'�� ������ �׸���
		$buffer = substr($buffer,1);
		$data = explode("|", $buffer);
		list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
		// �׸���ȣ, ff���ϸ�, �۾��ð�(��), ��ȣȭ���н�����, ������, ��Ͻð�, ȣ��Ʈ����, IP
    if($picno==$num){


	if($mov) $thisisfake=$mov;
	else {
	$nowpic=$picfn;
	$thisisfake = "";
	}
	
			}
  }
  else{ //���϶�
    if($picno==$num){
      if(substr($buffer,0,1)!=">") // ������ ���� �տ� '>'�� ������ �׸���
      {
        $data = explode("|", $buffer);
        list($autname,$comment,$rtime,$ip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $data;
        if($comment=="")continue;
        // �ۼ��ڸ�,�۳���,�̸�,Ȩ�ּ�,��Ͻð�,IP,�н�����
        
        // ������ ���� �� ���
        if($time==$rtime) { // mod.php���� ���� �ۼ� �ð�
        $mdata = $buffer;
        $mdata = explode("|", $buffer);
        list($mname,$mcom,$mtime,$mip,$mpasswd,$mkd_s,$mkd_m,$mkd_memo,$mkd_col,$mkd_replt) = $mdata;
        $mcom = str_replace("<br>","\n",$mcom);
        }
        // �������
        // ��б� ó��
        if($mkd_s == "on" && $isAdmin != "1") {
        showmsg("��б��� �����ڸ� ������ �� �ֽ��ϴ�.");
        exit();
        }
        
         
		if($kd_col == 'on')
			$old_name[] = "<font style='color:$comm_ad_namecol;'><b>$autname&nbsp;</b>\n"; 
		else 
			$old_name[] = "<font style='color:$comm_cu_namecol;'><b>$autname</b>\n";

		print "</font>";
		
        
        $comment = str_replace("%7C","|",$comment);
        $comment = autolink($comment);
        if($wreply_emo == 'on') $comment = emote_ev($comment, $emote_table);
        else  $comment = emote_invi($comment, $emote_table);


		if($kd_col == 'on'){ 
			$old_comment = "<font style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-face:����ü; font-size:7pt;'>";
     	    $old_comment .= date(" Y.m.d",$rtime)."&nbsp;</font>\n<br>";
				}
					
			else {
				$old_comment = "<font style='font-family:Tahoma; font-size:7pt; filter; letter-spacing:0; color:$comm_cu_datecol;'>";
        $old_comment .= date("m.d",$rtime)."&nbsp;</font>\n<br>";

			}
		if($kd_s == 'on' && $isAdmin!=1 ){
		$old_comment .= "<span style='color:$kd_seccol;'>Secret</span>";
		}
		else if($kd_m =='on'){

		$old_comment .= "<a class=\"more\" onclick=\"this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'>More ��</span></b></a><div style=\"display: none;\">$comment<br></div>";
		}




		else{	
		if($kd_s == 'on' && $isAdmin ==1 ) 
		$old_comment .= "<span style='color:$kd_seccol;'>$comment</span>";
		else if($kd_col == 'on') 
		$old_comment .= "<span style='color:$comm_ad_fontcol;'>$comment</span>";
		else	
		$old_comment .= $comment."\n<br>";
		if($kd_s == 'on') print "</span>";
		}


        if($option_ip == "on") {
          $old_comment .= "<div align=right style=font-family:Tahoma;font-size:7pt;>$ip</div>\n";
        }
        $old_comment .= "\n";
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
<title>���ۼ��ϱ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel=StyleSheet HREF=./style.css type=text/css title=style>
<? include ("style_Hatti.php"); ?><!-- �׸��� ���� -->
</head>
<script type="text/javascript" src="js/js_input.js" charset='utf-8'></script>
<body style="BACKGROUND : url(<?=$bgurl?>) <?=$bgcol?>">

<h3 align="center">
<a href="./index.php">������</a></h3>
<form name="write" method="post" action="mod_proc.php">

<TABLE border=0 width='50%' CELLSPACING='0' CELLPADDING='5' align='center'>




<?
if($wreply == 'on' ){
  $loop_max = sizeof($old_name);

   for($loop = 0; $loop < $loop_max; $loop++) {
    print "<tr><TD BORDER=1 bgcolor='$comm_bgcol' CELLSPACING=0 CELLPADDING=0 width=100%>";

    print $old_name[$loop];
	print "<font style='color:$comm_cu_fontcol; '>";
    print $old_comments[$loop];
	print "</font>";
    print "</td></tr>";
  }
}
?>

</table><br>


<table width='500' cellspacing='0' cellpadding='0'  style='border:1 solid $co_w_tb_bordercol;' align='center'>
<tr><td>

		<table width="100%" border="0" cellpadding=2  bgcolor="<?=$co_w_tbcol?>">
			<tr>
				<td border='0'width="85%">
				<input type="text" name="kd_memo" value="<?=$mkd_memo?>" style="color:<?=$co_w_txfontcol?>; border:none; background=<?=$co_m_textbox?>; width:100%;">
				</td>

				<td align="left" valign="bottom" width="15%"><span style="font-size:7pt; color:<?=$co_w_fontcol?>;">.memo</span></td>
			</tr>

			<tr>
				<td border='0' >
				<textarea name="comment" rows="6"  style="color:<?=$co_w_txfontcol?>; background:<?=$co_w_textbox?>; border:1px solid #ddd; width:100%; overflow:visible;"><?=$mcom?></textarea>
				</td>

				<td border='0' width="50">
				<input type="submit" name="Submit" value="WRITE" 
				style="width:50px; height:100%; font-size:7pt; font-weight:bold; color:#fff; border:1px solid <?=$co_w_submit?>; background-color:<?=$co_w_submit?>;">
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

<tr><td border='0' width="100%" bgcolor="<?=$co_w_tbcol?>"><font style="color:<?=$co_w_fontcol?>; font-size:10px;">
<? if ($mkd_col =='on'){
print "$ad_ico";
print "<input type='checkbox' name='kd_col' checked style='display:none'>";
}
?>
<?=$mname?>

<?
if($isAdmin==1) { print "<input type=password name=dpasswd value=$ckadminpasswd size='2' style='background:transparent; border:0px; border-bottom:1px solid #ccc; color:<?=$co_w_txfontcol?>;'>";

}
else print "<input type='password' name='passwd' size='2' style='background:transparent; border:0px; border-bottom:1px solid #ccc; color:<?=$co_w_txfontcol?>;'>&nbsp;";

?>

<input type="checkbox" name="kd_replt" <? if($mkd_replt == "on") echo "checked"; ?> style="display:none;"> 
<input type="checkbox" name="usecookiepw" value="on" <?if($ckpass!="")echo checked;?>> ���
<input type="checkbox" name="usecookie" value="on" <?if($ckuse=="on")echo checked;?>> ��Ű
<input type="checkbox" name="kd_s" <? if($mkd_s == "on") echo "checked"; ?>> ��б�
<input type="checkbox" name="kd_m" <? if($mkd_m == "on") echo "checked"; ?>> ����
</font>
</td>
</tr>

</table>


</form>
<script type='text/javascript'>js_input_checkboxs_skin_all(null,true);</script>
</body>
</html>