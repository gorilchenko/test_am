<?php

if(!empty($_FILES) && !empty($_FILES['json_data']) && !empty($_FILES['json_data']['tmp_name'])) {
	$json = file_get_contents($_FILES['json_data']['tmp_name']);
	
	$obj = json_decode($json, true);
	if($obj == null) {
		echo "Error parsing json " . $event['data'] . "!";
		die();
	}
	foreach($obj['events'] as $key => $event){
		$data = json_decode($event['data'], true);	
		if($data == null) {
			echo "Error parsing inner json " . $event['data'] . "!";
			die();
		}

		if(!empty($data)){
			$obj['events'][$key]["time_on"] = $data['time_on'];
			$obj['events'][$key]["type"] = $data['type'];
			unset($obj['events'][$key]['data']);
		}
	}

	date_default_timezone_set('Etc/GMT+7');

	function compare($v1, $v2) {
		return ($v1["time_on"] > $v2["time_on"])? -1: 1;
	}

	usort($obj['events'], "compare"); 
	?>

	<style>
	td {
	  padding:5px;
	  padding:5px;
	  padding:5px;   
	}

	</style>

	<script>
	function getRandomColor() {
	  var letters = '0123456789ABCDEF';
	  var color = '#';
	  for (var i = 0; i < 6; i++) {
		color += letters[Math.floor(Math.random() * 16)];
	  }
	  return color;
	}


	function randomizeRowColor(rowId) {
		document.getElementById(rowId).style.backgroundColor = getRandomColor();
	}
	</script>
	<h1><?php echo "{$obj['application_name']}, {$obj['country']}, {$obj['city']}, {$obj['app_id']}" ; ?></h1>
	  <table border="1">
	   <tr>
	   
		<th>event</th>
		<th>date</th>
		<th>time_on</th>
		<th>type</th>
		<th>shuffle color</th>
	   </tr>

	   
	   <?php $k = 0;?>
	   <?php foreach($obj['events'] as $key => $event):?>
	   <?php 
		$i = ""; $ic = ""; $b = ""; $bc = "";
		if($event['time_on'] > 0) {
			$i = "<i>";
			$ic = "</i>";
		} 
		if($event['type'] > 0) {
			$b = "<b>";
			$bc = "</b>";
		} 	
		?>
		
	   <tr id='tr_<?php echo $k;?>'>  
		  
		   <td> <?php echo $b . $i .strtolower($event['event']) . $ic . $bc; ?> </td>
		   <td> <?php echo $b . $i . date("Y-m-d H:i:s", (int)($event['timestamp']) / 1000) . $ic . $bc ; ?> </td>
		   <td> <?php echo $b . $i . $event['time_on'] . $ic . $bc  ; ?> </td>
		   <td> <?php echo $b . $i . $event['type'] . $ic . $bc  ; ?> </td>
		   <td> <input type="button" onclick="randomizeRowColor('tr_<?php echo $k;?>')" value="Push!"/></td>
	   </tr>
	   <?php $k++;?>
	   <?php endforeach;?>
	   </table>
	   <br />
	   
	   
<?php
}

?>
Upload json data here:
<form method="post" action="" enctype="multipart/form-data"/>
<input   name="json_data" type="file"   />
<input type="submit"/>
</form>

   