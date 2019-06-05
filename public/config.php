<?PHP
	$serverName = "35.198.215.166";
	$userName = "root";
	$userPassword = "@1122334455abXD";
	$dbName = "coop";

	$conn = mysqli_connect($serverName,$userName,$userPassword,$dbName);

if (mysqli_connect_errno())
{
  echo "เกิดข้อผิดพลาด :".mysqli_connect_error();
}
mysqli_set_charset($conn, "utf8");
?>
