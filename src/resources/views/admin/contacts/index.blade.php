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

  <form method="GET" action="{{ url('/admin') }}" class="admin-search">
    <div class="admin-search__row">
      <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="名前やメールアドレスを入力してください" class="admin-search__input">

    </div>

    <div class="admin-search__row">
      <select name="gender" class="admin-search__select">
        <option value="">性別</option>
        <option value="all" {{ request('gender') === 'all' ? 'selected' : '' }}>全て</option>
        <option value="1" {{ request('gender') === '1' ? 'selected' : '' }}>男性</option>
        <option value="2" {{ request('gender') === '2' ? 'selected' : '' }}>女性</option>
        <option value="3" {{ request('gender') === '3' ? 'selected' : '' }}>その他</option>
      </select>

      <select name="category_id" class="admin-search__select">
        <option value="">お問い合わせの種類</option>
        <option value="all" {{ request('category_id') === 'all' ? 'selected' : '' }}>全て</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
            {{ $category->content }}
          </option>
        @endforeach
      </select>

      <input type="date" name="date" value="{{ request('date') }}" class="admin-search__date">
    </div>

    <div class="admin-search__row">
      <button type="submit" class="admin-search__btn">検索</button>
      <a href="{{ url('/admin') }}" class="admin-search__reset">リセット</a>
    </div>
  </form>

  <div class="admin__table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>お名前</th>
          <th>性別</th>
          <th>メールアドレス</th>
          <th>お問い合わせの種類</th>
          <th>詳細</th>
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
              @else
                その他
              @endif
            </td>

            <td class="admin-table__cell">
              {{ $contact->email }}
            </td>

            <td class="admin-table__cell">
              {{ optional($contact->category)->content }}
            </td>

            <td class="admin-table__cell">
              <button type="button" class="admin-table__detail-btn js-open-modal"
              data-id="{{ $contact->id }}"
              data-destroy-url="{{ route('admin.contacts.destroy', $contact) }}"
              data-last_name="{{ e($contact->last_name ?? '') }}"
              data-first_name="{{ e($contact->first_name ?? '') }}"
              data-gender="{{ $contact->gender }}"
              data-email="{{ e($contact->email ?? '') }}"
              data-tel="{{ e($contact->tel ?? '') }}"
              data-address="{{ e($contact->address ?? '') }}"
              data-building="{{ e($contact->building ?? '') }}"
              data-category="{{ e(optional($contact->category)->content ?? '') }}"
              data-detail="{{ e($contact->detail ?? '') }}"
              data-created_at="{{ e((string)$contact->created_at) }}">詳細</button>
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

<div class="modal js-modal" aria-hidden="true">
  <div class="modal__overlay js-close-modal"></div>

  <div class="modal__content" role="dialog" aria-modal="true">
    <button type="button" class="modal__close js-close-modal">×</button>

    <h3 class="modal__title">お問い合わせ詳細</h3>

    <table class="modal-table">
      <tr><th>お名前</th><td class="js-m-name"></td></tr>
      <tr><th>性別</th><td class="js-m-gender"></td></tr>
      <tr><th>メール</th><td class="js-m-email"></td></tr>
      <tr><th>電話番号</th><td class="js-m-tel"></td></tr>
      <tr><th>住所</th><td class="js-m-address"></td></tr>
      <tr><th>建物名</th><td class="js-m-building"></td></tr>
      <tr><th>種類</th><td class="js-m-category"></td></tr>
      <tr><th>内容</th><td class="js-m-detail" style="white-space: pre-wrap;"></td></tr>
      <tr><th>送信日時</th><td class="js-m-created"></td></tr>
    </table>

    <form method="POST" action="{{ url('/admin/delete') }}" class="modal__delete">
      @csrf
      @method('DELETE')

      <input type="hidden" name="contact_id" class="js-delete-id">

      <button type="submit" class="modal__delete-btn">削除</button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.querySelector('.js-modal');
  if (!modal) return;

  const elName = modal.querySelector('.js-m-name');
  const elGender = modal.querySelector('.js-m-gender');
  const elEmail = modal.querySelector('.js-m-email');
  const elTel = modal.querySelector('.js-m-tel');
  const elAddress = modal.querySelector('.js-m-address');
  const elBuilding = modal.querySelector('.js-m-building');
  const elCategory = modal.querySelector('.js-m-category');
  const elDetail = modal.querySelector('.js-m-detail');
  const elCreated = modal.querySelector('.js-m-created');

  function genderLabel(val) {
    if (String(val) === '1') return '男性';
    if (String(val) === '2') return '女性';
    return 'その他';
  }

  function openModalFromButton(btn) {
    const id = btn.dataset.id;

    const deleteIdInput = modal.querySelector('.js-delete-id');
    if (deleteIdInput) {
      deleteIdInput.value = id;
    }

    elName.textContent = `${btn.dataset.last_name || ''} ${btn.dataset.first_name || ''}`;
    elGender.textContent = genderLabel(btn.dataset.gender);
    elEmail.textContent = btn.dataset.email || '';
    elTel.textContent = btn.dataset.tel || '';
    elAddress.textContent = btn.dataset.address || '';
    elBuilding.textContent = btn.dataset.building || '';
    elCategory.textContent = btn.dataset.category || '';
    elDetail.textContent = btn.dataset.detail || '';
    elCreated.textContent = btn.dataset.created_at || '';

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
  }

  document.addEventListener('click', function (e) {
    const openBtn = e.target.closest('.js-open-modal');
    if (openBtn) {
      openModalFromButton(openBtn);
      return;
    }

    const closeBtn = e.target.closest('.js-close-modal');
    if (closeBtn && modal.classList.contains('is-open')) {
      closeModal();
      return;
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) {
      closeModal();
    }
  });
});
</script>
@endsection