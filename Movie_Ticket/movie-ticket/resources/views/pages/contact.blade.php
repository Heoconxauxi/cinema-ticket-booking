@extends('layouts.app')
@section('title', 'Liên hệ')

@section('content')
<section class="py-3 py-md-5"
    style="background:linear-gradient(rgba(0,0,0,0.8),rgba(0,0,0,0.1)),url('https://wallpaperaccess.com/full/8406708.gif');
           background-repeat:no-repeat;background-size:cover;background-position:center;">

    <div class="container">
        <div class="row gy-3 align-items-xl-center">
            <div class="col-12 col-lg-6 d-none d-lg-block">
                <img class="img-fluid rounded" loading="lazy"
                    src="https://image.tmdb.org/t/p/w600_and_h900_bestv2/wobVTa99eW0ht6c1rNNzLkazPtR.jpg"
                    alt="Get in Touch">
            </div>

            <div class="col-12 col-lg-6">
                <div class="bg-white rounded-3 shadow overflow-hidden">
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row gy-2 p-4">
                            {{-- Họ tên --}}
                            <div class="col-12">
                                <label for="fullname" class="form-label fw-bold text-dark">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname') }}">
                                @error('fullname') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-bold text-dark">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label fw-bold text-dark">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                            </div>

                            {{-- Tiêu đề --}}
                            <div class="col-12">
                                <label for="subject" class="form-label fw-bold text-dark">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}">
                                @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Tin nhắn --}}
                            <div class="col-12">
                                <label for="message" class="form-label fw-bold text-dark">Tin nhắn <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Nút gửi --}}
                            <div class="col-12 mt-3">
                                <div class="d-grid">
                                    @if(!session('NDloggedIn'))
                                        <button class="btn btn-secondary btn-lg" disabled>🔒 Vui lòng đăng nhập để gửi tin nhắn</button>
                                    @else
                                        <button class="btn btn-primary btn-lg" type="submit">📩 Gửi tin nhắn</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Hiển thị thông báo --}}
                    @if(session('success'))
                        <div class="alert alert-success m-3">{{ session('success') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
