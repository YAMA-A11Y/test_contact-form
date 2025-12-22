@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="test_contact-form__content">
    <div class="test_contact-form__heading">
        <h2>Contact</h2>
    </div>

    <form class="form" action="/confirm" method="post">
        @csrf
        <div class="form-group">
            <div class="form__group-title">
                <label>お名前 <span class="required">※</span></label>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <div class="form__input-item">
                        <input type="text" name="last_name" placeholder="例：山田" value="{{ old('last_name') }}">
                        @error('last_name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form__input-item">
                        <input type="text" name="first_name" placeholder="例：太郎" value="{{ old('first_name') }}">
                        @error('first_name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>性別 <span class="required">※</span></label>
            </div>
            <div class="form__group-content">
                <div class="form__input--radio">
                    <label><input type="radio" name="gender" value="1" @if(old('gender') == '1') checked @endif> 男性</label>
                    <label><input type="radio" name="gender" value="2" @if(old('gender') == '2') checked @endif> 女性</label>
                    <label><input type="radio" name="gender" value="3" @if(old('gender') == '3') checked @endif> その他</label>
                </div>
                <div class="form__error">
                    @error('gender')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>メールアドレス <span class="required">※</span></label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="email" name="email" placeholder="例：test@example.com" value="{{ old('email') }}">
                </div>
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>     
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>電話番号 <span class="required">※</span></label>
            </div>
            <div class="form__group-content">
                <div class="form__input--tel">
                    <div class="form__input-item">
                        <input type="text" name="tel1" size="4" placeholder="080" value="{{ old('tel1') }}">
                        <div class="form__error">
                            @error('tel1')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <span class="form__hyphen">-</span>
                    <div class="form__input-item">
                        <input type="text" name="tel2" size="4" placeholder="1234" value="{{ old('tel2') }}">
                        <div class="form__error">
                            @error('tel2')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <span class="form__hyphen">-</span>
                    <div class="form__input-item">
                        <input type="text" name="tel3" size="4" placeholder="5678" value="{{ old('tel3') }}">
                        <div class="form__error">
                            @error('tel3')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>住所 <span class="required">※</span></label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" placeholder="例：東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}">
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>建物名</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" placeholder="例：千駄ヶ谷マンション101" value="{{ old('building') }}">
                </div>
                <div class="form__error">
                    @error('building')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>お問い合わせの種類 <span class="required">※</span></label>
            </div>
            <div class="form__group-content">
                <div class="form__input--select">
                    <select name="category_id">
                        <option value="">選択してください</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{ $category->content }}</option>
                        @endforeach
                    </select>
                    <div class="form__error">
                        @error('category_id')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form__group-title">
                <label>お問い合わせ内容 <span class="required">※</span></label>
            </div>
            <div class="form__group-content">
                <div class="form__input--textarea">
                    <textarea name="detail" rows="5" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
                </div>
                <div class="form__error">
                    @error('detail')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">確認画面</button>
        </div>
    </form>
</div>
@endsection