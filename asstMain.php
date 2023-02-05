<?php // http://localhost/YananLiuCodingAsst/asstmain.php 
require_once("assInclude.php");
require_once("clsDeleteSunglassRecord.php");

function displayMainForm()
{
   
    echo "<form action = ? method=post>";
    DisplayButton("f_CreateTable", "Create",
                  "./imgs/button_create-table.jpg", "Create Butoon");
    DisplayButton("f_AddRecord", "AddRecord",
                  "./imgs/button_add-record.png", "Add Record Butoon");
    DisplayButton("f_DeleteRecord", "DeleteRecord",
                  "./imgs/button_delete-record.png", "Delete Record Butoon");
    DisplayButton("f_DisplayData", "DisplayData",
                  "./imgs/button_display-data.png", "Display Data Butoon");
    echo"</form>";
}

function createTableForm($mysqlObj,$TableName)
{
    $BrandName = "BrandName varchar(10) PRIMARY KEY";
    $DateManufactured = "DateManufactured date" ;
    $CameraMP = "CameraMP int";
    $Color = "Color varchar(15)";
     
    $SQLStatment = "drop table if EXISTS $TableName";
    $stmtObj = $mysqlObj ->prepare ($SQLStatment);
    $stmtObj ->execute();

    $SQLStatment = "create table $TableName ($BrandName, 
                    $DateManufactured, $CameraMP, $Color)";

    $stmtObj = $mysqlObj ->prepare ($SQLStatment);
    $CreateResult = $stmtObj ->execute();

    if ($CreateResult) 
       echo "<div class=\"DataPair\"> Table $TableName created.</div>";
    else
      echo "Unable to create table $TableName.";
    $stmtObj -> close();
    
    echo "<form action = ? method=post>";
    DisplayButton("f_Home", "Home","./imgs/button_home.png", "Home Butoon"); 
    echo"</form>";
}

function addRecordForm($mysqlObj,$TableName)
{
    echo "<form action = ? method=post>";
    echo "<div class=\"DataPair\">";
    DisplayLabel("Brand name");
    DisplayTextbox("text", "f_BrandName", "10", "", "");
    echo "</div>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Date manufactured");
    DisplayTextBox("date", "f_DateManufactured", 10, date('Y-m-d'),"");
    echo "</div>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("CameraMP");
    echo "</div>";

    echo "<div class=\"DataPair\">";
    DisplayTextbox("radio","f_CamerMP" ,"5"  , "5", "checked");
    echo "5MP </div>";

    echo "<div class=\"DataPair\">";
    DisplayTextbox("radio","f_CamerMP" , "5"  , "10","");
    echo "10MP </div>";
    
    echo "<div class=\"DataPair\">";
    DisplayLabel("Color");
    DisplayTextBox("color", "f_Color", "7", "#DF307B", "");
    echo "</div>";
  
    DisplayButton("f_Save", "saveRecord",
                  "./imgs/button_save-record.png", "Save Record Butoon");
    DisplayButton("f_Home", "home",
                  "./imgs/button_home.png", "Home Butoon");
    echo "</form>";
}

function saveRecordtoTableForm($mysqlObj,$TableName)
{
  $BrandName = $_POST["f_BrandName"];
  $DateManufactured = $_POST["f_DateManufactured"];
  $CameraMP = $_POST["f_CamerMP"];
  $Color = $_POST["f_Color"];

  $query = "insert into $TableName (
            BrandName, DateManufactured, CameraMP, Color) values(?,?,?,?)";
  $stmtObj = $mysqlObj -> prepare($query);
  $BindSuccess = $stmtObj -> bind_param(
                "ssis",$BrandName, $DateManufactured, $CameraMP, $Color);
  if ($BindSuccess )
     $success = $stmtObj -> execute();
  else
     echo "Bind failed: ". $stmtObj -> error;
      
  if($success)
    {
      echo "<div class=\"DataPair\"> Record successfully added to $TableName </div>";
    }
  else
    echo "Unable to save the records";
  $stmtObj -> close();
 
  echo "<form action = ? method=post>";
  DisplayButton("f_Home", "Home","./imgs/button_home.png", "Home Butoon"); 
  echo"</form>";
}

