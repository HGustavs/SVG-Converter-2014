<html>
<head>
		<script language="javascript" src="SVG_httpAjax.js"></script>
		<script language="javascript">
				function returnedSVG(htmltext)
				{

						var conto=document.getElementById('content');
						conto.innerHTML=htmltext;

						str='var acanvas=document.getElementById("previewCanvas");acanvas.width=700;var c=acanvas.getContext("2d");'+htmltext;
						eval(str);

				}
		</script>
</head>
<body>
<table><tr>
	<td style="border:outset 3px #ffeedd; background-color: #fff8f8;font-family:Calibri,Georgia,Serif;" valign="top">
<?php

		function endsWith($haystack,$needle,$case=true) {
		    if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
		    return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
		}

		$dir = opendir('./Examples');
    while (($file = readdir($dir)) !== false) {
        if(endsWith($file,".svg")){
        		echo "<span onmouseover='this.style.backgroundColor=\"#000\";this.style.color=\"#fff\"' onmouseout='this.style.backgroundColor=\"#fff8f8\";this.style.color=\"#000\"' style='cursor:pointer;' onclick='getSVG(\"".$file."\");'>".$file."</span><br>";
      	}
    }

?>
</td>
<td id='preview' style="border:outset 3px #ffeedd; background-color: #fff8f8;font-family:Calibri,Georgia,Serif;" valign="top">
	Preview:<br>
	<canvas width="700" height="700" id="previewCanvas">
	</canvas>
</td>
<td style="border:outset 3px #ffeedd; background-color: #fff8f8;" valign="top">
		<textarea id='content' style='font-family:Calibri,Georgia,Serif;width:400px;height:800px;'>
		</textarea>
</td>
</tr></table>
</body>
</html>