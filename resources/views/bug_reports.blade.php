@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Raporty błędów</div>

                    <div class="panel-body row">

                        @foreach($bugReports as $bugReport)
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body post">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="post-header">
                                                    <a href="{{ $bugReport['user_link'] }}">
                                                        <img src="{{ $bugReport['avatar'] }}" class="min-avatar">
                                                        <h4>{{ $bugReport['user_name'] }}</h4>
                                                    </a>
                                                    <small class="pull-right">{{ $bugReport['created_at'] }}</small>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="post-body-section">
                                                    <p>{{ $bugReport['description'] }}</p>

                                                    @if($bugReport['is_repaired'])<strong style="color:green"><i class="fa fa-check"></i> Naprawione <small>({{ $bugReport['updated_at'] }})</small></strong>@endif
                                                    @if(!$bugReport['is_repaired'])<strong style="color:red"><i class="fa fa-times"></i> Nienaprawione</strong>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
