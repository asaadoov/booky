@extends('layouts.app', ['header_title' => 'View Poll'])
@include('plugins.datatable')

@if($poll->started_at->isFuture())
	@include('plugins.datetimepicker')
@endif

@section('content')
<div class="container-fluid">
    <div id="res"></div>
	<div class="card">
		<div class="card-body">
			<h5>Poll Information</h5>
			<small>Make sure to fill in all the required information below.</small>
		</div>
		<div class="card-body">
			<form id="form-update" method="POST" action="{{ route('poll.item', $poll->id) }}">
				@method('PATCH')
				<div class="row clearfix p-2">
					<div class="col-sm-6">
						<div class="form-group">
							<label class="form-label">Package</label>
							<select name="package_id" id="package_id" id="select-countries" class="form-control custom-select package">
								<option selected disabled hidden>-- Choose one --</option>
								@foreach($packages as $package)
								<option value="{{$package->id}}">{{$package->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<ul id="package_info">
								
							</ul>
						</div>
						<div class="form-group ">
							<label class="form-label">Expected Number Of Voters </label>
							<input type="text" class="form-control" id="voters" data-input="number" autocomplete="off"  name="total_voters" value="{{ $poll->total_voters }}"/>
						</div>
						<div class="form-group ">
							<label class="form-label">Total Fee </label>
							<input autocomplete="off" id="total_fee" type="text" class="form-control circle" disabled  value="{{ $poll->total_fee }}">
						</div>
						<div class="form-group ">
							<label class="form-label">Poll Title</label>
							<input name="title" autocomplete="off" required type="text" class="form-control circle" value="{{ $poll->title }}" >
						</div>
						<div class="form-group ">
							<label class="form-label">Description</label>
							<textarea name="description" required type="textarea" class="form-control required" rows="5" autocomplete="off">{{ $poll->description }}</textarea>
						</div>
						
					</div>
					<div class="col-sm-6">
						<div class="form-group ">
							<label class="form-label">Start At</label>
							<div class="input-group date" id="datetimepicker" data-target-input="nearest">
								<input name="started_at" type="text" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#datetimepicker" value="{{ $poll->started_at->format('d/m/Y h:i A') }}" />
								<div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
						<div class="form-group ">
							<label class="form-label">End At</label>
							<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
								<input name="ended_at" type="text" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#datetimepicker1" value="{{ $poll->ended_at->format('d/m/Y h:i A') }}" />
								<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
						<div class="form-group duration">
							<label class="form-label">Duration (in minutes)</label>
							<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
								<input type="text" class="form-control" id="duration" data-input="number" autocomplete="off" name="duration" value="{{ $poll->duration }}"/>
							</div>
						</div>
						<div class="form-group">
							<div class="selectgroup w-100">
								<label class="selectgroup-item">
									<input type="hidden" id="is_public_0" name="is_public" value="0" class="form-control" >
								</label>
								
							</div>
						</div>
						<div class="form-group">
							<label class="form-label">Physical Only</label>
							<div class="selectgroup w-100">
								<label class="selectgroup-item">
									<input type="radio" id="is_qr_only_1" name="is_qr_only" value="1" class="selectgroup-input" checked="">
									<span class="selectgroup-button">Yes</span>
								</label>
								<label class="selectgroup-item">
									<input type="radio" id="is_qr_only_0" name="is_qr_only" value="0" class="selectgroup-input">
									<span class="selectgroup-button">No</span>
								</label>
							</div>
						</div>
						<div class="form-group inviteVoters">
							<label class="form-label">Invited Voters Only</label>
							<div class="selectgroup w-100">
								<label class="selectgroup-item">
									<input type="radio" id="is_invited_only_1" name="is_invited_only" value="1" class="selectgroup-input" checked="">
									<span class="selectgroup-button">Yes</span>
								</label>
								<label class="selectgroup-item">
									<input type="radio" id="is_invited_only_0" name="is_invited_only" value="0" class="selectgroup-input">
									<span class="selectgroup-button">No</span>
								</label>
							</div>
						</div>
						<div class="form-group">
								<input type="hidden" name="select-countries" value="0" class="form-control">
								
							</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<a href="{{ route('poll') }}" class="btn btn-default">Back</a>
			@if($poll->started_at->isFuture())
			<button type="submit" data-submit="form-update" class="btn btn-primary pull-right" id="submit"><i class="fa fa-check mr-1"></i> Update</button>
			@endif
			@if(!$poll->is_active)
			<a class="btn btn-primary pull-right mr-1" href="{{ route('payment.pay', $poll->id) }}"><i class="fa fa-check mr-1"></i> Pay & Activate</a>
			@endif
		</div>
	</div>

	@if($poll->started_at->isFuture())
	<div class="card">
		<div class="card-body">
			<div class="pull-left">
				<h5>Questions & Answers</h5>
				<small>You can manage the questions and answers for this poll here.</small>
			</div>
			<div class="pull-right">
				<button class="btn bg-green text-white" data-toggle="modal" data-target="#modal-question"><i class="fa fa-plus mr-1"></i> New Question</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="table-question" class="table table-vcenter table_custom spacing5 border-style mb-0" style="width: calc(100% - 1px);">
					<thead>
						<tr>
							<th class="fit">#</th>
							<th>Question</th>
							<th>Options</th>
							<th class="fit"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
 
{{--invited voters section--}}
    <div class="card inviteSection" >
		<div class="card-body">
			<div class="pull-left">
				<h5>Invited Voters</h5>
				<small>You can manage who can vote for this poll here.</small>
			</div>
			<div class="pull-right">
				<button class="btn bg-green text-white inviteVoter" data-toggle="modal" data-target="#modal-invite-voter" ><i class="fa fa-plus mr-1"></i> Invite New Voter</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="table-invited-voters" class="table table-vcenter table_custom spacing5 border-style mb-0" style="width: calc(100% - 1px);">
					<thead>
						<tr>
							<th class="fit">#</th>
							<th>Name</th>
							<th>Identity</th>
							<th>Voting Power</th>

							<th class="fit"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div class="card-footer">
			<a href="{{ route('poll') }}" class="btn btn-default">Back</a>
		</div>
	</div>
	@else
    <div class="card">
        <div class="card-body">
            <div class="pull-left">
				<h5>Result</h5>
				<small>You can view the result for this poll here.</small>
			</div>
			<div class="pull-right">
				<a class="btn bg-green text-white" id="btn-modal" href="#animatedModal"><i class="fa fa-window-maximize mr-1"></i> Full Screen</a>
			</div>
        </div>
        <div class="card-body">
            <div class="row clearfix" id="results">
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('modal')
@if($poll->started_at->isFuture())
<div class="modal fade" id="modal-question" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

			<div class="modal-body">
				<form id="form-add" method="POST" action="{{ route('poll.item', $poll->id) }}">
					<div class="row clearfix p-2">
						<div class="col-12">
							<div class="form-group ">
								<label class="form-label">Question</label>
								<textarea name="text" required type="textarea" class="form-control required" rows="5" autocomplete="off"></textarea>
							</div>
						</div>
					</div>
					<div class="row clearfix p-2">
						<div class="col-12">
							<div class="form-group questions">
								<label class="form-label">Answer Options</label>
								<input name="options[]" autocomplete="off" type="text" class="form-control circle mb-1 question" >
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" data-submit="form-add"><i class="fa fa-check mr-1"></i> Submit</button>
			</div>
		</div>
	</div>
</div>

{{--invite new voter--}}
<div class="modal fade" id="modal-invite-voter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Invite New Voter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

			<div class="modal-body">
				<form 
				>
					<div class="row clearfix p-2">
						<div class="col-12">
							<div class="form-group ">
								<label class="form-label">Voter Name</label>
								<input name="name" required type="text" class="form-control required" rows="1" autocomplete="off" />
							</div>
                            <div class="form-group ">
								<label class="form-label">Voter Identity</label>
								<input name="identity" placeholder="Email/NRIC/Mobile No" required type="text" class="form-control required" rows="1" autocomplete="off" />
							</div>
							<div class="form-group ">
								<label class="form-label">Voting Power</label>
								<input name="voting_power" required type="text" class="form-control required" rows="1" autocomplete="off" />
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" data-submit="form-add-invited"><i class="fa fa-check mr-1"></i> Submit</button>
			</div>
		</div>
	</div>
</div>

@else
<div id="animatedModal" class="bg-white">
	<div class="modal-header border-0">
		<button id="btn-previous" type="button" data-key="37" class="btn btn-default mr-1"><i class="fa fa-angle-left mr-1"></i> Previous</button>
		<button id="btn-next" type="button" data-key="39"  class="btn btn-success">Next <i class="fa fa-angle-right ml-1"></i></button>
        <button type="button" data-key="27"  class="btn btn-default bg-white close close-animatedModal" aria-label="Close"></button>
    </div>

	<div class="modal-body p-5">
		@foreach($poll->questions as $question)
		<div class="slide">
			<h1>{{ $question->text }}</h1>
			<div style="font-size: 30px; margin-top: 30px;">
				<ul>
					@foreach($question->options as $option)
					@php
                    $percentage = $option->voting_power;
                    @endphp
					<li>
						<strong>{{ $option->text }} <span class="text-success">({{ $percentage }}%)</span></strong>
						<div class="w-100 mb-5" style="border: 1px solid #eee;">
							<div class="bg-green" style="height: 25px; width: {{ $percentage == 0 ? '0.5' : $percentage }}%;"></div>
						</div>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		@endforeach
	</div>
</div>
@endif
@endpush

@push('js')
<script type="text/javascript">
	$(function() {
		$("#is_public_{{ $poll->is_public }}").prop('checked', true).toggle('change');
		$("#is_qr_only_{{ $poll->is_qr_only }}").prop('checked', true).toggle('change');
		$("#is_invited_only_{{ $poll->is_invited_only }}").prop('checked', true).toggle('change');
		$("#category_id").val('{{ $poll->category_id }}').trigger('change');
		$("#package_id").val('{{ $poll->package_id }}').trigger('change');
	});

	@if($poll->started_at->isFuture())
	function autoIncrement() {
		$(".question").on('keydown', function() {
			if($(this).val() == '') {
				if($(this).next('input').length == 0) {
					$(this).parents('.questions').append('<input name="options[]" autocomplete="off" type="text" class="form-control circle mb-1 question" >');
					autoIncrement();
				}
			}
		});

		$(".question").on('blur', function() {
			if($(this).val() == '') {
				if($(this).next('input').length > 0) {
					$(this).remove();
				}
			}
		});
	}
	
	$(".package").on("change", function(){
		var optionSelected = $("option:selected", this);
		var packages = JSON.parse(`{!! json_encode($packages->keyBy('id')) !!}`);
		var package = packages[optionSelected.val()];

		$("#package_info").html(`
			<li>Fee per Voter : RM ${package.fee}</li>
			<li>Max Number of Questions : ${(package.total_questions) == null ? "Unlimited" : package.total_questions}</li>
			<li>Data Export : ${(package.is_export_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Invite Voters : ${(package.is_duration_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Set Poll Duration : ${(package.is_invite_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Assign Proxy : ${(package.is_proxy_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Assign Voting Power : ${(package.is_power_allowed) == 1 ? 'Yes':'No'}</li>
			<li >${(package.has_site_support) == 1 ? 'On-Site Support' : 'Online Support'}</li>
			<li>Anonymity Control :  ${(package.has_anonymity_control) == 1 ? 'Yes':'No'}</li>
		`);

		$("#voters").prop( "disabled", false);

		if(!package.is_duration_allowed) {
			$(".duration").hide();
		}
		else {
			$(".duration").show();
		}

		if(!package.is_invite_allowed) {
			$(".inviteVoters").hide();
		}
		else {
			$(".inviteVoters").show();
		}
		if(!package.is_invite_allowed){
			$(".inviteSection").hide();
		}
		else{
			$(".inviteSection").show();
		}

		var voters = $("#voters").val();
		$("#total_fee").val((voters*package.fee).toFixed(2));

		$("#voters ").on("keyup ", function() {
			var voters = $("#voters").val();
			$("#total_fee").val((voters*package.fee).toFixed(2));
		});
	});


	$(function() {
		var poll = JSON.parse(`{!! json_encode($poll) !!}`);
		$("#voters").on("keyup", function() {
			var voters = $("#voters").val();
			$("#total_fee").val((voters*poll.package_fee).toFixed(2));
		});
		$("#package_info").html(`
			<li>Fee per Voter : RM ${poll.package_fee}</li>
			<li>Max Number of Questions : ${(poll.package_data.total_questions) == null ? "Unlimited" : poll.package_data.total_questions}</li>
			<li>Data Export : ${(poll.package_data.is_export_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Invite Voters : ${(poll.package_data.is_duration_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Set Poll Duration : ${(poll.package_data.is_invite_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Assign Proxy : ${(poll.package_data.is_proxy_allowed) == 1 ? 'Yes':'No'}</li>
			<li>Assign Voting Power : ${(poll.package_data.is_power_allowed) == 1 ? 'Yes':'No'}</li>
			<li >${(poll.package_data.has_site_support) == 1 ? 'On-Site Support' : 'Online Support'}</li>
			<li>Anonymity Control :  ${(poll.package_data.has_anonymity_control) == 1 ? 'Yes':'No'}</li>
		`);
		if(!poll.package_data.is_duration_allowed) {
			$(".duration").hide();
		}
		else {
			$(".duration").show();
		}

		if(!poll.package_data.is_invite_allowed) {
			$(".inviteVoters").hide();
		}
		else {
			$(".inviteVoters").show();
		}

		if(!poll.is_invited_only){
			$(".inviteSection").hide();
		}
		else{
			$(".inviteSection").show();
		}
	});

	$("#is_invited_only_0").on("click" , function(){
		$(".inviteSection").hide();
	})

	$("#is_invited_only_1").on("click" , function(){
		$(".inviteSection").show();
	})


	autoIncrement();

	// Initialization
	$('#datetimepicker').datetimepicker({
		format: 'DD/MM/YYYY hh:mm A',
        startDate: new Date( {{ $poll->created_at->format('Y') }}, {{ $poll->created_at->format('n') }}, {{ $poll->created_at->format('j') }}),
	});

	$('#datetimepicker1').datetimepicker({
		useCurrent: false,
		format: 'DD/MM/YYYY hh:mm A',
        startDate: new Date( {{ $poll->created_at->format('Y') }}, {{ $poll->created_at->format('n') }}, {{ $poll->created_at->format('j') }}),

	});

	$("#datetimepicker").on("change.datetimepicker", function (e) {
		$('#datetimepicker1').datetimepicker('minDate', e.date);
	});

	$("#datetimepicker1").on("change.datetimepicker", function (e) {
		$('#datetimepicker').datetimepicker('maxDate', e.date);
	});

	$("#form-update").on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        if(!form.valid()) {
        	return;
        }

        $.ajax({
							url: form.attr('action'),
							method: form.attr('method'),
							data: new FormData(form[0]),
            dataType: 'json',
            async: true,
            contentType: false,
            processData: false,
            success: function(data) {
            	Swal.fire(data.title, data.message, data.status);
            },
            error: function() {
                Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
            }
        });
    });

    // Functions
    $("#form-add").on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        if(!form.valid()) {
        	return;
        }

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: new FormData(form[0]),
            dataType: 'json',
            async: true,
            contentType: false,
            processData: false,
            success: function(data) {
            	Swal.fire(data.title, data.message, data.status);
                if(data.status == 'success') {
					table.api().ajax.reload(null, false);
					form.trigger('reset');
					$('#modal-question').modal('hide');
                }
            },
            error: function() {
                Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
            }
        });
    });
    
    $("#form-add-invited").on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        if(!form.valid()) {
        	return;
        }

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: new FormData(form[0]),
            dataType: 'json',
            async: true,
            contentType: false,
            processData: false,
            success: function(data) {
            	Swal.fire(data.title, data.message, data.status);
                if(data.status == 'success') {
					invitedTable.api().ajax.reload(null, false);
					form.trigger('reset');
					$('#modal-invite-voter').modal('hide');
                }
            },
            error: function() {
                form.trigger('reset');
                Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
            }
        });
    });

    function edit(id) {
    	$('#div-modal').load('{{ route('poll.item', $poll->id) }}/question/'+id);
    }

    function remove(id) {
		Swal.fire({
			title: "Confirm Remove?",
            text: "Any deleted data would not be recoverable. Proceed?",
            type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Remove',
			showLoaderOnConfirm: true,
			allowOutsideClick: () => !Swal.isLoading(),
		}).then((confirm) => {
			if(confirm.value) {
				$.ajax({
                    url: '{{ route('poll.item', $poll->id) }}/question/'+id,
                    method: 'delete',
                    dataType: 'json',
                    async: true,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire(data.title, data.message, data.status);
						table.api().ajax.reload(null, false);
					},
					error: function() {
						Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
					}
				});
			}
		});
    }
    
    function removeInvited(id) {
		Swal.fire({
			title: "Confirm Remove?",
            text: "Any deleted data would not be recoverable. Proceed?",
            type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Remove',
			showLoaderOnConfirm: true,
			allowOutsideClick: () => !Swal.isLoading(),
		}).then((confirm) => {
			if(confirm.value) {
				$.ajax({
                    url: '{{ route('poll.invited', $poll->id) }}/'+id,
                    method: 'delete',
                    dataType: 'json',
                    async: true,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire(data.title, data.message, data.status);
						invitedTable.api().ajax.reload(null, false);
					},
					error: function() {
						Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
					}
				});
			}
		});
    }

    // Questions Datatable
    var table = $('#table-question');
    var settings = {
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "ajax":"{{ request()->fullUrl() }}",
        "columns": [
            { data: 'index', defaultContent: '', orderable: false, searchable: false, render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { data: "text", name: "text", render: function(data, type, row){
                return $("<div/>").html(data).text();
            }},
            { data: "option", name: "option", searchable: false, orderable: false, render: function(data, type, row){
                return $("<div/>").html(data).text();
            }},
            { data: "action", name: "action", searchable: false, orderable: false },
        ],
        "columnDefs": [
            { className: "nowrap", "targets": [3] },
            { className: "text-center", "targets": [0] },
        ],
        "sDom": "<t><'row'<i p>>",
        "destroy": true,
        "scrollCollapse": true,
        "iDisplayLength": 15
    };
    table.dataTable(settings);
    
    // Invited Voters Datatable
    var invitedTable = $('#table-invited-voters');
    var invitedSettings = {
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "ajax":"{{ request()->fullUrl() }}/invited",
        "columns": [
            { data: 'index', defaultContent: '', orderable: false, searchable: false, render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { data: "name", name: "name", render: function(data, type, row){
                return $("<div/>").html(data).text();
            }},
            { data: "identity", name: "identity", render: function(data, type, row){
                return $("<div/>").html(data).text();
            }},
			{ data: "voting_power", name: "voting_power", render: function(data, type, row){
                return $("<div/>").html(data).text();
            }},

            { data: "action", name: "action", searchable: false, orderable: false },
        ],
        "columnDefs": [
            { className: "nowrap", "targets": [3] },
            { className: "text-center", "targets": [0] },
        ],
        "sDom": "<t><'row'<i p>>",
        "destroy": true,
        "scrollCollapse": true,
        "iDisplayLength": 15
    };
    invitedTable.dataTable(invitedSettings);

    @else
    $(document).ready(function() {
    	getResult();
    	setInterval(getResult,5000);
    });

    function getResult( ){
        $.ajax({
            url: '{{ route('poll.item', $poll->id) }}/',
            method: 'get',
            dataType: 'json',
            async: true,
            contentType: false,
            processData: false,
            success: function(data) {
                let questions=data.data;
                let questionsHtml=``;
                
                $.each(questions, function(i,question){
                    questionsHtml+=`
	                    <div class="col-12 " >
	                        <div class="card " style="background-color: rgba(0,0,0,0.04);">
	                            <div class="card-header">
	                                <strong>${question.text}</strong>
	                            </div>
	                            <div class="card-body">
	                                <ul>
	                                ${ question.options.map(function(option){
                                        let percentage= option.voting_power ;
                                        return `
                                            <li>
                                                <div> ${option.text }</div>
                                                <div class="d-flex mb-2">
                                                    <div class="w-100 bg-white mr-2" style="height: 25px;">
                                                        <div class="bg-green m-1" style="width: calc(${ percentage  == 0 ? '0.5' : percentage  }% - 0.5rem); height: calc(100% - 0.5rem);"></div>
                                                    </div>
                                                    <div>${percentage }%</div>
                                                </div>
                                            </li>
                                        `;
	                                }) }
	                                </ul>
	                            </div>
	                        </div>
	                    </div>
	                `;
                });

                $('#results').html(questionsHtml);
            },
            error: function() {
                Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
            }
		});
    }
    
	$(function() {
	    $(".slide").hide();
		$(".slide").first().show();
	});

    $("#btn-modal").animatedModal();

    $("#btn-next").on('click', function() {
		var current = $(".slide:visible");
		if(current.next('.slide').length > 0) {
			current.hide();
			current.next('.slide').show();
		}
	});

	$("#btn-previous").on('click', function() {
		var current = $(".slide:visible");
		if(current.prev('.slide').length > 0) {
			current.hide();
			current.prev('.slide').show();
		}
	});

	{{-- switch between questions using arrow keys --}}
    $(window).on('keydown',function(e){
		if($(".animatedModal-on").length > 0) {
				$(`[data-key="${e.keyCode}"]`).click();
        }
    });
    @endif

    {{-- switch between questions using arrow keys --}}
    $('#btn-modal').on('click',function(){
        $(window).on('keydown',function(e){
            $(`[data-key="${e.keyCode}"]`).click();
        });
    });
</script>
@endpush
