<?

shell_exec('./restart.sh');
sleep(5);
header('Location: index.php');

?>