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
<? include ("style_Hatti.php"); ?><!-- �׸��� ���� -->		
</head>

<body background="<?=$bgurl?>" bgcolor="<?=$bgcol?>" text="<?=$b_fo?>" link="<?=$li_fo?>" vlink="<?=$vi_fo?>" alink="<?=$ac_fo?>">

<div align = "center">
<? print "SEARCH : <b>$keyw</b>"; ?><br>
<a href = "./index.php">������</a>
</p>

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

function result($fp)
{ global $isAdmin;
  global $option_ip; 
  global $kd_memocol;  global $kd_seccol;  global $kd_morecol;
  global $kd_ipcol;
  global $secret_text; global $open_text; global $close_text;
	


  $buffer = fgets($fp, 4096);
  $buffer = chop($buffer);

  while(substr($buffer,0,1)!=">" && $buffer != ""){    // ��� ���
    $reply = explode("|", $buffer);
    list($autname,$comment,$rtime,$repip,$passwd,$kd_s,$kd_m,$kd_memo,$kd_col,$kd_replt) = $reply;
    // �ۼ��ڸ�,�۳���,�̸�,Ȩ�ּ�,��Ͻð�,IP,�н�����

    if($comment=="")continue;
    print "<b>$autname</b>&nbsp;</font>";

    $comment = str_replace("%7C","|",$comment);
//    $comment = del_html($comment);
    $comment = autolink($comment);
	print "<span style='background-color:$comm_ad_datebgcol; color:$comm_ad_datecol; font-family:tahoma;font-size:8px;'>";
	print date("ymd��H:i",$rtime)."&nbsp;</span><br>\n";

	if($kd_memo) print "<font style='font-size:11px; color:$kd_memocol;'><b>memo. </b>$kd_memo</font><br>";
		if($kd_s == 'on'){
			if($isAdmin==1)	{ 
				print "<span style='color:$kd_seccol;'><b>HIDDEN MESSAGE</b>��</span><br><span style='color:#888;'>";
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

		print "</div>"; // �ڸ�Ʈ div �ݱ�
		
		print "<div style='border:4px solid transparent;'></div>"; // �ڸ�Ʈ�� ���̿� ������ �ֱ� 
		
    $buffer = fgets($fp, 4096);
  }

  if(!feof($fp)){
    $back = strlen($buffer);
    fseek ($fp,-$back,SEEK_CUR);
  }
  return $fp;
}

// ������ �н�������Ű�� �����鼭 �����ھ�ȣ�� ������ �����ڸ����
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
   	if(substr($buffer,0,1)==">"){  // ������ ���� �տ� '>'�� ������ �׸���
  		$buffer = substr($buffer,1);
	  	$data = explode("|", $buffer);
  		list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data;
	  	// �׸���ȣ, ���ϸ�, �۾��ð�(��), ��ȣȭ���н�����, ������, ��Ͻð�, ȣ��Ʈ����, IP

     	if(!file_exists("data/$picfn") && $isAdmin!=1)  continue; //�׸��� ���� �Ϲ��̸� ��ŵ
      $fpsav = ftell($fp);
      $intbl = 0;    
    }

  	else{  //����
      if($intbl==1) continue;
      
      $pos = strpos($buffer, $keyw);  //search
      if ($pos !== FALSE)  // === �ʼ�!
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
        //�˻�� �̸��̸� ip ������ ���α׸��������� Ȯ��.

     	  if($pagcnt==1)	$fsttime=$rtime;
       	else $lsttime=$rtime; //���� ������ �Ⱓ ���

        // �۾��ð��� �ú��� ������ ��ȯ
        $strjtime = sprintf("%d�ð� %d�� %d��",$sec/3600,($sec/60)%60,$sec%60);
        if($sec<3600)$strjtime = sprintf("%d�� %d��",($sec/60)%60,$sec%60);
        if($sec<60)$strjtime = sprintf("%d��",$sec%60);
        if($sec<=0)$strjtime = "�� �� ����";

       	if(!file_exists("data/$picfn") && $isAdmin!=1)  continue; //�׸��� ���� �Ϲ��̸� ��ŵ
       	else  $vhchoice = @GetImageSize("data/$picfn");

		print "<table width=$alltb_w border=0 cellpadding=0 cellspacing=0 align=center><td>"; // �Ʒ� ���̺� ��ü�� ���δ� table �ۼ�

		print "<div class=numMMB style='background:$pic_number;'>
		<a href='index.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>&nbsp;#</span></a>";
		print "<a href='delete.php?num=$picno'><span style='color:#fff; font-size:$pic_numsize;' class=numMMBspan>$picno</span></a>";
		print "</div>";		
		print "<TABLE id=cellMMB bgcolor='$logtb_bgc' style='border:$logtb_bor solid $logtb_borc;' width='100%' CELLSPACING='0' CELLPADDING='0' align=center>";
		print "<tr><TD align='center' valign='top' width=5% BGCOLOR='$pictb_bgc' rowspan='2' style='padding:12px 8px 12px 12px;'>\n"; // recent.php, search.php���� �׸��� ������ ���ʿ� ��ġ�ϸ鼭 ���� �е� 
		
       	if(!file_exists("data/$picfn")) echo "<center>Log<br>delete</center>"; // �����ڸ� ��ŵ�ʰ� ǥ��
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


		  
		include("mov.php"); //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// mov.php ��������



        }

        print "</td><TD BORDER=1 CELLSPACING=0 CELLPADDING=0 valign='top' width=100% style='background-color:$comtb_bgc; padding: 10px 10px 10px 0;'>\n"; // �ڸ�Ʈâ �е�
    	  // ���� �׸��� ����ũ�Ⱑ ���� ũ�� �̻��̸� ������ �׸� ������ ǥ���Ѵ�.
        $intbl = 1;
        //������� �׸� ǥ��
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
	print "</td></tr></table>"; // cellMMB �ݱ�
	print "</td></table><br>"; // ��ü ���� ���̺� �ݱ�
  $intbl = 0;
}

//next, prev ��ư
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
