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

//����� �Խ��� ���
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
	// ������ �н�������Ű�� �����鼭 �����ھ�ȣ�� ������ �����ڸ����
if($ckadminpasswd == $cfg_admin_passwd && $ckadminpasswd !="")
{
	$isAdmin = 1;
}

if($cfg_admin_passwd=="")
{
	print "������ �н����尡 �����Ǿ����� �ʰų� 'env.php' ������ �о����� �ʾҽ��ϴ�.";
	exit();
}



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

$i = 0;
// ����Ʈ�ε�
$fp = @fopen ("$datafo/recent.txt", "r") or die("�����Ͱ� �������� �ʽ��ϴ�.");
while(!feof($fp))
{
	$buffer = chop(fgets($fp, 4096));
	if($buffer!="")$olist[$i++] = $buffer;
}
fclose($fp);

$ototal = count($olist); //ǥ���� �Խù� ����
$usedline; // �׸��� ����� ���ڵ尳���� �����ҹ迭

$lastnum = $olist[0];
for($i=0;$i<$ototal;$i++)
{
	if($lastnum > $olist[$i] && $olist[$i]!="")$lastnum = $olist[$i];
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
<? include ("style_Hatti.php"); ?><!-- �׸��� ���� -->		
</head>
<script type="text/javascript" src="js/js_input.js" charset='utf-8'></script>
<body background="<?=$bgurl?>" bgcolor="<?=$bgcol?>" text="<?=$b_fo?>" link="<?=$li_fo?>" vlink="<?=$vi_fo?>" alink="<?=$ac_fo?>">

<div align = "center">
<a href = "./index.php">[�׸������ε��ư�]</a>
<a href = "recent.php">[��ۼ��ֱ�<?=$cfg_recent?>��]</a>
</div>

<?
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


for($logcnt=0;$logcnt<$ototal;$logcnt++){
  $dbnum = $olist[$logcnt]%100;
  $dbfile = "$datafo/$dbnum.dat";

  if(!file_exists($dbfile)) die("$dbfile �� �����ϴ�.");
  $fp = fopen("$dbfile","r");
  while(!feof($fp))
  {
	  $buffer = fgets($fp, 4096);
  	$oribuf = $buffer = chop($buffer);

  	if(substr($buffer,0,1)==">") // ������ ���� �տ� '>'�� ������ �׸���
	  {
		  $nownum = -1;
  		$buffer = substr($buffer,1);

	  	$data = explode("|", $buffer);
		  list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
  		// �׸���ȣ, ���ϸ�, �۾��ð�(��), ��ȣȭ���н�����, ������, ��Ͻð�, ȣ��Ʈ����, IP

	  	if($picno<$lastnum)break; // ������ �Ѿ�� �׸��ΰ�����

		  for($cnt=0;$cnt<$ototal;$cnt++)
  		if($picno==$olist[$cnt])
	  	{
		  	$subbuf[$cnt][0]=$oribuf;
			  $usedline[$cnt]=1;
  			$nownum = $cnt;
	  	}
  	}
  	else if($nownum!=-1)//����
	  {
		  $subbuf[$nownum][$usedline[$nownum]++]=$oribuf;
  	}
  }
  fclose($fp);
}

$tl = 0;
for($i=0;$i<$ototal;$i++)
{
	for($j=0;$j<$usedline[$i];$j++)
	{
		$outbuffers[$tl++] = $subbuf[$i][$j];
	}
}

$intbl = 0; // ���̺��� �����ִ��� ����

$totalline=$tl;

for($i=0;$i<$totalline;$i++)
{
	$buffer = $outbuffers[$i];

	if(substr($buffer,0,1)==">") // ������ ���� �տ� '>'�� ������ �׸���
	{
		if($intbl==1)
		{
			print "</td></tr></table>"; // ���⼭ cellMMB ���̺��� ����(������ �� �Ʒ��� ������ ��ư ���⼭ ����;)
			print "</td></table><br>\n\n"; // �Ʒ� ���̺� ��ü�� ���δ� table�� ���⼭ ����
			$intbl = 0;
		}

		$buffer = substr($buffer,1);

		$data = explode("|", $buffer);
		list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
		// �׸���ȣ, ���ϸ�, �۾��ð�(��), ��ȣȭ���н�����, ������, ��Ͻð�, ȣ��Ʈ����, IP

		// �۾��ð��� �ú��� ������ ��ȯ
		$strjtime = sprintf("%d�ð� %d�� %d��",$sec/3600,($sec/60)%60,$sec%60);
		if($sec<3600)$strjtime = sprintf("%d�� %d��",($sec/60)%60,$sec%60);
		if($sec<60)$strjtime = sprintf("%d��",$sec%60);
		if($sec<=0)$strjtime = "�� �� ����";

		print "<table width=$alltb_w border=0 cellpadding=0 cellspacing=0 align=center><td>"; // �Ʒ� ���̺� ��ü�� ���δ� table �ۼ�

		print "<div class=numMMB style='background:$pic_number;'>
		<a href='index.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>&nbsp;#</span></a>";
		print "<a href='delete.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>$picno</span></a>";
		print "</div>";		
		print "<TABLE id=cellMMB bgcolor='$logtb_bgc' style='border:$logtb_bor solid $logtb_borc;' width='100%' CELLSPACING='0' CELLPADDING='0' align=center>";
		print "<tr><TD align='center' valign='top' width=5% BGCOLOR='$pictb_bgc' rowspan='2' style='padding:12px 8px 12px 12px;'>\n"; // recent.php, search.php���� �׸��� ������ ���ʿ� ��ġ�ϸ鼭 ���� �е� 
		

    
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



		include("mov.php"); //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// mov.php ��������



		print "</td><TD BORDER=1 CELLSPACING=0 CELLPADDING=0 valign='top' width=100% style='background-color:$comtb_bgc; margin:0; padding: 10px 10px 10px 0;'>\n"; // �ڸ�Ʈâ �е�
		$intbl = 1;
	}
	  else //����
  	{
	  	if($intbl!=1)continue;
		  $data = explode("|", $buffer);
  		list($autname,$comment,$rtime,$ip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $data;
  		// �ۼ��ڸ�,�۳���,��Ͻð�,IP,�н�����
  		if($comment=="")continue;

		if($kd_col == 'on'){
			if($kd_replt == 'on') print "<div style='background:$repl_bgcol; padding:0 0 0 5px; border-left: 2px solid $replt_text;'><font style='color:$replt_text; font-size:$ad_namesize;'><b>RE: </b></font><font style='color:$comm_ad_namecol; font-size:$ad_namesize;'>";//��� ���̺��� �¿� ������ ���ְ� ������ margin�� 20�� 0���� ���ּ���:)
			else print "<div style='background:$comm_adbgcol; margin:0;'><font style='color:$comm_ad_namecol; font-size:$ad_namesize;'>";
		}
		else {
			if($kd_replt == 'on') print "<div style='background:$repl_bgcol; padding:0 0 0 5px; border-left: 2px solid #555;'><font style='color:$replt_text; font-size:$ad_namesize;'><b>RE: </b></font><font style='color:$comm_cu_namecol; font-size:$cu_namesize;'>";//��� ���̺��� �¿� ������ ���ְ� ������ margin�� 20�� 0���� ���ּ���:)
			else print"<div style='background:$comm_cuscol; padding-left:$cu_textpadding;'><font style='color:$comm_cu_namecol; font-size:$cu_namesize;'>";
		}
		$autname = emote_ev($autname, $emote_table);
		print "<b>$autname</b>&nbsp;</font>";
		
  		$comment = str_replace("%7C","|",$comment);
	  	//$comment = del_html($comment);
		$comment = autolink($comment);
  		$comment = emote_ev($comment, $emote_table);

	  	$altdate = date("Y�� m�� d�� H�� i�� s��",$rtime);

		if($kd_col == 'on'){ 
			print "<font title='$altdate'>";
			print "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;font-size:8px;'>";
			print date("ymd*H:i",$rtime)."&nbsp;</span></font>\n";
		} else {
			print "<font title='$altdate'>";
			print "<span style='color:$comm_cu_datecol; font-family:tahoma; font-size:8px;'>";
			print date("ymd*H:i",$rtime)."&nbsp;&nbsp;</span></font>";
		}

		$autname=urlencode($autname);//�����ڵ� �ذ�

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
			else print "<span style='color:$comm_cu_fontcol; font-size:11px;'>�� </span></a><br>\n";
		}
		else print "<span style='color:$comm_cu_fontcol; font-size:11px;'>�� </span></a><br>\n";
		
		}
		if($kd_memo) print "<font style='font-size:11px; color:$kd_memocol;'><b>memo. </b>$kd_memo</font><br>";

		if($kd_s == 'on'){
			if($isAdmin==1)	{ 
				print "<span style='color:$kd_seccol;'><b>HIDDEN MESSAGE</b>��</span><br><span style='color:#888;'>";
				if($kd_m =='on') print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<span style=color:$kd_morecol;>$close_text</span>': '<span style=color:$kd_morecol;>$open_text</span>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style=color:$kd_morecol;>$open_text</span></a><div style=\"display: none;\">\n";
				
				print "$comment</span>";
			
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
			print $comment."\n";
			print "</span>";
			
		if($kd_m =='on') 	print "</div>";

		
		if($option_ip == "on" && $kd_replt !='on') print "<div align=right style=font-family:Tahoma;font-size:7pt;color:$kd_ipcol;>$ip</div>\n";

			}



		

		print "</div>";

		print "<div style='border:4px solid transparent;'></div>"; // �ڸ�Ʈ�� ���̿� ������ �ֱ� 

  	}
}
print "<script type='text/javascript'>js_input_checkboxs_skin_all(null,true);</script>";
if($intbl==1)
{
	print "</td></tr></table>"; // cellMMB �ݱ�
	print "</td></table><br>"; // ��ü ���� ���̺� �ݱ�
	$intbl = 0;
}
?>
<div align="right"><a href="http://mahokokr.pooding.com/" target="_blank">MMB <?=$BBS_VERSION ?> &copy;Madoka</a> / <a href="http://blog.naver.com/ion01/" target="_blank"> &copy; Mic</a> / <a href="http://btool.net/" target="_blank">&copy;Bandi</a>
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
    <td bgcolor='$comm_bgcol' style='border:0 solid $menu_bordercol;' valign='middle' align='center'>
      <div align='center'>
            <input type='password' name='memberpasswd' style='width:100px; background:transparent; border:none; border-bottom:1px solid #aaa;'>&nbsp;
           	<input type='submit' name='submit2' value='OK' style='font-family:tahoma; font-size:7pt; font-weight:bold; color:#fff; border:1px solid #666; background-color:#666;'>
	  </div>
    </td>
  </tr>
</table>
</form>";
}
?>