<?php
	  ob_start();
if (!isset ($_SESSION)){
	session_start();
	}
	require('configuracion.php');
    require('funciones.php');
?>
<html>
<head>
<link href="css/imprimir.css" rel="stylesheet" type="text/css" media="print" />
<link href="css/no_imprimir.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<?php
 $fec = leer_param_gral();
 $logi = $_SESSION['login']; 
?>
  <div id="div_impresora" class="div_impresora" style="width:860px" align="right">
       <a href="javascript:print();">Imprimir</a>
	    <a href='ord_reportes.php'>Salir</a>
  </div>

<br><br>
<?php

$f_has ="";
$f_cal ="";
$t_cuo = 0;
$saldo = 0;
$tot_des = 0;
$log_usr = $_SESSION['login']; 
$total = 0;
$est1 = 3;
$est2 = 8;
$cas = "";
/*if(isset($_POST['ctot'])){  
	 $est1 = 3;
	 $est2 = 7;
      }
if(isset($_POST['cvig'])){
   $est1 = 3;
   $est2 = 3;
   }
if(isset($_POST['cven'])){
   $est1 = 6;
   $est2 = 6;
   }  
 if(isset($_POST['ceje'])){
   $est1 = 7;
   $est2 = 7;
   }     	  
if(isset($_POST['ccas'])){
   $est1 = 8;
   $est2 = 8;
   }

 
$cod_mon = 	$_POST['cod_mon'] ;
*/
$fec_des = $_POST['fec_des'] ;
$fec_has = $_POST['fec_has'] ; 
$f_des = cambiaf_a_mysql($fec_des); 
$f_has = cambiaf_a_mysql($fec_has); 
?> 
 <font size="+2"  style="" >

<?php
echo encadenar(40)."Detalle de Ordenes del".encadenar(2).$fec_des.
                                            encadenar(2)."al".encadenar(2).$fec_has;
?>
</font>
<br><br>
<?php
/*if ($cod_mon == 1){
   echo "Moneda Bolivianos";
   }
 if ($cod_mon == 2){
   echo "Moneda Dolares Americanos";
   }
  */    
 ?>  
 
  <table border="1" width="900">
	
	<tr>
	    <th align="center">Nro </th> 
		<th align="center">Operador </th>  
	   	<th align="center">Nro. Orden</th>
		<th align="center">Fecha</th> 
		<th align="center">Cliente</th>           
	    <th align="center">Servicios</th>
		<th align="center">Monto</th>
		<th align="center">Forma de Pago</th>
		<th align="center">Estado</th>
  </tr>	
     
 <?php  
$con_car  = "Select ORD_NUMERO,ORD_IMPORTE,ORD_OPE_RESP,ORD_FEC_INI,
             ORD_FORM_PAG,ORD_ESTADO,CLIENTE_AP_PATERNO,CLIENTE_AP_MATERNO,CLIENTE_NOMBRES
             From ord_maestro, cliente_general
             where (ORD_FEC_INI between '$f_des' and '$f_has') 
             and CLIENTE_COD = ORD_COD_CLI  
			 and CLIENTE_USR_BAJA is null and  ORD_MAE_USR_BAJA is null 
	  order by ORD_FEC_INI, ORD_NUMERO"; 
