@extends('layouts.app')

@section('content')
<div class="container">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="row justify-content-end">
					<a href="{{ route('bonus.create') }}" class="btn btn-sm btn-primary mb-3">Tambah</a>
				</div>
				<table id="main-table" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>No</th>
							<th>Total (Rp)</th>
							{{-- <th>Persentase (%)</th>
							<th>Bonus (Rp)</th> --}}
							<th>Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
@include('bonus.modal')
@endsection


@push('scripts')
	<script>
		$(document).ready(function () { 
			drawDatatable()
		})


		function drawDatatable(){
			$("#main-table").DataTable({
				destroy: true,
				"pageLength": 10,
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "{{ route('bonus.datatables') }}",
					"headers": { 'X-CSRF-TOKEN': CSRF_TOKEN },
					"dataType": "json",
					"type": "POST",
					"data":function(d) {
						d.search = $('#input-search').val()
					},
				},
				"columns": [
					{data: 'DT_RowIndex', name: 'DT_RowIndex', width: '5%', orderable: false},
					{data: 'bonus_total', name: 'bonus_total'},
					// {data: 'item_percentages', name: 'item_percentages'},
					// {data: 'item_values', name: 'item_values'},
					{data: 'action', name: 'action', orderable: false, }
				],
			});
		}

		$(document).on('click', '.btn-detail', async function (e) {  
			e.preventDefault()
			const id = $(this).data('id')
			const url = BASE_URL+id+"/get"
			showLoading('Harap Menunggu!', 'Sedang Mengirim Data');
			try {
				const res = await doAjax(url, {}, 'get')
				if(res.status){
					$('#detail-total').html('Total Pembayaran : '+ formatRupiah(res.data.total.toString(), 'Rp '))
					$('#detail-bonus-table > tbody').empty()
					let tbody = ''
					$.each(res.data.items, function (index, item) {  
						tbody += '<tr>'
						tbody += '	<td scope="row">'+(index + 1)+'</td>'
						tbody += '	<td>'+item.name+'</td>'
						tbody += '	<td>'+item.percentage+'</td>'
						tbody += '	<td>'+formatRupiah(item.value.toString(), 'Rp ')+'</td>'
						tbody += '</tr>'
					})
					$('#detail-bonus-table > tbody').append(tbody)
					Swal.close();
					$('#bonus-modal').modal('show')
				}else{
					showAlertError(res.message)
				}
			} catch (err) {
				showAlertError(err)
			}
		});
		
		$(document).on('click', '.btn-delete', function (e) {  
			e.preventDefault()
			const id = $(this).data('id')
			showDeletePopup(BASE_URL+id+"/delete", CSRF_TOKEN, '', '#main-table', '');
		});

	</script>
@endpush