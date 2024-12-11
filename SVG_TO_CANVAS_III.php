<?php
//--------------------------------------------------------------------------
// Version 4.4.1 
// Feature: Start of ignoring of repeated styles and begin path statements
//--------------------------------------------------------------------------
// Version 4.4
// Feature: Basic curve export to arrays (2024-04-19)
// Bug: Gradients are not exported with rest of data
//--------------------------------------------------------------------------
// Version 4.3.1
// Fix: Work with layer-less layouts. (2022-11-25)
//--------------------------------------------------------------------------
// Version 4.3
// Feature: Support for translating (and writing element names?) in order to support multiple objects and animation
// Feature: Mark start of functions and separate functions, to execute separately to 
// Feature: Collect minimum and maximum X/Y for all coordinates.
//--------------------------------------------------------------------------
// Version 4.2.1
// Bug: Rectangles placed at 0 has no X/Y coordinate initiate both
//--------------------------------------------------------------------------
// Version 4.2
//    Feature: Initial support for style elements (2021-10-05)
//    Feature: Initial support for classes (2021-10-05)
//                       fill style by class
//                       stroke style by class
//    Fix: Hyphens in gradient names are removed
//    Fix: Using url in styled canvas gradient
//    Bug: link to stops from other gradient xlink:href="#linear-gradient-2"
//    Bug: At end of group without id a } is written to stream even if no { was produced 
//    Fix: Display:none for styled elements
//--------------------------------------------------------------------------
// Version 4.1.2
//    Fix: More robust support for modern svg linear gradients (2021-10-04)
//--------------------------------------------------------------------------
// Version 4.1.1
//    Fix: Newline javascrupt (2020-07-22)
//--------------------------------------------------------------------------
// Version 4.1
//    Feature: Export of moveto lineto (using new architecture) to json format
//--------------------------------------------------------------------------
// Version 4.0.2
//    Feature: Affinity Designer Styling Support for Opacity
//    Feature: Affinity Designer Styling Support for Linear Gradients
//    Fix:     Gradients with colors but no opacity broken.
//--------------------------------------------------------------------------
// Version 4.0.1
//    Feature: Object hiding (2018-11-29)
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
//    Bug: No enter after ctx.stroke(); but enter after ctx.strokestyle. This makes it appear like strokestyle is in previous group -- Enter after stroke if there is no fill, never enter after strokestyle
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

// Extents of coordinate boxes.
$Xmin=10000;
$Xmax=-10000;
$Ymin=10000;
$Ymax=-10000;

// List of functions and display properties
$funclist = array();
$showlist = array();

// Rounding Decimal Count i.e. 1 is n.n 2 is n.nn
$coordsscale=1.0;	// Rescale output coordinates e.g. if we want sizes to be normalized

$elementcounter=0;
$graphnodes=array();
$colorstops=array();
$clipdefs=array();
$clippaths=array();

$styles=array();

$fontstyle="none";
$fontfamily="Arial";
$fontline="24px";

$caps="none";
$join="none";
$opacity="1.0";
$gradientname="foo";

$isinkscape=false;
$graphobjs=array();

$stopcounter=0;

// Tabbed version of echo
function tabbedecho($string,$notabs)
{
		for($i=0;$i<$notabs;$i++){
				echo "   ";
		}
		echo $string;
}

// Make point object (associated array with draw commands as elements) 3 versions with different number of parameters
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

function numb($numb,$ortho)
{
		global $rndp;
		global $Xmin;
		global $Xmax;
    global $Ymin;
    global $Ymax;

    if($ortho==0){
        // X Coordinate
        if($numb<$Xmin) $Xmin=$numb;
        if($numb>$Xmax) $Xmax=$numb;
    }else if($ortho==1){
        // Y Coordinate
        if($numb<$Ymin) $Ymin=$numb;
        if($numb>$Ymax) $Ymax=$numb;
    }else{
        // Ignore all others for now!
    }

    return  round(floatval($numb),$rndp);
}

