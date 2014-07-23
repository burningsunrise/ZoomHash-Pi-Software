<?

shell_exec('./restart.sh');
sleep(15);
header('Location: index.php');

?>