@extends('layouts.app', ['header_title' => 'List of Category'])
@include('plugins.dropify')

@section('content')
    <div class="container-fluid">
        <div class="row row-cards" id="categories">
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('admin.category') }}" method="POST" id="form">
                    @csrf
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-md-12 col-sm-6">
                                <div class="form-group">
                                    <input type="text" id="name" name="name" required class="form-control" placeholder="Category Title" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mt-2 mb-3" id="test">
                                    <input type="file" id="image" required name="image" class="dropify">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="add">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script type="text/javascript">
	$( document ).ready(function() {
        fetchCategoryList();
    });

    function fetchCategoryList(){
    	$("#categories").load("{{ request()->fullUrl() }}");
    }
    
    $("#form").on('submit', function (e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: new FormData(form[0]),
            dataType: 'json',
            async: true,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data.status == 'success') {
                    Swal.fire({
                        title: data.title,
                        text: data.message,
                        type: data.status,
                        showCancelButton: false,
                        closeOnConfirm: true,
                    }).then((confirm) => {
                        if(confirm.value) {
                            form.trigger('reset');
					        $('#modal').modal('hide');
                            $('.dropify-clear').click();

                            fetchCategoryList();
                        }
                    });
                }
                else {
                    Swal.fire(data.title, data.message, data.status);
                }
            },
            error: function() {
                Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
            }
        });
    });
    
    function remove(id) {
        Swal.fire({
                title: "Confirm Remove?",
                text: "Any deleted data would not be recoverable. Proceed?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Remove",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true,
                showLoaderOnConfirm: true,
            }).then((confirm) => {
                if(confirm.value) {
                    $.ajax({
                        url: '{{ route('admin.category') }}/' + id,
                        method: 'delete',
                        dataType: 'json',
                        async: true,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            Swal.fire({
                                title: data.title,
                                text: data.message,
                                type: data.status,
                                showCancelButton: false,
                                closeOnConfirm: true,
                            }).then((confirm) => {
                                if(confirm.value) {
                                    fetchCategoryList();
                                }
                            });
                        },
                        error: function () {
                            Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
                        },
                    });
                }
            }
        );
    }
</script>
@endpush
