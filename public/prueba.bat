$mysql = mysql_connect("localhost", "root", "1234");

if(mysql_select_db('base_captis', $mysql)){
    echo "databse exists";
}else{
    echo "Databse does not exists";
}
