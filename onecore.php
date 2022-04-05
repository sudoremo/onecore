<?php
    /**
     * onecore - the single file cms
     * github.com/sudoremo/onecore
    */

    session_start();
    ob_start();
?>

<conf>
	<flg_password_protected>false</flg_password_protected>
	<allowed_file_types>html,xhtml</allowed_file_types>
	<username>admin</username>
	<password>21232f297a57a5a743894a0e4a801fc3</password>
</conf>

<?php
    $configurationXml = ob_get_contents();
    ob_end_clean();
    
    class Conf extends SimpleXMLElement
    {
    	function asXmlWithoutXmlHeader()
    	{
    		$xml = $this->asXML();
    		$xml = explode("\n", $xml);
    		$xml[0] = "";
    		$xml = trim( implode($xml, "\n") );	
    		return $xml;
    	}	
    }
    
    // Read configuration
    $configuration = simplexml_load_string($configurationXml, 'Conf');
    
    if ($configuration == false)
   	{
   		echo "Error, could not read configuration. Exiting.";
   		exit;	
   	}
    ob_start();
?>

<style type="text/css">
	body {
		font-family: Verdana, Arial;
		font-size: 10pt;
		margin-left: 10px;
		padding-left: 3px;
		padding-bottom: 0px;
	}
		
	h2 {
		border-bottom: 1px solid darkgray;
	}
</style>
<h1>onecore</h1>
<p>This is sample content. To edit check out <a href="?admin">onecore.php?admin</a>.</p>
<p>Made with <a href="http://www.remofritzsche.com/projects/onecore/">onecore</a>.</p>

