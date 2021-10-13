<?= $this->extend('layout/main_page') ?>

<?= $this->section('page_title') ?>
<title><?= @$page_title ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('layout/side_menu') ?>

<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Product</h1>
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

        <?php if($access->data->group_id == '2'){ ?>

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add Product</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

          <form id="data_form" data-action="/product/create">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nim">Nama</label>
                        <input type="text" name="name" class="form-control"  >
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nama">SKU</label>
                        <input type="text" name="sku" class="form-control"  >
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nama">Price</label>
                        <input type="text" name="price" class="form-control"  >
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nama">Image</label>
                        <input type="file" name="file" class="form-control"  accept=".png, .jpg, .jpeg" />
                      </div>
                    </div>
                </div>
                
            </div>

            <div class="card-footer">
              <button type="submit" id="btn_submit" class="btn btn-primary">Tambah</button>
            </div>
          </form>

        </div>
        <!-- /.card -->
        <?php } ?>


        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Data Product</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
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


      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="edit_form" action="javascript:;" onsubmit="update();return false">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title white" id="myModalLabel11">Edit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <?php 
                    if($access->data->group_id == '2'){
                        $readonly = '';
                    } else {
                        $readonly = 'readonly';
                    }
                ?>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                                <input type="text" class="form-control" id="edit_name" name="name" <?= $readonly?> >
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit_sku" name="sku" <?= $readonly?> >
                                <input type="hidden" id="edit_id" name="id">
                            </div>
                        </div>
                    </div>

                    <?php if($access->data->group_id == '3'){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">Price</label>
                                <input type="text" name="price" id="edit_price" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($access->data->group_id == '2'){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">Image</label>
                                <input type="file" name="file" class="form-control"  accept=".png, .jpg, .jpeg" />
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success " id="button-edit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="myModal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title white" id="myModalLabel11">Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                                <label for="nama">Name</label>
                                <input type="text" class="form-control" id="detail-name" >
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">SKU</label>
                                <input type="text" class="form-control" id="detail-sku" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nama">Price</label>
                                <input type="text" class="form-control" id="detail-price" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <img  id="detail-img" style="height:100px;">
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	const base_url = '<?= base_url() ?>';

	$(document).ready(function() {
        myData();

        $('#data_form').submit(function(){
            $('#btn_submit').attr('disabled',true);
            var action = base_url + $('#data_form').attr("data-action");
            var csrfName = '<?= csrf_token(); ?>';
            var csrfHash = '<?= csrf_hash(); ?>';
            var myData = $('#data_form')[0];
            var myData = new FormData(myData);
            myData.append(csrfName, csrfHash);

            $.ajax({
              type: "POST",
              enctype: 'multipart/form-data',
              data: myData,
              contentType: false,
              processData: false,
              cache: false,
              url: action,
              dataType: 'json',
              headers: {
                'Authorization' : '<?= $token ?>'
              },
              beforeSend: function() { $('#spin-box').show(); },
              complete: function() { $('#spin-box').hide(); },
              success: function(res)
              {
                  if (res.status == 200) {
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
        });
	})

    function update() {
        $('#button-edit').attr('disabled',true);
        var action = '/product';
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var myData = $('#edit_form')[0];
        var myData = new FormData(myData);
        myData.append(csrfName, csrfHash);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            data: myData,
            contentType: false,
            processData: false,
            cache: false,
            url: action,
            dataType: 'json',
            headers: {
                'Authorization' : '<?= $token ?>'
            },
            success: function(res) {
                if (res.status == 200) {
                    toastr.success(res.message);
                    setTimeout(function(){ location.reload(); }, 500);  
                } else {
                    toastr.error(res.message);
                    $('#button-edit').attr('disabled',false);
                    // setTimeout(function(){ location.reload(); }, 500); 
                }
            }
        });
        return false;
    }

    function myData() {
        var action = '/product/data';
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var myData = {
            [csrfName]: csrfHash
        };
        var table = $('#myTable').dataTable({
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
                 { "title" : "Nama", "data": 'name' },
                 { "title" : "SKU", "data": 'sku' },
                 { "title" : "Price", "data": 'price' },
                 { "title" : "Outlet", "data": 'outlet' },
                 { "title" : "Gambar", "data": 'image' },
                 { "title" : "Action", "data": 'id',
                    render: function(data, type, row) {
                        var id = row.id;
                        var name = row.name;
                        var sku = row.sku;
                        var img = row.image;
                        var price = row.price;

                        var edit = '<button class="edit btn btn-xs btn-primary" data-name="'+name+'" data-sku="'+sku+'" data-id="'+id+'"  data-price="'+price+'" >EDIT</button> ';
                        var detail = '<button class="detail btn btn-xs btn-default" data-name="'+name+'" data-sku="'+sku+'" data-image="'+img+'" data-price="'+price+'" >Detail</button> ';
                        var del = '<button class="del btn btn-xs btn-danger" data-id="'+id+'" >Delete</button>';

                        return edit + detail + del;
                    }
                 }
            ],
            "ajax": {
                "url": action,
                "type": "POST",
                "headers": {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                    'Authorization' : '<?= $token ?>'
                },
                "dataType":'json',
                "data": myData,
                beforeSend: function() { $('#spin-box').show(); },
                complete: function() { $('#spin-box').hide(); },
            },
            "rowCallback": function(row, val, index) {
                var outlet_id = '<?= $access->data->outlet_id ?>';
                var img = '<img src="'+base_url+'/uploads/' + val.image +'" style="height:100px;" >'
                // if(val.outlet_id!='' && val.outlet_id!=outlet_id){
                //     $(row).hide();
                // }
                $('td:eq(2)', row).html(formatNUmber(val.price));
                $('td:eq(4)', row).html(img);
            }

        });

        $('#myTable tbody').on('click', 'button.edit', function() {
            var name = $(this).attr('data-name');
            var sku = $(this).attr('data-sku');
            var id = $(this).attr('data-id');
            var price = $(this).attr('data-price');

            $('#edit_name').val(name);
            $('#edit_sku').val(sku);
            $('#edit_id').val(id);
            $('#edit_price').val(price);

            $('#myModal').modal('show');                                 
        });

        $('#myTable tbody').on('click', 'button.detail', function() {
            $('#myModal-detail').modal('show');
            var name = $(this).attr('data-name');
            var sku = $(this).attr('data-sku');
            var img = $(this).attr('data-image');
            var price = $(this).attr('data-price');

            $('#detail-name').val(name);
            $('#detail-sku').val(sku);
            $('#detail-price').val(formatNUmber(price));
            $('#detail-img').attr('src', base_url + '/uploads/' +img);
        });

        $('#myTable tbody').on('click', 'button.del', function() {
            var action = base_url + '/product/delete';
            var id = $(this).attr('data-id');
            var csrfName = '<?= csrf_token(); ?>';
            var csrfHash = '<?= csrf_hash(); ?>';
            var myData = {
                id:id,
                [csrfName]: csrfHash
            };
            $.ajax({
                type: "POST",
                url: action,
                dataType: 'json',
                data : myData,
                headers: {
                    'Authorization' : '<?= $token ?>'
                },
                beforeSend: function() { $('#spin-box').show(); },
                complete: function() { $('#spin-box').hide(); },
                success: function(res)
                {
                      if (res.status == 200) {
                          toastr.success(res.message);
                          $('#myTable').DataTable().clear().draw();
                          // setTimeout(function(){ location.reload(); }, 100);  
                      } else {
                          toastr.error(res.message);
                          // setTimeout(function(){ location.reload(); }, 2500);  
                      }
                }
            });
            return false;
        });
    }

</script>


<?= $this->endSection() ?>
