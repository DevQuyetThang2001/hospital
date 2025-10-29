@extends('clients.layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="text-center text-primary mb-4">Gửi Đánh Giá Của Bạn</h2>

        @if (session('success'))
            <div class="alert alert-success text-center w-50 mx-auto">
                {{ session('success') }}
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        @if (Auth::check() && Auth::user()->role === 'patient')
                            <form action="{{ route('client.hospital.feedback.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Đánh giá</label>
                                    <div class="star-rating">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating"
                                                value="{{ $i }}" required>
                                            <label for="star{{ $i }}" title="{{ $i }} sao">★</label>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nhận xét của bạn</label>
                                    <textarea name="text" rows="5" class="form-control" placeholder="Hãy chia sẻ trải nghiệm của bạn..." required></textarea>
                                    @error('text')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2">Gửi Đánh Giá</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning text-center mt-3">
                                <strong>Bạn cần <a href="{{ route('login') }}">đăng nhập</a></strong> để gửi đánh giá.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .star-rating {
            direction: rtl;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #f5b301;
        }
    </style>
@endsection
