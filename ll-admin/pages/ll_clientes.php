<?php 
menu_lateral()
?>
<div id="contenido">
	<div id="titulo">Clientes</div>
	<div id="cuadro">
<?php
clientesactivos();
?>
	</div>
</div>
<?php
/*------------------------*/	

function menu_lateral(){
?>
<div id="lateralizq">
    <nav>
   	<ul>
        <li class="item seleccionado"><a href="clientes.php">Clientes</a></li>
    </ul>
    </nav>
</div>
<?php
}	
	
function clientesactivos(){
$query_cli = "SELECT * FROM clientes";
$cliente = obtener_todo($query_cli);
?>
<div id="boton"><button id="botondes">Descargar Clientes</button></div>
<div class="tabla_div">
	<div class="titulo_tabla_div">Relación Completa de Clientes</div>
		<div class="cabecera_tabla_div">
			<div class="cab_cell id">Id</div>
			<div class="cab_cell nombres">Nombres</div>
			<div class="cab_cell apellidos">Apellidos</div>
			<div class="cab_cell email">Email</div>
			<div class="cab_cell celular">Celular</div>
			<div class="cab_cell fijo">Fijo</div>
			<!--<div class="cab_cell">Ultimo ingreso</div>-->
		</div>
		<?php 
		foreach($cliente as $itemcli){
		?>
		<div class="tabla_row">	
			<div class="tabla_cell id">
				<?php 
				if($itemcli['id_cliente']){
				echo $itemcli['id_cliente'];
				}else{
				echo "-";
				}
				?>
			</div>
			<div class="tabla_cell nombres">
				<?php 
				if($itemcli['nombres_cliente']){
				echo $itemcli['nombres_cliente'];
				}else{
				echo "-";
				}
				?>
			</div>
			<div class="tabla_cell apellidos">
				<?php 
				if($itemcli['apellidos_cliente']){
				echo $itemcli['apellidos_cliente'];
				}else{
				echo "-";
				}
				?>
			</div>
			<div class="tabla_cell email">
				<?php 
				if($itemcli['email_cliente']){
				echo $itemcli['email_cliente'];
				}else{
				echo "-";
				}
				?>
				</div>
			<div class="tabla_cell celular">
				<?php 
				if($itemcli['celular_cliente']){
				echo $itemcli['celular_cliente'];
				}else{
				echo "-";
				}
				?>
				</div>
			<div class="tabla_cell fijo">
				<?php 
				if($itemcli['fijo_cliente']){
				echo $itemcli['fijo_cliente'];
				}else{
				echo "-";
				}
				?>
				</div>
			<!--<div class="tabla_cell"><?php echo $itemcli['ult_fecha_ingreso']?></div>-->
		</div>
        <?php	
        }
		?>
</div>
<div style="display:none;">
<table id="tablacli" border="1">
<tr>
<th>Id</th>
<th>Nombres</th>
<th>Apellidos</th>
<th>Email</th>
<th>Celular</th>
<th>Fijo</th>
</tr>
<?php 
foreach($cliente as $itemcli){
?>
<tr>
<th><?php echo $itemcli['id_cliente']?></th>
<th><?php echo $itemcli['nombres_cliente']?></th>
<th><?php echo $itemcli['apellidos_cliente']?></th>
<th><?php echo $itemcli['email_cliente']?></th>
<th><?php echo $itemcli['celular_cliente']?></th>
<th><?php echo $itemcli['fijo_cliente']?></th>
</tr>
<?php	
}
?>
</table>
</div>
<script>
function download_csv(csv, filename) {
    var csvFile;
    var downloadLink;
    csvFile = new Blob([csv], {type: "text/csv"});
    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}

function export_table_to_csv(html, filename) {
	var csv = [];
	var rows = document.querySelectorAll("#tablacli tr");
	
    for (var i = 0; i < rows.length; i++) {
		var row = [], cols = rows[i].querySelectorAll("td, th");
		
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
		csv.push(row.join(","));		
	}

    download_csv(csv.join("\n"), filename);
}

document.querySelector("#botondes").addEventListener("click", function () {
    var html = document.querySelector("table").outerHTML;
	export_table_to_csv(html, "cliente.csv");
});

</script>

<?php	
}	

?>
</div>
</div>
