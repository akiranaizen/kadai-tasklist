@extends('layouts.app')

@section('content')

    <h1>タスク新規登録ページ</h1>

    <div class="row">
        <div class="col-6">
            {!! Form::model($task, ['route' => 'tasks.store']) !!}
            
                <div class="form-group">
                    {!! Form::label('content','タスク:') !!}
                    {!! Form::text('content',null,['class' => 'form-control']) !!}
                    
                    {!! Form::submit('投稿') !!}
                </div>
            
            {!! Form::close() !!}
        </div>
    </div>
    
@endsection