$res_car = mysql_query($con_car)or die('No pudo seleccionarse ord_maestro 2');
$nro = 0;
$tot_pag = 0;
//echo $f_des,$f_has;
   while ($lin_car = mysql_fetch_array($res_car)) {
         $cod_ord = $lin_car['ORD_NUMERO']; 
		 $impo = $lin_car['ORD_IMPORTE'];
		// $desc = $lin_car['ORD_IMP_DES'];
		 $opera = $lin_car['ORD_OPE_RESP'];
		 $fec_ini = $lin_car['ORD_FEC_INI'];
		 $f_pag = $lin_car['ORD_FORM_PAG'];
		 $estado = $lin_car['ORD_ESTADO'];
		 $nom_cli = $lin_car['CLIENTE_AP_PATERNO'].encadenar(2).
					$lin_car['CLIENTE_AP_MATERNO'].encadenar(2).
					$lin_car['CLIENTE_NOMBRES'].encadenar(2);
		 $fec_i = cambiaf_a_normal($fec_ini);			
		 $nom_grp = "";
		 $cod_fon = 0;
		 $d_est = "";
		 $nom_of = "";
		 $mon_plan = 0;
		 $tot_pag = $tot_pag + $impo;
		 $tot_cta = 0; 
		
		 $tot_tde = 0;
		 $tot_tpa = 0;
		 $mon_tpa  = 0;
		 $mon_tde = 0;
		// $f_uno2= cambiaf_a_normal($f_uno);
		// echo $cod_sol;
		if ($opera > 0 ){
		  $nom_ope  = leer_nombre_ope($opera);
		  }else{
		  $nom_ope  = "Migrado";
          }
  /* 

*/
$con_serv = "Select ORD_DET_GRP,ORD_DET_MONTO  From ord_detalle
		              where ORD_DET_ORD = $cod_ord
		              and  ORD_DET_USR_BAJA is null";
	$servicios = "";
	$com = "";
	$impo = 0;	
	$t_impo = 0;			  
          $res_serv= mysql_query($con_serv)or die('No pudo seleccionarse tabla ord_detalle');	
		  while ($lin_serv = mysql_fetch_array($res_serv)){
	             $cod_serv = $lin_serv['ORD_DET_GRP'];
		         $impo = $lin_serv['ORD_DET_MONTO'];
				 $t_impo = $t_impo + $impo;
		 // $impo = $lin_serv['ORD_DET_MONTO'];
		          $servicios = $servicios.encadenar(2).$cod_serv;
		         // $com = $com.encadenar(1).$comen;
		         //  $impo_sera = $impo_sera + $impo;
		   }
		   	  
	  $con_est  = "Select * From gral_param_propios where GRAL_PAR_PRO_GRP = 809 and GRAL_PAR_PRO_COD = $estado";
      $res_est = mysql_query($con_est)or die('No pudo seleccionarse tabla');
	  while ($linea = mysql_fetch_array($res_est)) {
	  $d_est = $linea['GRAL_PAR_PRO_DESC'];
	  $s_est =  $linea['GRAL_PAR_PRO_SIGLA'];
	  }  
	  $con_fpa  = "Select * From gral_param_propios where GRAL_PAR_PRO_GRP = 600 and GRAL_PAR_PRO_COD = $f_pag";
      $res_fpa = mysql_query($con_fpa)or die('No pudo seleccionarse tabla');
	  while ($linea = mysql_fetch_array($res_fpa)) {
	  $f_pagd = $linea['GRAL_PAR_PRO_DESC'];
	  $s_pag =  $linea['GRAL_PAR_PRO_SIGLA'];
	  }	

	//Datos del cart_det_tran						
	/* 			
	*/				
//$saldo = $saldo + (	$tot_tde - $tot_tpa);
//$tot_des = $tot_des + $tot_tde;	

//if ($tot_tde > 0 ){	
	$nro = $nro + 1;			
			?>
	<center>
	<tr>
	    <td align="right" ><?php echo number_format($nro, 0, '.',','); ?></td>
		 <td align="left" ><?php echo  $nom_ope; ?></td>
	 	<td align="left" ><?php echo $cod_ord; ?></td>
		<td align="left" ><?php echo  $fec_i; ?></td>
	    <td align="left" ><?php echo $nom_cli; ?></td>
		<td align="left" ><?php echo $servicios; ?></td>
		<td align="right" ><?php echo number_format($t_impo , 2, '.',','); ?></td>
		<td align="right" ><?php echo $f_pagd; ?></td>
		<td align="left" ><?php echo $s_est; ?></td>
		
	</tr>	
	<?php
	}
     //  }
	   
    ?>
	<tr>
	    <td align="right" ><?php echo encadenar(2); ?></td>
	 	<td align="left" ><?php echo encadenar(2); ?></td>
	    <td align="left" ><?php echo encadenar(2)."Total"; ?></td>
       	<td align="left" ><?php echo encadenar(2); ?></td>
		<td align="left" ><?php echo encadenar(2); ?></td>
		<td align="left" ><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo number_format($tot_pag , 2, '.',','); ?></td>
		<td align="right" ><?php echo encadenar(2); ?></td>
		<td align="right" ><?php echo number_format($saldo, 2, '.',','); ?></td>
	</tr>  
</table>		  
<br>
 
<?php
		 	include("footer_in.php");
		 ?>

</div>
</body>
</html>



<?php
ob_end_flush();
 ?>

