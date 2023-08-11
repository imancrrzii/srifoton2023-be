@extends('layouts.app')

@push('css')
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins-init/datatables.init.js') }}"></script>
    <script src="{{ asset('vendor/toastr/js/toastr.min.js') }}"></script>

    <script src="{{ asset('js/plugins-init/toastr-init.js') }}"></script>
    <script>
        $(document).ready(function() {
            // edit modal
            $(document).on('show.bs.modal', '#editModalWebdev', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const teamName = button.data('team-name');
                const proof = button.data('proof');
                const paymentMethod = button.data('payment-method');
                const isVerified = button.data('is-verified');
                const editModalTitle = $('#editModalTitle');
                const modal = $(this);
                const editForm = $('#editFormWebdev');

                modal.find('#teamName').val(teamName);
                modal.find('#paymentMethod').val(paymentMethod);
                modal.find('#proof').attr('src', '{{ asset('storage') }}/' + proof);

                if (isVerified) {
                    editForm.hide();
                    editModalTitle.html(teamName + ' telah diverifikasi');
                } else {
                    editForm.show();
                    editForm.attr('action', '{{ route('competition.webdev.verification', ':id') }}'.replace(
                        ':id', id));
                    editModalTitle.html('Verifikasi Pembayaran');
                }
            });

            // Delete Modal
            $(document).on('show.bs.modal', '#deleteModalWebdev', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const teamName = button.data('team-name');
                const modal = $(this);
                const deleteForm = $('#deleteFormWebdev');
                const deleteModalBody = $('#deleteModalBody');

                deleteModalBody.html(`Apakah anda yakin ingin menghapus tim ${teamName}`);
                deleteForm.attr('action', '{{ route('competition.webdev.delete', ':id') }}'.replace(':id',
                    id));

                modal.find('#teamName').val(teamName);
            });
        });
    </script>
    <script>
        @if (session()->has('success'))
            toastr.success('{{ session('success') }}', 'Success', {
                timeOut: 5e3,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                positionClass: "toast-top-right",
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1
            })
        @endif
        @if (session()->has('error'))
            toastr.error('{{ session('error') }}', 'Failed', {
                timeOut: 5e3,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                positionClass: "toast-top-right",
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1
            })
        @endif
    </script>
@endpush

@section('content')
    <div class="col-12">
        <div class="card" style="overflow-x: scroll">
            <div class="card-header">
                <h4 class="card-title text-primary fw-medium">Web Development
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="example3_wrapper" class="dataTables_wrapper no-footer">
                        <table id="example3" class="display dataTable cell-border no-footer mb-3" style="min-width: 845px"
                            role="grid" aria-describedby="example3_info">
                            <thead>
                                <tr class="text-center" role="row">
                                    <th class="sorting">No</th>
                                    <th class="sorting">Team Name</th>
                                    <th class="sorting">Member 1</th>
                                    <th class="sorting">Member 2</th>
                                    <th class="sorting">Member 3</th>
                                    <th class="sorting">Submission</th>
                                    <th class="sorting">Status</th>
                                    <th class="sorting">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($webdevs as $index => $webdev)
                                    <tr role="row">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="">{{ $webdev->team_name }}</td>
                                        <td>{{ $webdev->name1 }}</td>
                                        <td>{{ $webdev->name2 }}</td>
                                        <td>{{ $webdev->name3 }}</td>
                                        <td><span
                                                class="badge light badge-rounded badge-sm w-100 {{ $webdev->submission ? 'badge-success' : 'badge-warning' }}">
                                                <i
                                                    class="bi bi-file-earmark{{ $webdev->submission ? '-check' : '-x' }} me-1"></i>
                                                {{ $webdev->submission ? 'Submitted' : 'Unsubmitted' }}
                                            </span></td>
                                        <td class="text-center">
                                            <span
                                                class="badge light badge-rounded badge-sm w-100 {{ $webdev->isVerified ? 'badge-success' : 'badge-warning' }}">
                                                <i
                                                    class="{{ $webdev->isVerified ? 'bi bi-cash-stack me-1' : 'bi bi-hourglass-split me-1' }}"></i>
                                                {{ $webdev->isVerified ? 'Paid' : 'Awaiting' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('competition.webdev.download', $webdev->id) }}"
                                                    class="btn btn-success shadow btn-rounded btn-xs sharp me-1"> <i
                                                        class="bi bi-file-earmark-arrow-down-fill"></i></a>
                                                <a href="{{ route('competition.webdev.show', $webdev->id) }}"
                                                    class="btn btn-primary shadow btn-rounded btn-xs sharp me-1"><i
                                                        class="bi bi-eye-fill"></i></a>
                                                <button class="btn btn-warning shadow btn-rounded btn-xs sharp me-1"
                                                    data-bs-toggle="modal" data-bs-target="#editModalWebdev"
                                                    data-id="{{ $webdev->id }}"
                                                    data-team-name="{{ $webdev->team_name }}"
                                                    data-proof="{{ $webdev->proof }}"
                                                    data-payment-method="{{ $webdev->payment_method }}"
                                                    data-is-verified={{ $webdev->isVerified }}><i
                                                        class="bi bi-pencil-fill text-dark"></i></button>
                                                <button class="btn btn-danger shadow btn-rounded btn-xs sharp"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModalWebdev"
                                                    data-id={{ $webdev->id }}
                                                    data-team-name="{{ $webdev->team_name }}"><i
                                                        class="bi bi-trash-fill"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editModalWebdev">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary" id="editModalTitle"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td>Nama Tim</td>
                                                <td>
                                                    <input type="text" class="form-control w-100 mb-3" id="teamName"
                                                        readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Metode Pembayaran</td>
                                                <td>
                                                    <input type="text" class="form-control w-100" id="paymentMethod"
                                                        readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Bukti Pembayaran</td>
                                                <td>
                                                    <img class="img-fluid rounded-1 mb-3" alt="" id="proof"
                                                        style="max-height: 500px">
                                                </td>
                                            </tr>

                                        </table>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light"
                                            data-bs-dismiss="modal">Close</button>
                                        <form method="post" id="editFormWebdev">
                                            @csrf
                                            <button type="submit" name="isVerified"
                                                class="btn btn-primary">Verified</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Delete Modal --}}
                        <div class="modal fade" id="deleteModalWebdev">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <div class="modal-body" id="deleteModalBody"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light"
                                            data-bs-dismiss="modal">Close</button>
                                        <form method="post" id="deleteFormWebdev">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
