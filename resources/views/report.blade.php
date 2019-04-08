<html>
	<head></head>
	<body>
		<table border="1">
			<thead>
				<tr>
					<th>Date</th>
					<th>Country</th>
					<th>Unique Customers</th>
					<th>No of Deposits</th>
					<th>Total Deposit Amount</th>
					<th>No of Withdraws</th>
					<th>Total Withdraws Amount</th>
				</tr>
			</thead>
			<tbody>
				
					@foreach($deposits as $date => $transfers)
						@foreach($transfers as $state => $data)
							<tr>
								<td>{{$date}}</td>
								<td>{{$state}}</td>
								<td>{{$data['unique_customers']}}</td>				
								<td>{{$data['no_of_deposits']}}</td>
								<td>{{$data['total_deposit_amount']}}</td>
								<td>{{$data['no_of_withdraws']}}</td>
								<td>{{$data['total_withdraws_amount']}}</td>
							</tr>
						@endforeach
					@endforeach
				
			</tbody>
		</table>
	</body>
</html>