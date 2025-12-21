@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('header-right')
<form action="{{ route('logout') }}" method="post">
  @csrf
  <button type="submit" class="header__register">logout</button>
</form>
@endsection

@section('content')
<div class="admin">
  <div class="admin__heading">
    <h2>Admin</h2>
  </div>

  <div class="admin__table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>お名前</th>
          <th>性別</th>
          <th>メールアドレス</th>
          <th>お問い合わせの種類</th>
        </tr>
      </thead>

      <tbody>
        @foreach($contacts as $contact)
          <tr>
            <td class="admin-table__cell">
              {{ $contact->last_name ?? '' }} {{ $contact->first_name ?? '' }}
            </td>

            <td class="admin-table__cell">
              @if($contact->gender == 1)
                男性
              @elseif($contact->gender == 2)
                女性
              @else($contact->gender == 3)
                その他
              @endif
            </td>

            <td class="admin-table__cell">
              {{ $contact->email }}
            </td>

            <td class="admin-table__cell">
              {{ optional($contact->category)->content }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin__pagination">
    {{ $contacts->links() }}
  </div>
</div>
@endsection
