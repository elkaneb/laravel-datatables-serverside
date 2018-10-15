@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                    <div class="float-right">
                        <button type="button" title="export" class="btn btn-success btn-sm export"><i class="far fa-file-excel"></i> Export</button>
                        <button type="button" url="{{ route('users.create') }}" title="add new user" class="btn btn-light btn-sm add"><i class="far fa-address-card"></i> Add</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered" id="mydata">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Bootstrap 4 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<!-- Fontawesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<style>
    tr td:last-child {
        width: 0.1%;
        white-space:nowrap;
    }
</style>

@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<!-- Sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.4/dist/sweetalert2.all.min.js"></script>

<script>

//DataTable init
$(function() {
    $('#mydata').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('usersapi') !!}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ]
    });
});

//export btn
$('.export').click(function(event){
    var url = '{!! route('export') !!}';
    event.preventDefault();
    window.location.href = url;
});

//Delete btn
$(document).on('click', '.delete', function(){
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {

        var btn = $(this), url = btn.attr('url');
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url : url,
                type : 'DELETE',
                success : function() {
                    $('#mydata').DataTable().ajax.reload();
                    swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                },
                error : function() {
                    
                }
            });
        }
    })
});

// add btn
$(document).on('click', '.add', function(event){
    //event.preventDefault();
    var me = $(this), title = me.attr('title'), url = me.attr('url');

    $('.modal-title').text(title);

    $.ajax({
        url: url,
        dataType: 'html',
        success: function (response) {
            $('.modal-body').html(response);
            $('.modal').modal('show')
        }
    });
});
</script>


@endpush
