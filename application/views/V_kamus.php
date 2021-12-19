<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Pemeriksaan Bahasa Asing</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
<style>
.container {
    position: relative;
    text-align: center;
}

.bg-image{
	background-image: url('http://localhost/wordchecker/img/Unpad.png'); 
	height: 100vh;
	background-color: #71dfe7; 
	background-repeat: no-repeat;
	background-position: center top;
}
</style>
</head>
<body>
    <div class="p-3 bg-info text-dark text-center"><h1>Pemeriksaan Bahasa Asing</h1></div>
	<div class="bg-image">
		<div class="mask" style="background-color: rgba(255, 255, 255, 0.6); height: 100%">
			<div class="container">
				<!-- <img src="http://localhost/wordchecker/img/Unpad.png" alt="Snow" style="width:100%; opacity: 10%;" alt="#"> -->
				<form class="form-group" action="<?php echo base_url('C_kamus/inputFile')?>" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="exampleFormControlFile1">Input pdf</label>
					<input type="file" class="form-control-file" name="file" required>
					<input class="btn btn-success" type="submit" value="submit">
					<a href="<?php echo base_url('C_kamus/reset') ?>" class="btn btn-danger">reset</a>
				</div>
				</form>
				<div class="mt-5">
					<h1>Hasil: </h1>
					<?php if(isset($result_content)){ ?>
					<table class="table table-light">
						<thead>
							<tr>
								<th scope="col">Bahasa</th>
								<th scope="col">Banyak Kata</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach($result_content as $value => $bahasa) {?>
								<tr>
									<th scope="row"><?php echo $value ?></th>
									<td><?php echo $bahasa ?></td>
								</tr>
							
							<?php } ?>
						</tbody>
					</table>
					<br><br>
					

					<div id="chartContainer" style="height: 370px; width: 100%; "></div>
					<?php } else { ?>
						<h5><?php echo $error;?></h5>
					<?php } ?>
				</div>
				<?php if(isset($unknown_words)) {?>
				<h3>Daftar Kata yang tidak dapat dikelompokkan :</h3>
				<table class="table">
					<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">Kata</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($unknown_words as $index => $word) {?>
						<tr>
						<th scope="row"><?php echo $index+1 ?></th>
						<td><?php echo $word ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if(isset($data_points))
	{ ?>
		<script>
		window.onload = function() {
		
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			backgroundColor: "rgba(225,150,150,0)",
			title: {
				text: "Presentase penggunaan bahasa"
			},
			data: [{
				type: "pie",
				yValueFormatString: "#,##0.00\"%\"",
				indexLabel: "{label} ({y})", 
				dataPoints: <?php echo json_encode($data_points, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart.render();
		
		}
	</script>
	<?php } ?>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>