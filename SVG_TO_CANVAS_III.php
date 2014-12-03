<?php
// Version 3.7
//    Bug: Some times context instead of c
//		Bug: Invisible Lines
//		Fix: c instead of canvas and (linex1,liney1) to (linex2,liney2 instead of linex1,liney1)
//    Bug: Texts with even very simple tspans fail.
//		Fix: Simple tspan workaround.
//--------------------------------------------------------------------------
// Version 3.6 
//		Transparency fix for ellipse/circle
//		Group Start and Group End Handling
//		Preliminary Clipping Test
//				Defs mode handling
//				Clippath Translation
//				Defs Implementation
//							Polygon / Polyline etc. 
//				Single Object Clip
//							Rect
//				Grouped Object Clip (Not Implemented)
//	  Fix for "Line" Command. "Dog" from wikimedia now works
//    Fix for SVG_Award Scientific number parsing for dostr loop in polygons and lixnegroups 368.99742,-1.6192e from -1.6192e-006 now makes better string.
//    Fix for lack of SVG arc command for path - skip operation draw line instead ... Bug: SVG_Award c.fillStyle = '#ffffff'; c.lineWidth = "0.5"; c.moveTo(431.36357,334.73636); c.lineTo(A,60.482193);
//    Fix for SVG Font Styling using style="" attribute from wikipedia proporcion example file
//		Fix for SVG Style attribute errors if no ; is found i.e. attribute is last attribute of style string the code now works (untested)
//		Fix for SVG Font Size Bug - px was replaced with 0 instead of "" meaning that some times 2px would turn to 20
//		Fix: fontstyle or line style etc missing if style="" is used instead of font attributes. Error produced: fontstyle fontline fontfamily missing line 707-709 / Translate Rotate Scale missing 710-712	among others svg ARC 02	 Should work now.
//		Bug: Empty text in SVG text in proporcion
//    Bug: If fill is after line, the change of fill is performed after line is drawn. (SVI_CFG from wikipedia and proporcion from wikipedia)
//		Bug: translate support for groups or individual objects such as proporcion transform="translate(642.5,-1381.291)" or transform="matrix(0.999962,-8.674822e-3,8.674822e-3,0.999962,1115.829,2072.128)" 
//--------------------------------------------------------------------------
// Version 3.5
//    Fix for stroking of some objects
//		Stroked text and font
//    Handler for Bold Oblique Italic and Bold Italic and some other combos. Not all is covered
//--------------------------------------------------------------------------
// Version 3.4
//		Support for Circles / Ellipses
//		Support for Groups
//    Limited Default Fill 
//--------------------------------------------------------------------------
// Version 3.3
//		Fix for mixed gradient and non-gradient objects
//		Fix for all black objects
//		Radial Gradients added
//		Transparency added
//		Ellipse / Circle Support
//		Rectangle Fix
//--------------------------------------------------------------------------
// Version 3.2
//		Preliminary Support for Gradients
//		Gradient support for Illustrator (svg1.1 export) and Inkscape
//--------------------------------------------------------------------------
// Version 3.1
//		Fixed no-fill styles and colored strokes
//		Fixed drawing of polygons
//		Fixed inkscape support for styles
//--------------------------------------------------------------------------
// Version 3.0
// 		Supports the new user interface
//		Works even without filename or with errors in file name 
//		Fixed strokes so that strokes are above fills in all cases
//-------------------------------------------------------------------------- Future Version Blueprint
// 
//    Curve Closing (html5)
//		Support for transformed gradients (??Is this in current version??)
// 		Real Arc Drawing using Complex Math from values, draw arc using math in formula
//    Support for clip masks
//		Support for Javascript errors in evaluated script with line errors
//		Better Support for printing ID-s
//		Drawing support
//		Click-map drawing support
//		Font/styling support
//		Tspan support
//		golf_example 
//		Smarter ID printing depending on kind of element.... i.e. line etc but different behavior on group/text etc
//		Supporting of use statement for instancing like in golf_field example

$elementcounter=0;
$graphnodes=array();
$colorstops=array();
$clipdefs=array();
$clippaths=array();

$fillstyle="none";
$linestyle="none";
$opacity="1.0";
$gradientname="foo";

$isinkscape=false;

$stopcounter=0;

