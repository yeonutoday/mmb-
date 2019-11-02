<?
include "env.php";
include "KDM_skin_data.php";
include "KDM_tb_data.php";

if($logout=="on"){
	setcookie ("ckadminpasswd","",time()-3600);
	$ckadminpasswd="";
}

// 관리자 패스워드쿠키가 있으면서 관리자암호와 같으면 인증됨 표시
if($ckadminpasswd === $cfg_admin_passwd && $ckadminpasswd !="")
{
	$adminpasswd = $ckadminpasswd;
}

if($adminpasswd === $cfg_admin_passwd)
{
	setcookie ("ckadminpasswd", $adminpasswd,time()+30*24*3600);
	$isAdmin = 1;
}

// 비공개 게시판 모드 비밀번호 체크
if($memberpasswd === $cfg_member_passwd)
{
  setcookie ("memberlogin",$memberpasswd,0);
  $isMember = 1;
}
else $isMember = 0;

// 관리자로그인되지 않았을때 보여줄 화면
function print_authscr()
{
	global $bgurl; global $bgcol;
echo "
<html>
<head>
<title>Administrator Authorization</title>
<link rel=StyleSheet HREF=style.css type=text/css title=style>
</head>

<body background='$bgurl' bgcolor='$bgcol' text='#000000'>
<form name='admin' method='post' action='admin.php'>
  <table width='320' border='0' cellspacing='0' cellpadding='0' height='100%' align='center'>
  <tr>
    <td valign='center' align='center'>
		<b>ADMINSTRATOR LOG IN</b>
		<br>
			<input type='password' name='adminpasswd' size=5 style='border:none; border-bottom:1px solid #aaa; background:transparent;'>
			<input type='submit' name='submit2' value='ENTER' style='width:50px; font-size:7pt; font-weight:bold; color:#fff; border:1px solid #fff; background-color:#555;'>	
    </td>
  </tr>
</table>
</form>
";
}

// 멤버 로그인 화면
function member_login()
{
	global $bgurl; global $bgcol;
echo "
<html>
<head>
<title>MEMBER LOG IN</title>
<link rel=StyleSheet HREF=style.css type=text/css title=style>
</head>

<body background='$bgurl' bgcolor='$bgcol' text='#000000'>
<form name='member' method='post' action='admin.php?member=1'>
  <table width='320' border=0 cellspacing='0' cellpadding='0' height='100%' align='center'>
  <tr>
    <td valign=center align=center>
		<b>MEMBER LOG IN</b>
		<br>
            <input type='password' name='memberpasswd' size=5 style='border:none; border-bottom:1px solid #aaa; background:transparent;'>
           	<input type='hidden' name='mid' value='./index.php'>
			<input type='submit' name='submit2' value='ENTER' style='width:50px; font-size:7pt; font-weight:bold; color:#fff; border:1px solid #fff; background-color:#555;'>	
    </td>
  </tr>
</table>
</form>
";
}

if($member == 1 && $isMember == 0){
  member_login();
}
else if($isAdmin==1 || $logout=="on" || $isMember == 1){
	echo("
	<html>
	<head>
	<title><?=$browser_title?></title>
	<meta http-equiv='refresh' content='0; url=./index.php'>
	</head></html>
	");
}
else{
  print_authscr();
}
?>
</body>
</html>
