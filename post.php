<?
header("Content-type: text/plain");
include "env.php";
include "config_data.php";


$piclimit = $cfg_piclimit;

$input = $HTTP_RAW_POST_DATA;
$spos = strpos($input, "f\r\n");
$inlen = strlen($input);

if($spos === false) // '===' ��Ÿ�ƴմϴ�. 0���� false���� �����ϱ� ���� ��.
{
	print"�������� ��û�� �ƴմϴ�.";
	exit();
}
else{
	$spos = $spos+3;
}

$wtpos = strpos($input, "iWTM")+4;
if($wtpos>16)
{
	$tmpint = substr($input,$wtpos,4);
	$worktime = ord($tmpint[0])*0x1000000 + ord($tmpint[1])*0x10000 + ord($tmpint[2])*0x100 + ord($tmpint[3]);
}

// �� �κ��� JPG�ϰ���� �۾��ð� �б�
$wtpos = strpos($input, "bTOL")+8;
if($wtpos>20)
{
	$tmpint = substr($input,$wtpos,4);
	$worktime = ord($tmpint[0])*0x1000000 + ord($tmpint[1])*0x10000 + ord($tmpint[2])*0x100 + ord($tmpint[3]);
}

//�������ϸ� �����޼����� ������ BTool��Ʈ�ѿ��� �������������� �̵��� ����
$result = proclock();
if($result==0)
{
	print"���� �����߽��ϴ�.";
	exit();
}

if($spos<3)
{
	print "Error!\nsize:$inlen\n";
	exit();
	// �����α����ϰ���(�۾��ȵǾ�����;)
}
else
{
	$passwd = substr($input,0,$spos-3);

	$passtmp = $passwd;
  $passtmp = substr($passtmp,-2);
  if(strlen($passtmp)==0)  $passtmp=$passwd;//��й�ȣ�� ���ڸ��� ��ü
  $pw = crypt($passwd,$passtmp);
  if($passwd=="")$pw = "";

//--------dbindex���� pixcnt ����

 	$fp = fopen($dbindex,"r");
  $buffer = fgets($fp, 4096);
 	fclose($fp);
  $pixcount = intval($buffer)+1;

//-------�׸� �ۼ�------------
  $sjp = strpos($input, "JFIF");
  if ($sjp === false) { $file_ext = "png"; } else { $file_ext = "jpg"; }

	$nowtime = time();
	$newfile = "$nowtime.$file_ext";

	$fp = fopen ("$picfo/$newfile","wb");
	fwrite($fp,substr($input, $spos));
	fclose($fp);

//-------dbfile�� ���� ����
	$i_filesize=filesize("$picfo/$newfile");
	$outdata = array(">$pixcount","$newfile",$pw,time(),$REMOTE_ADDR,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide);
	$nowdata = join("|",$outdata);

  $dbnum = $pixcount%100;
  $dbfile = "$datafo/$dbnum.dat";

	if(!file_exists($dbfile)){
   	$fp = fopen($dbfile,"w");
		fclose($fp);
		chmod ($dbfile, 0666);
  	$fp = fopen("$dbfile","r");
	}
	else $fp = fopen("$dbfile","r");

	$cnt = 0;
  $delmode=0;
  while(!feof($fp))
  {
	  $buffer =$data[$cnt++] = fgets($fp,4096);
   	if(substr($buffer,0,1)==">"){ // ������ ���� �տ� '>'�� ������ �׸���
      $delmode=0;
     	$buffer = substr($buffer,1);
      $data_arr = explode("|", $buffer);
 	  	list($picno,$picfn,$pass,$rtime,$ip,$loadAdmin,$loadFold,$loadMember,$mov,$loadWidth,$loadHeight,$loadWidthWide) = $data_arr;
  		if($picno==$pixcount){
        $delmode=1;
        $cnt--;
    	}
    }
    else if($delmode==1)  $cnt--;
  }
  fclose($fp);

	$totalrec = $cnt;
	$cnt = 0;

	$fp = fopen("$dbfile","w");
	fputs($fp,$nowdata."\n");
	while($cnt<$totalrec)
	{
		fputs($fp,$data[$cnt++]);
	}
	fclose($fp);

//--------dbindex ���� ����-----------

	$cnt = 0;
	$fp = fopen("$dbindex","r");
	while(!feof($fp))
	{
		$data[$cnt++] = fgets($fp,4096);
	}
	fclose($fp);

	$cnt = 0;
	$fp = fopen("$dbindex","w");
	fputs($fp,$pixcount."\n");
 	while($cnt<$piclimit)
	{
		fputs($fp,$data[$cnt++]);
	}
	fclose($fp);
	$cnt = 0;

//--------����log ����----------------
	print "recvsize:";
	print $i_filesize;
	print "\nno:$pixcount\n";
  $allow = explode(",",$cfg_allowExt);
  if(is_array($allow)) $check = in_array($ext,$allow);

	if($pixcount>$piclimit){
		$delnum = $pixcount-$piclimit;
	  for($cnt=0;count($allow)>$cnt;$cnt++){
    	@unlink("$picfo/$delnum.".$allow[$cnt]);
    }
	}
}
// ���
procunlock();
print "lock:$result\n";

// ��
?>