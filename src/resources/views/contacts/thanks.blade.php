@extends('layouts.app')

@section('no-header')
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thanks">
  <div class="thanks__content">
    <div class="thanks__heading">
      <h2>お問い合わせありがとうございました</h2>
    </div>

    <div class="thanks__button">
      <a href="{{ route('contact.index') }}">HOME</a>
    </div>
  </div>
</div>
@endsection