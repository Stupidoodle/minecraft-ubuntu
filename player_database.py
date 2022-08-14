import mysql.connector
from mcrcon import MCRcon

minecraft_db = mysql.connector.connect(
    host = "localhost",
    user = "bryan",
    password = "PASSWORD"
)

mycursor = minecraft_db.cursor();

mycursor.execute("use minecraft_server_status")

with MCRcon("172.28.215.222", "SussyBaka") as mcr:
    mcr.command("say Python script connected.")
    list = mcr.command("list")
    playerlist = list.split(": ")[1]
    print(playerlist)   
    if(playerlist == ""):
        playerlist = "Null"
    sql = "insert into snapshots values(null, null, " + "\"" + playerlist + "\"" + ")"
    mycursor.execute(sql)
    minecraft_db.commit()
    #print(mycursor.execute("select * from snapshots"))
    print(mycursor.rowcount, "record inserted.")
    
    #truncate table snapshots; 24h