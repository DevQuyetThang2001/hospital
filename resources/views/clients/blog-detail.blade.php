@extends('clients.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <article class="bg-white p-4 shadow-sm rounded">
                    @if ($blog->images->count() > 0)
                        <img src="{{ asset('storage/' . $blog->images->first()->image) }}" class="img-fluid rounded mb-4"
                            alt="{{ $blog->title }}" style="width:100%; height:400px; object-fit:cover;">
                    @endif

                    <h1 class="fw-bold mb-3">{{ $blog->title }}</h1>

                    <div class="d-flex align-items-center mb-4 text-muted">
                        @if(!empty($blog->doctor->user->image))
                            <img src="{{ asset('storage/' . $blog->doctor->user->image) }}" class="rounded-circle me-2"
                                width="40" height="40" style="object-fit:cover;">
                        @endif
                        <small>
                            {{ $blog->doctor->user->name ?? 'Bác sĩ ẩn danh' }} •
                            {{ $blog->created_at->format('d/m/Y') }}
                        </small>
                    </div>

                    <div class="content">
                        {!! $blog->description !!}
                    </div>
                </article>

                @if ($relatedBlogs->count() > 0)
                    <div class="mt-5">
                        <h3 class="mb-4">🩺 Bài viết khác từ bác sĩ này</h3>
                        <div class="row g-4">
                            @foreach ($relatedBlogs as $item)
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm">
                                        @if ($item->images->count() > 0)
                                            <img src="{{ asset('storage/' . $item->images->first()->image) }}" class="card-img-top"
                                                style="height:180px; object-fit:cover;">
                                        @endif
                                        <div class="card-body">
                                            <a href="{{ route('client.blog.show', $item->slug) }}"
                                                class="stretched-link fw-bold text-dark text-decoration-none">
                                                {{ Str::limit($item->title, 60) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection