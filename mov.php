<?

	  if(!$mov) {
		  print "\n";
		  if($loadAdmin == 'on') // ����������
		  { 
		  	 if($isAdmin==1){
				print "<div style='width:100%; background:#333;color:#fff; text-align:center;'><b>ADMIN ONLY</b></div>";	

				if($loadWidth == 'on') // �ʺ�����
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxW></a></div>";
					elseif($loadWidthWide == 'on') // �ʺ� 600 ����
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxWide></a></div>";
					elseif($loadHeight == 'on') // ��������
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxH></a></div>";
					elseif($loadFold == 'on') // ����
					  { print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<img src=image/click.gif border=0>': '<img src=image/click.gif border=0>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'><img src=image/click.gif border='0'></span></a><div style=\"display: none;\">\n";
						print "<img src='$picfo/$picfn' class=width99 border='0'></a>";
						print "</div>"; }
					else print "<img src='$picfo/$picfn' class=width99 border='0'></a>";

			  }
			  else print "<div class=adminOnlyTd><img src=image/locked.gif border='0' style='padding-bottom:3px;'><br><b>ADMIN ONLY</b></div>\n";
		}
		  
		  elseif($loadMember == 'on') // �������
		  {  
			if($isAdmin==1 || $logout=="on" || $isMember == 1){
					print "<div style='width:100%; background:#cf2a19;color:#fff; text-align:center;'><b>MEMBERS ONLY</b></div>";

					if($loadWidth == 'on') // �ʺ�����
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxW></a></div>";
					elseif($loadWidthWide == 'on') // �ʺ� 600 ����
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxWide></a></div>";
					 elseif($loadHeight == 'on') // ��������
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxH></a></div>";
					elseif($loadFold == 'on') // ����
					  { print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<img src=image/click.gif border=0>': '<img src=image/click.gif border=0>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'><img src=image/click.gif border='0'></span></a><div style=\"display: none;\">\n";
						print "<img src='$picfo/$picfn' class=width99 border='0'></a>";
						print "</div>"; }
					else print "<img src='$picfo/$picfn' class=width99 border='0'></a>";
					

			  }
			  else {
				  print "</a>";
				  member_login();
				  print "\n";
			  }
		}
		  
		  elseif($loadFold == 'on') // ����
		  { print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<img src=image/click.gif border=0>': '<img src=image/click.gif border=0>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'><img src=image/click.gif border='0'></span></a><div style=\"display: none;\">\n";

					if($loadWidth == 'on') // �ʺ�����
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxW></a></div>";
					elseif($loadWidthWide == 'on') // �ʺ� 600 ����
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxWide></a></div>";
					elseif($loadHeight == 'on') // ��������
					  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxH></a></div>";
					else print "<img src='$picfo/$picfn' class=width99 border='0'></a>";
					
			print "</div>"; }
		  
		  elseif($loadWidth == 'on') // �ʺ�����
		  {  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxW></a></div>"; }

		  elseif($loadHeight == 'on') //��������
		  {  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxH></a></div>"; }
	
		  elseif($loadWidthWide == 'on') // �ʺ�����
		  {  print "<div class=mouseOn><a href='$picfo/$picfn' rel=lightbox class=foldImage><img src='$picfo/$picfn' class=maxWide></a></div>"; }
				
		  else // �׳� �ε�
		  print "<img src='$picfo/$picfn' class=width99 border='0'>"; 
		  
		} // $mov if�� ��
		
	  else { 
		  print "<div align=center>";
		  if($loadFold =='on') {
			  print "<a class=\"more\" onclick=\"this.innerHTML=(this.nextSibling.style.display=='none')?'<img src=image/click.gif border=0>': '<img src=image/click.gif border=0>';this.nextSibling.style.display=(this.nextSibling.style.display== 'none')?'block':'none';\" href=\"javascript:void(0);\" onfocus=\"blur()\"><span style='color:$kd_morecol; line-height:130%;'><img src=image/click.gif border='0'></span></a><div style=\"display: none;\">\n";
		  }
		  print "$mov";
		  if($loadFold =='on') print "</div>";
	  }
	  
?>