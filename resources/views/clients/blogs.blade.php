@extends('clients.layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-5 fw-bold text-primary">Bài Viết Mới Nhất</h2>

        <div class="row g-4">
            @forelse ($blogs as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 blog-card">
                        <div class="card-img-top-wrapper">
                            @if ($item->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->images->first()->image) }}" class="card-img-top"
                                    alt="{{ $item->title }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="Không có ảnh">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $item->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">
                                {!! Str::limit(strip_tags($item->description), 120, '...') !!}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex align-items-center">
                                    @if (!empty($item->doctor->user->image))
                                        <img src="{{ asset('storage/' . $item->doctor->user->image) }}" class="rounded-circle me-2"
                                            width="35" height="35" alt="">
                                    @else
                                        <img src="{{ asset('images/default-doctor.png') }}" class="rounded-circle me-2" width="35"
                                            height="35" alt="">
                                    @endif
                                    <small>{{ $item->doctor->user->name ?? 'Bác sĩ' }}</small>
                                </div>
                                <a href="{{ route('client.blog.show', $item->slug) }}" class="btn btn-sm btn-primary">Đọc
                                    thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>Hiện chưa có bài viết nào.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $blogs->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- CSS nội tuyến cho đồng nhất chiều cao --}}
    <style>
        .card-img-top-wrapper {
            height: 220px;
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .blog-card:hover .card-img-top {
            transform: scale(1.05);
        }
    </style>
@endsection