function displayDataForm($mysqlObj,$TableName)
{
  $query = "select BrandName, DateManufactured, CameraMP, Color from $TableName order by BrandName";
  $stmtObj = $mysqlObj->prepare($query);
  $stmtObj -> execute();
  $BindResult = $stmtObj->bind_result(
                $BrandName, $DateManufactured, $CameraMP, $Color);
  echo"
  <table id = \"displayTable\">
  <tr>
     <th colspan = 4>Bluetooth Smart Sunglasses Inventory</th>
  </tr>
  <tr>
     <th> Brand Name</th>
     <th> Date Manufactured </th>
     <th> Camera MP </th>
     <th> Color </th>
 </tr>
";

  while ($stmtObj->fetch())
  {
   echo "
    <tr>
      <td> $BrandName </td>
      <td> $DateManufactured</td>
      <td> $CameraMP</td>
      <td bgcolor = \"$Color\"> </td>
    </tr>";
  }
  echo "</table>";
  $stmtObj->close();

  echo "<form action = ? method=post>";
  DisplayButton("f_Home", "Home","./imgs/button_home.png", "Home Butoon"); 
  echo"</form>";
}

function deleteRecordForm($mysqlObj,$TableName)
{
  echo"<form action = ? method=post>";
  echo "<div class=\"DataPair\">";
  DisplayLabel("Brand name");
  DisplayTextbox("text", "f_TargetBrandName", "10", "", "");
  DisplayLabel("Caution: The delete is final!"); 
  echo "</div>";
  
  DisplayButton("f_IssueDelete", "delete",
                "./imgs/button_delete.png", "Delete Butoon");

  DisplayButton("f_Home", "home",
                "./imgs/button_home.png", "Home Butoon");
  echo "</form>";
}

function issueDeleteForm($mysqlObj,$TableName)
{
  $userBrandName = $_POST["f_TargetBrandName"];
  $newDeleteRecord = new clsDeleteSunglassRecord() ;
  $result = $newDeleteRecord ->deleteTheRecord(
                               $mysqlObj,$TableName, $userBrandName);
  if($result == 0)
     echo "<div class=\"DataPair\"> 
           $userBrandName record does not exist.</div>";  
  else
     echo "<div class=\"DataPair\"> 
             $userBrandName record deleted. </div>";

  echo "<form action = ? method=post>";
  DisplayButton("f_Home", "Home","./imgs/button_home.png", "Home Butoon"); 
  echo"</form>";
}


// main
date_default_timezone_set ('America/Toronto');
//edited by Yanan $mysqlObj; 
$mysqlObj = CreateConnectionObject();
$TableName = "Sunglasses"; 
// writeHeaders call 
WriteHeaders("Bluetooth Smart Sunglasses", "BluetoothSmartSunglasses", "asstStyle.css"); 
if (isset($_POST['f_CreateTable'])) 
   createTableForm($mysqlObj,$TableName);
else if (isset($_POST['f_Save'])) 
        saveRecordtoTableForm($mysqlObj,$TableName);
   else if (isset($_POST['f_AddRecord'])) 
        addRecordForm($mysqlObj,$TableName) ;	   
	  else if (isset($_POST['f_DeleteRecord'])) 
            deleteRecordForm($mysqlObj,$TableName) ;	 
         else if (isset($_POST['f_DisplayData'])) 
                 displayDataForm ($mysqlObj,$TableName);
     		else if (isset($_POST['f_IssueDelete']))
                 issueDeleteForm ($mysqlObj,$TableName);
		        else displayMainForm();
if (isset($mysqlObj)) $mysqlObj->close();
writeFooters();
?>