function recurseelement($element){
		global $elementcounter;
		global $graphnodes;

		if($element->getName()=="clipPath"){
					$attrs=$element->attributes();
					$sxe = new SimpleXMLElement("<use id='".$attrs['id']."' />");
					$graphnodes[$elementcounter]=$sxe;
					$elementcounter++;
		}
		
		foreach ($element->children() as $child) {
				if($child->getName()=="clipPath"||$child->getName()=="defs"||$child->getName()=="g"||$child->getName()=="linearGradient"||$child->getName()=="radialGradient"){

						// Most others except for clipPath are added
						if($child->getName()=="defs"||$child->getName()=="g"||$child->getName()=="linearGradient"||$child->getName()=="radialGradient"){
								$graphnodes[$elementcounter]=$child;
								$elementcounter++;
						}
						recurseelement($child);

				}else if($child->getName()=="use"||$child->getName()=="ellipse"||$child->getName()=="circle"||$child->getName()=="stop"||$child->getName()=="polygon"||$child->getName()=="line"||$child->getName()=="polyline"||$child->getName()=="path"||$child->getName()=="rect"||$child->getName()=="text"||$child->getName()=="tspan"){
						// Add element to queue
						$graphnodes[$elementcounter]=$child;
						$elementcounter++;
				}else{
						echo "//Unknown inner element: ".$child->getName()."\n";
				}
		}		

		if($element->getName()=="g"){
									// End of group code
									$attrs=$element->attributes();			
									$sxe = new SimpleXMLElement("<eg id='".$attrs['id']."' />");
									$graphnodes[$elementcounter]=$sxe;
									$elementcounter++;
						
		}

		if($element->getName()=="defs"){
									$sxe = new SimpleXMLElement("<defsend/>");
									$graphnodes[$elementcounter]=$sxe;
									$elementcounter++;
						
		}

}

