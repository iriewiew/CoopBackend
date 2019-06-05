<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>สรุป</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<style>
		@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);
	</style>
</head>

<body>
	<?php
	include("config.php");
	error_reporting(E_ALL ^ E_NOTICE);
	$event_id = $_REQUEST['id'];
	$eva_id = $_REQUEST['eva_id'];

	?>

	<?php

	$query = mysqli_query($conn, "SELECT * FROM `event` WHERE id = $event_id");
	$event_name = mysqli_fetch_array($query);
	?>



	<?php

	$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM evaluationreportdetail WHERE event_id = $event_id");
	$rs = mysqli_fetch_assoc($query);
	?>

	<div class="container">
		<div class="col-xl-12" style="margin-top:5mm;">
			<div style="margin-top:10mm;">
				<h3 style="font-weight:bold; text-align: center;">ส่วนที่ 3</h3>
			</div>
			การวิเคราะห์ข้อมูลของผู้เข้าร่วม<b> <?php $event_name["event_name"] ?> </b>จากผลการสำรวจแบบสอบถามความคิด

			จำนวน <?php $rs["total"] ?> ฉบับ ได้รับข้อมูลกลับคืน จำนวน <?php $rs["total"] ?> ฉบับ คิดเป็นร้อยละ 100% ซึ่งเป็นแบบสอบถามความคิดเห็นต่อโครงการข้างต้น จำแนกออกเป็น 3 ตอนดังนี้
			<p style="margin-top:10mm;">
				<b>ตอนที่ ๑ </b>ข้อมูลพื้นฐาน <br />
				<b>ตอนที่ ๒ </b>ความคิดเห็นที่มีต่อ "<?php $event_name["event_name"] ?>" <br />
				<b>ตอนที่ ๓ </b>ข้อเสนอแนะ
			</p>

			<span style="margin-top:10mm;"><b>ตอนที่ ๑ ข้อมูลพื้นฐาน</b> </span><br />
			<span>ตารางที่ 1 แสดงข้อมูลพื้นฐานผู้เข้าร่วมโครงการ</span>
			<table class="table table-bordered" width="100%" style="margin-top:2mm;">
				<thead>
					<tr class="text-center table-primary">
						<th width="50%">ข้อมูลพื้นฐาน</th>
						<th width="25%">ความถี่</th>
						<th width="25%">ร้อยละ</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="3"><b>สังกัดคณะ</b></td>
					</tr>
					<?php
					$no = 0;
					$query = mysqli_query($conn, "SELECT faculty_name, COUNT(*) AS total FROM evaluationreportdetail e INNER JOIN faculty f ON f.id=e.faculty_id WHERE event_id =$event_id GROUP BY faculty_id ASC");
					while ($results = mysqli_fetch_assoc($query)) {
						$no++;
						?>
						<tr>
							<td><?php $no ?>) <?php $results["faculty_name"] ?></td>
							<td class="text-center"><?php $results["total"] ?></td>
							<td class="text-center">
								<?php
								$persent = ($results["total"] / $rs["total"]) * 100;
								echo number_format($persent, 1, ".", "");
								?>
							</td>
						</tr>
					<?php
				}
				?>
				</tbody>
				<tfoot>
					<tr class="text-center">
						<th>รวม</th>
						<th><?php $rs["total"] ?></th>
						<th>100</th>
					</tr>
				</tfoot>
			</table>

			<div>
				<p>
					จากตารางที่ ๑ แสดงให้เห็นถึงจำนวนของผู้ตอบแบบสอบถามรวมทั้งสิ้น <?php $rs["total"] ?> คน ลักษณะข้อมูลทั่วไปของผู้ตอบแบบสอบถามสังกัดคณะ
				</p>
				<?php
				$query = mysqli_query($conn, "SELECT faculty_name, COUNT(*) AS total FROM evaluationreportdetail e INNER JOIN faculty f ON f.id=e.faculty_id WHERE event_id =$event_id GROUP BY faculty_id ORDER BY total DESC");
				$numRow = mysqli_num_rows($query);
				$text = "";
				$text = "";
				$n = 1;
				while ($results = mysqli_fetch_assoc($query)) {
					$persent = ($results["total"] / $rs["total"]) * 100;
					$persent = number_format($persent, 1, ".", "");
					if ($n == 1) {
						$text .= "ผู้ตอบแบบสอบถามสังกัดคณะ{$results["faculty_name"]} มากที่สุด คิดเป็นร้อยละ {$persent} ของผู้ตอบแบบสอบถามทั้งหมด";
					}
					if ($n == 2) {
						$text2 .= !empty($text2) ? " , " : "";
						$text2 .= $persent;
						$text .= " รองลงได้มาได้แก่คณะ{$results["faculty_name"]}";
					}
					if ($n > 2) {
						$text2 .= !empty($text2) ? " , " : "";
						$text2 .= $persent;
						if ($n == $numRow) {
							$text .= "และ";
							$text .= " คิดเป็นร้อยละ {$text2} ตามลำดับ";
						} else {
							$text .= " คณะ{$results["faculty"]}";
						}
					}
					$n++;
				}
				?>
				<p>
					<?php $text ?>
				</p>
			</div>

			<?php
			$num = 2;
			$resppoint = 0;
			$resAllpoint = 0;
			$query = mysqli_query($conn, "SELECT * FROM evaluationtitile  WHERE eva_id =$eva_id ORDER BY id ASC");
			while ($results = mysqli_fetch_assoc($query)) {
				$no = 1;
				?>
				<div style="margin-top:10mm;">
					<span style="margin-top:10mm;"><b>ตอนที่ <?php $num ?> ผลการวิเคราะห์<?php $results["eva_titile_name"] ?>ของการจัดโครงการ ฯ ดังนี้</b> </span><br />
					<span>ตารางที่ <?php $num ?> ผลการวิเคราะห์<?php $results["eva_titile_name"] ?>ของผู้เข้าร่วมโครงการ ฯ</span><br />
					<table class="table table-bordered" width="100%" style="margin-top:2mm;">
						<thead>
							<tr class="table-primary text-center">
								<th width="10%">ลำดับ</th>
								<th width="45%">ข้อความ</th>
								<th width="15%">ค่าเฉลี่ย</th>
								<th width="15%">ส่วนเบี่ยงเบนมาตรฐาน</th>
								<th width="15%">ผลประเมิน</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$q = mysqli_query($conn, "SELECT ep.eva_list_id, ed.eva_list_name , AVG(ep.eva_point) AS total , ed.eva_point FROM evaluationreport ep LEFT JOIN evaluationlist ed ON ep.eva_list_id=ed.id WHERE ep.eva_titile_name_id = {$results["id"]} AND event_id=$event_id GROUP BY ep.eva_list_id ASC");
							while ($res = mysqli_fetch_assoc($q)) {
								?>
								<tr>
									<td class="text-center"><?php $no ?></td>
									<td><?php $res["eva_list_name"] ?></td>
									<td class="text-center"><?php number_format($res["total"], 2) ?></td>
									<td class="text-center">
										<?php
										$qs = mysqli_query($conn, "SELECT STDDEV(ep.eva_point) AS sd FROM evaluationreport ep LEFT JOIN evaluationlist ed ON ep.eva_list_id=ed.id WHERE ep.eva_titile_name_id = {$results["id"]} AND event_id=$event_id AND ep.eva_list_id={$res["eva_list_id"]}");
										$resu = mysqli_fetch_assoc($qs);
										echo number_format($resu["sd"], 2);
										?>
									</td>
									<td class="text-center">
										<?php
										$fullPoint = $res["eva_point"] * $rs["total"];
										$qsum = mysqli_query($conn, "SELECT SUM(eva_point) AS total FROM evaluationreport WHERE event_id= $event_id AND eva_list_id = {$res["eva_list_id"]}");
										$resSum = mysqli_fetch_assoc($qsum);
										$resSumy = ($resSum["total"] * 100) / $fullPoint;
										echo number_format($resSumy, 2);
										?>
									</td>
								</tr>
								<?php
								$no++;
							}
							?>
						</tbody>
						<tfoot>
							<tr class="text-center">
								<th colspan="2">รวม</th>
								<th>
									<?php
									$qry = mysqli_query($conn, "SELECT AVG(ep.eva_point) AS total FROM evaluationreport ep LEFT JOIN evaluationlist ed ON ep.eva_list_id=ed.id WHERE ep.eva_titile_name_id = {$results["id"]} AND event_id=$event_id ");
									$resu_avg = mysqli_fetch_assoc($qry);
									echo number_format($resu_avg["total"], 2);
									?>
								</th>
								<th>
									<?php
									$qry = mysqli_query($conn, "SELECT STDDEV(ep.eva_point) AS total FROM evaluationreport ep LEFT JOIN evaluationlist ed ON ep.eva_list_id=ed.id WHERE ep.eva_titile_name_id = {$results["id"]} AND event_id=$event_id ");
									$resu_sd = mysqli_fetch_assoc($qry);
									echo number_format($resu_sd["total"], 2);
									?>
								</th>
								<th>
									<?php
									$n_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM evaluationlist WHERE eva_titile_name_id={$results["id"]}");
									$nPoint = mysqli_fetch_assoc($n_query);
									$fullPoint = (5 * $nPoint["total"]) * $rs["total"];

									$q_point = mysqli_query($conn, "SELECT SUM(eva_point) AS total FROM evaluationreport WHERE eva_titile_name_id={$results["id"]} AND event_id=$event_id ");
									$Point = mysqli_fetch_assoc($q_point);

									$persent = ($Point["total"] / $fullPoint) * 100;
									// echo number_format($persent, 2);
									echo $persent >= $achieve["eva_titile_achieve_point"] ? "บรรลุ" : "ไม่บรรลุ";

									?>

									<?php
									$a_query = mysqli_query($conn, "SELECT * FROM evaluationtitile WHERE eva_id =$eva_id ORDER BY id ASC");
									$achieve = mysqli_fetch_assoc($a_query);
									?>


								</th>
							</tr>
						</tfoot>
					</table>
				</div>
				<?php
				$num++;
			}
			?>

			<div style="margin-top:10mm;">
				<span style="margin-top:10mm;"><b>ตอนที่ 3 ข้อเสนอแนะ</b></span><br />
				<?php
				$query = mysqli_query($conn, "SELECT * FROM evaluationreportdetail WHERE event_id = $event_id ");
				while ($re = mysqli_fetch_assoc($query)) {
					if (empty($re['eva_msg']) || $re['eva_msg'] == '-') continue;
					echo "- {$re["eva_msg"]} <br/>";
				}
				?>




			</div>

			<div style="margin-top:10mm;">
				<h3 style="font-weight:bold; text-align: center;">ส่วนที่ 4</h3>
			</div>
			<table class="table table-bordered" width="100%">
				<thead>
					<tr class="table-primary text-center">
						<th width="10%">ลำดับ</th>
						<th width="60%">ข้อความ</th>
						<th width="15%">ร้อยละ</th>
						<th width="15%">ผลการบรรลุเป้าหมาย</th>
					</tr>
				</thead>
				<?php
				$query = mysqli_query($conn, "SELECT * FROM evaluation WHERE id = $eva_id");
				$achieve_point = mysqli_fetch_assoc($query);


				?>
				<tbody>
					<tr>
						<td class="text-center">1</td>
						<td>ผู้เข้าร่วมกิจกรรม ไม่น้อยกว่าร้อยละ <?php $achieve_point["eva_achieve_point"] ?> </td>
						<td class="text-center">
							<?php
							$query = mysqli_query($conn, "SELECT COUNT(*) AS total_join FROM evaluationreportdetail WHERE event_id = $event_id");
							$join = mysqli_fetch_assoc($query);

							$persent = ($rs["total"] / $join["total_join"]) * 100;
							echo number_format($persent, 2);
							?>
						</td>
						<td class="text-center">

							<?php $persent >= $achieve_point["eva_achieve_point"] ? "บรรลุ" : "ไม่บรรลุ" ?>
						</td>
					</tr>
					<?php
					$no = 2;
					$query = mysqli_query($conn, "SELECT * FROM evaluationtitile WHERE eva_id =$eva_id ORDER BY id ASC");
					while ($cate = mysqli_fetch_assoc($query)) {
						?>
						<tr>
							<td class="text-center"><?php $no ?></td>
							<td>นักศึกษาที่เข้าร่วมมี<?php str_replace("ด้าน", "", $cate["eva_titile_name"]) ?>ไม่น้อยกว่าร้อยละ <?php $cate["eva_titile_achieve_point"] ?></td>
							<td class="text-center">
								<?php
								$n_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM evaluationlist WHERE eva_titile_name_id={$cate["id"]}");
								$nPoint = mysqli_fetch_assoc($n_query);
								$fullPoint = (5 * $nPoint["total"]) * $rs["total"];

								$q_point = mysqli_query($conn, "SELECT SUM(eva_point) AS total FROM evaluationreport WHERE eva_titile_name_id={$cate["id"]} AND event_id=$event_id ");
								$Point = mysqli_fetch_assoc($q_point);

								$persent = ($Point["total"] / $fullPoint) * 100;
								echo number_format($persent, 2);
								?>
							</td>
							<td class="text-center">
								<?php

								echo $persent >= $cate["eva_titile_achieve_point"] ? "บรรลุ" : "ไม่บรรลุ";

								?>
							</td>
						</tr>
						<?php
						$no++;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

</body>

</html>