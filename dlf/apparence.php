<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Directory Listing of <?php echo str_replace('\\', '', dirname(strip_tags($_SERVER['PHP_SELF']))).'/'.$leadon;?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $includeurl; ?>dlf/styles.css" />
<?php
if($showthumbnails) {
?>
<script language="javascript" type="text/javascript">
<!--
function o(n, i) {
	document.images['thumb'+n].src = '<?php echo $includeurl; ?>dlf/i.php?f='+i<?php if($memorylimit!==false) echo "+'&ml=".$memorylimit."'"; ?>;

}

function f(n) {
	document.images['thumb'+n].src = 'dlf/trans.gif';
}
//-->
</script>
<?php
}
?>
</head>
<body>
<div id="container">
  <h1>Directory Listing of <?php echo str_replace('\\', '', dirname(strip_tags($_SERVER['PHP_SELF']))).'/'.$leadon;?></h1>
  <div id="breadcrumbs"> <a href="<?php echo strip_tags($_SERVER['PHP_SELF']);?>">home</a> 
  <?php
 	 $breadcrumbs = split('/', str_replace($startdir, '', $leadon));
  	if(($bsize = sizeof($breadcrumbs))>0) {
  		$sofar = '';
  		for($bi=0;$bi<($bsize-1);$bi++) {
			$sofar = $sofar . $breadcrumbs[$bi] . '/';
			echo ' &gt; <a href="'.strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode($sofar).'">'.$breadcrumbs[$bi].'</a>';
		}
  	}
  
	$baseurl = strip_tags($_SERVER['PHP_SELF']) . '?dir='.strip_tags($_GET['dir']) . '&amp;';
	$fileurl = 'sort=name&amp;order=asc';
	$sizeurl = 'sort=size&amp;order=asc';
	$dateurl = 'sort=date&amp;order=asc';
	
	switch ($_GET['sort']) {
		case 'name':
			if($_GET['order']=='asc') $fileurl = 'sort=name&amp;order=desc';
			break;
		case 'size':
			if($_GET['order']=='asc') $sizeurl = 'sort=size&amp;order=desc';
			break;
			
		case 'date':
			if($_GET['order']=='asc') $dateurl = 'sort=date&amp;order=desc';
			break;  
		default:
			$fileurl = 'sort=name&amp;order=desc';
			break;
	}
  ?>
  </div>
  <div id="listingcontainer">
    <div id="listingheader"> 
	<div id="headerfile"><a href="<?php echo $baseurl . $fileurl;?>">File</a></div>
	<div id="headersize"><a href="<?php echo $baseurl . $sizeurl;?>">Size</a></div>
	<div id="headermodified"><a href="<?php echo $baseurl . $dateurl;?>">Last Modified</a></div>
	</div>
    <div id="listing">
	<?php
	$class = 'b';
	if($dirok) {
	?>
	<div><a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode($dotdotdir);?>" class="<?php echo $class;?>"><img src="<?php echo $includeurl; ?>dlf/dirup.png" alt="Folder" /><strong>..</strong> <em>&nbsp;</em>&nbsp;</a></div>
	<?php
		if($class=='b') $class='w';
		else $class = 'b';
	}
	$arsize = sizeof($dirs);
	for($i=0;$i<$arsize;$i++) {
	?>
	<div><a href="<?php echo strip_tags($_SERVER['PHP_SELF']).'?dir='.urlencode(str_replace($startdir,'',$leadon).$dirs[$i]);?>" class="<?php echo $class;?>"><img src="<?php echo $includeurl; ?>dlf/folder.png" alt="<?php echo $dirs[$i];?>" /><strong><?php echo $dirs[$i];?></strong> <em>-</em> <?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$dirs[$i]));?></a></div>
	<?php
		if($class=='b') $class='w';
		else $class = 'b';	
	}
	
	$arsize = sizeof($files);
	for($i=0;$i<$arsize;$i++) {
		$icon = 'unknown.png';
		$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
		$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
		$thumb = '';
		
		if($showthumbnails && in_array($ext, $supportedimages)) {
			$thumb = '<span><img src="dlf/trans.gif" alt="'.$files[$i].'" name="thumb'.$i.'" /></span>';
			$thumb2 = ' onmouseover="o('.$i.', \''.urlencode($leadon . $files[$i]).'\');" onmouseout="f('.$i.');"';
			
		}
		
		if($filetypes[$ext]) {
			$icon = $filetypes[$ext];
		}
		
		$filename = $files[$i];
		if(strlen($filename)>43) {
			$filename = substr($files[$i], 0, 40) . '...';
		}
		
		$fileurl = $includeurl . $leadon . $files[$i];
		if($forcedownloads) {
			$fileurl = $_SESSION['PHP_SELF'] . '?dir=' . urlencode(str_replace($startdir,'',$leadon)) . '&download=' . urlencode($files[$i]);
		}

	?>
	<div><a href="<?php echo $fileurl;?>" class="<?php echo $class;?>"<?php echo $thumb2;?>><img src="<?php echo $includeurl; ?>dlf/<?php echo $icon;?>" alt="<?php echo $files[$i];?>" /><strong><?php echo $filename;?></strong> <em><?php echo round(filesize($includeurl.$leadon.$files[$i])/1024);?>KB</em> <?php echo date ("M d Y h:i:s A", filemtime($includeurl.$leadon.$files[$i]));?><?php echo $thumb;?></a></div>
	<?php
		if($class=='b') $class='w';
		else $class = 'b';	
	}	
	?></div>
	<?php
	if($allowuploads) {
		$phpallowuploads = (bool) ini_get('file_uploads');		
		$phpmaxsize = ini_get('upload_max_filesize');
		$phpmaxsize = trim($phpmaxsize);
		$last = strtolower($phpmaxsize{strlen($phpmaxsize)-1});
		switch($last) {
			case 'g':
				$phpmaxsize *= 1024;
			case 'm':
				$phpmaxsize *= 1024;
		}
	
	?>
	<div id="upload">
		<div id="uploadtitle">
			<strong>File Upload</strong> (Max Filesize: <?php echo $phpmaxsize;?>KB)
			
			<?php if($uploaderror) echo '<div class="upload-error">'.$uploaderror.'</div>'; ?>
		</div>
		<div id="uploadcontent">
			<?php
			if($phpallowuploads) {
			?>
			<form method="post" action="<?php echo strip_tags($_SERVER['PHP_SELF']);?>?dir=<?php echo urlencode(str_replace($startdir,'',$leadon));?>" enctype="multipart/form-data">
			<input type="file" name="file" /> <input type="submit" value="Upload" />
			</form>
			<?php
			}
			else {
			?>
			File uploads are disabled in your php.ini file. Please enable them.
			<?php
			}
			?>
		</div>
		
	</div>
	<?php
	}
	?>
  </div>
</div>
<div id="copy">Directory Listing Script &copy;2008 Evoluted, <a href="http://www.evoluted.net/">Web Design Sheffield</a>.</div>
</body>
</html>