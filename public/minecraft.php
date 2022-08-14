<html>
    <style>
        html{
            text-align: center;
        }
        body {
            margin-left: auto;
            margin-right: auto;
            font-family: "Cascadia Mono", sans-serif;
            color: white;
        }

        #bloc1, #bloc2 {
            display:inline;
        }
        .nav_bar{
			filter: brightness(100%);
			border-style: solid;
			border-top: rgb(49, 49, 49);
			border-bottom: rgb(46, 46, 46);
			font-family: "Cascadia Mono", sans-serif;
			font-size: 30px;
			background-color: white;
			border: 1px solid transparent;
			position: fixed;
			top: 0px;
			width: 100%;
			text-align: center;
			z-index: 999999999999999;
		}

        .active{
			color: black;
			margin: 25px;
		}

        .text{
			color: rgb(0, 0, 184);
			margin: 25px;
		}

        .myButton {
            box-shadow:inset 0px 1px 0px 0px #ffffff;
            background-color:transparent;
            border-radius:6px;
            border:1px solid #dcdcdc;
            display:inline-block;
            cursor:pointer;
            color: white;
            font-family: "Cascadia Mono", sans-serif;
            font-size:15px;
            padding:6px 24px;
            text-decoration:none;
            text-shadow:0px 1px 0px #ffffff;
        }
        .myButton:hover {
            background-color:transparent;
        }
        .myButton:active {
            position:relative;
            top:1px;
        }
        table.unstyledTable {
            font-family: "Cascadia Mono", Monaco, monospace;
            background-color: transparent;
            margin-left: auto;
            margin-right: auto;
        }
        table.unstyledTable td, table.unstyledTable th {

        }
        table.unstyledTable tbody td {
            color: white;
            font-family: "Cascadia Mono", Monaco, monospace;
        }
        table.unstyledTable thead {
            background: transparent;
            color: white;
        }
        table.unstyledTable thead th {
            font-weight: normal;
        }
        table.unstyledTable tfoot {
            font-weight: bold;
        }
    </style>

    <body style="background-color:black;">
        <?php
            use Thedudeguy\Rcon;
            require_once("../libs/Rcon.php");
            //Check if Minecraft is running
            $output = shell_exec("pgrep -a java");

            if($output == "" || $output == null || empty($output)){
                echo "<div id = 'bloc1'; style = 'color: red'>●</div> <div id = 'bloc2'>Server is not running.</div>";
            }
            else{
                echo "<div id = 'bloc1'; style = 'color: green'>●</div> <div id = 'bloc2'>Server is running. Process: $output</div>";
            }
            //End
            //Check for players
            $ini_array = parse_ini_file("../config/config.ini");

            $host = $ini_array["rcon_minecraft_ip"];            // Server host name or IP
            $port = $ini_array["rcon_minecraft_port"];          // Port rcon is listening on
            $password = $ini_array["rcon_minecraft_password"];  // rcon.password setting set in server.properties
            $timeout = 3;                                       // How long to timeout.

            $rcon = new Rcon($host, $port, $password, $timeout);

            if($rcon->connect()){
                $test = $rcon->sendCommand("say Web Client connected.");
                echo("<br></br>");
                print_r($rcon->sendCommand("list"));
            }
            else{
                echo "<div>Something went wrong</div>";
            }
            //End
            echo("<br></br>");
            //Check Table for last players
            $connection = new mysqli('localhost', 'bryan', 'PASSWORD', "minecraft_server_status"); //Password to config
            if($connection->connect_error)
                die("Connection failed: " . $connection->connect_error);

            $sql = "SELECT * FROM snapshots"; //You don't need a ; like you do in SQL
            $result = $connection->query($sql);

            echo "<table class='unstyledTable'>"; // start a table tag in the HTML
            echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Time</th>
                    <th>Players</th>
                </tr>
            </thead>
            ";
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tbody><tr><td>" . $row["ID"] . "</td><td>" . $row["Time"] . "</td><td>" . $row["Players"] . "</td></tr></tbody>";
                }
            }
            else{
                echo("0 results");
            }

            echo "</table>"; //Close the table in HTML
            $connection->close(); //Make sure to close out the database connection
            //End
            echo("<br></br>");
            //Update button
            if($_GET['run']){
                $list = $rcon->sendCommand("list");
                $playerlist = explode(": ", $list);

                if($playerlist[1] == ""){
                    $playerlist[1] = "Null";
                }

                $sql = "insert into snapshots values(null, null, " . "\"" . $playerlist[1] . "\"" . ")";
                $connection = new mysqli('localhost', 'bryan', 'PASSWORD', "minecraft_server_status"); //Password to config
                
                if($connection->connect_error)
                    die("Connection failed: " . $connection->connect_error);
                
                $result = $connection->query($sql);
                $connection->close();
                header("Location: http://172.28.215.222/minecraft.php");
                die();
            }
        ?>

    <a style="font-family: 'Cascadia Mono', Monaco, monospace;" href="?run=true">Update Players</a>
    
    
    </body>
</html>