<?php
    $html = ob_get_contents();
    ob_end_clean();

    // Read current page
    $filename = explode("/", $_SERVER['PHP_SELF']);
    $filename = $filename[ count($filename) - 1 ];
    $pagebuffer = file($filename);

    // Get php section A
    $i = 0;
    
    for ($i = 0; $i < count($pagebuffer); $i++)
    {
    	if ($pagebuffer[$i] != "?>\n")
    	{
    		$phpSectionA .= $pagebuffer[$i];
    	}
    	else
    	{
    		$phpSectionA .= "?>\n";
    		break;	
    	}
    }
    
    // Get php section B
    $read = false;
    for ($i = $i+1; $i < count($pagebuffer); $i++)
    {
    	if ($pagebuffer[$i] == "<?php\n")
    		$read = true;
    	
    	if ($pagebuffer[$i] != "?>\n")
    	{
    		if ($read)
    			$phpSectionB .= $pagebuffer[$i];
    	}
    	else
    	{
    		$phpSectionB .= "?>\n";
    		break;	
    	}
    }
    
    
    // Get php section C
    for ($i = (count($pagebuffer)-1); $i >= 0; $i--)
    {
    	if ($pagebuffer[$i] != "<?php\n")
    	{
    		$phpSectionC = $pagebuffer[$i] . $phpSectionC;
    	}
    	else
    	{
    		$phpSectionC = "<?php\n" . $phpSectionC;
    		break;	
    	}
    }
    
    // Check for permissions
   	if ((isset($_GET['admin']) || isset($_GET['edit']) || isset($_GET['conf']) || isset($_GET['import']) || isset($_GET['export'])) && $configuration->flg_password_protected == "true" &! $_SESSION['ok'] == true)
   	{
   		echo 'Error, please <a href="'.$filename.'?login">log in</a> first.';
   		exit;	
   	}
   	
   	if (isset($_GET['login']))
   	{
   		if (!isset($_GET['do']))
   		{
    		echo '<h1>onecore Login: '.$filename.'</h1>';
	    	echo '<form action="' . $filename . '?login&do" method="POST" />';
	    	echo '<table><tr>';
	    	echo '<tr><td>Username</td><td>';
	    	echo '<input type="text" name="username" id="username" />';
	    	echo '</td></tr>';
	    	echo '<tr><td>Password</td><td>';
	    	echo '<input type="password"" name="password"" id="password" />';
	    	echo '</td></tr>';
	    	echo '</table>';
	    	echo '<input type="submit" value="Login" />';
	    	echo '<input type="reset" value="Abort" onClick="history.back()" />';
	    	echo '</form>';
   		}
   		else
   		{
   			if ($_POST['username'] == $configuration->username && md5($_POST['password']) == $configuration->password)
   			{
   				$_SESSION['ok'] = true;
   				echo 'Thank you for login. Proceed <a href="'.$filename.'?admin">here</a>.';
   			}
   			else
   			{
   				echo 'Sorry, could not login. <a href="'.$filename.'?login">Try again</a>.';
   			}
   		}
   	}
    
    if (isset($_GET['edit']))
    {
    	echo '<script type="text/javascript">'."\n";
	echo 'function insertTab(event,obj) {'."\n";
	echo '    var tabKeyCode = 9;'."\n";
	echo '    if (event.which) // mozilla'."\n";
	echo '        var keycode = event.which;'."\n";
	echo '    else // ie'."\n";
	echo '        var keycode = event.keyCode;'."\n";
	echo '    if (keycode == tabKeyCode) {'."\n";
	echo '        if (event.type == "keydown") {'."\n";
	echo '            if (obj.setSelectionRange) {'."\n";
	echo '                // mozilla'."\n";
	echo '                var s = obj.selectionStart;'."\n";
	echo '                var e = obj.selectionEnd;'."\n";
	echo '                obj.value = obj.value.substring(0, s) + '."\n";
	echo '                    "\t" + obj.value.substr(e);'."\n";
	echo '                obj.setSelectionRange(s + 1, s + 1);'."\n";
	echo '                obj.focus();'."\n";
	echo '            } else if (obj.createTextRange) {'."\n";
	echo '                // ie'."\n";
	echo '                document.selection.createRange().text="\t"'."\n";
	echo '                obj.onblur = function() { this.focus(); this.onblur = null; };'."\n";
	echo '            } else {'."\n";
	echo '                // unsupported browsers'."\n";
	echo '            }'."\n";
	echo '        }'."\n";
	echo '        if (event.returnValue) // ie ?'."\n";
	echo '            event.returnValue = false;'."\n";
	echo '        if (event.preventDefault) // dom'."\n";
	echo '            event.preventDefault();'."\n";
	echo '        return false; // should work in all browsers'."\n";
	echo '    }'."\n";
	echo '    return true;'."\n";
	echo '}'."\n";
	echo '</script>'."\n";
		
    echo '<h1>Edit page</h1>';
	echo '<form action="' . $filename . '?save" method="POST" />';
	echo '<textarea id="html" onkeydown="return insertTab(event,this);" onkeyup="return insertTab(event,this);" onkeypress="return insertTab(event,this);" name="html" style="width:100%;height:350px;" />'.$html.'</textarea>';
	echo '<br />';
	echo '<input type="submit" value="Save page" />';
	echo '<input type="reset" value="Abort" onClick="history.back()" />';
	echo '</form>';
	echo '<script type="text/javascript" language="javascript">';
	echo '	function getPreview()';
	echo '	{';
	echo '		if (document.getElementById(\'btnPreview\').value != \'Close preview\')';
	echo '		{';
	echo '			document.getElementById(\'preview\').innerHTML = \'<hr />\' + document.getElementById(\'html\').value;';
	echo '			document.getElementById(\'btnPreview\').value = \'Close preview\';';
	echo '		}';
	echo '		else';
	echo '		{';
	echo '			document.getElementById(\'preview\').innerHTML = \'&nbsp;\';';
	echo '			document.getElementById(\'btnPreview\').value = \'Preview\';';
	echo '		}';
	echo '	}';
	echo '</script>';	
	echo '<input type="submit" value="Preview" onClick="getPreview()" id="btnPreview" />';
	echo '<div id="preview">&nbsp;</div>';
    }
	
	if (isset($_GET['conf']))
	{
		if (!isset($_GET['sav']))
		{
			echo '<h1>onecore Configuration: '.$filename.'</h1>';
		    echo '<form action="' . $filename . '?conf&sav" method="POST" />';
		    echo '<table><tr>';
		   	echo '<tr><td>Password protection</td><td>';
		   	$checked = ($configuration->flg_password_protected == 'true') ? 'checked' : '';
		   	echo '<input type="checkbox" name="passwordprotection" id="passwordprotection" ' . $checked . '/>';
		   	echo '</td></tr>';
		   	echo '<tr><td>Username</td><td>';
		   	echo '<input type="text" name="username" id="username" value="'.$configuration->username.'" />';
		   	echo '</td></tr>';
		   	echo '<tr><td>Password<br />(only to set a new)</td><td>';
		   	echo '<input type="password" name="password" id="password" />';
		   	echo '</td></tr>';
		   	echo '<tr><td>Allowed import file extensions<br />Example: \'html,xhtml\'</td><td>';
		   	echo '<input type="text" name="allowed_extensions" id="allowed_extensions" value="'.$configuration->allowed_file_types.'" />';
		   	echo '</td></tr>';
		   	echo '</table>';
		    echo '<input type="submit" value="Save configuration" />';
		    echo '<input type="reset" value="Abort" onClick="history.back()" />';
		    echo '</form>';
		}
		else
		{
			
			if (isset($_POST['passwordprotection']))
			{
				$configuration->flg_password_protected = 'true';
			}
			else
			{
				$configuration->flg_password_protected = 'false';
			}
			
			$configuration->username = $_POST['username'];
			
			if (isset($_POST['password']) && $_POST['password'] != "")
			{
				$configuration->password = md5($_POST['password']);	
			}
			
			$configuration->allowed_file_types = $_POST['allowed_extensions'];
			
			
			if (!file_put_contents($filename, trim($phpSectionA . "\n" . $configuration->asXmlWithoutXmlHeader() . "\n" . $phpSectionB . "\n" . stripslashes($html) . "\n" . $phpSectionC)))
	    	{
	    		echo '<p><b>Error: Could not save.</b></p>';	
	    	}
	   		else
	        {
	        	echo '<p><b>Saved.</b></p><p>Continue <a href="'.$filename.'">here</a>.</p>';
	        	$html = $_POST['html'];
	        }
			
		}
	}
    
    if (isset($_GET['save']))
    {   	  	
    	if (!file_put_contents($filename, rtrim($phpSectionA . "\n" . $configurationXml . "\n" . $phpSectionB . "\n" . stripslashes($_POST['html']) . "\n" . $phpSectionC)))
    	{
    		echo '<p><b>Error: Could not save.</b></p>';	
    	}
   	else
        {
        	echo '<p><b>Saved.</b></p><p>Continue <a href="'.$filename.'">here</a>.</p>';
        	$html = $_POST['html'];
        }
    }
    
    if (isset($_GET['export']))
    {
    	$exportFilename = explode(".", $filename);
    	$exportFilename = $exportFilename[0] . ".html";
    	
		header('Content-type: text/html');
		header('Content-Disposition: attachment; filename="'.$exportFilename.'"');
    }
    
    if (isset($_GET['admin']))
    {
    	echo '<h1>onecore Administration: '.$filename.'</h1>';
		echo '<p><ul>';
		echo '<li><a href="'.$filename.'?edit">Edit page</a>';
		echo '<li><a href="'.$filename.'?export">Export page</a>';
		echo '<li><a href="'.$filename.'?import">Import page</a>';
		echo '<li><a href="'.$filename.'?conf">Settings</a>';
		echo '<li><a href="'.$filename.'">Back to page</a>';
		if ($configuration->flg_password_protected == 'true')
			echo '<li><a href="'.$filename.'?logout">Logout</a>';
		echo '</ul></p>';
    }
    
    if (isset($_GET['logout']))
    {
    	$_SESSION['ok'] = false;
    	echo "Logged out successfully. Thank you for using onecore<hr />";	
    }
    
    if (isset($_GET['import']))
    {
    	if (!isset($_GET['do']))
    	{
	    	echo '<h1>Import HTML File</h1>';
			echo '<form enctype="multipart/form-data" action="' . $filename . '?import&do" method="POST">';
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="30000" />';
			echo '<input name="userfile" type="file" value="Browse" />';
			echo '<input type="submit" value="Send file" />';
			echo '<input type="reset" value="Abort" onClick="history.back()" />';
			echo '</form>';
		}
		else
		{	
			$extension = explode(".", $_FILES['userfile']['name']);
    		$extension = $extension[1];
    		
 			$ALLOWED_EXTENSIONS = explode(",", $configuration->allowed_file_types);
 			
 			if (!in_array($extension, $ALLOWED_EXTENSIONS))
 			{
 				echo '<p><b>Error:</b> The file extension \''.$extension.'\' is not supported. Please use one of these instead:';
 				echo '<p><ul>';
 				foreach ($ALLOWED_EXTENSIONS as $ext)
 				{
 					echo "<li>.$ext</li>";	
 				}	
 				echo '</ul>';
 				echo '</p>';
 				echo '<p><a href="'.$filename.'?import">Try again</a>.</p>';	
 			}
			else
			{
				$html = implode( file($_FILES['userfile']['tmp_name']) , "" );
				
				if (!file_put_contents($filename, trim($phpSectionA . "\n" . $configurationXml . "\n" . $phpSectionB . "\n" . stripslashes($html) . "\n" . $phpSectionC)))
				{
					echo '<p><b>Error: Could not upload data.</b></p><p>Go <a href="'.$filename.'">back</a>.</p>';	
		    	}
		   		else
		        {
		        	echo '<p><b>Upload successfull.</b></p><p>Continue <a href="'.$filename.'">here</a>.</p>';
		        	$html = $_POST['html'];
		        }
	        }
		}
    }
    
    if (!isset($_GET['save']) &! isset($_GET['edit']) &! isset($_GET['import']) &! isset($_GET['admin']) &! isset($_GET['login']) &! isset($_GET['conf']))
    	echo stripslashes($html);
?>
