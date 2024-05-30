@extends('admin.layouts.app')
@section('content')
<form action="{{route('tour.admin.extras.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{$row->id}}">
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between mb20">
                    <div class="">
                        <h1 class="title-bar">{{$row->id ? __('Edit: ').$row->name : __('Add new Extra')}}</h1>
                    </div>
                </div>
                @include('admin.message')
                @if($row->id)
                @include('Language::admin.navigation')
                @endif
                <div class="lang-content-box">
                    <div class="panel">
                        <div class="panel-title">
                            <strong>{{__("Extra Content")}}</strong>
                        </div>
                        <div class="panel-body">
                            @include('Tour::admin/extras/form')
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span></span>
                    <button class="btn btn-primary" type="submit">{{__("Save Change")}}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection