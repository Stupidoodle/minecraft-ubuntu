<html>
    <body>
    <?php
        $output = shell_exec("uptime");
        echo($output);
    ?>
    </body>
</html>