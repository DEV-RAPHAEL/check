@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{__('Disputes')}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a href="{{route('open.ticket')}}" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{__('Open Ticket')}}</a>
      </div>
    </div>
    <div class="row">
      @if(count($ticket)>0)
        @foreach($ticket as $k=>$val)
          <div class="col-md-6">
            <div class="card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-7">
                      <!-- Title -->
                      <h3 class="mb-0 font-weight-bolder">#{{$val->ticket_id}}</h3>
                    </div>
                    <div class="col-5 text-right">
                      <a href="{{url('/')}}/user/reply-ticket/{{$val->id}}" class="btn btn-sm btn-neutral">{{__('Reply')}}</a>
                      <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="btn btn-sm btn-danger">{{__('Delete')}}</a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <p class="text-sm mb-0">{{__('Subject')}}: {{$val->subject}}</p>
                      <p class="text-sm mb-0">{{__('Transaction Reference')}}: @if($val->ref_no==null){{__('Null')}} @else {{$val->ref_no}} @endif</p>
                      <p class="text-sm mb-0">{{__('Priority')}}: {{$val->priority}}</p>
                      <p class="text-sm mb-0">{{__('Status')}}: @if($val->status==0){{__('Open')}} @elseif($val->status==1){{__('Closed')}} @elseif($val->status==2){{__('Resolved')}} @endif</p>
                      <p class="text-sm mb-0">{{__('Created')}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                      <p class="text-sm mb-2">{{__('Updated')}}: {{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</p>
                      <span class="badge badge-pill badge-success">{{$val->type}}</span>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                      <div class="modal-body p-0">
                          <div class="card bg-white border-0 mb-0">
                              <div class="card-header">
                                <h3 class="mb-0 font-weight-bolder">{{__('Delete Ticket')}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="mb-0 text-xs">{{__('Are you sure you want to delete this?, all replies to this ticket will be deleted')}}</span>
                              </div>
                              <div class="card-body">
                                  <a  href="{{route('ticket.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{__('Proceed')}}</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        @endforeach
      @else
      <div class="col-md-12 mb-5">
          <div class="text-center mt-8">
            <div class="mb-3">
              <img src="{{url('/')}}/asset/images/empty.svg">
            </div>
            <h3 class="text-dark">No Ticket Found</h3>
            <p class="text-dark text-sm card-text">We couldn't find any ticket to this account</p>
          </div>
        </div>
      @endif
    </div>
    <div class="row">
      <div class="col-md-12">
      {{ $ticket->links('pagination::bootstrap-4') }}
      </div>
    </div>
@stop