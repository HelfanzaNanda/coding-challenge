<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
	const BASE_URL = "{{ asset('') }}";
	const CSRF_TOKEN = "{{ csrf_token() }}"
	
	function doAjax(url, data = {}, method) {
		try {
			return $.ajax({
				url: url,
				type: method,
				"headers": { 'X-CSRF-TOKEN': CSRF_TOKEN, },
				dataType: 'JSON',
				data: data
			});
		} catch (error) {
			console.error(error);
		}
	}

	function doAjaxWithImage(url, data = {}, method) {
		try {
			return $.ajax({
				url: url,
				type: method,
				"headers": { 'X-CSRF-TOKEN': CSRF_TOKEN, },
				dataType: 'JSON',
				contentType: false,
				processData: false,
				data: data
			});
		} catch (error) {
			console.error(error);
		}
	}

	const showAlertOnSubmit = (params, modal, table, reload) => {
		if(params.status){
			setTimeout(function() {
				Swal.fire({
					title: "Sukses",
					text: params.message,
					showConfirmButton: false,
					icon: "success",
					timer: 1500
				}).then(() => {
					if (modal) {
						$(modal).modal('hide');
					}
					if (table) {
						$(table).DataTable().ajax.reload( null, false );
					}
					if (reload) {
						window.location.replace(reload);
					}
				});
			}, 200);
		} else {
			showAlertError(params.message);
		}
	}

	const showAlertError = (msg) => {
		Swal.fire({
			title: "Error",
			text: msg,
			showConfirmButton: true,
			confirmButtonColor: '#0760ef',
			icon: "error"
		});
	}

	const showAlertSuccess = (msg) => {
		Swal.fire({
			title: "Sukses",
			text: msg,
			showConfirmButton: false,
			icon: "success",
			timer: 1500
		})
	}

	const showDeletePopup = (url, token, modal, table, reload) =>  {
		Swal.fire({
			title: 'Apakah Anda Yakin?',
			text: "Apakah Anda Yakin Menghapus Data Ini?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			// cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus!',
			cancelButtonText: 'Batal',
		}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: url,
				"headers": { 'X-CSRF-TOKEN': token },
				type: "DELETE"
			})
			.done( function( data ) {
				if (data.status) {
					Swal.fire({
						title: "Berhasil",
						text: 'Data Berhasil di Hapus',
						showConfirmButton: false,
						icon: "success",
						timer: 1500
					})
					//Swal.fire( "Delete!", "Data berhasil di hapus!", "success" );
					if (modal) {
						$(modal).modal('hide');
					}
					if (table) {
						$(table).DataTable().ajax.reload( null, false );
					}
					if (reload) {
						window.location.replace(reload);
					}
				}else {
					Swal.fire( "Error!", data.MESSAGE, "error" );
				}
			})
			.fail( function( data ) {
					Swal.fire( "Oops", "We couldn't connect to the server!", "error" );
			});
		}
		})
	}

	const showLoading = (title, message) => {
		Swal.fire({
			title: title,
			html: message,
			didOpen: () => {
				Swal.showLoading();
			}
		});
	}

	function resetAllInputOnForm(formId) {
		$(formId).find('input, textarea').val('');
		$(formId).find('select').each(function () {
			$(this).select2('destroy').val("").select2({width: '100%'});
		});
		//$(formId).find('input, textarea, select').css('border', '1px solid #ced4da')
	}

	function formatRupiah(angka, prefix){
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
		split   		= number_string.split(','),
		sisa     		= split[0].length % 3,
		rupiah     		= split[0].substr(0, sisa),
		ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if(ribuan){
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
	}
</script>