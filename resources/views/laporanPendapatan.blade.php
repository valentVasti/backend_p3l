<!DOCTYPE html>
<html>
<head>
	<title>Laporan Pendapatan Go-Fit</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>Laporan Pendapatan</h4>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>Bulan</th>
				<th>Aktivasi</th>
				<th>Deposit</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($laporan as $data)
			<tr>
				<td>{{ $data['bulan'] }}</td>
				<td>{{ $data['aktivasi'] }}</td>
				<td>{{ $data['total_deposit'] }}</td>
				<td>{{ $data['total'] }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>