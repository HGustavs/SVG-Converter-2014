<?php
//--------------------------------------------------------------------------
// Version 4.0
//    Feature: Settings pane for decimals and json. (2017-12-29)
//		Feature: Object creation before javascript / json export
//        - Will allow grouping of identically colored objects 
//        - Default coloring
//        - Automatic js generation
//--------------------------------------------------------------------------
// Version 3.9
//    Bug: Duplicate closing commands. A good idea to check for previous closings before adding more identical ones
//    Bug: Undefined variable fontstyle (bool2)
//    Bug: Json Groups not output completely correctly - commas , are missing
//		Bug: Fixed bug with ellipse command
//    Feature: Settings pane for decimals and json. (2015-08-26)
//		Feature: JSON Export
//								MoveTo 			(Done) 0
//								LineTo      (Done) 1
//                Quadratic   (Done) 4
//								Text
//								Group       (Done) [ and ]
//								Opacity			(Done) 100
//								Fillstyle   (Done) 101
//								beginPath		(Done) [				
//                Linestyle
//								Fill							 500
//								Stroke						 501
//		JSON String
//--------------------------------------------------------------------------
// Version 3.8
//		Bug: Dashed Lines do not work
//		Bug: ID-s of elements can be printed as comments (to simplify reading of code) 
//		Bug: Line Width as string ctx.lineWidth="10"; rather than ctx.lineWidth=10;
//    Bug: No enter after c.stroke(); but enter after c.strokestyle. This makes it appear like strokestyle is in previous group -- Enter after stroke if there is no fill, never enter after strokestyle
//		Bug: Too many Opacity and Linewidth. Only add linewidth/Opacity if it changes
//		Bug: End Caps do not work for bricks example
//		Fix: Added better comment printing using single line comment
//		Added a basic polygon export functionality wich exports polyline coordinates as arrays.
//--------------------------------------------------------------------------
// Version 3.7
//		Selective Rounding with parameter
//    Bug: Sun example does not Work
//		Fix: Remove rounding of color stops.
//		Bug: Rounded caps for lines do not work.
//		Fix: Rounded cap interpretation with styling
//		Bug: Rounded joins for lines do not work.
//		Fix: Rounded joins interpretation with styling
//--------------------------------------------------------------------------
// Version 3.7
//    Bug: Some times context instead of c
//		Bug: Invisible Lines
//		Fix: c instead of canvas and (linex1,liney1) to (linex2,liney2 instead of linex1,liney1)
//    Bug: Texts with even very simple tspans fail.
//		Fix: Simple tspan workaround.
//		Bug: Switch and foreignObject elements are not supported
//		Fix: Recurse into but ignore Switch and ignore foreignObject completely
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
//		Bug: Black things are handled as transparent i.e. no color given.
//		Bug: hyphens "-" in identifiers for gradients etc breaks code in some cases the id "3456-123" is interpreted as a number by javascript

if(isset($_POST['decimals'])){
		$rndp=($_POST['decimals']);
}else{
		$rndp=0;
}

if(isset($_POST['kind'])){
		if($_POST['kind']==1){
				$coordsmode=1;			// Output coordinates as list instead of moveto lineto				
		}else{
				$coordsmode=0;			// Output coordinates as moveto lineto
		}
}else{
		$coordsmode=0;			// Output coordinates as moveto lineto
}

// Rounding Decimal Count i.e. 1 is n.n 2 is n.nn
$coordsscale=1.0;	// Rescale output coordinates e.g. if we want sizes to be normalized

$elementcounter=0;
$graphnodes=array();
$colorstops=array();
$clipdefs=array();
$clippaths=array();

$fillstyle="none";
$linestyle="none";
$fontstyle="none";
$fontfamily="Arial";
$fontline="24px";

$caps="none";
$join="none";
$opacity="1.0";
$gradientname="foo";

$isinkscape=false;

$stopcounter=0;

$jsonstr="var plaflaster=";

// Make point object (associated array with draw commands as elements)

function makePnt2($kind,$p1,$p2)
{
		$pp=array();
		array_push($pp,$kind,$p1,$p2);
		return $pp;
}

