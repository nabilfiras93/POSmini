<?= $this->extend('layout/main_page') ?>

<?= $this->section('page_title') ?>
<title><?= @$page_title ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style type="text/css">.lunas{ background-color:#8fe68f !important; } .expired{ background-color:#f9a9a9 !important; }</style>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="content-wrapper my_bg">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tagihan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Advanced Form</li> -->
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Tambah Tagihan</h3>
            <div class="card-tools">
              <button id="btn_add_siakad" class="btn btn-warning">Sinkron tagihan dari Siakad</button>

              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

          <!-- <form id="data_form" data-action="/invoice/insert_invoice__"> -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" name="nim" class="form-control" id="nim" placeholder="nim">
                      </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button id="cek_siswa" class="btn btn-success">Cek</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="myTable_inv" class="table table-bordered table-striped"></table>
                        </div>
                    </div>
                </div>
                
                <!-- <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="phone">Telepon</label>
                        <input type="text" name="phone" class="form-control" id="phone" >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="email">Jumlah Tagihan</label>
                        <input type="text" name="amount" class="form-control nominal" id="amount" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" >
                      </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label ></label>
                            <h1 style="font-weight: bold;" class="text-center" id="nominal_format">0</h1>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="card-footer">
              <button type="submit" id="btn_submit" class="btn btn-primary">Tambah Tagihan</button>
            </div>
          <!-- </form> -->

        </div>
        <!-- /.card -->


        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Cek Tagihan</h3>
            <div class="card-tools">
              <button id="btn_sinkron" class="btn btn-danger">Sinkron dari IDN</button>

              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <!-- <form id="table_all_invoices"> -->
            <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <input type="text" name="nim" class="form-control" id="cek_nim" placeholder="nim">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <button id="get_table" class="btn btn-primary">Cek</button>
                  </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped"></table>
                </div>
              </div>
            </div>
          </div>
          <!-- </form> -->
        </div>
        <!-- /.card -->


        <!-- SELECT2 EXAMPLE -->
        <div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">Hapus Tagihan</h3>
            <div class="card-tools">
              <button onclick="delete_all()" class="btn btn-default">Hapus semua tagihan</button>

              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

          <form id="data_hapus" data-action="/invoice/delete_invoice">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" name="invoice_id" id="nim_hapus" class="form-control" placeholder="Invoice Id">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" id="btn_hapus" class="btn btn-primary">Hapus</button>
                      </div>
                    </div>
                </div>
            </div>
          </form>

        </div>
        <!-- /.card -->


      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="edit_form" action="javascript:;" onsubmit="edit_();return false">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title white" id="myModalLabel11">Edit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                                <input type="text" class="form-control" id="edit_name" readonly="">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit_amount">
                                <input type="hidden" id="edit_bill_id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success " id="button-edit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="myModal_payment" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="myModalLabel11">Data Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="myTable_payment" class="table table-bordered table-striped"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="myModal_deleteAll" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white" id="myModalLabel11">Tagihan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title white" id="myModalLabel11">Anda yakin akan menghapus semua tagihan yang belum dibayar ?</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 15px;margin-right: 30px;">Batal</span>
                </button>
                <button class="btn btn-success " id="button-deleteAll">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	const base_url = '<?= base_url() ?>';

	$(document).ready(function() {
        // myData();
		$("input[name='nim']").focus();

        $("#nim").on("change", function() {
            // get_mahasiswa();
            get_tagihan_siakad();
        });

        $("#cek_siswa").on("click", function() {
            get_tagihan_siakad();
        });

        $("#get_table").on("click", function() {
            myData()
        });

        $("#btn_sinkron").on("click", function() {
            sync()
        });

        $("#btn_add_siakad").on("click", function() {
            add_from_siakad()
        });

        $("input.nominal").keyup(function(e) {
            if(this.value == 0){
                $("#nominal_format").html('0');
            } else {
                $("#nominal_format").html(formatRupiah(this.value,''));
            }
        });

        $('#data_hapus').submit(function(){
            $('#btn_hapus').attr('disabled',true);
          var action = base_url + $('#data_hapus').attr("data-action");
          var myData = $('#data_hapus').serialize();
          var csrfName = '<?= csrf_token(); ?>';
          var csrfHash = '<?= csrf_hash(); ?>';
              $.ajax({
                type: "POST",
                url: action,
                dataType: 'json',
                data : myData + '&'+[csrfName]+'='+csrfHash,
                beforeSend: function() { $('#spin-box').show(); },
                complete: function() { $('#spin-box').hide(); },
                success: function(res)
                {
                    $('#btn_hapus').attr('disabled',false);
                    $('#nim_hapus').val('');

                    if (res.status == true) {
                        toastr.success(res.message);
                        // setTimeout(function(){ location.reload(); }, 2000);  
                    } else {
                        toastr.error(res.message);
                        // setTimeout(function(){ location.reload(); }, 2500);  
                    }
                }
              });
              return false;
        });

        // $('#btn_submit').submit(function(){
        $("#btn_submit").on("click", function() {
          var nim = $('#nim').val();
          if(nim == ''){
              toastr.error('NIM harus terisi');
          } else {
            $('#btn_submit').attr('disabled',true);
            var action = base_url + '/invoice/save_invoice_siakad';
            var csrfName = '<?= csrf_token(); ?>';
            var csrfHash = '<?= csrf_hash(); ?>';
            var nim = $("#nim").val();
            var myData = {
                nim: nim,
                [csrfName]: csrfHash
            };
                $.ajax({
                  type: "POST",
                  url: action,
                  dataType: 'json',
                  data : myData,
                  beforeSend: function() { $('#spin-box').show(); },
                  complete: function() { $('#spin-box').hide(); },
                  success: function(res)
                  {
                      if (res.status == true) {
                          toastr.success(res.message);
                          setTimeout(function(){ location.reload(); }, 100);  
                      } else {
                          toastr.error(res.message);
                            $('#btn_submit').attr('disabled',false);
                          // setTimeout(function(){ location.reload(); }, 2500);  
                      }
                  }
                });
              return false;
          }
        });

        $('#myTable tbody').on('click', 'button', function() {
            var data = myTable.row( $(this).parents('tr') ).data();
            $('#myModal').modal('show');                                 
        });

        $('#button-deleteAll').on('click', function() {
            var action = base_url + '/invoice/delete_allInvoice';
            var csrfName = '<?= csrf_token(); ?>';
            var csrfHash = '<?= csrf_hash(); ?>';
            var myData = {
                [csrfName]: csrfHash
            };
            $.ajax({
                type: "POST",
                url: action,
                dataType: 'json',
                data : myData,
                beforeSend: function() { $('#spin-box').show(); },
                complete: function() { $('#spin-box').hide(); },
                success: function(res) {
                    if (res.status == false) {
                        toastr.error(res.message);
                        // setTimeout(function(){ location.reload(); }, 500);  
                    } else {
                        toastr.success(res.message);
                        setTimeout(function(){ location.reload(); }, 500); 
                    }
                }
            });
            return false;
        });

	})

    function get_mahasiswa() {
        var action = base_url + '/mahasiswa/get_data';
        // var myData = $('#data_form').serialize();
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var nim = $("#nim").val();
        var myData = {
            nim: nim,
            [csrfName]: csrfHash
        };
        $.ajax({
            type: "POST",
            url: action,
            dataType: 'json',
            // data : myData + '&'+[csrfName]+'='+csrfHash,
            data: myData,
            beforeSend: function() { $('#spin-box').show(); },
            complete: function() { $('#spin-box').hide(); },
            success: function(res) {

                if (res.status == true) {
                    var nama = res.data[0].nama;
                    var phone = res.data[0].hp;
                    var email = res.data[0].email;
                    var nim = res.data[0].nim;
                    var pendidikan = res.data[0].prodi;

                    $("#nama").val(nama);
                    $("#phone").val(phone);
                    $("#email").val(email);
                    $("#nim").val(nim);
                    $("#amount").focus();
                } else {
                    toastr.error(res.message);
                    $("#nama").val(nama);
                    $("#phone").val(phone);
                    $("#email").val(email);
                    $("#nim").val('');
                }
            }
        });
        return false;
    }

    function get_tagihan_siakad() {
        var nim = $("#nim").val();
        try {
            if(nim == ''){
                throw "NIM Harus diisi";
            }
            var action = base_url + '/invoice/get_invoice_siakad';
            // var myData = $('#data_form').serialize();
            var csrfName = '<?= csrf_token(); ?>';
            var csrfHash = '<?= csrf_hash(); ?>';
            var myData = {
                nim: nim,
                [csrfName]: csrfHash
            };
            var myTable = $('#myTable_inv').DataTable({
                "serverSide": false,
                "responsive": true,
                "destroy": true,
                "order": [
                ],
                "lengthMenu": [
                    [10, 20, 50, -1],
                    [10, 20, 50, 'All']
                ],
                'select': true,
                "columns": [
                     { "title" : "NIM", "data": 'bill_key' },
                     { "title" : "Mahasiswa", "data": 'bill_name' },
                     { "title" : "Jumlah", "data": 'amount' },
                     { "title" : "Tagihan", "data": 'bill_component_name' }
                ],
                "ajax": {
                    "url": action,
                    "type": "POST",
                    "headers": {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    "dataType":'json',
                    "data": myData,
                    beforeSend: function() { $('#spin-box').show(); },
                    complete: function() { $('#spin-box').hide(); },
                },
                "rowCallback": function(row, val, index) {
                    var nim         = val.bill_key==null ? '' : val.bill_key ;
                    var bill_name   = val.bill_name==null ? '' : val.bill_name ;
                    var amount      = val.amount==null ? '' : formatRupiah(val.amount .toString(),'') ;
                    var tagihan     = val.bill_component_name==null ? '' : val.bill_component_name;

                    $('td:eq(0)', row).html(nim);
                    $('td:eq(1)', row).html(bill_name);
                    $('td:eq(2)', row).html(amount);
                    $('td:eq(3)', row).html(tagihan);
                    return row;
                }
            });
        }
        catch(err) {
            toastr.error(err);
        }
    }

    
    function myData() {
        var action = base_url + '/invoice/get_all_invoices';
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var nim = $("#cek_nim").val();
        var nama = $("#cek_nama").val();
        var myData = {
            nim: nim,
            nama: nama,
            [csrfName]: csrfHash
        };
        var myTable = $('#myTable').DataTable({
            "serverSide": true,
            "responsive": true,
            "destroy": true,
            "order": [
            ],
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, 'All']
            ],
            'select': true,
            "columns": [
                 { "title" : "Mahasiswa", "data": 'bill_name' },
                 { "title" : "Tagihan", "data": 'bill_component_name' },
                 { "title" : "Tgl tagihan", "data": 'bill_date' },
                 { "title" : "Keterangan", "data": 'bill_description' },
                 { "title" : "Bank", "data": 'biller_bank_name' },
                 { "title" : "No.Reff", "data": 'bill_ref_number' },
                 { "title" : "Jumlah", "data": 'amount' },
                 { "title" : "Piutang", "data": 'amount_paid' },
                 // { "title" : "Merchant", "data": 'merchant_name' },
                 // { "title" : "Telah Dibayar", "data": 'amount_paid' },
                 // { "title" : "Biaya Admin", "data": 'admin_fee' },
                 // { "title" : "Tgl pembayaran", "data": 'payment_date' },
                 { "title" : "Invoice Id", "data": 'bill_component_id' },
                 { "title" : "NIM", "data": 'bill_key' },
                 { "title" : "Action", "data": 'bill_component_id',
                    render: function(data, type, row) {
                      var bill_name = row[0]==undefined ? (row.bill_name==null ? '' : row.bill_name) : (row[0]==null ? '' : row[0]);
                      var amount = row[1]==undefined ? (row.amount==null ? '' : row.amount) : (row[1]==null ? 0 : row[1]); 
                      var bill_component_id = row[11]==undefined ? (row.bill_component_id==null ? '' : row.bill_component_id) : (row[11]==null ? '' : row[11]);
                      var state = row[12]==undefined ? (row.state==null ? '' : row.state) : (row[12]==null ? 0 : row[12]); 

                      var detail = '<button class="detail btn btn-xs btn-warning" data-id="'+bill_component_id+'" >Detail</button>';

                      if(state=='provisioned'){
                          return '<button class="edit btn btn-xs btn-primary" data-name="'+bill_name+'" data-amount="'+amount+'" data-id="'+bill_component_id+'"  >EDIT</button>' + detail;
                      } else {
                          if(state=='paid'){
                              var warna = 'success'; var statusnya = 'Telah Dibayar'; 
                          } else if(state=='expired'){
                              var warna = 'default'; var statusnya = 'Kadaluarsa'; 
                          } else if(state=='deleted'){
                              var warna = 'danger'; var statusnya = 'Dihapus'; 
                          }
                          return '<span class="btn btn-xs btn-'+warna+'">'+statusnya+'</span>' + detail;
                      }
                    }
                 }
            ],
            "ajax": {
                "url": action,
                "type": "POST",
                "headers": {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                "dataType":'json',
                "data": myData,
                beforeSend: function() { $('#spin-box').show(); },
                complete: function() { $('#spin-box').hide(); },
            },
            "rowCallback": function(row, val, index) {
                var jumlah = index[1]==undefined ? (val.amount==null ? 0 : val.amount) : (index[1]==null ? 0 : index[1]) ;
                var dibayar = index[8]==undefined ? (val.amount_paid==null ? 0 : val.amount_paid) : (index[8]==null ? 0 : index[8]);
                var _jumlah = (formatRupiah(jumlah.toString(),''));
                var _dibayar = (formatRupiah(dibayar.toString(),''));

                var bill_ref_number = index[6]==undefined ? (val.bill_ref_number==null ? '' : val.bill_ref_number) : (index[6]==null ? 0 : index[6]);
                var merchant_name = index[7]==undefined ? (val.merchant_name==null ? '' : val.merchant_name) : (index[7]==null ? 0 : index[7]);
                var admin_fee = index[9]==undefined ? (val.admin_fee==null ? '' : val.admin_fee) : (index[9]==null ? 0 : index[9]);
                var notes = val.notes==undefined ? '' : (val.notes==null ? '' : val.notes);
                var bill_name = index[0]==undefined ? (val.bill_name==null ? '' : val.bill_name) : (index[0]==null ? 0 : index[0]);
                var bill_component_name = index[2]==undefined ? (val.bill_component_name==null ? '' : val.bill_component_name) : (index[2]==null ? 0 : index[2]);
                var bill_description = index[3]==undefined ? (val.bill_description==null ? '' : val.bill_description) : (index[3]==null ? 0 : index[3]);
                var biller_bank_name = index[5]==undefined ? (val.biller_bank_name==null ? '' : val.biller_bank_name) : (index[5]==null ? 0 : index[5]);
                var payment_date = index[10]==undefined ? (val.payment_date==null ? '' : val.payment_date) : (index[10]==null ? 0 : index[10]);
                var bill_component_id = index[11]==undefined ? (val.bill_component_id==null ? '' : val.bill_component_id) :  (index[11]==null ? 0 : index[11]);
                var state = index[12]==undefined ? (val.bill_component_id==null ? '' : val.bill_component_id) :  (index[12]==null ? 0 : index[12]);
                var nim = index[13]==undefined ? (val.bill_key==null ? '' : val.bill_key) :  (index[13]==null ? 0 : index[13]);

                dibayar = dibayar=='' ? 0 : dibayar;

                var piutang = parseFloat(jumlah) - parseFloat(dibayar);
                var piutang = formatRupiah(piutang.toString(),'');


                if(state=='deleted'){
                    $(row).hide();
                }
                if(state=='expired'){
                    $(row).addClass('expired');
                }
                if(state=='provisioned'){
                    $(row).addClass('danger');
                }

                $('td:eq(0)', row).html(bill_name);
                $('td:eq(1)', row).html(bill_component_name);
                $('td:eq(2)', row).html(time_idn(val.bill_date));
                $('td:eq(3)', row).html(bill_description+' '+notes);
                $('td:eq(4)', row).html(biller_bank_name);
                $('td:eq(5)', row).html(bill_ref_number);
                $('td:eq(6)', row).html(_jumlah);
                $('td:eq(7)', row).html(piutang);
                // $('td:eq(7)', row).html(merchant_name);
                // $('td:eq(8)', row).html(_dibayar);
                // $('td:eq(9)', row).html(admin_fee);
                // $('td:eq(10)', row).html(time_idn(payment_date));
                $('td:eq(8)', row).html(bill_component_id);
                $('td:eq(9)', row).html(nim);

                return row;
            }
        });

        $('#myTable tbody').on('click', 'button.edit', function() {
            var name = $(this).attr('data-name');
            var amount = $(this).attr('data-amount');
            var id = $(this).attr('data-id');
            $('#edit_name').val(name);
            $('#edit_amount').val(amount);
            $('#edit_bill_id').val(id);
            $('#myModal').modal('show');                                 
        });

        $('#myTable tbody').on('click', 'button.detail', function() {
            var bill_id = $(this).attr('data-id');
            console.log(bill_id)
            detail(bill_id)
        });

    }

    function edit_() {
        $('#button-edit').attr('disabled',true);
        var action = base_url + '/invoice/update_invoice';
        var amount = $('#edit_amount').val();
        var id = $('#edit_bill_id').val();
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var myData = {
            amount: amount,
            id: id,
            [csrfName]: csrfHash
        };
        $.ajax({
            type: "POST",
            url: action,
            dataType: 'json',
            data : myData,
            success: function(res) {
                if (res.status == false) {
                    toastr.error(res.message);
                    // setTimeout(function(){ location.reload(); }, 500);  
                } else {
                    toastr.success(res.message);
                    setTimeout(function(){ location.reload(); }, 500); 
                }
            }
        });
        return false;
    }

    function detail(bill_id) {
        $('#myModal_payment').modal('show');                                 

        var action = base_url + '/invoice/get_payment_by_inv';
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var myData = {
            bill_id: bill_id,
            [csrfName]: csrfHash
        };
        var table = $('#myTable_payment').dataTable({
            "serverSide": true,
            "responsive": true,
            "destroy": true,
            "order": [
            ],
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, 'All']
            ],
            "columns": [
                 { "title" : "Tagihan","data": 'tagihan' },
                 { "title" : "Dibayar","data": 'dibayar' },
                 { "title" : "Biaya Admin","data": 'admin_fee' },
                 { "title" : "Merchant","data": 'merchant' },
                 { "title" : "No. Reff","data": 'ref_number' },
                 { "title" : "Tanggal Dibayar","data": 'paid_date' },
            ],
            "ajax": {
                "url": action,
                "type": "POST",
                "headers": {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                "dataType":'json',
                "data": myData,
                beforeSend: function() { $('#spin-box').show(); },
                complete: function() { $('#spin-box').hide(); },
            },
            "rowCallback": function(row, val, index) {
                var dibayar = (formatRupiah(val.dibayar.toString(),''));

                $('td:eq(0)', row).html(val.tagihan);
                $('td:eq(1)', row).html(dibayar);
                $('td:eq(2)', row).html(val.admin_fee);
                $('td:eq(3)', row).html(val.merchant);
                $('td:eq(4)', row).html(val.ref_number);
                $('td:eq(5)', row).html(val.paid_date);
            }
        });

    }

    function sync() {
        var action = base_url + '/invoice/sync';
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var myData = {
            [csrfName]: csrfHash
        };
        $.ajax({
            type: "POST",
            url: action,
            dataType: 'json',
            data: myData,
            beforeSend: function() { $('#spin-box').show(); },
            complete: function() { $('#spin-box').hide(); },
            success: function(res) {
                if (res.status == true) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
            }
        });
        return false;
    }

    function add_from_siakad() {
        $('#btn_add_siakad').attr('disabled',true);
        var action = base_url + '/invoice/insert_invoices';
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var myData = {
            [csrfName]: csrfHash
        };
        $.ajax({
            type: "POST",
            url: action,
            dataType: 'json',
            data: myData,
            beforeSend: function() { $('#spin-box').show(); },
            complete: function() { $('#spin-box').hide(); },
            success: function(res) {
                $('#btn_add_siakad').attr('disabled',false);
                if (res.status == true) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
            }
        });
        return false;
    }

    function time_idn(time) {
        if(time==null || time==''){
            return '';
        } else {
            let res = time.replace(/T|Z/gi, function (x) {
                return ' ';
            });
            return res.split(".")[0];
        }
    }

    function delete_all() {
        $('#myModal_deleteAll').modal('show');                                 
    }

</script>


<?= $this->endSection() ?>