if(isset($_POST['svgname'])){

			$svg = simplexml_load_file("Examples/".$_POST['svgname']);
			
			// Recurse into elements and add to element stack
			// its important that we only process hierarchies of g elements and layers
			foreach ($svg as $element) {
					if($element->getName()=="clipPath"||$element->getName()=="defs"||$element->getName()=="g"||$element->getName()=="linearGradient"||$element->getName()=="defs"||$element->getName()=="radialGradient"||$element->getName()=="text"){
							if($element->getName()=="clipPath"||$element->getName()=="defs"||$element->getName()=="g"||$element->getName()=="linearGradient"||$element->getName()=="radialGradient"||$element->getName()=="text"){
									$graphnodes[$elementcounter]=$element;
									$elementcounter++;
							}
							recurseelement($element);
					}else if($element->getName()=="use"||$element->getName()=="radialGradient"||$element->getName()=="linearGradient"||$element->getName()=="polygon"||$element->getName()=="line"||$element->getName()=="polyline"||$element->getName()=="path"||$element->getName()=="rect"||$element->getName()=="ellipse"||$element->getName()=="circle"||$element->getName()=="tspan"){
							// Add element to queue
							$graphnodes[$elementcounter]=$element;
							$elementcounter++;
					}else{
							echo "//Unknown outer element: ".$element->getName()."\n";
					}
			}

			$defsmode=0;
			$defsstring="";
			$defsid="";
			$clipid="";
						
			// Process elements
			foreach ($graphnodes as $graphelement) {

				// Clear Line Style and Fill Styles
				$fillstyle="none";
				$linestyle="none";	
				$opacity="1.0";

				// For tspan element get text content...
				// This currently clashes with the text element.
				if($graphelement->getName()=="tspan"){
						echo "// tspan ";
						if(isset($graphelement[0])){
								$textline=$graphelement[0];
						}
						echo  "\n";
				}
							
				// For text element get (simple tspan fix... now supports simple tspans)
				if($graphelement->getName()=="text"){
						echo "// text";
						if(isset($graphelement[0])){
								// There is a tspan (only a tspan?)
								if(property_exists ( $graphelement[0] ,"tspan" )){
										$textline=($graphelement[0]->tspan);																
								}else{
										$textline=$graphelement[0];								
								}
						}
						echo  "\n";
				}

				// ID printing disabled for clarity?
				if(isset($attrs['id'])){
//						echo "// ID: ".$attrs['id']."\n";
				}

				// To get ID comment/code
				$attrs=$graphelement->attributes();
				$xlinkattrs=$graphelement->attributes('http://www.w3.org/1999/xlink');

				// For use element get 
				if($graphelement->getName()=="use"){
						if(isset($attrs['id'])){
								// We are in an id use statement
								$clipid=strval($attrs['id']);
						}else{
								// We are in the reference use statement
								$clippaths[$clipid]=substr(strval($xlinkattrs['href']),1);
						}
				}

				if($graphelement->getName()=="linearGradient"){
						
						if(isset($attrs['id'])){
								$gradientname=$attrs['id'];
						}
						if(!isset($attrs['x1'])){
								// Linear Gradient is not complete, this means that it is an inkscape element!
								$isinkscape=true;
						}else if(isset($xlinkattrs['href'])){

								echo "var ".$gradientname."=c.createLinearGradient(".$attrs['x1'].",".$attrs['y1'].",".$attrs['x2'].",".$attrs['y2'].");\n";

								// Now we create a new gradient with the following properties
								$gradientref=$xlinkattrs['href'];
								$gradientref=substr($gradientref,1,strlen($gradientref)-1);
								
								if(isset($colorstops["$gradientref"])){
										foreach($colorstops["$gradientref"] as $key => $value){
												echo $gradientname.".addColorStop(".$value.");\n";
										}
								}
																
						}else{
								echo "var ".$gradientname."=c.createLinearGradient(".$attrs['x1'].",".$attrs['y1'].",".$attrs['x2'].",".$attrs['y2'].");\n";
						}
				}else if($graphelement->getName()=="radialGradient"){
						if(isset($attrs['id'])){
								$gradientname=$attrs['id'];
						}
						if(!isset($attrs['cx'])){
								// Radial Gradient is not complete, this means that it is an inkscape element!
								$isinkscape=true;
						}else if(isset($xlinkattrs['href'])){
								echo "var ".$gradientname."=c.createRadialGradient(".$attrs['cx'].",".$attrs['cy'].",0,".$attrs['cx'].",".$attrs['cy'].",".$attrs['r'].");\n";

								// Now we create a new gradient with the following properties
								$gradientref=$xlinkattrs['href'];
								$gradientref=substr($gradientref,1,strlen($gradientref)-1);
								
								if(isset($colorstops["$gradientref"])){
										foreach($colorstops["$gradientref"] as $key => $value){
												echo $gradientname.".addColorStop(".$value.");\n";
										}
								}
																
						}else{
								echo "var ".$gradientname."=c.createRadialGradient(".$attrs['cx'].",".$attrs['cy'].",0,".$attrs['cx'].",".$attrs['cy'].",".$attrs['r'].");\n";
						}
				}else if($graphelement->getName()=="stop"){
						$stopcolor=$attrs['style'];
						if(strpos($stopcolor,";",11)!==false){
								$stopcolorend=strpos($stopcolor,";",11);
						}else{
								$stopcolorend=strlen($stopcolor);
						}
						$stopcolor=substr($stopcolor,11,$stopcolorend-11);
						
						if($isinkscape){
									if(isset($colorstops["$gradientname"])){
											array_push($colorstops["$gradientname"],$attrs['offset'].",'".$stopcolor."'");
									}else{
											$poo=array();
											array_push($poo,$attrs['offset'].",'".$stopcolor."'");
											$colorstops["$gradientname"]=$poo;
									}
						}else{
									echo $gradientname.".addColorStop(".$attrs['offset'].",'".$stopcolor."');\n";
						}
						
				}else{
								// We assume that defsid is the only use of ID
								if(isset($attrs['id'])) $defsid=$attrs['id'];
				}

				// For each attribute of svg 								

				// We process attributes after gradients but before the drawing elements.

			  foreach ($graphelement->attributes() as $key => $val) {

					// Get font parameters!
			    if ($key == "if"&&$graphelement->getName()=="text"){
							$fontline=$val;
			    }		
			  	
					// Get font parameters!
			    if ($key == "font-size"&&$graphelement->getName()=="text"){
							$fontline=$val;
			    }		
			    	    
			    if ($key == "font-family"&&$graphelement->getName()=="text"){
							$fontfamily=$val;
			    		$fontfamily=str_replace("'","",$fontfamily);
			    		$fontstyle="";
			    		if(!(strpos($fontfamily,"-Oblique")===FALSE)){
			    				$fontfamily=str_replace("-Oblique","",$fontfamily);
			    				$fontstyle="Bold";			    				
			    		}
			    		if(!(strpos($fontfamily,"-Bold")===FALSE)){
			    				$fontfamily=str_replace("-Bold","",$fontfamily);
			    				$fontstyle="Bold";			    				
			    		}
			    		if(!(strpos($fontfamily,"-Italic")===FALSE)){
			    				$fontfamily=str_replace("-Italic","",$fontfamily);
			    				$fontstyle="Italic";			    				
			    		}			    		
			    		if(!(strpos($fontfamily,"Bold")===FALSE)){
			    				$fontfamily=str_replace("Bold","",$fontfamily);
			    				$fontstyle=$fontstyle." Bold";			    				
			    		}
			    		if(!(strpos($fontfamily,"Italic")===FALSE)){
			    				$fontfamily=str_replace("Italic","",$fontfamily);
			    				$fontstyle=$fontstyle." Italic";			    				
			    		}

			    }

			    if ($graphelement->getName()=="text"){
							if($key=="x") $textx=$val;
							if($key=="y") $texty=$val;
			    }

			    if ($graphelement->getName()=="rect"){
							if($key=="x") $linex1=$val;
							if($key=="y") $liney1=$val;
							if($key=="width") $linex2=$val;
							if($key=="height") $liney2=$val;
			    }

			    if ($graphelement->getName()=="ellipse"||$graphelement->getName()=="circle"){
							// Element is either a circle or an ellipse
							if($key=="cx") $cx=$val;
							if($key=="cy") $cy=$val;
							if($key=="r"){
									$rx=$val;
									$ry=$val;
							} 
							if($key=="rx") $rx=$val;
							if($key=="ry") $ry=$val;
			    }
			    
			    if (($key == "x1"||$key == "y1"||$key == "x2"||$key == "y2")&&$graphelement->getName()=="line"){
							// Element is a line element with 4 coordinate parameters
							if($key=="x1") $linex1=$val;
							if($key=="x2") $linex2=$val;
							if($key=="y1") $liney1=$val;
							if($key=="y2") $liney2=$val;
			    }else if ($key == "transform"&&$graphelement->getName()=="text"){
			    	
			 				$j=0;
							$dostr="";
							$params=array();
							$noparams=0;
							$workstr=$val;
			
							do{
									$dochr=substr($workstr,$j,1);
									if($dochr==" "||$dochr=="("||$dochr==")"){
			   							if(trim($dostr)!=""&&$dostr!="matrix"){
			   									$params[$noparams++]=$dostr;
			   							}
											if($dochr=="-"){
													$dostr=$dochr;		 										
											}else{
													$dostr="";
											}
									}else{
											$dostr.=$dochr;
									}
									$j++;
							}while($j<=strlen($workstr));    									
			   			if(trim($dostr)!=""){
									$params[$noparams++]=$dostr;
							}
							 				
							$translate="c.translate(".$params[4].",".$params[5].");\n";
							$rotate="c.rotate(".$params[1].",".$params[2].");\n";
							$scale="c.scale(".$params[0].",".$params[3].");\n";
															
			    }elseif ($key == "stroke"){
							echo "" .'c.strokeStyle = "' . $val . '";' . "\n";
							$linestyle=$val;
			    }elseif ($key == "opacity"){
			    		$opacity=$val;
			    }elseif ($key == "fill"){
				      if($val!="none"){
									if(strpos($val,"url(")===false){
											echo "" .'c.fillStyle = "' . $val. '";' . "\n";
									}else{
											echo "c.fillStyle=".substr($val,5,strlen($val)-6).";\n";
									}
							}
							$fillstyle=$val;
			    }elseif ($key == "style"){
			    		$fillpos=strpos($val,"fill:");
			    		if($fillpos!==false){
			    				$fillposend=strpos($val,";",$fillpos);
		    		  		if($fillposend===false) $fillposend=strlen($val);		    		
			    				$fillstyle=substr($val,$fillpos+5,$fillposend-$fillpos-5);
							    if($fillstyle!="none"){
											if(strpos($fillstyle,"url(")===false){
													echo "c.fillStyle = '".$fillstyle ."';\n";
											}else{
													echo "c.fillStyle=".substr($fillstyle,5,strlen($fillstyle)-6).";\n";
											}
									}	
			    		}

			    		$strokepos=strpos($val,"stroke:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$linestyle=substr($val,$strokepos+7,$strokeposend-$strokepos-7);
							      if($linestyle!="none"){
												echo "" .'c.strokeStyle = "' . $linestyle . '";' . "\n";
										}
			    		}

			    		$strokepos=strpos($val,"stroke-width:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$strokewidth=substr($val,$strokepos+13,$strokeposend-$strokepos-13);
										$strokewidth=str_replace("px","",$strokewidth);
										echo "\n" . 'c.lineWidth = "' . $strokewidth . '";' . "\n";
			    		}	

			    		$fontsizepos=strpos($val,"font-size:");
			    		if($fontsizepos!==false){
			    		  		$fontsizeend=strpos($val,";",$fontsizepos);
			    		  		if($fontsizeend===false) $fontsizeend=strlen($val);
							  		$fontline=substr($val,$fontsizepos+10,$fontsizeend-$fontsizepos-10);
										$fontline=str_replace("px","",$fontline);
			    		}	

			    		$fontfamilypos=strpos($val,"font-family:");
			    		if($fontfamilypos!==false){
			    		  		$fontfamilyend=strpos($val,";",$fontfamilypos);
			    		  		if($fontfamilyend===false) $fontfamilyend=strlen($val);
							  		$fontfamily=substr($val,$fontfamilypos+12,$fontfamilyend-$fontfamilypos-12);
			    		}	

			    		$fontstylepos=strpos($val,"font-style:");
			    		if($fontstylepos!==false){
			    		  		$fontstyleend=strpos($val,";",$fontstylepos);
			    		  		if($fontstyleend===false) $fontstyleend=strlen($val);
							  		$fontstyle=substr($val,$fontstylepos+11,$fontstyleend-$fontstylepos-11);
			    		}	
			    					    				    		
			    }elseif ($key == "stroke-width"){
							echo "\n" . 'c.lineWidth = "' . $val . '";' . "\n";
			    }elseif ($key == "points"&&($graphelement->getName()=="polygon"||$graphelement->getName()=="polyline"||$graphelement->getName()=="line")) {
			      	if($defsmode){
			      			$defsstring.="c.beginPath();\n";			      				      				      	
			      	}else{
			      			echo "c.beginPath();\n";			      	
			      	}

							// dostr loop for polygons. Bugfix: if old char is e, then we do not break string at -							
							$j=0;
							$dostr="";
							$dochr="";
							$params=array();
							$noparams=0;
							$workstr=$val;
							$oldchr="";
							do{
									$oldchr=$dostr;
									$dochr=substr($workstr,$j,1);
									if(($dochr=="-" && $oldchr!="e" && $oldchr!="E")||$dochr==","||$dochr==" "){
			   							if(trim($dostr)!=""){
			   									$params[$noparams++]=$dostr;
			   							}
											if($dochr=="-"){
													$dostr=$dochr;		 										
											}else{
													$dostr="";
											}
									}else{
											$dostr.=$dochr;
									}
									$j++;
							}while($j<=strlen($workstr));    									
			   			if(trim($dostr)!=""){
									$params[$noparams++]=$dostr;
							}
							
							for($j=0;$j<$noparams;$j+=2){
										if($j==0){
							      	if($defsmode){
													$defsstring.="c.moveTo(".$params[$j].",".$params[$j+1].");\n";							
							      	}else{
													echo "c.moveTo(".$params[$j].",".$params[$j+1].");\n";							
							      	}
										}else{
							      	if($defsmode){
													$defsstring.="c.lineTo(".$params[$j].",".$params[$j+1].");\n";														
							      	}else{
													echo "c.lineTo(".$params[$j].",".$params[$j+1].");\n";														
							      	}
										}
							}
			
							// If a polygon close path if not i.e. polyline keep it open
							if($noparams>=2&&$graphelement->getName()=="polygon"){
					      	if($defsmode){
											$defsstring.="c.lineTo(".$params[0].",".$params[1].");\n";														
					      	}else{
											echo "c.lineTo(".$params[0].",".$params[1].");\n";														
					      	}
							}
						
			      	if(!$defsmode){
									echo "c.globalAlpha = $opacity;\n";
			      	}

							if($fillstyle=="none"&&$linestyle=="none"){
					      	if(!$defsmode){
											echo 'c.fillStyle = "#000";';
											echo "\n";
						      		echo "c.fill();\n\n";
					      	}
							}
							if($fillstyle!="none"){
					      	if(!$defsmode){
						      		echo "c.fill();\n";
						    			if($linestyle=="none") echo "\n";
					      	}
					    }
					    if($linestyle!="none"){
					      	if(!$defsmode){
						      		echo "c.stroke();\n\n";
					      	}
					    }
					    
					    if($defsmode){
					    		$defsstring.="c.clip();\n\n";		
					    }
				    								
			    }elseif ($key == "d") {
			    	
			    	$dval=$val;
			    	
			    }
			  }

				// Draw d line commands. This is a fix for the bug that requires the line settings to be assigned before the line commands
				
				if(isset($dval)){
				
			      echo "c.beginPath();\n";
			      
			      $i=0;
			      $str=$dval;
			      $workstr="";
			      $command="";
			      $cx=0;
			      $cy=0;
			      $firstpoint=0;
			      $firstpointx=0;
			      $firstpointy=0;
			      $lastpointx=0;
			      $lastpointy=0;
			      do{
			     			$chr=substr($str,$i,1);
			     			if($chr=="H"||$chr=="h"||$chr=="V"||$chr=="v"||$chr=="M"||$chr=="m"||$chr=="C"||$chr=="c"||$chr=="L"||$chr=="A"||$chr=="a"||$chr=="l"||$chr=="z"||$chr=="Z"||$chr=="s"||$chr=="S"||$i==strlen($str)){
									// Process Parameters for any parameter command
									if($command=="M"||$command=="m"||$command=="c"||$command=="C"||$command=="v"||$command=="V"||$command=="h"||$command=="H"||$command=="s"||$command=="S"||$command=="l"||$command=="L"||$command=="a"||$command=="A"){
					    				$j=0;
					  					$dostr="";
											$dochr="";
					  					$oldchr="";
					  					$params=array();
					  					$noparams=0;
					  					
					  					// dochr loop for other drawing commands. Bug fix: If we encounter - and before it is an e we do nothing, otherwise proceed as normal
					  					
					  					do{
					 								$oldchr=$dochr;
					 								$dochr=substr($workstr,$j,1);
					 								if(($dochr=="-" && $oldchr!="e" && $oldchr!="E")||$dochr==","||$dochr==" "){
						     							if(trim($dostr)!=""){
						     									$params[$noparams++]=$dostr;
						     							}
					 										if($dochr=="-"){
					 												$dostr=$dochr;		 										
					 										}else{
					 												$dostr="";
					 										}
					 								}else{
					 										$dostr.=$dochr;
					 								}
					 								$j++;
					  					}while($j<=strlen($workstr));    									
						     			if(trim($dostr)!=""){
													$params[$noparams++]=$dostr;
											}
									}
			
			     				if($command=="M" || $command=="m"){
											for($j=0;$j<$noparams;$j+=2){
													if($j==0){
																// Normal moveto set cx,cy
																if($command=="M"){
																				$cx=$params[$j];
																				$cy=$params[$j+1];
																				echo "c.moveTo(".$cx.",".$cy.");\n";
																				$firstpoint=1;
																	      $firstpointx=$cx;
			      														$firstpointy=$cy;
																}else if($command=="m"){
																		if(!$firstpoint){
																				$cx=$params[$j];
																				$cy=$params[$j+1];
																				echo "c.moveTo(".$cx.",".$cy.");\n";
																				$firstpoint=1;
																	      $firstpointx=$cx;
			      														$firstpointy=$cy;
																		}else{
																				$cx+=$params[$j];
																				$cy+=$params[$j+1];															
																				echo "c.moveTo(".$cx.",".$cy.");\n";
																		}													
																}
													}else{
																// Implicit lineto
																if($command=="M"){
																		$cx=$params[$j];
																		$cy=$params[$j+1];
																		echo "c.lineTo(".$cx.",".$cy.");\n";
																}else if($command=="m"){
																		$cx+=$params[$j];
																		$cy+=$params[$j+1];															
																		echo "c.lineTo(".$cx.",".$cy.");\n";
																}
													}
											}
			     				}else if ($command=="C"||$command=="c"){
			     						// Bezier Curveto
											for($j=0;$j<$noparams;$j+=6){
													if($command=="C"){
															$p1x=$params[$j];
															$p1y=$params[$j+1];
															$p2x=$params[$j+2];
															$p2y=$params[$j+3];
															$cx=$params[$j+4];
															$cy=$params[$j+5];
															$lastpointx=$p2x;
															$lastpointy=$p2y;
															echo "c.bezierCurveTo(".$p1x.",".$p1y.",".$p2x.",".$p2y.",".$cx.",".$cy.");\n";
															// Curveto absolute set cx to final point, other coordinates are control points
													}else if($command=="c"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$p1x=$cx+$params[$j];
															$p1y=$cy+$params[$j+1];
															$p2x=$cx+$params[$j+2];
															$p2y=$cy+$params[$j+3];
															$lastpointx=$p2x;
															$lastpointy=$p2y;
															$cx+=$params[$j+4];
															$cy+=$params[$j+5];
															echo "c.bezierCurveTo(".$p1x.",".$p1y.",".$p2x.",".$p2y.",".$cx.",".$cy.");\n";
													}
											}
			     				}else if ($command=="S"||$command=="s"){
															//context . quadraticCurveTo(cpx, cpy, x, y)
			     						// Quadratic Curveto
											for($j=0;$j<$noparams;$j+=4){
													if($command=="S"){
															$p1x=$params[$j];
															$p1y=$params[$j+1];
															$cx=$params[$j+2];
															$cy=$params[$j+3];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
														  echo "c.bezierCurveTo(".$lastpointx.",".$lastpointy.",".$p1x.",".$p1y.",".$cx.",".$cy.");\n";
														  // Curveto absolute set cx to final point, other coordinates are control points
													}else if($command=="s"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$p1x=$cx+$params[$j];
															$p1y=$cy+$params[$j+1];
															$cx+=$params[$j+2];
															$cy+=$params[$j+3];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															echo "c.bezierCurveTo(".$lastpointx.",".$lastpointy.",".$p1x.",".$p1y.",".$cx.",".$cy.");\n";
													}
											}
			     				}else if ($command=="V"||$command=="v"){
			     						// Vertical Lineto
											for($j=0;$j<$noparams;$j++){
													if($command=="V"){
															$cy=$params[$j];
															echo "c.lineTo(".$cx.",".$cy.");\n";   				
													}else if($command=="v"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cy+=$params[$j];
														echo "c.lineTo(".$cx.",".$cy.");\n";   				
													}
											}
			     				}else if ($command=="H"||$command=="h"){
			     						// horizontal Lineto
											for($j=0;$j<$noparams;$j++){
													if($command=="H"){
															$cx=$params[$j];
															echo "c.lineTo(".$cx.",".$cy.");\n";   				
													}else if($command=="h"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cx+=$params[$j];
															echo "c.lineTo(".$cx.",".$cy.");\n";   				
													}
											}
			     				}else if ($command=="L"||$command=="l"){
			     						// Lineto
											for($j=0;$j<$noparams;$j+=2){
													if($command=="L"){
															$cx=$params[$j];
															$cy=$params[$j+1];
															echo "c.lineTo(".$cx.",".$cy.");\n";   				
													}else if($command=="l"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cx+=$params[$j];
															$cy+=$params[$j+1];
															echo "c.lineTo(".$cx.",".$cy.");\n";   				
													}
											}
			     				}else if ($command=="Z"||$command=="z"){
											echo "c.lineTo(".$firstpointx.",".$firstpointy.");\n";   				
									}else if ($command=="A"||$command=="a"){
											// To avoid hard math - draw arc as a line
											for($j=0;$j<$noparams;$j+=7){
													$rx=$params[0];
													$ry=$params[1];
													$ang=$params[2];
													$largeflag=$params[3];
													$sweepflag=$params[4];
													$cx=$params[$j];
													$cy=$params[$j+1];
													echo "c.lineTo(".$cx.",".$cy.");\n";   				
			     						}
			     				}
			     				
			     				// Init new command!
			     				$command=$chr;
			     				$workstr="";
			     			}else{
			     				$workstr.=$chr;
			     			}
			      		$i++;
			      }while($i<=strlen($str));
						
						echo "c.globalAlpha = $opacity;\n";

						if($fillstyle=="none"&&$linestyle=="none"){
								echo 'c.fillStyle = "#000";';
			      		echo "\n";
			      		echo "c.fill();\n\n";
						}
						if($fillstyle!="none"){
//								echo "c.save();\n\n";
//								echo "c.scale(2.5,2.5);\n";								
			      		echo "c.fill();\n";
//								echo "c.restore();\n\n";
				    		if($linestyle=="none") echo "\n";
			    	}
			    	if($linestyle!="none"){
			      		echo "c.stroke();\n";
			      		echo "\n";
			      }
			      
			      unset($dval);
				
				}
			
			  if($graphelement->getName()=="text"){
			
						echo "c.globalAlpha = $opacity;\n";

						echo "c.beginPath();\n";
						echo "c.save();\n";
						echo "c.font = '".$fontstyle." ".$fontline."px ".$fontfamily."';\n";

						if(isset($translate)&&isset($rotate)&&isset($scale)){
								echo $translate;
								echo $rotate;
								echo $scale;
						}
						
						if(isset($textx)&&isset($texty)){
								echo "c.fillText('".$textline."',".$textx.",".$texty.");\n";						
						}else{
								echo "c.fillText('".$textline."',0,0);\n";												
						}
						

			    	if($linestyle!="none"){
								if(isset($textx)&&isset($texty)){
			      				echo "c.strokeText('".$textline."',".$textx.",".$texty.");\n";
					  		}else{
			      				echo "c.strokeText('".$textline."',0,0);\n";					  		
					  		}
					  }				    

						echo "c.restore();\n\n";

			  }elseif($graphelement->getName()=="rect"){
						
						// This rectangle must be clipped!
						if(isset($attrs['clip-path'])){
									$clipid=substr($attrs['clip-path'],5);
									$clipid=substr($clipid,0,strlen($clipid)-1);
									$clipid=$clippaths[$clipid];
									
									echo "c.save();\n";
									
									echo $clipdefs[strval($clipid)];
						}
						
						echo "c.globalAlpha = $opacity;\n";

						echo "c.beginPath();\n";
						echo "c.moveTo(".$linex1.",".$liney1.");\n";
						echo "c.lineTo(".($linex1+$linex2).",".($liney1).");\n";
						echo "c.lineTo(".($linex1+$linex2).",".($liney1+$liney2).");\n";
						echo "c.lineTo(".($linex1).",".($liney1+$liney2).");\n";			
						echo "c.lineTo(".($linex1).",".($liney1).");\n";			
				    if($fillstyle!="none"){
//								echo "c.save();\n\n";
//								echo "c.scale(1,1);\n";								
			      		echo "c.fill();\n";
//								echo "c.restore();\n\n";

				    		if($linestyle=="none") echo "\n";
			    	}
				    if($linestyle!="none"){
			     		echo "c.stroke();\n";
			     }
						// This rectangle must be clipped!
						if(isset($attrs['clip-path'])){
									echo "c.restore();\n";
						}
						
						echo "\n";

			  }elseif($graphelement->getName()=="circle"||$graphelement->getName()=="ellipse"){
						echo "c.globalAlpha = $opacity;\n";			  	
			  	
						echo "c.beginPath();\n";
						
						$xs=$cx-$rx;
						$xe=$cx+$rx;
						$ys=$cy-$ry;
						$ye=$cy+$ry;

						$xsp=$cx-($rx*0.552);
						$xep=$cx+($rx*0.552);
						$ysp=$cy-($ry*0.552);
						$yep=$cy+($ry*0.552);
						
						echo "c.moveTo(".$cx.",".$ys.");\n";						
  					echo "c.bezierCurveTo(".$xsp.",".$ys.",".$xs.",".$ysp.",".$xs.",".$cy.");\n";
  					echo "c.bezierCurveTo(".$xs.",".$yep.",".$xsp.",".$ye.",".$cx.",".$ye.");\n";
  					echo "c.bezierCurveTo(".$xep.",".$ye.",".$xe.",".$yep.",".$xe.",".$cy.");\n";
  					echo "c.bezierCurveTo(".$xe.",".$ysp.",".$xep.",".$ys.",".$cx.",".$ys.");\n";

				    if($fillstyle!="none"){
			      		echo "c.fill();\n";
				    		if($linestyle=="none") echo "\n";
			    	}
				    if($linestyle!="none"){
			     		echo "c.stroke();\n";
			     		echo "\n";
			     }
				}elseif($graphelement->getName()=="line"){
						echo "c.beginPath();\n";
						echo "c.moveTo(".$linex1.",".$liney1.");\n";
						echo "c.lineTo(".$linex2.",".$liney2.");\n";
						echo "c.stroke();\n";

				}elseif($graphelement->getName()=="g"){
							// We only print groups that have an ID
//							if(isset($attrs['id'])){
									echo "//-------------------------------\n";
									echo "// Group: ".$attrs['id']."\n";
									echo "//-------------------------------\n";
//							}							
				}elseif($graphelement->getName()=="eg"){
							// We only print groups that have an ID
							if(isset($attrs['id'])){
									echo "//-------------------------------\n";
									echo "// GroupEnd: ".$attrs['id']."\n";
									echo "//-------------------------------\n";
									echo "\n\n\n";
							}							
				}elseif($graphelement->getName()=="defs"){
									$defsmode=1;
									$defsstring="";
				}elseif($graphelement->getName()=="defsend"){
									if($defsid!=""){
											$clipdefs[strval($defsid)]=$defsstring;
									}
									$defsmode=0;
  			}

		}
}


?>
