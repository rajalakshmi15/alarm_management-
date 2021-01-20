<html>
    <head>
        <meta http-equiv="refresh" content="5" /> 
    </head>
    <script>
        function vlv_close_txt(div_id, txt_id, fl_pipe, fl_pipe_a)
        {
            document.getElementById(div_id).style.backgroundColor = "green";
            document.getElementById(fl_pipe).src = "images/running_dots.png";
            document.getElementById(fl_pipe_a).style.display = "none";
            document.getElementById(txt_id).innerHTML = "Close";
        }
        function vlv_open_txt(div_id, txt_id, fl_pipe, fl_pipe_a)
        {
            document.getElementById(div_id).style.backgroundColor = "red";
            document.getElementById(fl_pipe).src = "images/running_dots.gif";
            document.getElementById(fl_pipe_a).style.display = "block";
            document.getElementById(txt_id).innerHTML = "Open";
        }
        
  function getSPval(def_val,val_for) {
  var sp_val = prompt("Please Enter Setpoint Value for: "+val_for+" in cm/min", def_val);
  if(val_for=="MAIN STREAM FLOW")  document.getElementById("msp").value = sp_val;
  if(val_for=="LOW STREAM FLOW")  document.getElementById("lsp").value = sp_val;
  document.getElementById("sp_form").submit();
  /*
  if (person != null) {
    document.getElementById("demo").innerHTML =  "Hello " + person + "! How are you today?";
  }
         * 
   */
}
    </script>

    <?php
    $servername = "localhost:3306";
    $username = "swarna";
    $password = "swarna1";

$conn=mysqli_connect($servername,$username,$password,"gail");
// Check connection
if (mysqli_connect_errno())
  {
 die("Failed to connect to MySQL: " . mysqli_connect_error());
  }
  ?>

    <body  style="background:url('images/bg02.jpg');">
        <div style="width:1200px;height:800px; background:white;margin-left:10px;" >
            <!-------------------------------------TITLE-------------------------------------------->
            <table width="100%" border="0" style="background: url('images/bg01.jpg');">
                <tr>
                    <td style="width:50px;"><img src="images/gail_logo.png"  width="50px"></td>
                    <td>
                        <font face="arial" color="Magenta" >
                <center><a style="font-weight: bold;font-size:30px;">Automatic Stream Changeover for Flow-metering </a></center>
                </font>
                </td>
                </tr>
            </table>
            <hr>
            <!--------------------------------------------------------------------------------------------->

            <!-------------------------------------MENU------------------------------------------------->
            <table width="100%" border="0" style="background: blue;">
                <tr>
                    <td style="width:20px;"><a href="index.php" style="color:white;" >Home</a></td>
                    <td style="width:20px;"><a href="index.php" style="color:white;width:60px;" >Home</a></td>
                    <td ><a href="index.php" style="color:white;width:60px;" >Home</a></td>
                </tr>
            </table>
            <hr>
            <!---------------------------------------------------------------------------------------------->
            <!-------------------------------------Main Flow Animation Variables------------------------------------>
            <?php
              isset($_POST["main_stream_sp"]) ? $main_stream_sp=$_POST["main_stream_sp"] : $main_stream_sp="";
              isset($_POST["main_stream_sp"]) ? $low_stream_sp=$_POST["low_stream_sp"] : $low_stream_sp="";
              //echo $main_stream_sp;
            $top = 250;
            $left = 20;
            $flow1_val = mt_rand(80000, 100000) / 100;
			$sql = "SELECT * FROM actualval";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
             while($row = $result->fetch_assoc()) {
            if( $row["stream"]=="MAIN") $flow1_val=$row["value"];
            if( $row["stream"]=="LOW") $flow2_val=$row["value"];
            }
                } else {
    $flow1_val="sql error";
    $flow2_val="sql error";
}
            if($main_stream_sp!="")
            {
             $sql = "UPDATE setpoints SET set_val=$main_stream_sp WHERE sp_type='MAIN_FLOW_SP'; ";   
            $result1 = $conn->query($sql);
            $sql = "commit; ";   
           $result1 = $conn->query($sql);
              }
              if($low_stream_sp!="")
            {
             $sql = "UPDATE setpoints SET set_val=$low_stream_sp WHERE sp_type='LOW_FLOW_SP'; ";   
            $result1 = $conn->query($sql);
            $sql = "commit; ";   
           $result1 = $conn->query($sql);
              }
            $sql = "SELECT * FROM setpoints";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
             while($row = $result->fetch_assoc()) {
            if( $row["sp_type"]=="MAIN_FLOW_SP") $main_flow_sp=$row["set_val"];
            if( $row["sp_type"]=="LOW_FLOW_SP") $low_flow_sp=$row["set_val"];
            }
                } else {
    $main_flow_sp="sql error";
    $low_flow_sp="sql error";
}
/*
$sql = "SELECT * FROM vlvstatus";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
             while($row = $result->fetch_assoc()) {
            if( $row["valve_id"]=="MAIN_FLOW_VALVE") $main_valve_stat=$row["valve_stat"];
            if( $row["valve_id"]=="LOW_FLOW_VALVE") $low_valve_stat=$row["valve_stat"];
            }
                } else {
    $main_valve_stat="sql error";
    $low_valve_stat="sql error";
}
*/