function makePnt4($kind,$p1,$p2,$p3,$p4)
{
		$pp=array();
		array_push($pp,$kind,$p1,$p2,$p3,$p4);
		return $pp;
}

function makePnt6($kind,$p1,$p2,$p3,$p4,$p5,$p6)
{
		$pp=array();
		array_push($pp,$kind,$p1,$p2,$p3,$p4,$p5,$p6);
		return $pp;
}

function numb($numb)
{
		global $rndp;
		return  round($numb,$rndp);
}

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
				}else if($child->getName()=="foreignObject"){
						// Ignore this element
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
					if($element->getName()=="clipPath"||$element->getName()=="defs"||$element->getName()=="g"||$element->getName()=="linearGradient"||$element->getName()=="defs"||$element->getName()=="radialGradient"||$element->getName()=="text"||$element->getName()=="switch"){
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
	
			$graphobjs=array();
	
			// Process elements
			foreach ($graphnodes as $graphelement) {
				
				$graphobj=array();

				// Clear Line Style and Fill Styles
				$fontstyle="none";
				$fillstyle="none";
				$linestyle="none";
				$fontfamily="Arial";
				$caps="none";
					
				$opacity="1.0";
				$graphobj['opacity']="1.0";

				// For tspan element get text content...
				// This currently clashes with the text element.
				if($graphelement->getName()=="tspan"){
						if(isset($graphelement[0])){
								$textline=$graphelement[0];
						}
				}
							
				// For text element get (simple tspan fix... now supports simple tspans)
				if($graphelement->getName()=="text"){
						if(isset($graphelement[0])){
								// There is a tspan (only a tspan?)
								if(property_exists ( $graphelement[0] ,"tspan" )){
										$textline=($graphelement[0]->tspan);																
								}else{
										$textline=$graphelement[0];								
								}
						}
				}

				// ID printing (disabled for clarity?)
				if(isset($attrs['id'])){
						$graphobj['id']=(string)$attrs['id'];
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

								echo "var ".$gradientname."=c.createLinearGradient(".numb($attrs['x1']).",".numb($attrs['y1']).",".numb($attrs['x2']).",".numb($attrs['y2']).");\n";

								// Now we create a new gradient with the following properties
								$gradientref=$xlinkattrs['href'];
								$gradientref=substr($gradientref,1,strlen($gradientref)-1);
								
								if(isset($colorstops["$gradientref"])){
										foreach($colorstops["$gradientref"] as $key => $value){
												echo $gradientname.".addColorStop(".$value.");\n";
										}
								}
																
						}else{
								echo "var ".$gradientname."=c.createLinearGradient(".numb($attrs['x1']).",".numb($attrs['y1']).",".numb($attrs['x2']).",".numb($attrs['y2']).");\n";
						}
				}else if($graphelement->getName()=="radialGradient"){
						if(isset($attrs['id'])){
								$gradientname=$attrs['id'];
						}
						if(!isset($attrs['cx'])){
								// Radial Gradient is not complete, this means that it is an inkscape element!
								$isinkscape=true;
						}else if(isset($xlinkattrs['href'])){
								echo "var ".$gradientname."=c.createRadialGradient(".numb($attrs['cx']).",".numb($attrs['cy']).",0,".numb($attrs['cx']).",".numb($attrs['cy']).",".numb($attrs['r']).");\n";

								// Now we create a new gradient with the following properties
								$gradientref=$xlinkattrs['href'];
								$gradientref=substr($gradientref,1,strlen($gradientref)-1);
								
								if(isset($colorstops["$gradientref"])){
										foreach($colorstops["$gradientref"] as $key => $value){
												echo $gradientname.".addColorStop(".$value.");\n";
										}
								}
																
						}else{
								echo "var ".$gradientname."=c.createRadialGradient(".numb($attrs['cx']).",".numb($attrs['cy']).",0,".numb($attrs['cx']).",".numb($attrs['cy']).",".numb($attrs['r']).");\n";
						}
				}else if($graphelement->getName()=="stop"){
						$stopcolor=$attrs['style'];

						if(strpos($stopcolor,"opacity")>0){
								$stopR=hexdec(substr($stopcolor,12,2));
								$stopG=hexdec(substr($stopcolor,14,2));
								$stopB=hexdec(substr($stopcolor,16,2));
								$stopA=substr($stopcolor,strrpos($stopcolor,":")+1);
								$stopcolor="RGBA(".$stopR.",".$stopG.",".$stopB.",".$stopA.")";
						}else{
								if(strpos($stopcolor,";",11)!==false){
										$stopcolorend=strpos($stopcolor,";",11);
								}else{
										$stopcolorend=strlen($stopcolor);
								}
								$stopcolor=substr($stopcolor,11,$stopcolorend-11);
						}
												
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
							
							$graphobj['fontstyle']=$fontstyle;

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
						
							$graphobj['translate']=numb($params[4]).",".numb($params[5]);
							$graphobj['rotate']=numb($params[1]).",".numb($params[2]);
							$graphobj['scale']=numb($params[0]).",".numb($params[3]);
							 				
							$translate="c.translate(".numb($params[4]).",".numb($params[5]).");\n";
							$rotate="c.rotate(".numb($params[1]).",".numb($params[2]).");\n";
							$scale="c.scale(".numb($params[0]).",".numb($params[3]).");\n";
															
			    }elseif ($key == "stroke"){
							$graphobj['strokestyle']=$val;
			    }elseif ($key == "opacity"){
			    		$opacity=$val;
							$graphobj['opacity']=$val;
					}elseif ($key == "stroke-linecap"){
							$caps=$val;
							$graphobj['linecap']=$val;
			    }elseif ($key == "stroke-linejoin"){
							$join=$val;
							$graphobj['linejoin']=$val;
			    }elseif ($key == "fill"){
				      if($val!="none"){
									if(strpos($val,"url(")===false){
											$graphobj['fillstyle']=$val;
									}else{
											$graphobj['fillstyle']=substr($val,5,strlen($val)-6);												
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
													$graphobj['fillstyle']=$fillstyle;				
											}else{
													$graphobj['fillstyle']=substr($fillstyle,5,strlen($fillstyle)-6);																	
											}
									}	
			    		}

			    		$strokepos=strpos($val,"stroke:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$linestyle=substr($val,$strokepos+7,$strokeposend-$strokepos-7);
							      if($linestyle!="none"){
												$graphobj['strokestyle']=$linestyle;
										}
			    		}

			    		$strokepos=strpos($val,"stroke-width:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$strokewidth=substr($val,$strokepos+13,$strokeposend-$strokepos-13);
										$strokewidth=str_replace("px","",$strokewidth);
										$graphobj['strokewidth']=numb(floatval($strokewidth));
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
							$graphobj['strokestyle']=$linestyle;
			    }elseif ($key == "points"&&($graphelement->getName()=="polygon"||$graphelement->getName()=="polyline"||$graphelement->getName()=="line")) {
			      	
							if($defsmode){
									$defsstring.="c.beginPath();\n";			      				      				      	
							}

							$graphobj['kind']=$graphelement->getName();
							$graphobj['opacity']=$opacity;
							$graphobj['pntarr']=array();

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
												$defsstring.="c.moveTo(".numb($params[$j]).",".numb($params[$j+1]).");\n";							
										}
										array_push($graphobj['pntarr'],makePnt2("M",numb($params[$j]),numb($params[$j+1])));
									}else{
										if($defsmode){
												$defsstring.="c.lineTo(".numb($params[$j]).",".numb($params[$j+1]).");\n";														
										}
										array_push($graphobj['pntarr'],makePnt2("L", numb($params[$j]),numb($params[$j+1])));
									}
							}
			
							// If a polygon closes path if not i.e. polyline keep it open
							if($noparams>=2&&$graphelement->getName()=="polygon"){
									if($defsmode){
											$defsstring.="c.lineTo(".numb($params[0]).", ".numb($params[1]).");\n";														
									}
									array_push($graphobj['pntarr'],makePnt2("L",numb($params[0]),numb($params[1])));
							}
	
							if($defsmode){
									$defsstring.="c.clip();\n\n";		
							}
			    }elseif ($key == "d") {
			    	
			    	$dval=$val;
			    	
			    }
			  }

				// Draw d path commands. This is a fix for the svg data that requires that the line settings have to be assigned before the line commands
				if(isset($dval)){
				
						$graphobj['kind']="Path";
						$graphobj['opacity']=$opacity;

						$graphobj['pntarr']=array();
			      
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
					
						// T command is partially incorrect - relative quadratic 
			      do{
			     			$chr=substr($str,$i,1);
			     			if($chr=="H"||$chr=="h"||$chr=="V"||$chr=="v"||$chr=="M"||$chr=="m"||$chr=="C"||$chr=="c"||$chr=="L"||$chr=="A"||$chr=="a"||$chr=="l"||$chr=="z"||$chr=="Z"||$chr=="q"||$chr=="Q"||$chr=="s"||$chr=="S"||$chr=="T"||$chr=="t"||$i==strlen($str)){
									// Process Parameters for any parameter command
									if($command=="T"||$command=="t"||$command=="M"||$command=="m"||$command=="c"||$command=="C"||$command=="v"||$command=="V"||$command=="h"||$command=="H"||$command=="s"||$command=="S"||$command=="l"||$command=="L"||$command=="a"||$command=="A"||$command=="q"||$command=="Q"){
					    				$j=0;
					  					$dostr="";
											$dochr="";
					  					$oldchr="";
					  					$params=array();
					  					$noparams=0;
					  					
					  					// do chr loop for other drawing commands. Bug fix: If we encounter - and before it is an e we do nothing, otherwise proceed as normal
					  					
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
			 												// We found second decimal point i.e. 1.4.6 is not a number but 1.4 followed by .6
					 										if($dochr==="." && strpos($dostr, ".")!==false){
						     									$params[$noparams++]=$dostr;
					 												$dostr="";
					 										}
					 										$dostr.=$dochr;
					 								}
					 								$j++;
					  					}while($j<=strlen($workstr));    									
						     			if(trim($dostr)!=""){
													$params[$noparams++]=$dostr;
											}
									}
			     				if($command=="Q" || $command=="q"){
			     						// Bezier Curveto
											for($j=0;$j<$noparams;$j+=4){
													if($command=="Q"){
															$p1x=$params[$j];
															$p1y=$params[$j+1];
															$cx=$params[$j+2];
															$cy=$params[$j+3];
															$lastpointx=$cx;
															$lastpointy=$cy;
															array_push($graphobj['pntarr'],makePnt4("Q",numb($p1x/$coordsscale),numb($p1y/$coordsscale),numb($cx/$coordsscale),numb($cy/$coordsscale)));
													}else if($command=="q"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$p1x=$cx+$params[$j];
															$p1y=$cy+$params[$j+1];
															$lastpointx=$p2x;
															$lastpointy=$p2y;
															$cx+=$params[$j+2];
															$cy+=$params[$j+3];
															array_push($graphobj['pntarr'],makePnt4("Q",numb($p1x/$coordsscale),numb($p1y/$coordsscale),numb($cx/$coordsscale),numb($cy/$coordsscale)));
													}
											}
			     				}
			     				if($command=="M" || $command=="m"){
											for($j=0;$j<$noparams;$j+=2){
													if($j==0){
															// Normal moveto set cx,cy
															if($command=="M"){
																			$cx=$params[$j];
																			$cy=$params[$j+1];
																			array_push($graphobj['pntarr'],makePnt2("M",numb($cx/$coordsscale),numb($cy/$coordsscale)));
																			$firstpoint=1;
																			$firstpointx=$cx;
																			$firstpointy=$cy;
															}else if($command=="m"){
																	if(!$firstpoint){
																			$cx=$params[$j];
																			$cy=$params[$j+1];
																			array_push($graphobj['pntarr'],makePnt2("M",numb($params[$j]/$coordsscale),numb($params[$j+1]/$coordsscale)));
																			$firstpoint=1;
																			$firstpointx=$cx;
																			$firstpointy=$cy;
																	}else{
																			$cx+=$params[$j];
																			$cy+=$params[$j+1];															
																			array_push($graphobj['pntarr'],makePnt2("M",numb($cx/$coordsscale),numb($cy/$coordsscale)));
																	}													
															}
													}else{
																// Implicit lineto (m is relative)
																if($command=="M"){
																		$cx=$params[$j];
																		$cy=$params[$j+1];
																		array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale),numb($cy/$coordsscale)));
																}else if($command=="m"){
																		$cx+=$params[$j];
																		$cy+=$params[$j+1];		
																		array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale).",".numb($cy/$coordsscale)));
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
															array_push($graphobj['pntarr'],makePnt6("B",numb($p1x/$coordsscale),numb($p1y/$coordsscale),numb($p2x/$coordsscale),numb($p2y/$coordsscale),numb($cx/$coordsscale),numb($cy/$coordsscale)));
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
															array_push($graphobj['pntarr'],makePnt6("B",numb($p1x/$coordsscale),numb($p1y/$coordsscale),numb($p2x/$coordsscale),numb($p2y/$coordsscale),numb($cx/$coordsscale),numb($cy/$coordsscale)));
													}
											}
			     				}else if ($command=="S"||$command=="s"){
			     						// Bezier Curveto
											for($j=0;$j<$noparams;$j+=4){
													if($command=="S"){
															$p1x=$params[$j];
															$p1y=$params[$j+1];
															$cx=$params[$j+2];
															$cy=$params[$j+3];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															array_push($graphobj['pntarr'],makePnt6("B",numb($lastpointx/$coordsscale),numb($lastpointy/$coordsscale),numb($p1x/$coordsscale),numb($p1y/$coordsscale),numb($cx/$coordsscale),numb($cy/$coordsscale)));
														  // Curveto absolute set cx to final point, other coordinates are control points
													}else if($command=="s"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$p1x=$cx+$params[$j];
															$p1y=$cy+$params[$j+1];
															$cx+=$params[$j+2];
															$cy+=$params[$j+3];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															// echo "c.bezierCurveTo(".numb($lastpointx).",".numb($lastpointy).",".numb($p1x).",".numb($p1y).",".numb($cx).",".numb($cy).");\n";
															array_push($graphobj['pntarr'],makePnt6("B",numb($lastpointx),numb($lastpointy),numb($p1x),numb($p1y),numb($cx),numb($cy)));
													}
											}
			     				}else if ($command=="T"||$command=="t"){
			     						// Quadratic Relative Curveto
											for($j=0;$j<$noparams;$j+=2){
													if($command=="T"){
															$cx=$params[$j];
															$cy=$params[$j+1];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															array_push($graphobj['pntarr'],makePnt4("Q",numb($lastpointx/$coordsscale),numb($lastpointy/$coordsscale),numb($cx/$coordsscale),numb($cy/$coordsscale)));
															// Curveto absolute set cx to final point, other coordinates are control points
													}else if($command=="t"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cx+=$params[$j];
															$cy+=$params[$j+1];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															// echo "c.quadraticCurveTo(".numb($lastpointx).",".numb($lastpointy).",".numb($cx).",".numb($cy).");\n";
															array_push($graphobj['pntarr'],makePnt4("Q",numb($lastpointx),numb($lastpointy),numb($cx),numb($cy)));
													}
											}
									}else if ($command=="V"||$command=="v"){
			     						// Vertical Lineto
											for($j=0;$j<$noparams;$j++){
													if($command=="V"){
															$cy=$params[$j];
													}else if($command=="v"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cy+=$params[$j];
													}
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale),numb($cy/$coordsscale)));
											}
			     				}else if ($command=="H"||$command=="h"){
			     						// horizontal Lineto
											for($j=0;$j<$noparams;$j++){
													if($command=="H"){
															$cx=$params[$j];
													}else if($command=="h"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cx+=$params[$j];
													}
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale),numb($cy/$coordsscale)));
											}
			     				}else if ($command=="L"||$command=="l"){
			     						// Lineto
											for($j=0;$j<$noparams;$j+=2){
													if($command=="L"){
															$cx=$params[$j];
															$cy=$params[$j+1];
													}else if($command=="l"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cx+=$params[$j];
															$cy+=$params[$j+1];
													}
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale),numb($cy/$coordsscale)));
											}
			     				}else if ($command=="Z"||$command=="z"){
											array_push($graphobj['pntarr'],makePnt2("L",numb($firstpointx/$coordsscale),numb($firstpointy/$coordsscale)));
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
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale),numb($cy/$coordsscale)));
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

						$graphobj['kind']="Rect";
						$graphobj['opacity']=$opacity;

						$graphobj['pntarr']=array();
						array_push($graphobj['pntarr'],makePnt2("M",numb($linex1),numb($liney1)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1+$linex2),numb($liney1)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1+$linex2),numb($liney1+$liney2)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1),numb($liney1+$liney2)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1),numb($liney1)));

						// This rectangle must be clipped!
						if(isset($attrs['clip-path'])){
									echo "c.restore();\n";
						}
			  }elseif($graphelement->getName()=="circle"||$graphelement->getName()=="ellipse"){

						$xs=$cx-$rx;
						$xe=$cx+$rx;
						$ys=$cy-$ry;
						$ye=$cy+$ry;

						$xsp=$cx-($rx*0.552);
						$xep=$cx+($rx*0.552);
						$ysp=$cy-($ry*0.552);
						$yep=$cy+($ry*0.552);

						$graphobj['kind']="Circ";
						$graphobj['opacity']=$opacity;

						$graphobj['pntarr']=array();
						array_push($graphobj['pntarr'],makePnt2("M",numb($xsp/$coordsscale),numb($ys/$coordsscale),numb($xs/$coordsscale),numb($ysp/$coordsscale),numb($xs/$coordsscale),numb($cy/$coordsscale)));
						array_push($graphobj['pntarr'],makePnt6("B",numb($xs/$coordsscale),numb($yep/$coordsscale),numb($xsp/$coordsscale),numb($ye/$coordsscale),numb($cx/$coordsscale),numb($ye/$coordsscale)));
						array_push($graphobj['pntarr'],makePnt6("B",numb($xep/$coordsscale),numb($ye/$coordsscale),numb($xe/$coordsscale),numb($yep/$coordsscale),numb($xe/$coordsscale),numb($cy/$coordsscale)));
						array_push($graphobj['pntarr'],makePnt6("B",numb($xe/$coordsscale),numb($ysp/$coordsscale),numb($xep/$coordsscale),numb($ys/$coordsscale),numb($cx/$coordsscale),numb($ys/$coordsscale)));

				}elseif($graphelement->getName()=="line"){
						$graphobj['kind']="Line";
						$graphobj['opacity']=$opacity;
						$graphobj['pntarr']=array();
						array_push($graphobj['pntarr'],makePnt2("M", numb($linex1/$coordsscale),numb($liney1/$coordsscale) ));
						array_push($graphobj['pntarr'],makePnt2("L", numb($linex2/$coordsscale),numb($liney2/$coordsscale)));
				}elseif($graphelement->getName()=="g"){
							// We only print groups that have an ID
							if($coordsmode==0){
									if(isset($attrs['id'])){
											echo "//-------------------------------\n";
											echo "// Group: ".$attrs['id']."\n";
											echo "//-------------------------------\n";
									}							
							}else{
									// if it is a group in non-coordinate mode we output a json array construct
									$jsonstr.= ",[";									
							}
				}elseif($graphelement->getName()=="eg"){
							// We only print groups that have an ID
							if($coordsmode==0){
									if(isset($attrs['id'])){
											echo "//-------------------------------\n";
											echo "// GroupEnd: ".$attrs['id']."\n";
											echo "//-------------------------------\n";
											echo "\n\n\n";
									}							
							}else{
									// if it is a group in non-coordinate mode we output a json array construct
									$jsonstr.= "]";									
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

				// Add object to drawing queue
				array_push($graphobjs,$graphobj);
		}

}

echo "/*";
print_r($graphobjs);
echo "*/";

?>