function recurseelement($element){
		global $elementcounter;
		global $graphnodes;
    global $styles;

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
        }else if($child->getName()=="style"){
            // handle style in defs element - read style text and create array element in style element for each class
            $classes=explode("}",$child);
            foreach($classes as $val){
                // Class list is before { character and declaration is after
                $declarationstart=strpos($val,"{");
                $declarationtext=substr($val,$declarationstart+1);
                $classtext=substr($val,0,$declarationstart);
                $classarr=explode(",",$classtext);

                foreach($classarr as $classname){
                    $classname=substr(trim($classname),1);
                    if($classname!=""){
                        // Make key value proposition of each declaration... but first create array if it does not exist
                        if(!isset($styles[$classname])){
                            $styles[$classname]=array();
                        }

                        // Make each declaration a key value pair in the style class array
                        $declarations=explode(";",$declarationtext);
                        foreach($declarations as $declaration){
                            $colonpos=strpos($declaration,":");
                            $declarationval=trim(substr($declaration,$colonpos+1));
                            $declarationkey=trim(substr($declaration,0,$colonpos));
                            if($declarationkey!==""){
                                $styles[$classname][$declarationkey]=$declarationval;
                            }
                        }
                    }
                }
            }
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

if(isset($_POST['svgname'])||isset($_GET['svgname'])){

			if(isset($_POST['svgname'])){
					$filename=$_POST['svgname'];
			}else{
					$filename=$_GET['svgname'];			
			}
	
			$svg = simplexml_load_file("Examples/".$filename);
			
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
					}else if($element->getName()=="style"){
            // handle style in defs element - read style text and create array element in style element for each class
            $classes=explode("}",$element);
            foreach($classes as $val){
                // Class list is before { character and declaration is after
                $declarationstart=strpos($val,"{");
                $declarationtext=substr($val,$declarationstart+1);
                $classtext=substr($val,0,$declarationstart);
                $classarr=explode(",",$classtext);

                foreach($classarr as $classname){
                    $classname=substr(trim($classname),1);
                    if($classname!=""){
                        // Make key value proposition of each declaration... but first create array if it does not exist
                        if(!isset($styles[$classname])){
                            $styles[$classname]=array();
                        }

                        // Make each declaration a key value pair in the style class array
                        $declarations=explode(";",$declarationtext);
                        foreach($declarations as $declaration){
                            $colonpos=strpos($declaration,":");
                            $declarationval=trim(substr($declaration,$colonpos+1));
                            $declarationkey=trim(substr($declaration,0,$colonpos));
                            if($declarationkey!==""){
                                $styles[$classname][$declarationkey]=$declarationval;
                            }
                        }
                    }
                }
            }
          }else{
							echo "//Unknown outer element: ".$element->getName()."\n";
					}
			}

