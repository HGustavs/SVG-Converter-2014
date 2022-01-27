<!DOCTYPE html>
<html>
<head>
		<script language="javascript" src="SVG_httpAjax.js"></script>
		<script language="javascript">
				
				function returnedSVG(htmltext)
				{
						var kind=document.getElementById('kind').value;


						// We only execute if the mode is set to canvas. JSON mode is executed in a separate manner
						if(kind==0){

                if(htmltext.indexOf("Function calls:")==-1){
                    var code=htmltext;
                    var funcs=[];
                    var params=[];
                    var wid=parseFloat(params[params.length-2]);
                    var hei=parseFloat(params[params.length-3]);
                    alert("NF!");
                }else{
                    var code=htmltext.substring(0,htmltext.indexOf("Function calls:")-3);
                    var funcs=htmltext.substring(htmltext.indexOf("Function calls:")-3,htmltext.length);
                    funcs=funcs.split("\n");
                    var params=funcs[0].split(" ");
                    var wid=0;
                    var hei=0;
                }

                var conto=document.getElementById('content');
						    conto.innerHTML=code;

                var prestr="var acanvas=document.getElementById('previewCanvas');acanvas.width=700;var ctx=acanvas.getContext('2d');ctx.translate(200,200);ctx.save();";
                var poststr="ctx.restore();ctx.strokeStyle='#464';ctx.beginPath();ctx.moveTo(-20,-20);ctx.lineTo(20,20);ctx.moveTo(20,-20);ctx.lineTo(-20,20);ctx.stroke();";
								eval(prestr);
                eval(code);

                ctx.save();

/*
                ctx.beginPath();
                ctx.moveTo(0,0);
                ctx.lineTo(wid,0);
                ctx.lineTo(wid,hei);
                ctx.lineTo(0,hei);
                ctx.closePath();
                ctx.stroke();
*/

                for(var i=1;i<funcs.length;i++){
                      eval(funcs[i]);
                }
                ctx.restore();

                ctx.save();
                ctx.translate(wid,0);
                for(var i=1;i<funcs.length;i++){
                      eval(funcs[i]);
                      ctx.translate(0,hei);
                }
                ctx.restore();
                
                eval(poststr);						
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
					$filelist=array();
			    while (($file = readdir($dir)) !== false) {
			        if(endsWith($file,".svg")){
									array_push($filelist,$file);
			      	}
			    }
					sort($filelist);
					foreach($filelist as $file){
			    		echo "<span onmouseover='this.style.backgroundColor=\"#000\";this.style.color=\"#fff\"' onmouseout='this.style.backgroundColor=\"#fff8f8\";this.style.color=\"#000\"' style='cursor:pointer;' onclick='getSVG(\"".$file."\",document.getElementById(\"decimals\").value,document.getElementById(\"kind\").value);'>".$file."</span><br>";						
					}
					
			?>
		</td>
		<td id='preview' class="svgview" valign="top">
			Preview:<br>
			<canvas width="700" height="2100" id="previewCanvas">
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