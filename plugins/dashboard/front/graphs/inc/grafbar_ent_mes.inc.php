
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$sql_tec = "
SELECT count(glpi_tickets.id) AS conta, glpi_entities.name AS name, glpi_entities.completename AS cname
FROM `glpi_entities`, glpi_tickets
WHERE glpi_tickets.entities_id = glpi_entities.id
AND glpi_tickets.is_deleted = 0
".$entidade."
AND glpi_tickets.date ".$datas."
GROUP BY cname
ORDER BY conta DESC";

$query_tec = $DB->query($sql_tec);

$contador = $DB->numrows($query_tec);

//chart height
if($contador > 9) {	
	$height = '800';	
}
else {
	$height = '450';
}


echo "
<script type='text/javascript'>

$(function () {
        $('#graf1').highcharts({
            chart: {
                type: 'bar',
                height: ".$height."
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            categories: [ ";

while ($entity = $DB->fetch_assoc($query_tec)) {

echo "'". $entity['cname']."',";

}

//zerar rows para segundo while
$DB->data_seek($query_tec, 0) ;

echo "    ],
                title: {
                    text: null
                },
                labels: {
                	style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    },
                     borderWidth: 1,
                	borderColor: 'white',
                	shadow:true,
                	showInLegend: false
                },
               series: {
			    	  animation: {
			        duration: 2000,
			        easing: 'easeOutBounce'
			    	  }
					}
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 0,
                backgroundColor: '#FFFFFF',
                shadow: true,
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
            	 dataLabels: {
            	 	//color: '#000099'
            	 	},
                name: '". __('Tickets','dashboard') ."',
                data: [
";

while ($entity = $DB->fetch_assoc($query_tec))

{
echo $entity['conta'].",";
}

echo "]
            }]
        });
    });

</script>
";

		?>
