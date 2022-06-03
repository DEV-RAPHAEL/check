@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12">
        @if($ticket->files!=null)
          <div class="card">
            <div class="card-header">
              <!-- Title -->
              <h5 class="h3 mb-0 font-weight-bolder">{{__('Attachements')}}</h5>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                @foreach(json_decode($ticket->files) as $val)
                  <div class="timeline-block">
                    <span class="timeline-step badge-success">
                      <i class="fa fa-file"></i>
                    </span>
                    <div class="timeline-content">
                      <div class="d-flex justify-content-between pt-1">
                        <div>
                          <a href="{{url('/')}}/asset/profile/{{$val}}"><span class="text-muted text-sm">{{$val}}</span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
        <div class="card shadow">
          <div class="card-header">
            <h3 class="mb-0 font-weight-bolder">{{__('Log')}}</h3>
          </div>
          <div class="card-body">
            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
              <div class="timeline-block">
                  <span class="timeline-step badge-primary">
                      <i class="fa fa-star"></i>
                  </span>
                  <div class="timeline-content">
                      <small class="text-xs">{{date("Y/m/d h:i:A", strtotime($ticket->created_at))}}</small>
                      <h5 class="mt-3 mb-0">{{$ticket->message}}</h5>
                      <p class="text-sm mt-1 mb-0">{{$ticket->user['first_name'].' '.$ticket->user['last_name']}}</p>
                  </div>
              </div>
            @foreach($reply as $df)
              @if($df->status==1)
                <div class="timeline-block">
                  <span class="timeline-step badge-primary">
                    <i class="fa fa-star"></i>
                  </span>
                  <div class="timeline-content">
                    <small class="text-xs">{{date("Y/m/d h:i:A", strtotime($df->created_at))}}</small>
                    <h5 class="mt-3 mb-0">{{$df->reply}}</h5>
                    <p class="text-sm mt-1 mb-0">{{$ticket->user['first_name'].' '.$ticket->user['last_name']}}</p>
                  </div>
                </div>
                @elseif($df->status==0)
                  <div class="timeline-block">
                      <span class="timeline-step badge-primary">
                      <i class="fa fa-star"></i>
                      </span>
                      <div class="timeline-content">
                      <small class="text-xs">{{date("Y/m/d h:i:A", strtotime($df->created_at))}}</small>
                      <h5 class="mt-3 mb-0">{{$df->reply}}</h5>
                      <p class="text-sm mt-1 mb-0">@if($df->staff_id==1) {{__('Administrator')}} @else {{$df->staff['first_name'].' '.$df->staff['last_name']}} - <span class="badge badge-pill badge-success">Staff</span> @endif</p>
                      </div>
                  </div>
                @endif
            @endforeach
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0 font-weight-bolder">{{__('Reply')}}</h3>
          </div>

          <div class="card-body">
            <form action="{{url('user/reply-ticket')}}" method="post">
            @csrf
              <div class="form-group row">
                  <div class="col-lg-12">
                  <textarea name="details" class="form-control no-border" placeholder="Enter your message..." rows="4" required></textarea>
                  <input name="id" value="{{$ticket->ticket_id}}" type="hidden">
                  </div>
              </div>               
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-sm">{{__('Send')}}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
@stop