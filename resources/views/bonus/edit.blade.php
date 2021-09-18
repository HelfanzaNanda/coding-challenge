@extends('layouts.app')

@section('content')
<div class="container">
	<div class="col-md-12">

		<form action="{{ route('bonus.create') }}" method="post" id="main-form">
			@csrf

			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card px-3">
						<div class="card-body">
							<div class="form-group">
								<label for="">Pembayaran</label>
								<div class="input-group mb-3">
									<div class="input-group-append">
										<span class="input-group-text" id="basic-addon2">Rp</span>
									</div>
									<input required type="text" class="form-control" name="total" id="input-total" value="{{ $bonus->total }}">
								</div>
							</div>
							<hr>
							<button class="btn btn-primary btn-sm btn-add-item mb-3">Tambah Item</button>
							<div id="items">
								@foreach ($bonus->items as $key => $item)
									<div class="row item-{{ $key }}">
										<div class="col-md-3">
											<label for="">Buruh {{ $key + 1 }}</label>
											<input type="hidden" name="name[]" value="Buruh {{ $key + 1 }}" value="{{ $item->name }}">
										</div>
										<div class="col-md-3">
											<div class="input-group mb-3">
												<input required data-key="{{ $key }}" type="text" name="percentage[]" 
												class="form-control percentage" id="input-percentage-{{ $key }}" value="{{ $item->percentage }}">
												<div class="input-group-append">
													<span class="input-group-text" id="basic-addon2">%</span>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<input readonly type="hidden" class="form-control value" value="{{ $item->value }}" name="value[]" id="input-value-{{ $key }}">
											<input readonly type="text" class="form-control value-text" value="{{ 'Rp '. number_format($item->value, 0, ',', '.') }}" id="input-value-text-{{ $key }}">
										</div>
										@if ($key > 2)	
											<div class="col-md-1">
												<button type="button" data-key="{{ $key }}" class="btn btn-danger btn-remove-item"><i class="fa fa-remove"></i></button>
											</div>
										@endif
									</div>	
								@endforeach
							</div>
							
							<button type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@push('scripts')
	<script>
		let index = parseInt("{{ count($bonus->items) }}")

		$(document).on('keyup', '#input-total', function () {
			if (/\D/g.test(this.value)){
				this.value = this.value.replace(/\D/g, '');
			}
			let value = $(this).val()
			if (value == ''){
				$('.percentage').val('')
				$('.value').val('')
				$('.value-text').val('')
			}
		})

		$(document).on('keyup', '.percentage', function () {
			if (/\D/g.test(this.value)){
				this.value = this.value.replace(/\D/g, '');
			}
			let total = $('#input-total').val()
			let percentage = $(this).val()
			let key = $(this).data('key')
			if(total == ''){
				$(this).val('')
				showAlertError('silahkan masukkan pembayaran dahulu')
				return
			}
			const value = total * (percentage / 100)
			$('#input-value-'+key).val(value)
			$('#input-value-text-'+key).val(formatRupiah(value.toString(), 'Rp '))
		})
		
		$(document).on('submit', '#main-form', async function (e) {  
			e.preventDefault()
			const totalPercentage = sumTotalPercentage()
			if(totalPercentage != 100){
				showAlertError('total persentase harus 100%')
				return
			}
			const id = "{{ $bonus->id }}"
			const form = $(this).serialize()
			const url = BASE_URL+id+"/edit"
			showLoading('Harap Menunggu!', 'Sedang Mengirim Data');
			try {
				const res = await doAjax(url, form, 'post')
				if(res.status){
					showAlertOnSubmit(res, '', '', BASE_URL);
					Swal.close();
				}else{
					showAlertError(res.message)
				}
			} catch (err) {
				showAlertError(err)
			}
		})


		function sumTotalPercentage() {  
			let total = 0
			$('.percentage').each(function (index, obj) {  
				total += parseInt($(this).val())
			})
			return total
		}
		

		
		$(document).on('click', '.btn-add-item', function (e) {  
			e.preventDefault()
			$('#items').append(addItem())
			index++
		})
		
		$(document).on('click', '.btn-remove-item', function (e) {  
			e.preventDefault()
			let key = $(this).data('key')
			$('.item-'+key).remove()
		})

		function addItem() { 
			html = ''
			html += '<div class="row item-'+index+'">'
			html += '	<div class="col-md-3">'
			html += '		<label for="">Buruh '+(index + 1) +'</label>'
			html += '		<input type="hidden" name="name[]" value="Buruh '+(index+1)+'">'
			html += '	</div>'
			html += '	<div class="col-md-3">'
			html += '		<div class="input-group mb-3">'
			html += '			<input required data-key="'+index+'" type="text" name="percentage[]" class="form-control percentage" id="input-percentage-'+index+'">'
			html += '			<div class="input-group-append">'
			html += '				<span class="input-group-text" id="basic-addon2">%</span>'
			html += '			</div>'
			html += '		</div>'
			html += '	</div>'
			html += '	<div class="col-md-5">'
			html += '		<input readonly type="hidden" class="form-control value" name="value[]" id="input-value-'+index+'">'
			html += '		<input readonly type="text" class="form-control value-text" id="input-value-text-'+index+'">'
			html += '	</div>'
			html += '	<div class="col-md-1">'
			html += '		<button type="button" data-key="'+index+'" class="btn btn-danger btn-remove-item"><i class="fa fa-remove"></i></button>'
			html += '	</div> '
			html += '</div>'
			return html
		}
	</script>
@endpush