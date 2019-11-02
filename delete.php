<html>
<head>
<title><?=$browser_title?></title>
<link rel=StyleSheet HREF=style.css type=text/css title=style>
</head>
<body>
<?
include "env.php";
include "option_data.php";
include "mtype_plugin/db_admin.php";

//비공개 게시판 모드
if($mem_login=='on'){
  if($memberlogin == $cfg_member_passwd);
  else{
    gourl("./admin.php?member=1");
    exit;
  }
}

function gourl($url)
{
	echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
	echo"</head></html>";
}

function print_member($num,$name,$time)
{
echo "
 <table width='320' border='0' cellspacing='0' cellpadding='0' height='100%' align='center'>
  <tr>
    <td>
      <div align=center>
		<b>DELETE?</b>
        <form name=formdel method=get action=delete_proc.php>
          <input type=password name=dpasswd size=5 style='border:none; border-bottom:1px solid #aaa; background:transparent;'>
          <input type=submit name=Submit value='DELETE' style='width:50px; font-size:7pt; font-weight:bold; color:#fff; border:1px solid #fff; background-color:#555;'>
          <input type=hidden name=dnum  value='$num'>
          <input type=hidden name=dauth value='$name'>
          <input type=hidden name=dtime value='$time'>
        </form>
        <p>&nbsp;</p>
      </div>
    </td>
  </tr>
</table>
";
}//보통의 삭제 패스워드 입력

if($restrict_del=='on'){
  if($action != 'login'){
  echo "
  <table width=640 border=0 cellspacing=0 cellpadding=0 align=center height=100%>
    <tr>
      <td>
        <div align=center>
          <p>1234</p>
          <form name=memdel method=get action=delete.php>
            <input type=password name=mpasswd>
            <input type=submit name=Submit value='인증'>
            <input type=hidden name=action value=login>
            <input type=hidden name=num  value='$num'>
            <input type=hidden name=name value='$name'>
            <input type=hidden name=time value='$time'>
          </form>
          <p>&nbsp;</p>
        </div>
      </td>
    </tr>
  </table>
  ";
  }
  else if($cfg_member_passwd != $mpasswd){
    echo "회원 패스워드가 틀렸습니다.\n";
  }
  else print_member($num,$name,$time);
}
else print_member($num,$name,$time);

?>
</body>
</html>