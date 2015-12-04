<html>
<head>
		<script language="javascript" src="SVG_httpAjax.js"></script>
		<script language="javascript">
				
				function returnedSVG(htmltext)
				{
						var kind=document.getElementById('kind').value;

						var conto=document.getElementById('content');
						conto.innerHTML=htmltext;

						// We only execute if the mode is set to canvas. JSON mode is executed in a separate manner
						if(kind==0){
								str='var acanvas=document.getElementById("previewCanvas");acanvas.width=700;var c=acanvas.getContext("2d");c.translate(200,200);c.save();'+htmltext+"c.restore();c.strokeStyle='#464';c.beginPath();c.moveTo(-20,-20);c.lineTo(20,20);c.moveTo(20,-20);c.lineTo(-20,20);c.stroke();";
								console.log(str);
								eval(str);						
						}else{
								// If it is a json object do nothing for now!
						}
			}
		
		</script>
		<style>
				.svgview{
						box-shadow: 1px 1px 2px #876 inset; 
						border: 1px solid #ffeedd; 
						background-color: #fff8f8; 
						border-radius:6px;
						font-family: calibri;
						font-size: 16px;
						margin: 3px;
						padding: 4px;
				}	
		</style>
</head>
<body>
<table>
	<tr>
			<td colspan="3">
				Settings:
				
				Decimals: 
					<select id="decimals">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				Kind: 
					<select id="kind">
						<option value="0">Canvas</option>
						<option value="1">JSON</option>
					</select>
				
			
			</td>
	</tr>
	<tr>
		<td class="svgview" id='preview' valign="top">
			<?php
					function endsWith($haystack,$needle,$case=true) {
					    if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
					    return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
					}
					$dir = opendir('./Examples');
			    while (($file = readdir($dir)) !== false) {
			        if(endsWith($file,".svg")){
			        		echo "<span onmouseover='this.style.backgroundColor=\"#000\";this.style.color=\"#fff\"' onmouseout='this.style.backgroundColor=\"#fff8f8\";this.style.color=\"#000\"' style='cursor:pointer;' onclick='getSVG(\"".$file."\",document.getElementById(\"decimals\").value,document.getElementById(\"kind\").value);'>".$file."</span><br>";
			      	}
			    }
			?>
		</td>
		<td id='preview' class="svgview" valign="top">
			Preview:<br>
			<canvas width="700" height="700" id="previewCanvas">
			</canvas>
		</td>
		<td id='preview' class="svgview" valign="top">
				<textarea id='content' style='font-family:Calibri,Georgia,Serif;width:400px;height:800px;'>
				</textarea>
		</td>
	</tr>
</table>
</body>
</html>