$sql = "SELECT * FROM vlvstatus WHERE valve_id='ALL'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
             while($row = $result->fetch_assoc()) {
			$vlv_stat=$row["valve_stat"];
			echo $vlv_stat; 
			if (($vlv_stat & 1)==0) $main_valve_stat="OPEN";
			if (($vlv_stat & 2)==0) $main_valve_stat="CLOSE";
			if (($vlv_stat & 4)==0) $low_valve_stat="OPEN";
			if (($vlv_stat & 8)==0) $low_valve_stat="CLOSE";	
			/*	 
            if( $row["valve_id"]=="MAIN_FLOW_VALVE") $main_valve_stat=$row["valve_stat"];
            if( $row["valve_id"]=="LOW_FLOW_VALVE") $low_valve_stat=$row["valve_stat"];
            */
			}
                } else {
    $main_valve_stat="sql error";
    $low_valve_stat="sql error";
}

?>
            <!---------------------------------------------------------------------------------------------->

            <!-------------------------------------Main Flow Animation------------------------------------------------->
            <div style="position:fixed; top:<?= $top ?>px;left:<?= $left ?>px;width:500px; height:15px;border:2px solid black;">
                <img  id="fl_pipe_1" src="images/running_dots.jpg" style="width:500px; height:15px;">
            </div>
            <img src="images/valve.png" style="position:fixed; top:<?= $top - 120 ?>px;left:<?= $left + 450 ?>px; height:150px;">

            <div style="position:fixed; top:<?= $top ?>px;left:<?= $left + 545 ?>px;width:650px; height:15px;border:2px solid black;">
                <img id="fl_pipe_1a" src="images/running_dots.gif" style=" width:650px; height:15px;">
            </div>
            <img src="images/FT_orifice.png" style="position:fixed; top:<?= $top - 55 ?>px;left:<?= $left + 680 ?>px; height:80px;">


            <div id="vlv_stat_1" style="background:green; position:fixed; top:<?= $top - 25 ?>px;left:<?= $left + 550 ?>px;width:100px; height:18px;border:2px solid black;" >
                <font face="arial" color="white" >
                <center><a style="font-weight: bold;font-size:15px;"  id="vlv_stat_1_txt"  >hello </a></center>
                </font>
            </div>
            <?php
            if ($main_valve_stat=="OPEN")
            {
            ?>
            <script>vlv_open_txt("vlv_stat_1", "vlv_stat_1_txt", "fl_pipe_1", "fl_pipe_1a");</script>
            <?php
            }
            else
            {
            ?>
            <script>vlv_close_txt("vlv_stat_1", "vlv_stat_1_txt", "fl_pipe_1", "fl_pipe_1a");</script>
            <?php
            }
            ?>
            <div style="position:fixed;background:cyan; top:<?= $top -130 ?>px;left:<?= $left + 680 ?>px;width:210px; height:21px;border:2px solid black;" >
                <font face="arial" color="black" >
                <a  style="float:left;width:100px;text-align:center;margin-top:3px;"><?=$main_flow_sp?></a>
                <button type="submit" value="Modify SetPoint" style="float:left;" onclick="getSPval('<?= $main_flow_sp?>','MAIN STREAM FLOW');">Modify SetPoint</button>
                </font>
                 </div>
            <div style="background:lightgreen; position:fixed; top:<?= $top - 100 ?>px;left:<?= $left + 680 ?>px;width:100px; height:18px;border:2px solid black;" >
                <font face="arial" color="black" >
                <center><a style="font-weight: bold;font-size:15px;"  id="vlv_stat_1_txt" > <?= $flow1_val . "<br>cm/min" ?> </a></center>
                </font>
            </div>
            <!---------------------------------------------------------------------------------------------->
            <!-------------------------------------Low Flow Animation------------------------------------------------->
            <div style="position:fixed; top:<?= $top + 300 ?>px;left:<?= $left ?>px;width:500px; height:15px;border:2px solid black;">
                <img  id="fl_pipe_2" src="images/running_dots.jpg" style="width:500px; height:15px;">
            </div>
            <img src="images/valve.png" style="position:fixed; top:<?= $top + 180 ?>px;left:<?= $left + 450 ?>px; height:150px;">
            <div style="position:fixed; top:<?= $top + 300 ?>px;left:<?= $left + 545 ?>px;width:650px; height:15px;border:2px solid black;">
                <img id="fl_pipe_2a" src="images/running_dots.gif" style="width:650px; height:15px;">
            </div>
            <img src="images/FT_orifice.png" style="position:fixed; top:<?= $top + 245 ?>px;left:<?= $left + 680 ?>px; height:80px;">
            <div id="vlv_stat_2" style="background:green; position:fixed; top:<?= $top - 25 + 300 ?>px;left:<?= $left + 550 ?>px;width:100px; height:18px;border:2px solid black;" >
                <font face="arial" color="white" >
                <center><a style="font-weight: bold;font-size:15px;"  id="vlv_stat_2_txt"  >hello </a></center>
                </font>
            </div>
			
			<?php
            if ($low_valve_stat=="OPEN")
            {
            ?>
            <script>vlv_open_txt("vlv_stat_2", "vlv_stat_2_txt", "fl_pipe_2", "fl_pipe_2a");</script>
            <?php
            }
            else
            {
            ?>
            <script>vlv_close_txt("vlv_stat_2", "vlv_stat_2_txt", "fl_pipe_2", "fl_pipe_2a");</script>
            <?php
            }
            ?>
          
            
            <div style="position:fixed;background:cyan; top:<?= $top -130 +300?>px;left:<?= $left + 680 ?>px;width:210px; height:21px;border:2px solid black;" >
                <font face="arial" color="black" >
                <a  style="float:left;width:100px;text-align:center;margin-top:3px;"><?=$low_flow_sp?></a>
                <button type="submit" value="Modify SetPoint" style="float:left;" onclick="getSPval('<?= $low_flow_sp?>','LOW STREAM FLOW');">Modify SetPoint</button>
                </font>
                 </div>
            
            <div style="background:lightgreen; position:fixed; top:<?= $top - 100 + 300 ?>px;left:<?= $left + 680 ?>px;width:100px; height:18px;border:2px solid black;" >
                <font face="arial" color="black" >
                <center><a style="font-weight: bold;font-size:15px;"  id="vlv_stat_1_txt" > <?= $flow2_val . "<br>cm/min" ?> </a></center>
                </font>
            </div> 
            <!---------------------------------------------------------------------------------------------->
            <!-------------------------------------Set Point Form------------------------------------------------->
             <form  id="sp_form"action="index.php" method="POST">
                    <input id="msp" type="hidden" name="main_stream_sp" value="">
                    <input id="lsp" type="hidden" name="low_stream_sp" value="">
                </form>
            <!---------------------------------------------------------------------------------------------->
            <?php
            
            
            mysqli_close($conn);
            ?>
    </body>    
</html>

