<?
include "KDM_skin_data.php";
include "KDM_fontcol_data.php";
include "KDM_tb_data.php";
?>
<html>
<head>
<title>이모티콘 목록</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
</head>
<body  bgcolor="<?=$menutb_bgc?>" text="<?=$b_fo?>" link="<?=$li_fo?>" vlink="<?=$vi_fo?>" alink="<?=$ac_fo?>">
<center><font size=2><p>이모티콘 목록</p></font>
<TABLE BORDER='0' width="200" CELLSPACING='2' CELLPADDING='0' align='center' valign='top'>

<?
include "config_data.php";
include "env.php";




$cp = fopen("$datafo/emote_data.txt", "r");
$exc_icon=0;
$cnt=0;
$isAdmin=0;

// 관리자 패스워드쿠키가 있으면서 관리자암호와 같으면 관리자모드임
if($ckadminpasswd == $cfg_admin_passwd && $ckadminpasswd !="")
{
	$isAdmin = 1;
}

while(!feof($cp)) {
  for($cnt=0;$cnt<$cfg_emolist && !feof($cp);$cnt++){
    $first_arg[$cnt] = chop(fgets($cp, 4096));
    $second_arg[$cnt] = chop(fgets($cp, 4096));
    if(substr($first_arg[$cnt],0,3)=="---"){
      if($isAdmin==0){
        $exc_icon=1;
        break;
      }
      else{
        $cnt--;
        continue;
      }
    }
  }
  echo "<tr>\n";

  for($prt=0;$prt<$cnt;$prt++)
  	echo "<td align=center valign=bottom BGCOLOR=$menutb_bgc CELLSPACING=0 CELLPADDING=0 width='200'><img src='image/$second_arg[$prt]' border=0></td>\n";
  echo  "</tr><tr>\n";
  for($prt=0;$prt<$cnt;$prt++)
   	echo  "<td align=center valign=bottom BGCOLOR=$menutb_bgc CELLSPACING=0 CELLPADDING=0 width='200'><center><span style='font-size:9pt;'>$first_arg[$prt]</center></td>\n";
  echo  "</tr>\n";

  if($exc_icon==1) break;
}
?>
</TABLE></center>
</body></html>