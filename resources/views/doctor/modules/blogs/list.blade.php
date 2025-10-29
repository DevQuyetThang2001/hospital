@extends('doctor.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-uppercase text-lg mb-4">Danh sách bài viết</h4>

                    {{-- Hiển thị thông báo --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('update'))
                        <div class="alert alert-secondary">{{ session('update') }}</div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif
                    @if (session('delete'))
                        <div class="alert alert-danger">{{ session('delete') }}</div>
                    @endif

                    <a href="{{ route('doctor.blogs.create') }}" class="btn btn-success mb-3">Thêm mới bài viết</a>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Nội dung</th>
                                    <th>Ngày tạo</th>
                                    <th>Bác sĩ</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($blogs as $item)
                                    <tr>
                                        {{-- Ảnh đầu tiên --}}
                                        <td>
                                            @if ($item->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->images->first()->image) }}"
                                                    alt="Ảnh bài viết" width="90" height="70" class="rounded border">
                                            @else
                                                <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh" width="90"
                                                    height="70" class="rounded border">
                                            @endif
                                        </td>

                                        {{-- Tiêu đề --}}
                                        <td>{{ $item->title }}</td>

                                        {{-- Giới hạn nội dung 100 ký tự --}}
                                        <td>{!! Str::limit(strip_tags($item->description), 100, '...') !!}</td>

                                        {{-- Ngày tạo --}}
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>

                                        {{-- Người tạo --}}
                                        <td>{{ $item->doctor->user->name ?? 'Chưa xác định' }}</td>

                                        {{-- Thao tác --}}
                                        <td>
                                            <a href="{{route('doctor.blogs.edit', $item->id) }}"
                                                class="btn btn-sm btn-secondary">
                                                <i class="mdi mdi-pencil"></i> Sửa
                                            </a>

                                            <!-- Nút xóa mở modal -->
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#modal-{{ $item->id }}">
                                                <i class="mdi mdi-delete"></i> Xóa
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Xóa -->
                                    <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="modalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $item->id }}">Xóa bài viết</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn chắc chắn muốn xóa bài viết <strong>{{ $item->title }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('doctor.blogs.delete', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Chưa có bài viết nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection