<?
include "const.php";
?>
<html>
<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<style>
	.bs-callout {
		border-left: 3px solid #EEEEEE;
		margin: 20px 0;
		padding: 20px;
	}
	.bs-callout h4 {
		margin-bottom: 5px;
		margin-top: 0;
	}
	.bs-callout p:last-child {
		margin-bottom: 0;
	}
	.bs-callout-danger {
		background-color: #FDF7F7;
		border-color: #D9534F;
	}
	.bs-callout-danger h4 {
		color: #D9534F;
	}
	.bs-callout-warning {
		background-color: #FCF8F2;
		border-color: #F0AD4E;
	}
	.bs-callout-warning h4 {
		color: #F0AD4E;
	}
	.bs-callout-info {
		background-color: #F4F8FA;
		border-color: #5BC0DE;
	}
	.bs-callout-info h4 {
		color: #5BC0DE;
	}
	.bb-alert {
		font-size: 1em;
		margin-bottom: 0;
		padding: 0.4em 0.8em;
		position: fixed;
		right: 5px;
		top: 5px;
		z-index: 2000;
	}
	</style>
	<link rel="icon" href="https://mega.co.nz/favicon.ico" type="image/x-icon">
	<title>Mega Download Manager</title>
</head>
<body>
<div style="display:none;" class="bb-alert alert alert-info">
	<span></span>
</div>
<div class="container">
	<h1><a href="."><span class='glyphicon glyphicon-home'></span></a> Mega download Manager</h1>
	
	<form action="submit.api.php" method="POST" data-success-class="alert-success" data-success="Le téléchargement va commencer..." onsubmit="ajaxSubmit(this); return false;">
		<div class="input-group">
			<input type="text" name="link" class="form-control" autocomplete="off" placeholder="URL du nouveau fichier à télécharger"/>
			<span class="input-group-btn">
				<button class="btn btn-default" type="submit">Télécharger</button>
			</span>
		</div>
	</form>

	<table class="table table-striped table-condensed" id="progress_data">
		<thead>
			<tr>
			<th>Fichier</th>
			<th width="160">Progression</th>
			<th width="120">Durée</th>
			<th width="120">ETA</th>
			<th width="80">Action</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<script>
function ajaxSubmit(form) {
	var $form = $(form);
	
	$.ajax({
		type: $form.attr('method'),
		url: $form.attr('action'),
		data: $form.serialize(),
 
		success: function(data, status) {
			showMsg($form.attr('data-success'), $form.attr('data-success-class'));
			updateProgression();
		}
	});	 
	$form[0].reset();
}

function showMsg(msg, msgClass) {
	var elem = $(".bb-alert");
	elem.removeClass();
	elem.addClass("bb-alert alert");
	elem.addClass(msgClass);
	elem.find("span").html(msg);
	elem.delay(200).fadeIn().delay(4000).fadeOut();
}

function updateProgression() {
	 $.ajax({
	url: "progress.api.php", 
	success: function(data, status) {
		var json = eval("(" + data + ")");
		$("#progress_data tbody").html("");
		$.each(json.progress, function(index, progress) {
			$("#progress_data tbody").append(
			"<tr>"+
				"<td>" + progress.filename + "</td>"+
				"<td>" +
				( progress.progress ?
					"<div class='progress progress-striped'>" +
						"<div class='progress-bar' role='progressbar' aria-valuenow='" + progress.progress +"' aria-valuemin='0' aria-valuemax='100' style='width:" + progress.progress +"%;'>&#160;" + progress.progress +"%</div>" +
					"</div>"
					 : '')+
				"</td>"+
				"<td>" + (progress.duration || "") + "</td>"+
				"<td>" + (progress.eta || "") + "</td>"+
				"<td align='center'>" +
				(progress.id && progress.cancelInProgress != "true" ? 
					(
						"<form action='cancel.api.php' method='POST' data-success-class='alert-danger' data-success='Annulation en cours...' onsubmit='ajaxSubmit(this); return false;' style='display:inline;'>" +
							"<input type='hidden' name='id' value='" + progress.id + "'/>" +
							"<button type='submit'><span class='glyphicon glyphicon-trash'></span></button>" +
						"</form>" 
					) 
					+
					( 
						progress.paused == "true" 
						?
						(
						"<form action='resume.api.php' method='POST' data-success-class='alert-info' data-success='Téléchargement en pause...' onsubmit='ajaxSubmit(this); return false;' style='display:inline;'>" +
							"<input type='hidden' name='id' value='" + progress.id + "'/>" +
							"<button type='submit'><span class='glyphicon glyphicon-play'></span></button>" +
						"</form>"
						)
						:
						(
						"<form action='pause.api.php' method='POST' data-success-class='alert-success' data-success='Téléchargement en pause...' onsubmit='ajaxSubmit(this); return false;' style='display:inline;'>" +
							"<input type='hidden' name='id' value='" + progress.id + "'/>" +
							"<button type='submit'><span class='glyphicon glyphicon-pause'></span></button>" +
						"</form>"
						)
					)
					
					: '')+	
			"</tr>");	
		});
		setTimeout("updateProgression()",5000);
	}
	});
}
showMsg("Chargement des donnée en cours...", "alert-info");
updateProgression();
</script>
</body>
</html>