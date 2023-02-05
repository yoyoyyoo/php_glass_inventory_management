<?php // http://localhost/YananLiuCodingAsst/asstMain.php 
function WriteHeaders($Heading, $TitleBar, $fileName)
{
    echo "
      <!doctype html>
      <html lang = \"en\">
      <link rel =\"stylesheet\" type = \"text/css\" href=\"$fileName\"/>
	  <head>
		  <meta charset = \"UTF-8\">
		  <title>$TitleBar</title>\n
	  </head>
      <body>\n
      <h1>$Heading</h1>
	
	";
}

function DisplayLabel($name = "I am a label")
{
    echo "<label> ". $name ." </label>";
}

function DisplayTextbox($Type, $Name, $Size, $Value=0, $IsChecked)
{
 echo "<input type =\"$Type\" name =\"$Name\" size=$Size value = \"$Value\" $IsChecked>";
}

function DisplayImage($FileName, $alt, $Height, $Width)
{
    echo 
    "    
     <img src=\"$FileName\" height = \"$Height\"
                                     width =\"$Width\"  alt=\"$alt\"/>
  
    ";
}

function DisplayButton($Name, $Text, $FileName, $Alt="Picture Load Fail")
{
  if($FileName === "")       
  echo "  
  <button type=\"submit\" name = \"$Name\">$Text</button>
   ";
  else
  {
    echo "  
    <button type=\"submit\" name = \"$Name\">";   
    DisplayImage($FileName, $Alt, "55", "120");
    echo " </button>";
  }
}

function DisplayContactInfo()
{    
    echo"
    <footer>
        <p> Questions? Comments? Contact Yanan Liu: 
           <a href=\"mailto:yanan.liu@student.sl.on.ca\">
                              yanan.liu@student.sl.on.ca</a></p>
    </footer>";
}

function WriteFooters()
{
    DisplayContactInfo();
    echo "</body>\n";
    echo "</html>\n";
}

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);
    if ($mysqlObj->connect_errno != 0) 
    {
     echo "<p>Connection failed. Unable to open database $Database. Error: "
              . $mysqlObj->connect_error . "</p>";
     exit;
    }
    return ($mysqlObj); 
}
?>