//      print_r($styles);

			$defsmode=0;
			$defsstring="";
			$defsid="";
			$clipid="";
	
			$graphobjs=array();
	
			$lastgradient=null;
	
			// Process elements
			foreach ($graphnodes as $graphelement) {
				
				$graphobj=array();

				// Clear Line Style and Fill Styles
				$fontstyle="none";
				$fontfamily="Arial";
				$caps="none";
					
				$graphobj['kind']=$graphelement->getName();
				$graphobj['pntarr']=array();

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

				// To get ID comment/code
				$attrs=$graphelement->attributes();
				$xlinkattrs=$graphelement->attributes('http://www.w3.org/1999/xlink');

				// ID printing (disabled for clarity?)
				if(isset($attrs['id'])){
						$graphobj['id']=(string)$attrs['id'];
				}

				// We update array with ids of shown / hidden objects
				if(isset($attrs['id'])&&isset($attrs['display'])){
						if($attrs['display']=="none"){
									$showlist[(string)$attrs['id']]="hide";
						}else{
									$showlist[(string)$attrs['id']]="show";
						}
				}

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
						// Create array to store stops
						$graphobj['stops']=array();
					
						$graphobj['gradientx1']=numb((string)$attrs['x1'],2);
						$graphobj['gradienty1']=numb((string)$attrs['y1'],2);
						$graphobj['gradientx2']=numb((string)$attrs['x2'],2);
						$graphobj['gradienty2']=numb((string)$attrs['y2'],2);
					
						if(isset($attrs['gradientTransform'])) $graphobj['gradientTransform']=(string)$attrs['gradientTransform'];
					
						$graphobj['gradientid']=str_replace("-","",(string)$attrs['id']);

          }else if($graphelement->getName()=="radialGradient"){
						// Create array to store stops
						$graphobj['stops']=array();
					
						$graphobj['gradientcx']=numb((string)$attrs['cx'],2);
						$graphobj['gradientcy']=numb((string)$attrs['cy'],2);
						$graphobj['gradientr']=numb((string)$attrs['r'],2);

						// Either set second center to same as first center or use second center
						if(isset($attrs['fx'])){
								$graphobj['gradientfx']=numb((string)$attrs['fx'],2);
								$graphobj['gradientfy']=numb((string)$attrs['fy'],2);
						}else{
								$graphobj['gradientfx']=numb((string)$attrs['cx'],2);
								$graphobj['gradientfy']=numb((string)$attrs['cy'],2);						
						}
					
						$graphobj['gradientid']=str_replace("-","",(string)$attrs['id']);
				}else if($graphelement->getName()=="stop"){
						$stopcolor=$attrs['style'];
            if(strlen($stopcolor)==0) $stopcolor=$attrs['stop-color'];

						if(strpos($stopcolor,"#")>=0){
                $hashpos=strpos($stopcolor,"#");
								$stopR=hexdec(substr($stopcolor,$hashpos+1,2));
								$stopG=hexdec(substr($stopcolor,$hashpos+3,2));
								$stopB=hexdec(substr($stopcolor,$hashpos+5,2));
								if(strpos($stopcolor,"opacity:")>0){
										$stopA=substr($stopcolor,strrpos($stopcolor,":")+1);
										$stopcolor="RGBA(".$stopR.",".$stopG.",".$stopB.",".$stopA.")";
								}else{
										$stopcolor="RGB(".$stopR.",".$stopG.",".$stopB.")";
								}
						}else if(strpos($stopcolor,"rgb(")>=0){
								$colstart=strpos($stopcolor,"rgb(");
								$colend=strpos($stopcolor,");");
								$stopA=substr($stopcolor,strrpos($stopcolor,":")+1);
								$stopcolor="RGBA(".substr($stopcolor,$colstart+4,$colend-$colstart-4).",".$stopA.")";
						}else{
								if(strpos($stopcolor,";",11)!==false){
										$stopcolorend=strpos($stopcolor,";",11);
								}else{
										$stopcolorend=strlen($stopcolor);
								}
								$stopcolor=substr($stopcolor,11,$stopcolorend-11);
						}

						$stop=array();
						$stop[0]=(string)$attrs['offset'];
						$stop[1]=$stopcolor;
						array_push($graphobjs[$lastgradient]['stops'],$stop);
				}else{
								// We assume that defsid is the only use of ID
								if(isset($attrs['id'])) $defsid=$attrs['id'];
				}

				// For each attribute of svg 								
				// We process attributes after gradients but before the drawing elements.
			  
        // Extra clear for rectangle in origo in either dimension
        if ($graphelement->getName()=="rect"){
            $linex1=0;
            $liney1=0;
        }

			  foreach ($graphelement->attributes() as $key => $val) {

					// Get font parameters!
			    if ($key == "if"&&$graphelement->getName()=="text"){
							$fontline=$val;
			    }		
			  	
					// Get font parameters!
			    if ($key == "font-size"&&$graphelement->getName()=="text"){
							$graphobj['fontsize']=$val;
			    }		
			    	    
			    if ($key == "font-family"&&$graphelement->getName()=="text"){
							$arr = explode("-", $val, 2);
							$graphobj['fontstyle']=$arr[0];
			    }

			    if ($graphelement->getName()=="text"){
							if($key=="x") $graphobj['textx']=$val;
							if($key=="y") $graphobj['texty']=$val;
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
          }else if ($key == "class"){
              $classstyle=$styles[strval($val)];
              if(isset($classstyle['fill'])) $graphobj['fillstyle']=(string)$classstyle['fill'];
              if(isset($classstyle['stroke'])) $graphobj['strokestyle']=(string)$classstyle['stroke'];              
              if(isset($classstyle['display'])&&isset($attrs['id'])){
                    if($classstyle['display']==="none"){
                        $showlist[(string)$attrs['id']]="hide";
                    }else{
                        $showlist[(string)$attrs['id']]="show";
                    }
              }                 
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
							$graphobj['translate']=numb($params[4],2).",".numb($params[5],2);
							$graphobj['rotate']=numb($params[1],2).",".numb($params[2],2);
							$graphobj['scale']=numb($params[0],2).",".numb($params[3],2);
			    }elseif ($key == "stroke"){
							$graphobj['strokestyle']=(string)$val;
			    }elseif ($key == "opacity"){
			    		$opacity=$val;
							$graphobj['opacity']=$val;
					}elseif ($key == "stroke-linecap"){
							$caps=$val;
							$graphobj['linecap']=$val;
			    }elseif ($key == "stroke-linejoin"){
							$join=$val;
							$graphobj['linejoin']=$val;
			    }elseif ($key == "stroke-dasharray"){
							$dash=$val;
							$graphobj['dasharr']=$dash;
			    }elseif ($key == "fill"){
				      if($val!="none"){
									if(strpos($val,"url(")===false){
											$graphobj['fillstyle']=(string)$val;
									}else{
											$graphobj['fillgradient']=substr($val,5,strlen($val)-6);												
									}
							}
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
													$graphobj['fillgradient']=substr($fillstyle,5,strlen($fillstyle)-6);																	
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

			    		$strokepos=strpos($val,"fill-opacity:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$opacity=substr($val,$strokepos+13,$strokeposend-$strokepos-13);
										$graphobj['opacity']=floatval($opacity);
							}	
						
			    		$strokepos=strpos($val,"stroke-opacity:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$opacity=substr($val,$strokepos+15,$strokeposend-$strokepos-15);
										$graphobj['opacity']=floatval($opacity);
							}	

							$strokepos=strpos($val,"stroke-width:");
			    		if($strokepos!==false){
			    		  		$strokeposend=strpos($val,";",$strokepos);
			    		  		if($strokeposend===false) $strokeposend=strlen($val);
							  		$strokewidth=substr($val,$strokepos+13,$strokeposend-$strokepos-13);
										$strokewidth=str_replace("px","",$strokewidth);
										$graphobj['strokewidth']=numb(floatval($strokewidth),2);
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
							$graphobj['strokewidth']=(string)$val;
			    }elseif ($key == "points"&&($graphelement->getName()=="polygon"||$graphelement->getName()=="polyline"||$graphelement->getName()=="line")) {
			      	
							if($defsmode){
									$defsstring.="ctx.beginPath();\n";			      				      				      	
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
												$defsstring.="ctx.moveTo(".numb($params[$j],0).",".numb($params[$j+1],1).");\n";							
										}
										array_push($graphobj['pntarr'],makePnt2("M",numb($params[$j],0),numb($params[$j+1],1)));
									}else{
										if($defsmode){
												$defsstring.="ctx.lineTo(".numb($params[$j],0).",".numb($params[$j+1],1).");\n";														
										}
										array_push($graphobj['pntarr'],makePnt2("L", numb($params[$j],0),numb($params[$j+1],1)));
									}
							}
			
							// If a polygon closes path if not i.e. polyline keep it open
							if($noparams>=2&&$graphelement->getName()=="polygon"){
									if($defsmode){
											$defsstring.="ctx.lineTo(".numb($params[0],0).", ".numb($params[1],1).");\n";														
									}
									array_push($graphobj['pntarr'],makePnt2("L",numb($params[0],0),numb($params[1],1)));
							}
	
							if($defsmode){
									$defsstring.="ctx.clip();\n\n";		
							}
			    }elseif ($key == "d") {
			    	
			    	$dval=$val;
			    	
			    }
			  }

				// Draw d path commands. This is a fix for the svg data that requires that the line settings have to be assigned before the line commands
				if(isset($dval)){
				
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
															array_push($graphobj['pntarr'],makePnt4("Q",numb($p1x/$coordsscale,0),numb($p1y/$coordsscale,1),numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
													}else if($command=="q"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$p1x=$cx+$params[$j];
															$p1y=$cy+$params[$j+1];
															$lastpointx=$p2x;
															$lastpointy=$p2y;
															$cx+=$params[$j+2];
															$cy+=$params[$j+3];
															array_push($graphobj['pntarr'],makePnt4("Q",numb($p1x/$coordsscale,0),numb($p1y/$coordsscale,1),numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
																			array_push($graphobj['pntarr'],makePnt2("M",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
																			$firstpoint=1;
																			$firstpointx=$cx;
																			$firstpointy=$cy;
															}else if($command=="m"){
																	if(!$firstpoint){
																			$cx=$params[$j];
																			$cy=$params[$j+1];
																			array_push($graphobj['pntarr'],makePnt2("M",numb($params[$j]/$coordsscale,0),numb($params[$j+1]/$coordsscale,1)));
																			$firstpoint=1;
																			$firstpointx=$cx;
																			$firstpointy=$cy;
																	}else{
																			$cx+=$params[$j];
																			$cy+=$params[$j+1];															
																			array_push($graphobj['pntarr'],makePnt2("M",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
																	}													
															}
													}else{
																// Implicit lineto (m is relative)
																if($command=="M"){
																		$cx=$params[$j];
																		$cy=$params[$j+1];
																		array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
																}else if($command=="m"){
																		$cx+=$params[$j];
																		$cy+=$params[$j+1];		
																		array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
																		array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
															array_push($graphobj['pntarr'],makePnt6("B",numb($p1x/$coordsscale,0),numb($p1y/$coordsscale,1),numb($p2x/$coordsscale,0),numb($p2y/$coordsscale,1),numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
															array_push($graphobj['pntarr'],makePnt6("B",numb($p1x/$coordsscale,0),numb($p1y/$coordsscale,1),numb($p2x/$coordsscale,0),numb($p2y/$coordsscale,1),numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
															array_push($graphobj['pntarr'],makePnt6("B",numb($lastpointx/$coordsscale,0),numb($lastpointy/$coordsscale,1),numb($p1x/$coordsscale,0),numb($p1y/$coordsscale,1),numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
														  // Curveto absolute set cx to final point, other coordinates are control points
													}else if($command=="s"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$p1x=$cx+$params[$j];
															$p1y=$cy+$params[$j+1];
															$cx+=$params[$j+2];
															$cy+=$params[$j+3];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															array_push($graphobj['pntarr'],makePnt6("B",numb($lastpointx,0),numb($lastpointy,1),numb($p1x,0),numb($p1y,1),numb($cx,0),numb($cy,1)));
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
															array_push($graphobj['pntarr'],makePnt4("Q",numb($lastpointx/$coordsscale,0),numb($lastpointy/$coordsscale,1),numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
															// Curveto absolute set cx to final point, other coordinates are control points
													}else if($command=="t"){
															// Curveto relative set cx to final point, other coordinates are relative control points
															$cx+=$params[$j];
															$cy+=$params[$j+1];
															$lastpointx=$p1x;
															$lastpointy=$p1y;
															array_push($graphobj['pntarr'],makePnt4("Q",numb($lastpointx,0),numb($lastpointy,1),numb($cx,0),numb($cy,1)));
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
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
											}
			     				}else if ($command=="Z"||$command=="z"){
											array_push($graphobj['pntarr'],makePnt2("L",numb($firstpointx/$coordsscale,0),numb($firstpointy/$coordsscale,1)));
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
													array_push($graphobj['pntarr'],makePnt2("L",numb($cx/$coordsscale,0),numb($cy/$coordsscale,1)));
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
						$graphobj['textline']=$textline;
			  }elseif($graphelement->getName()=="rect"){
						
						array_push($graphobj['pntarr'],makePnt2("M",numb($linex1,0),numb($liney1,1)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1+$linex2,0),numb($liney1,1)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1+$linex2,0),numb($liney1+$liney2,1)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1,0),numb($liney1+$liney2,1)));
						array_push($graphobj['pntarr'],makePnt2("L",numb($linex1,0),numb($liney1,1)));
			  }elseif($graphelement->getName()=="circle"||$graphelement->getName()=="ellipse"){

						$xs=$cx-$rx;
						$xe=$cx+$rx;
						$ys=$cy-$ry;
						$ye=$cy+$ry;

						$xsp=$cx-($rx*0.552);
						$xep=$cx+($rx*0.552);
						$ysp=$cy-($ry*0.552);
						$yep=$cy+($ry*0.552);

						array_push($graphobj['pntarr'],makePnt2("M",numb($cx/$coordsscale,0),numb($ys/$coordsscale,1)));					
						array_push($graphobj['pntarr'],makePnt6("B",numb($xsp/$coordsscale,0),numb($ys/$coordsscale,1),numb($xs/$coordsscale,0),numb($ysp/$coordsscale,1),numb($xs/$coordsscale,0),numb($cy/$coordsscale,1)));
						array_push($graphobj['pntarr'],makePnt6("B",numb($xs/$coordsscale,0),numb($yep/$coordsscale,1),numb($xsp/$coordsscale,0),numb($ye/$coordsscale,1),numb($cx/$coordsscale,0),numb($ye/$coordsscale,1)));
						array_push($graphobj['pntarr'],makePnt6("B",numb($xep/$coordsscale,0),numb($ye/$coordsscale,1),numb($xe/$coordsscale,0),numb($yep/$coordsscale,1),numb($xe/$coordsscale,0),numb($cy/$coordsscale,1)));
						array_push($graphobj['pntarr'],makePnt6("B",numb($xe/$coordsscale,0),numb($ysp/$coordsscale,1),numb($xep/$coordsscale,0),numb($ys/$coordsscale,1),numb($cx/$coordsscale,0),numb($ys/$coordsscale,1)));
				}elseif($graphelement->getName()=="line"){
						array_push($graphobj['pntarr'],makePnt2("M", numb($linex1/$coordsscale,0),numb($liney1/$coordsscale,1) ));
						array_push($graphobj['pntarr'],makePnt2("L", numb($linex2/$coordsscale,0),numb($liney2/$coordsscale,1)));
				}elseif($graphelement->getName()=="g"||$graphelement->getName()=="eg"){
						$graphobj['id']=(string)$attrs['id'];
				}elseif($graphelement->getName()=="eg"){
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
				if($graphobj['kind']=="linearGradient"||$graphobj['kind']=="radialGradient"){
						$lastgradient=array_push($graphobjs,$graphobj)-1;
				}else if($graphobj['kind']!="stop"){
						array_push($graphobjs,$graphobj);				
				}
		}

}

$lastopacity="1.0";
$lastdash="";
$tabs=0;
$lastid=Array();

// Provide a default objid
$objid="UNK";
$objcount=0;

// lastFill lastStroke startedPath -- if we have same mode as previous
$lastFill="None";
$lastStroke="None";
$startedPath=false;

if($coordsmode==1) echo "[\n";
foreach ($graphobjs as $graphobj) {
		global $coordsmode;

		if($graphobj['kind']=="g"){
				if(($tabs==0)&&(!empty($graphobj['id']))){
						if($coordsmode==0){
								tabbedecho("function ".$graphobj['id']."(){\n",$tabs);
						}else{
            		if($coordsmode==0){
								    echo "// --------======####".$graphobj['id']." START ####======--------\n";
                }else{
                    if(isset($graphobj['id'])) $objid=$graphobj['id'];
                }
            }
						array_push($funclist,$graphobj['id']);
            array_push($lastid,$graphobj['id']);
				}else{
            if($coordsmode==0){
						    tabbedecho("// --------======####".$graphobj['id']." START ####======--------\n",$tabs);		
            }else{
                if(isset($graphobj['id'])) $objid=$graphobj['id'];
            }
            array_push($lastid,"");
          }
				$tabs++;
		}else if($graphobj['kind']=="eg"){
				$tabs--;
        $closeid=array_pop($lastid);
				if(($tabs==0)&&($closeid!=="")){
						if($coordsmode==0){
								tabbedecho("}\n\n",$tabs);
						}
				}else{
            if($coordsmode==0) tabbedecho("// --------======####".$closeid." END ####======--------\n",$tabs+1);		
				}
		}else{
//				echo "/*\n";
//				print_r($graphobj);
//				echo "/*\n";
				if(isset($graphobj['id'])){
						if($coordsmode==0){
                tabbedecho("//--==## ".$graphobj['id']." ".$graphobj['kind']." ##==--\n",$tabs+1);
            }else{
                $localobjid=$graphobj['id'];
            }
				}else{
            $localobjid="";
        }
		}
				
		if(!isset($graphobj['opacity'])){
				if($lastopacity!="none"){
						if($coordsmode==0) tabbedecho("ctx.globalAlpha=1.0;\n",$tabs);
						$lastopacity="none";
				}
		}
		if(!isset($graphobj['dasharr'])){
				if($lastdash!=""){
						tabbedecho("ctx.setLineDash([]);\n",$tabs);
						$lastdash="";
				}
		}
	
		$pnts=$graphobj['pntarr'];
		$pntcount=count($pnts);

		if($graphobj['kind']=="linearGradient"){

				// We need to apply matrix exported from affinity designer
				// The math for applying 2d transform:
				// x' = a(0)*x + c(2)*y + e(4) 
				// y' = b(1)*x + d(3)*y + f(5)
				// https://stackoverflow.com/questions/14684846/flattening-svg-matrix-transforms-in-inkscape
			
				if(isset($graphobj['gradientTransform'])){
						$matrixwork=substr($graphobj['gradientTransform'],7,strlen($graphobj['gradientTransform'])-8);
						if(strpos($matrixwork,",")>0){
								$matrix=explode(",",$matrixwork);
						}else{
								$matrix=explode(" ",$matrixwork);
						}

						$x1=(($graphobj['gradientx1']*$matrix[0])+($graphobj['gradienty1']*$matrix[2])+$matrix[4]);
						$y1=(($graphobj['gradientx1']*$matrix[1])+($graphobj['gradienty1']*$matrix[3])+$matrix[5]);
						$x2=(($graphobj['gradientx2']*$matrix[0])+($graphobj['gradienty2']*$matrix[2])+$matrix[4]);
						$y2=(($graphobj['gradientx2']*$matrix[1])+($graphobj['gradienty2']*$matrix[3])+$matrix[5]);
				
						$graphobj['gradientx1']=$x1;
						$graphobj['gradienty1']=$y1;
						$graphobj['gradientx2']=$x2;
						$graphobj['gradienty2']=$y2;
					
				}
			
        if($coordsmode==0){
    				tabbedecho("var ".$graphobj['gradientid']."=ctx.createLinearGradient(".$graphobj['gradientx1'].",".$graphobj['gradienty1'].",".$graphobj['gradientx2'].",".$graphobj['gradienty2'].");\n",$tabs);
    				foreach($graphobj['stops'] as $key => $value){
    						tabbedecho($graphobj['gradientid'].".addColorStop(".$value[0].",'".$value[1]."');\n",$tabs);
    				}        
        }else{
            // Make code for handling gradients and stops
        }
		}else if($graphobj['kind']=="radialGradient"){
        if($coordsmode==0){
      				tabbedecho("var ".$graphobj['gradientid']."=ctx.createRadialGradient(".$graphobj['gradientcx'].",".$graphobj['gradientcy'].",0,".$graphobj['gradientfx'].",".$graphobj['gradientfy'].",".$graphobj['gradientr'].");\n",$tabs);
      				foreach($graphobj['stops'] as $key => $value){
      						tabbedecho($graphobj['gradientid'].".addColorStop(".$value[0].",'".$value[1]."');\n",$tabs);
      				}
        }else{
            // Make code for handling gradients and stops
        }
		}else if($graphobj['kind']=="text"){
				if(isset($graphobj['textx'])){
						tabbedecho("ctx.fillText('".$graphobj['textline']."',".$graphobj['textx'].",".$graphobj['texty'].");\n",$tabs);						
				}
				if(isset($graphobj['translate'])){
						tabbedecho("ctx.save();\n",$tabs);
						//echo "c.font = '".$fontstyle." ".$fontline."px ".$fontfamily."';\n";
						tabbedecho("ctx.translate(".$graphobj['translate'].");\n",$tabs);
						tabbedecho("ctx.rotate(".$graphobj['rotate'].");\n",$tabs);
						tabbedecho("ctx.scale(".$graphobj['scale'].");\n",$tabs);
						tabbedecho("ctx.fillText('".$graphobj['textline']."',0,0);\n",$tabs);					
						tabbedecho("ctx.restore();\n",$tabs);
				}
		}else if($graphobj['kind']=="g"||$graphobj['kind']=="eg"){

		}else if($pntcount>0){
				$fill="none";
				$stroke="none";
				foreach($graphobj as $key => $value){
						if($key=="strokestyle"){
								if($coordsmode==0){
										tabbedecho("ctx.strokeStyle='".$value."';\n",$tabs);
								}
								$stroke=$value;
						}else if($key=="fillgradient"){
								if($coordsmode==0){
                    if(strpos($value,"url(")!==false){
                        $value=substr($value,5,-1);
                    }				
										tabbedecho("ctx.fillStyle=".str_replace("-","",$value).";\n",$tabs);
								}
								$fill=$value;
						}else if($key=="fillstyle"){
                if($coordsmode==0){
                    if(strpos($value,"url(")!==false){
                        $value=substr($value,5,-1);
                        tabbedecho("ctx.fillStyle=".str_replace("-","",$value).";\n",$tabs);
                    }else{
                        tabbedecho("ctx.fillStyle='".str_replace("-","",$value)."';\n",$tabs);
                    }				
								}
								$fill=$value;
						}else if($key=="strokewidth"){
								if($coordsmode==0){
										tabbedecho("ctx.lineWidth='".$value."';\n",$tabs);
								}
						}else if($key=="linecap"){
								if($coordsmode==0){
								    tabbedecho("ctx.lineCap='".$value."';\n",$tabs);
                }
						}else if($key=="linejoin"){
								if($coordsmode==0){								
                    tabbedecho("ctx.lineJoin='".$value."';\n",$tabs);
                }
						}else if($key=="opacity"){
								if($coordsmode==0) tabbedecho("ctx.globalAlpha=".$value.";\n",$tabs);
								$lastopacity=$value;
						}else if($key=="dasharr"){
								if($coordsmode==0){
                    tabbedecho("ctx.setLineDash([".$value."]);\n",$tabs);
                }
                $lastdash=$value;
						}
				}

        // Handling if no fill or stroke is given
				if($fill=="none" && $stroke=="none" && $coordsmode==0) tabbedecho("ctx.fillStyle='#000';\n",$tabs);

				if($coordsmode==0){
            // echo "// Modes ".$fill." ".$lastFill." ".$stroke." ".$lastStroke." ".$startedPath."\n";
						tabbedecho("ctx.beginPath();\n",$tabs);
				}else{
            if($objcount++>0) echo ",";
						echo "[";
            // Object ID
            if($localobjid!=""){
                echo '"'.$objid." ".$localobjid.'",';		
            }else{
                echo '"'.$objid.'",';		            
            }
            // Write fill and stroke style to array
            echo '"'.$fill.'",';
            echo '"'.$stroke.'",';
				}
        $pointcnt=0;
				foreach($pnts as $pnt){
						if($pnt[0]=="M"){
								if($coordsmode==0){
										tabbedecho("ctx.moveTo(".$pnt[1].",".$pnt[2].");\n",$tabs);
								}else{
                    if($pointcnt!=0) echo ",";
										echo '"M",'.$pnt[1].",".$pnt[2];					
								}
						}else if($pnt[0]=="L"){
								if($coordsmode==0){							
										tabbedecho("ctx.lineTo(".$pnt[1].",".$pnt[2].");\n",$tabs);								
								}else{
											echo ',"L",'.$pnt[1].",".$pnt[2];								
								}
						}else if($pnt[0]=="Q"){
								if($coordsmode==0){	
								    tabbedecho("ctx.quadraticCurveTo(".$pnt[1].",".$pnt[2].",".$pnt[3].",".$pnt[4].");\n",$tabs);
                }else{
											echo ',"Q",'.$pnt[1].",".$pnt[2].",".$pnt[3].",".$pnt[4];								
								}
						}else if($pnt[0]=="B"){
								if($coordsmode==0){	            
								    tabbedecho("ctx.bezierCurveTo(".$pnt[1].",".$pnt[2].",".$pnt[3].",".$pnt[4].",".$pnt[5].",".$pnt[6].");\n",$tabs);
                }else{
											echo ',"B",'.$pnt[1].",".$pnt[2].",".$pnt[3].",".$pnt[4].",".$pnt[5].",".$pnt[6];								
								}
 						}
            $pointcnt++;
				}
			
				if($coordsmode==0){
						if($fill!="none") tabbedecho("ctx.fill();\n",$tabs);
						if($stroke!="none") tabbedecho("ctx.stroke();\n",$tabs);
						if($fill=="none" && $stroke=="none") tabbedecho("ctx.fill();\n",$tabs);
				}else{
						echo "],\n";
				}

        $lastFill=$fill;
        $lastStroke=$stroke;
			
		}else{
				echo "//".$graphobj['kind']."\n";
		}	
}

if($coordsmode==1) echo "]";

$Yoffs=($Ymax-$Ymin)+4;
$Xoffs=($Xmax-$Xmin)+4;


if($coordsmode==0){
		if((count($funclist)>0)&&(!isset($_GET['nofuncs']))){
				echo "\n// Function calls: ".$Xoffs." ".$Yoffs." ".$Xmin." ".$Xmax." ".$Ymin." ".$Ymax." \n";
				foreach($funclist as $value){
						if(isset($showlist[$value])){
								if($showlist[$value]!="hide"){
										echo $value."();\n";				
								}
						}else{
										echo $value."();\n";								
                    // echo "ctx.translate(0,".$Yoffs.");";
            }
				}

		}else{
			echo "\n// Canvas extents: ".$Xoffs." ".$Yoffs." ".$Xmin." ".$Xmax." ".$Ymin." ".$Ymax." \n";
		}
}

?>
